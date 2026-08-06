<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
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

    public function status(): JsonResponse
    {
        $this->authorizeAdministrator();

        return response()->json([
            'status' => $this->readJson(storage_path('framework/deploy-status.json')),
            'version' => File::exists(public_path('version.txt')) ? trim(File::get(public_path('version.txt'))) : null,
            'log' => File::exists(storage_path('logs/deploy.log')) ? File::get(storage_path('logs/deploy.log')) : null,
            'commit' => $this->gitValue(['rev-parse', '--short', 'HEAD']),
        ]);
    }

    public function store(): RedirectResponse|JsonResponse
    {
        $this->authorizeAdministrator();

        try {
            $script = base_path('deploy.sh');
            abort_unless(File::exists($script), 500, 'deploy.sh fayli topilmadi.');

            File::put(storage_path('framework/deploy-status.json'), json_encode([
                'status' => 'queued',
                'message' => 'Deploy ishga tushirilmoqda',
                'version' => '',
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ], JSON_UNESCAPED_UNICODE));

            $command = 'nohup bash '.escapeshellarg($script).' >/dev/null 2>&1 &';
            $process = Process::fromShellCommandline($command, base_path(), [
                'DEPLOY_GIT_REPOSITORY' => config('deploy.repository'),
                'DEPLOY_GIT_REMOTE' => config('deploy.remote'),
                'DEPLOY_GIT_BRANCH' => config('deploy.branch'),
            ]);
            $process->setTimeout(10)->run();

            if (! $process->isSuccessful()) {
                return $this->deployResponse(false, 'deploy.sh jarayonini ishga tushirib bo‘lmadi.');
            }

            return $this->deployResponse(true, 'Deploy jarayoni fonda ishga tushirildi.');
        } catch (Throwable $exception) {
            report($exception);

            return $this->deployResponse(false, 'Deployni ishga tushirishda xatolik yuz berdi.');
        }
    }

    private function deployResponse(bool $successful, string $message): RedirectResponse|JsonResponse
    {
        if (request()->expectsJson()) {
            return response()->json(['successful' => $successful, 'message' => $message], $successful ? 202 : 500);
        }

        return $successful
            ? back()->with('status', $message)
            : back()->withErrors(['deploy' => $message]);
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
