@extends('layouts.app')

@section('content')
<h4>Moje zamówienia</h4>

@if($orders->isEmpty())
    <p class="text-muted">Nie masz jeszcze żadnych zamówień.</p>
@else
    @foreach($orders as $order)
        <div class="card mb-3">
            <div class="card-body">
                <h5>Zamówienie nr {{ $order->id }}</h5>

                <p>
                    Status: {{ $order->status }}
                </p>

                <p>
                    Data: {{ $order->created_at->format('d.m.Y H:i') }}
                </p>

                <ul>
                    @foreach($order->items as $item)
                        <li>
                            {{ $item->product->name ?? 'Produkt niedostępny' }}
                            — {{ $item->quantity }} szt.
                            — {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} zł
                        </li>
                    @endforeach
                </ul>

                <strong>
                    Łącznie: {{ number_format($order->total, 2, ',', ' ') }} zł
                </strong>
            </div>
        </div>
    @endforeach
@endif
@endsection