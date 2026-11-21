@extends('layouts.app')

@section('content')
<h4>Witaj, {{ auth()->user()->name }}!</h4>
<p class="text-muted">Rola: <strong>{{ auth()->user()->role }}</strong></p>

{{-- Wyszukiwanie i filtrowanie produktów --}}
<form method="GET" action="{{ route('dashboard') }}" class="row g-2 mb-4">
    <div class="col-md-5">
        <input type="text" name="q" class="form-control" placeholder="Szukaj produktu..." value="{{ request('q') }}">
    </div>
    <div class="col-md-4">
        <select name="category" class="form-select">
            <option value="">Wszystkie kategorie</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-dark">Szukaj</button>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Wyczyść</a>
    </div>
</form>

{{-- Lista produktów --}}
<div class="row row-cols-1 row-cols-md-3 g-4">
    @forelse($products as $product)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <span class="badge bg-secondary mb-2">{{ $product->category->name }}</span>
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted">{{ $product->description }}</p>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>{{ number_format($product->price, 2, ',', ' ') }} zł</strong>
                        @if($product->stock > 0)
                            <small class="text-success">Dostępne: {{ $product->stock }} szt.</small>
                        @else
                            <small class="text-danger">Brak w magazynie</small>
                        @endif
                    </div>
                    {{-- TU Osoba 3 doda przycisk "Dodaj do koszyka" --}}
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">Nie znaleziono produktów.</div>
        </div>
    @endforelse
</div>
@endsection
