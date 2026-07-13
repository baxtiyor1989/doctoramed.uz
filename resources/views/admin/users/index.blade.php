@extends('admin.layout')

@section('title', 'Foydalanuvchilar')

@section('actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-success">
        <i class="ri-add-line align-bottom"></i> Qo‘shish
    </a>
@endsection

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
                            <th>Ism</th>
                            <th>Login</th>
                            <th>Rol</th>
                            <th>Sana</th>
                            <th class="text-end">Amallar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->login }}</td>
                                <td><span class="badge bg-primary">{{ $roles[$item->role] ?? $item->role }}</span></td>
                                <td>{{ $item->created_at->format('d.m.Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.users.edit', $item) }}" class="btn btn-sm btn-primary">Tahrirlash</a>
                                    @if (! $item->is(auth()->user()))
                                        <form method="POST" action="{{ route('admin.users.destroy', $item) }}" class="d-inline" onsubmit="return confirm('O‘chirasizmi?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">O‘chirish</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Foydalanuvchi yo‘q.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $items->links() }}
        </div>
    </div>
@endsection
