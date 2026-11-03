@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="mb-4">Dodaj użytkownika</h4>

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Imię i nazwisko</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hasło</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Powtórz hasło</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Rola</label>
                        <select name="role" class="form-select">
                            <option value="klient">klient</option>
                            <option value="moderator">moderator</option>
                            <option value="admin">admin</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dark">Dodaj</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Anuluj</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

