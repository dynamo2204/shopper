
@extends('layouts.app')

@section('content')
<h4>Twój koszyk</h4>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

@if(empty($cart))
    <p class="text-muted">Koszyk jest pusty.</p>
@else
    <table class="table">
        <thead>
            <tr>
                <th>Produkt</th>
                <th>Cena</th>
                <th>Ilość</th>
                <th>Wartość</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart as $id => $item)
                <tr>
                    <td>{{ $item['name'] }}</td>

                    <td>{{ number_format($item['price'], 2, ',', ' ') }} zł</td>

                    <td>
                        <form method="POST" action="{{ route('cart.update', $id) }}" class="d-flex gap-2">
                            @csrf
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control" style="width: 90px">
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                Zmień
                            </button>
                        </form>
                    </td>

                    <td>{{ number_format($item['price'] * $item['quantity'], 2, ',', ' ') }} zł</td>

                    <td>
                        <form method="POST" action="{{ route('cart.remove', $id) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                Usuń
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-end mt-3">
        <h5>
            Łącznie:
            {{ number_format(collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']), 2, ',', ' ') }} zł
        </h5>
    </div>
        <div class="text-end mt-3">
        <form method="POST" action="{{ route('order.store') }}">
            @csrf
            <button type="submit" class="btn btn-success">
                Złóż zamówienie
            </button>
        </form>
    </div>
@endif
@endsection
