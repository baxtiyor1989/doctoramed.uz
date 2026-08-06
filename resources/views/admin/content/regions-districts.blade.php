@extends('admin.layout')

@section('title', 'Viloyatlar va tumanlar')

@section('content')
    <div class="row g-4">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between gap-3">
                    <div><h5 class="card-title mb-1">Viloyatlar</h5><p class="text-muted mb-0 fs-13">Hududlarni boshqarish</p></div>
                    <a href="{{ route('admin.content.create', 'regions') }}" class="btn btn-success btn-sm"><i class="ri-add-line"></i> Viloyat qo‘shish</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th>Viloyat nomi</th><th>Tumanlar</th><th>Holat</th><th></th></tr></thead>
                            <tbody>
                                @forelse ($regions as $region)
                                    <tr data-region-row="{{ $region->id }}">
                                        <td><button type="button" class="region-filter-button" data-region-filter="{{ $region->id }}" data-region-title="{{ $region->title }}"><i class="ri-map-2-line"></i> {{ $region->title }}</button></td>
                                        <td><span class="badge bg-soft-primary text-primary">{{ $region->districts_count }}</span></td>
                                        <td><span class="badge bg-{{ $region->is_active ? 'success' : 'secondary' }}">{{ $region->is_active ? 'Faol' : 'O‘chiq' }}</span></td>
                                        <td class="text-end text-nowrap">
                                            <a href="{{ route('admin.content.edit', ['regions', $region]) }}" class="btn btn-sm btn-primary">Tahrirlash</a>
                                            @if ($region->districts_count > 0)
                                                <button type="button" class="btn btn-sm btn-danger" disabled title="Avval viloyatga tegishli tumanlarni o‘chiring yoki boshqa viloyatga o‘tkazing">O‘chirish</button>
                                            @else
                                                <form method="POST" action="{{ route('admin.content.destroy', ['regions', $region]) }}" class="d-inline" onsubmit="return confirm('Viloyatni o‘chirasizmi?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">O‘chirish</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">Viloyatlar kiritilmagan.</td></tr>
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
                    <div><h5 class="card-title mb-1">Tumanlar</h5><p class="text-muted mb-0 fs-13" data-districts-caption>Viloyatlarga tegishli barcha tumanlar</p></div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-soft-secondary btn-sm" data-show-all-districts hidden>Barchasi</button>
                        <a href="{{ route('admin.content.create', 'districts') }}" class="btn btn-success btn-sm"><i class="ri-add-line"></i> Tuman qo‘shish</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th>Tuman nomi</th><th>Viloyat</th><th>Holat</th><th></th></tr></thead>
                            <tbody>
                                @forelse ($districts as $district)
                                    <tr data-district-row data-region-id="{{ $district->region_id }}">
                                        <td class="fw-semibold">{{ $district->title }}</td>
                                        <td>{{ $district->region?->title ?? 'Biriktirilmagan' }}</td>
                                        <td><span class="badge bg-{{ $district->is_active ? 'success' : 'secondary' }}">{{ $district->is_active ? 'Faol' : 'O‘chiq' }}</span></td>
                                        <td class="text-end text-nowrap">
                                            <a href="{{ route('admin.content.edit', ['districts', $district]) }}" class="btn btn-sm btn-primary">Tahrirlash</a>
                                            <form method="POST" action="{{ route('admin.content.destroy', ['districts', $district]) }}" class="d-inline" onsubmit="return confirm('Tumanni o‘chirasizmi?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger">O‘chirish</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">Tumanlar kiritilmagan.</td></tr>
                                @endforelse
                                @if ($districts->isNotEmpty())<tr data-filter-empty hidden><td colspan="4" class="text-center text-muted py-4">Bu viloyatga tuman biriktirilmagan.</td></tr>@endif
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
        .region-filter-button { display:inline-flex; align-items:center; gap:8px; padding:7px 10px; border:0; border-radius:9px; color:#293447; background:transparent; font-weight:700; text-align:left; cursor:pointer; transition:.18s ease; }
        .region-filter-button:hover, [data-region-row].is-selected .region-filter-button { color:#b7212d; background:rgba(183,33,45,.1); }
        [data-region-row].is-selected { background:rgba(183,33,45,.035); }
    </style>
@endpush

@push('scripts')
    <script>
        (() => {
            const filters = document.querySelectorAll('[data-region-filter]');
            const districtRows = document.querySelectorAll('[data-district-row]');
            const emptyRow = document.querySelector('[data-filter-empty]');
            const caption = document.querySelector('[data-districts-caption]');
            const showAll = document.querySelector('[data-show-all-districts]');
            const filterDistricts = (regionId = null, regionTitle = '') => {
                let visibleCount = 0;
                districtRows.forEach((row) => { const visible = !regionId || row.dataset.regionId === regionId; row.hidden = !visible; if (visible) visibleCount++; });
                document.querySelectorAll('[data-region-row]').forEach((row) => row.classList.toggle('is-selected', row.dataset.regionRow === regionId));
                if (emptyRow) emptyRow.hidden = !regionId || visibleCount > 0;
                if (caption) caption.textContent = regionId ? `${regionTitle} viloyatiga tegishli tumanlar` : 'Viloyatlarga tegishli barcha tumanlar';
                if (showAll) showAll.hidden = !regionId;
            };
            filters.forEach((button) => button.addEventListener('click', () => filterDistricts(button.dataset.regionFilter, button.dataset.regionTitle)));
            showAll?.addEventListener('click', () => filterDistricts());
        })();
    </script>
@endpush
