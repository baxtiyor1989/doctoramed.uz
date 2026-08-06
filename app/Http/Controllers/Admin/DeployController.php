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
            'repository' => config('deploy.repository'),
            'commit' => $this->gitValue(['rev-parse', '--short', 'HEAD']),
            'status' => $this->readJson(storage_path('framework/deploy-status.json')),
            'version' => File::exists(public_path('version.txt')) ? File::get(public_path('version.txt')) : null,
            'log' => File::exists(storage_path('logs/deploy.log')) ? File::get(storage_path('logs/deploy.log')) : null,
        ]);
    }

    public function store(): RedirectResponse
    {
        $this->authorizeAdministrator();

        try {
            $script = base_path('deploy.sh');
            abort_unless(File::exists($script), 500, 'deploy.sh fayli topilmadi.');

            $command = 'nohup bash '.escapeshellarg($script).' >/dev/null 2>&1 &';
            $process = Process::fromShellCommandline($command, base_path(), [
                'DEPLOY_GIT_REPOSITORY' => config('deploy.repository'),
                'DEPLOY_GIT_REMOTE' => config('deploy.remote'),
                'DEPLOY_GIT_BRANCH' => config('deploy.branch'),
            ]);
            $process->setTimeout(10)->run();

            if (! $process->isSuccessful()) {
                return back()->withErrors(['deploy' => 'deploy.sh jarayonini ishga tushirib bo‘lmadi.']);
            }

            return back()->with('status', 'Deploy jarayoni fonda ishga tushirildi. Natijani yangilab kuzating.');
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors(['deploy' => 'Deployni ishga tushirishda xatolik yuz berdi.']);
        }
    }

    private function authorizeAdministrator(): void
    {
        abort_unless(
            config('deploy.enabled', true)
            && in_array(
                request()->getHost(),
                config('deploy.allowed_hosts', ['doctoramed.uz', 'www.doctoramed.uz']),
                true
            ),
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

    private function readJson(string $path): ?array
    {
        if (! File::exists($path)) {
            return null;
        }

        $value = json_decode(File::get($path), true);

        return is_array($value) ? $value : null;
    }
}
