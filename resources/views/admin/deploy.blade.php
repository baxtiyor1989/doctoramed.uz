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

        <div class="card" id="deploy-log-card" @if (!$status && !$log) style="display:none" @endif>
            <div class="card-header"><h5 class="card-title mb-0">Deploy log</h5></div>
            <div class="card-body">
                @php($statusColor = ['success' => 'success', 'failed' => 'danger', 'running' => 'warning', 'queued' => 'info'][$status['status'] ?? ''] ?? 'secondary')
                <div class="d-flex align-items-center gap-2 mb-3" id="deploy-status-row" @if (!$status) style="display:none" @endif>
                    <span class="badge bg-{{ $statusColor }}" id="deploy-status-badge">{{ ['success' => 'Muvaffaqiyatli', 'failed' => 'Xatolik', 'running' => 'Bajarilmoqda', 'queued' => 'Kutilmoqda'][$status['status'] ?? ''] ?? ($status['status'] ?? '') }}</span>
                    <span id="deploy-status-message">{{ $status['message'] ?? '' }}</span>
                    <strong id="deploy-status-version">{{ $status['version'] ?? '' }}</strong>
                </div>
                <pre class="deploy-log mb-0" id="deploy-log">{{ $log }}</pre>
            </div>
        </div>
@endsection

@push('styles')
    <style>
        .deploy-log { min-height: 220px; max-height: 560px; overflow: auto; padding: 16px; border-radius: 4px; background: #212529; color: #f8f9fa; white-space: pre-wrap; font-size: 12px; }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('admin-assets/assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        (() => {
            const form = document.getElementById('deploy-form');
            const button = document.getElementById('deploy-button');
            const card = document.getElementById('deploy-log-card');
            const row = document.getElementById('deploy-status-row');
            const badge = document.getElementById('deploy-status-badge');
            const message = document.getElementById('deploy-status-message');
            const version = document.getElementById('deploy-status-version');
            const log = document.getElementById('deploy-log');
            const labels = { queued: 'Kutilmoqda', running: 'Bajarilmoqda', success: 'Muvaffaqiyatli', failed: 'Xatolik' };
            const colors = { queued: 'info', running: 'warning', success: 'success', failed: 'danger' };
            let polling = {{ in_array($status['status'] ?? null, ['queued', 'running'], true) ? 'true' : 'false' }};
            let completionShown = false;

            function render(data) {
                const state = data.status;
                card.style.display = '';
                if (state) {
                    row.style.display = '';
                    badge.className = `badge bg-${colors[state.status] || 'secondary'}`;
                    badge.textContent = labels[state.status] || state.status;
                    message.textContent = state.message || '';
                    version.textContent = state.version || '';
                }
                log.textContent = data.log || '';
                log.scrollTop = log.scrollHeight;

                if (state && ['success', 'failed'].includes(state.status) && !completionShown) {
                    completionShown = true;
                    polling = false;
                    button.disabled = false;
                    button.innerHTML = '<i class="ri-github-line align-middle me-1"></i> GitHub’dan deploy qilish';
                    Swal.fire({
                        icon: state.status === 'success' ? 'success' : 'error',
                        title: state.status === 'success' ? 'Deploy muvaffaqiyatli!' : 'Deployda xatolik!',
                        text: state.message || '',
                        confirmButtonText: 'Yopish',
                        confirmButtonColor: '#405189'
                    });
                }
            }

            async function poll() {
                if (!polling) return;
                try {
                    const response = await fetch(@json(route('admin.deploy.index', ['status' => 1])), { headers: { Accept: 'application/json' } });
                    if (response.ok) render(await response.json());
                } finally {
                    if (polling) window.setTimeout(poll, 2000);
                }
            }

            form?.addEventListener('submit', async (event) => {
                event.preventDefault();
                const confirmation = await Swal.fire({
                    icon: 'question',
                    title: 'Deploy boshlansinmi?',
                    text: 'Production sayt GitHub’dan yangilanadi.',
                    showCancelButton: true,
                    confirmButtonText: 'Ha, boshlash',
                    cancelButtonText: 'Bekor qilish',
                    confirmButtonColor: '#405189'
                });
                if (!confirmation.isConfirmed) return;

                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Yangilanmoqda...';
                completionShown = false;
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: { Accept: 'application/json' }
                    });
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.message || 'Deployni boshlashda xatolik yuz berdi.');
                    polling = true;
                    poll();
                } catch (error) {
                    button.disabled = false;
                    button.innerHTML = '<i class="ri-github-line align-middle me-1"></i> GitHub’dan deploy qilish';
                    Swal.fire({ icon: 'error', title: 'Xatolik!', text: error.message, confirmButtonText: 'Yopish' });
                }
            });

            if (polling) {
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Yangilanmoqda...';
                poll();
            }
        })();
    </script>
@endpush
