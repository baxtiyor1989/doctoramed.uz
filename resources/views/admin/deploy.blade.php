@extends('admin.layout')

@section('title', 'Production deploy')

@section('actions')
    <a href="{{ route('admin.deploy.index') }}" class="btn btn-soft-secondary btn-sm">
        <i class="ri-refresh-line align-middle"></i> Natijani yangilash
    </a>
@endsection

@section('content')
    @if (session('deploy_error'))
        <div class="alert alert-danger">{{ session('deploy_error') }}</div>
    @endif

    <div class="card">
        <div class="card-header"><h5 class="card-title mb-0">Production deploy</h5></div>
        <div class="card-body">
            <p class="text-muted">Kod <a href="{{ preg_replace('/\.git$/', '', $repository) }}" target="_blank" rel="noopener noreferrer">{{ preg_replace('/\.git$/', '', $repository) }}</a> repositorysining <code>{{ $branch }}</code> branchidan olinadi, Composer, NPM build, migratsiya va Laravel keshlari yangilanadi.</p>
            <div class="bg-light rounded p-3 mb-3 font-monospace small">
                <div>Repository: {{ $repository }}</div>
                <div>Branch: {{ $branch }}</div>
                <div>Joriy commit: {{ $commit }}</div>
                @if ($version)<div class="mt-2">{!! nl2br(e(trim($version))) !!}</div>@endif
            </div>
            <form method="POST" action="{{ route('admin.deploy.store') }}" id="deploy-form">
                @csrf
                <button type="submit" class="btn btn-primary" id="deploy-button">
                    <i class="ri-github-line align-middle me-1"></i> GitHub’dan deploy qilish
                </button>
            </form>
        </div>
    </div>

    @if ($status || $log)
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Deploy log</h5></div>
            <div class="card-body">
                @if ($status)
                    @php($statusColor = ['success' => 'success', 'failed' => 'danger', 'running' => 'warning'][$status['status']] ?? 'secondary')
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-{{ $statusColor }}">{{ ['success' => 'Muvaffaqiyatli', 'failed' => 'Xatolik', 'running' => 'Bajarilmoqda'][$status['status']] ?? $status['status'] }}</span>
                        <span>{{ $status['message'] ?? '' }}</span>
                        @if (!empty($status['version']))<strong>{{ $status['version'] }}</strong>@endif
                    </div>
                @endif
                @if ($log)<pre class="deploy-log mb-0">{{ $log }}</pre>@endif
            </div>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .deploy-log { min-height: 220px; max-height: 560px; overflow: auto; padding: 16px; border-radius: 4px; background: #212529; color: #f8f9fa; white-space: pre-wrap; font-size: 12px; }
    </style>
@endpush

@push('scripts')
    <script>
        document.getElementById('deploy-form')?.addEventListener('submit', function (event) {
            if (!confirm('Production saytini GitHub’dan yangilashni tasdiqlaysizmi?')) {
                event.preventDefault();
                return;
            }
            const button = document.getElementById('deploy-button');
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Yangilanmoqda...';
        });
        @if (($status['status'] ?? null) === 'running')
            window.setTimeout(() => window.location.reload(), 5000);
        @endif
    </script>
@endpush
