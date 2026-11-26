@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Kategorie</h4>
    <a href="{{ route('manage.products.index') }}" class="btn btn-outline-secondary">Wróć do produktów</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="POST" action="{{ route('manage.categories.store') }}" class="row g-2">
            @csrf
            <div class="col-md-8">
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nazwa nowej kategorii" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-dark">+ Dodaj kategorię</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nazwa</th>
                    <th>Produkty</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->products_count }}</td>
                        <td>
                            <form method="POST" action="{{ route('manage.categories.destroy', $category) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Na pewno usunąć tę kategorię?')">Usuń</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Brak kategorii. Dodaj pierwszą powyżej.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
