<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\Process\Process;
use Throwable;

class DeployController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdministrator();

        return view('admin.deploy', [
            'branch' => config('deploy.branch'),
            'remote' => config('deploy.remote'),
            'repository' => config('deploy.repository'),
            'commit' => $this->gitValue(['rev-parse', '--short', 'HEAD']),
            'deployed' => $this->deploymentResult(),
        ]);
    }

    public function store(): RedirectResponse
    {
        $this->authorizeAdministrator();

        $lockPath = storage_path('framework/deploy.lock');
        File::ensureDirectoryExists(dirname($lockPath));
        $lock = fopen($lockPath, 'c+');

        if ($lock === false || ! flock($lock, LOCK_EX | LOCK_NB)) {
            return back()->withErrors(['deploy' => 'Boshqa yangilash jarayoni hali tugamagan.']);
        }

        $remote = (string) config('deploy.remote');
        $branch = (string) config('deploy.branch');
        $repository = (string) config('deploy.repository');
        $commands = [
            ['label' => 'GitHub repository manzili tekshirilmoqda', 'command' => ['git', 'remote', 'set-url', $remote, $repository]],
            ['label' => "GitHub holati olinmoqda ({$remote}/{$branch})", 'command' => ['git', 'fetch', $remote, $branch]],
            ['label' => 'Kod yangilanmoqda', 'command' => ['git', 'merge', '--ff-only', "{$remote}/{$branch}"]],
            ['label' => 'Composer paketlari o‘rnatilmoqda', 'command' => ['composer', 'install', '--no-dev', '--no-interaction', '--prefer-dist', '--optimize-autoloader']],
            ['label' => 'NPM paketlari o‘rnatilmoqda', 'command' => ['npm', 'ci', '--no-audit', '--no-fund']],
            ['label' => 'Frontend build qilinmoqda', 'command' => ['npm', 'run', 'build']],
            ['label' => 'Ma’lumotlar bazasi migratsiyalari bajarilmoqda', 'command' => [PHP_BINARY, 'artisan', 'migrate', '--force']],
            ['label' => 'Laravel keshlari yangilanmoqda', 'command' => [PHP_BINARY, 'artisan', 'optimize:clear']],
            ['label' => 'Laravel production keshi yaratilmoqda', 'command' => [PHP_BINARY, 'artisan', 'optimize']],
        ];

        $log = [];
        $successful = true;

        try {
            foreach ($commands as $item) {
                $log[] = '['.now()->format('Y-m-d H:i:s').'] '.$item['label'].'...';
                $process = new Process($item['command'], base_path());
                $process->setTimeout((int) config('deploy.timeout'));
                $process->run();
                $output = trim($process->getOutput().$process->getErrorOutput());
                if ($output !== '') {
                    $log[] = $output;
                }
                if (! $process->isSuccessful()) {
                    $successful = false;
                    $log[] = 'Xatolik: buyruq '.$process->getExitCode().' kodi bilan yakunlandi.';
                    break;
                }
            }
        } catch (Throwable $exception) {
            report($exception);
            $successful = false;
            $log[] = 'Xatolik: '.$exception->getMessage();
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }

        $result = [
            'successful' => $successful,
            'commit' => $this->gitValue(['rev-parse', '--short', 'HEAD']),
            'deployed_at' => now()->toIso8601String(),
            'log' => implode(PHP_EOL.PHP_EOL, $log),
        ];
        File::put(storage_path('app/deploy-result.json'), json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return back()->with($successful ? 'status' : 'deploy_error', $successful
            ? 'GitHub’dan yangilash muvaffaqiyatli yakunlandi.'
            : 'Yangilash yakunlanmadi. Deploy logini tekshiring.');
    }

    private function authorizeAdministrator(): void
    {
        abort_unless(
            app()->isProduction()
            && config('deploy.enabled')
            && in_array(request()->getHost(), config('deploy.allowed_hosts', []), true),
            404
        );

        abort_unless(auth()->user()?->role === 'administrator', 403);
    }

    private function gitValue(array $arguments): string
    {
        try {
            $process = new Process(array_merge(['git'], $arguments), base_path());
            $process->setTimeout(10)->run();

            return $process->isSuccessful() ? trim($process->getOutput()) : 'noma’lum';
        } catch (Throwable) {
            return 'noma’lum';
        }
    }

    private function deploymentResult(): ?array
    {
        $path = storage_path('app/deploy-result.json');
        if (! File::exists($path)) {
            return null;
        }

        return json_decode(File::get($path), true);
    }
}
