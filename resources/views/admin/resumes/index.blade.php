@extends('admin.layout')

@section('title', 'Rezyumelar')

@section('content')
    <div class="card">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Ism familiya</th>
                            <th>Telefon</th>
                            <th>Lavozim</th>
                            <th>Filial</th>
                            <th>Sana</th>
                            <th class="text-end">Amallar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->full_name }}</strong>
                                    @if ($item->message)
                                        <div class="text-muted small mt-1">{{ $item->message }}</div>
                                    @endif
                                </td>
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->position ?: '-' }}</td>
                                <td>{{ $item->branch ?: '-' }}</td>
                                <td>{{ $item->created_at->format('d.m.Y H:i') }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('admin.resumes.destroy', $item) }}" onsubmit="return confirm('O‘chirasizmi?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">O‘chirish</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Rezyume yuborilmagan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $items->links() }}
        </div>
    </div>
@endsection
