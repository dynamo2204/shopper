@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="mb-4">Edytuj użytkownika</h4>

                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Imię i nazwisko</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nowe hasło <span class="text-muted">(zostaw puste, żeby nie zmieniać)</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Powtórz nowe hasło</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Rola</label>
                        <select name="role" class="form-select">
                            <option value="klient" @selected(old('role', $user->role) === 'klient')>klient</option>
                            <option value="moderator" @selected(old('role', $user->role) === 'moderator')>moderator</option>
                            <option value="admin" @selected(old('role', $user->role) === 'admin')>admin</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dark">Zapisz</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Anuluj</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

