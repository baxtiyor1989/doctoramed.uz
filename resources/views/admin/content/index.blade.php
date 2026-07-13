@extends('admin.layout')

@section('title', $config['title'])

@section('actions')
    <a href="{{ route('admin.content.create', $resource) }}" class="btn btn-success">
        <i class="ri-add-line align-bottom"></i> Qo‘shish
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-nowrap">
                    <thead>
                        <tr>
                            <th>Nomi</th>
                            <th>Tartib</th>
                            <th>Holat</th>
                            <th class="text-end">Amallar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $item->title ?? $item->name }}</td>
                                <td>{{ $item->sort_order }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->is_active ? 'success' : 'secondary' }}">
                                        {{ $item->is_active ? 'Faol' : 'O‘chiq' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.content.edit', [$resource, $item]) }}" class="btn btn-sm btn-primary">Tahrirlash</a>
                                    <form method="POST" action="{{ route('admin.content.destroy', [$resource, $item]) }}" class="d-inline" onsubmit="return confirm('O‘chirasizmi?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">O‘chirish</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">Ma’lumot yo‘q.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
