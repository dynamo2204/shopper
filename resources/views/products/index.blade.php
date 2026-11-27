@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Zarządzanie produktami</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('manage.categories.index') }}" class="btn btn-outline-dark">Kategorie</a>
        <a href="{{ route('manage.products.create') }}" class="btn btn-dark">+ Dodaj produkt</a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nazwa</th>
                    <th>Kategoria</th>
                    <th>Cena</th>
                    <th>Magazyn</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ number_format($product->price, 2, ',', ' ') }} zł</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <a href="{{ route('manage.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edytuj</a>
                            <form method="POST" action="{{ route('manage.products.destroy', $product) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Na pewno usunąć ten produkt?')">Usuń</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Brak produktów.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
