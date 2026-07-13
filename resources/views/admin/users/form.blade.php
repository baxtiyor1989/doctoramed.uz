@extends('admin.layout')

@section('title', $item->exists ? 'Foydalanuvchini tahrirlash' : 'Foydalanuvchi qo‘shish')

@section('content')
    <form method="POST" action="{{ $item->exists ? route('admin.users.update', $item) : route('admin.users.store') }}">
        @csrf
        @if ($item->exists)
            @method('PUT')
        @endif

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="name">Ism</label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $item->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="login">Login</label>
                        <input class="form-control @error('login') is-invalid @enderror" id="login" name="login" value="{{ old('login', $item->login) }}" required>
                        @error('login')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="role">Rol</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            @foreach ($roles as $value => $label)
                                <option value="{{ $value }}" @selected(old('role', $item->role) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="password">Parol</label>
                        <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" @required(! $item->exists)>
                        @if ($item->exists)
                            <div class="form-text">O‘zgartirmasangiz bo‘sh qoldiring.</div>
                        @endif
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="password_confirmation">Parolni tasdiqlash</label>
                        <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" @required(! $item->exists)>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex gap-2">
                <button type="submit" class="btn btn-success">Saqlash</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-light">Orqaga</a>
            </div>
        </div>
    </form>
@endsection
