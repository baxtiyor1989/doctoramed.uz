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
                        <table class="table align-middle mb-0">
                            <thead><tr><th>Filial nomi</th><th>Vakansiyalar</th><th>Holat</th><th></th></tr></thead>
                            <tbody>
                                @forelse ($branches as $branch)
                                    <tr>
                                        <td class="fw-semibold">{{ $branch->title }}</td>
                                        <td><span class="badge bg-soft-primary text-primary">{{ $branch->vacancies_count }}</span></td>
                                        <td><span class="badge bg-{{ $branch->is_active ? 'success' : 'secondary' }}">{{ $branch->is_active ? 'Faol' : 'O‘chiq' }}</span></td>
                                        <td class="text-end text-nowrap">
                                            <a href="{{ route('admin.content.edit', ['branches', $branch]) }}" class="btn btn-sm btn-primary">Tahrirlash</a>
                                            <form method="POST" action="{{ route('admin.content.destroy', ['branches', $branch]) }}" class="d-inline" onsubmit="return confirm('Filialni o‘chirasizmi? Unga biriktirilgan vakansiyalar biriktirilmagan holatga o‘tadi.')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger">O‘chirish</button>
                                            </form>
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
                        <p class="text-muted mb-0 fs-13">Filiallarga biriktirilgan vakansiyalar</p>
                    </div>
                    <a href="{{ route('admin.content.create', 'vacancies') }}" class="btn btn-success btn-sm"><i class="ri-add-line"></i> Vakansiya qo‘shish</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr><th>Lavozim</th><th>Filial</th><th>Holat</th><th></th></tr></thead>
                            <tbody>
                                @forelse ($vacancies as $vacancy)
                                    <tr>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
