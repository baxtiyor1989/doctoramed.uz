@extends('admin.layout')

@section('title', 'Filiallar va vakant lavozimlar')

@section('content')
    <div class="row g-4">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between gap-3">
                    <div>
                        <h5 class="card-title mb-1">Filiallar</h5>
                        <p class="text-muted mb-0 fs-13">Klinika filiallarini boshqarish</p>
                    </div>
                    <a href="{{ route('admin.content.create', 'branches') }}" class="btn btn-success btn-sm"><i class="ri-add-line"></i> Filial qo‘shish</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" data-branches-table>
                            <thead><tr><th>Filial nomi</th><th>Vakansiyalar</th><th>Holat</th><th></th></tr></thead>
                            <tbody>
                                @forelse ($branches as $branch)
                                    <tr data-branch-row="{{ $branch->id }}">
                                        <td><button type="button" class="branch-filter-button" data-branch-filter="{{ $branch->id }}" data-branch-title="{{ $branch->title }}"><i class="ri-building-line"></i> {{ $branch->title }}</button></td>
                                        <td><span class="badge bg-soft-primary text-primary">{{ $branch->vacancies_count }}</span></td>
                                        <td><span class="badge bg-{{ $branch->is_active ? 'success' : 'secondary' }}">{{ $branch->is_active ? 'Faol' : 'O‘chiq' }}</span></td>
                                        <td class="text-end text-nowrap">
                                            <a href="{{ route('admin.content.edit', ['branches', $branch]) }}" class="btn btn-sm btn-primary">Tahrirlash</a>
                                            @if ($branch->vacancies_count > 0)
                                                <button type="button" class="btn btn-sm btn-danger" disabled title="Avval filialga tegishli vakant lavozimlarni o‘chiring yoki boshqa filialga o‘tkazing">O‘chirish</button>
                                            @else
                                                <form method="POST" action="{{ route('admin.content.destroy', ['branches', $branch]) }}" class="d-inline" onsubmit="return confirm('Filialni o‘chirasizmi?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">O‘chirish</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">Filiallar kiritilmagan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between gap-3">
                    <div>
                        <h5 class="card-title mb-1">Vakant lavozimlar</h5>
                        <p class="text-muted mb-0 fs-13" data-vacancies-caption>Filiallarga biriktirilgan barcha vakansiyalar</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-soft-secondary btn-sm" data-show-all-vacancies hidden>Barchasi</button>
                        <a href="{{ route('admin.content.create', 'vacancies') }}" class="btn btn-success btn-sm"><i class="ri-add-line"></i> Vakansiya qo‘shish</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th>Lavozim</th><th>Filial</th><th>Holat</th><th></th></tr></thead>
                            <tbody>
                                @forelse ($vacancies as $vacancy)
                                    <tr data-vacancy-row data-branch-id="{{ $vacancy->branch_id }}">
                                        <td class="fw-semibold">{{ $vacancy->title }}</td>
                                        <td>{{ $vacancy->branch?->title ?? 'Biriktirilmagan' }}</td>
                                        <td><span class="badge bg-{{ $vacancy->is_active ? 'success' : 'secondary' }}">{{ $vacancy->is_active ? 'Faol' : 'O‘chiq' }}</span></td>
                                        <td class="text-end text-nowrap">
                                            <a href="{{ route('admin.content.edit', ['vacancies', $vacancy]) }}" class="btn btn-sm btn-primary">Tahrirlash</a>
                                            <form method="POST" action="{{ route('admin.content.destroy', ['vacancies', $vacancy]) }}" class="d-inline" onsubmit="return confirm('Vakansiyani o‘chirasizmi?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger">O‘chirish</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">Vakant lavozimlar kiritilmagan.</td></tr>
                                @endforelse
                                @if ($vacancies->isNotEmpty())
                                    <tr data-filter-empty hidden><td colspan="4" class="text-center text-muted py-4">Bu filialga vakant lavozim biriktirilmagan.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .branch-filter-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 10px;
            border: 0;
            border-radius: 9px;
            color: #293447;
            background: transparent;
            font-weight: 700;
            text-align: left;
            cursor: pointer;
            transition: .18s ease;
        }
        .branch-filter-button:hover,
        [data-branch-row].is-selected .branch-filter-button {
            color: #b7212d;
            background: rgba(183, 33, 45, .1);
        }
        [data-branch-row].is-selected { background: rgba(183, 33, 45, .035); }
    </style>
@endpush

@push('scripts')
    <script>
        (() => {
            const filters = document.querySelectorAll('[data-branch-filter]');
            const vacancyRows = document.querySelectorAll('[data-vacancy-row]');
            const emptyRow = document.querySelector('[data-filter-empty]');
            const caption = document.querySelector('[data-vacancies-caption]');
            const showAll = document.querySelector('[data-show-all-vacancies]');

            const filterVacancies = (branchId = null, branchTitle = '') => {
                let visibleCount = 0;
                vacancyRows.forEach((row) => {
                    const visible = !branchId || row.dataset.branchId === branchId;
                    row.hidden = !visible;
                    if (visible) visibleCount++;
                });
                document.querySelectorAll('[data-branch-row]').forEach((row) => row.classList.toggle('is-selected', row.dataset.branchRow === branchId));
                if (emptyRow) emptyRow.hidden = !branchId || visibleCount > 0;
                if (caption) caption.textContent = branchId ? `${branchTitle} filialiga tegishli vakansiyalar` : 'Filiallarga biriktirilgan barcha vakansiyalar';
                if (showAll) showAll.hidden = !branchId;
            };

            filters.forEach((button) => button.addEventListener('click', () => filterVacancies(button.dataset.branchFilter, button.dataset.branchTitle)));
            showAll?.addEventListener('click', () => filterVacancies());
        })();
    </script>
@endpush
