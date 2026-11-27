@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Podsumowanie zamówienia</h2>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Produkty</h5>
        </div>

        <div class="card-body">
            @foreach ($products as $product)
                <div class="d-flex justify-content-between align-items-start border-bottom py-3">
                    <div>
                        <strong>{{ $product['name'] }}</strong>
                        <div class="text-muted">
                            {{ number_format($product['price'], 2, ',', ' ') }} zł × {{ $product['quantity'] }}
                        </div>
                    </div>

                    <div class="text-end">
                        {{ number_format($product['price'] * $product['quantity'], 2, ',', ' ') }} zł
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Dane odbiorcy</h5>
        </div>

        <div class="card-body">
            <p class="mb-1">
                <strong>{{ $validated['shipping_first_name'] }} {{ $validated['shipping_last_name'] }}</strong>
            </p>

            <p class="mb-1">{{ $validated['shipping_address'] }}</p>
            <p class="mb-1">{{ $validated['shipping_postal_code'] }} {{ $validated['shipping_city'] }}</p>
            <p class="mb-1">Telefon: {{ $validated['shipping_phone'] }}</p>
            <p class="mb-0">E-mail: {{ $validated['shipping_email'] }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Dostawa i płatność</h5>
        </div>

        <div class="card-body">
            <p class="mb-2"><strong>Dostawa:</strong> Kurier</p>
            <p class="mb-2"><strong>Koszt dostawy:</strong> {{ number_format($shippingCost, 2, ',', ' ') }} zł</p>
            <p class="mb-0"><strong>Płatność:</strong> Przelew tradycyjny</p>
        </div>
    </div>

    @if (!empty($validated['customer_notes']))
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Uwagi do zamówienia</h5>
            </div>
            <div class="card-body">
                {{ $validated['customer_notes'] }}
            </div>
        </div>
    @endif

    @if (!empty($validated['invoice_requested']))
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Dane do faktury</h5>
            </div>

            <div class="card-body">
                <p class="mb-1"><strong>{{ $validated['invoice_company'] }}</strong></p>
                <p class="mb-1">NIP: {{ $validated['invoice_nip'] }}</p>
                <p class="mb-1">{{ $validated['invoice_address'] }}</p>
                <p class="mb-0">{{ $validated['invoice_postal_code'] }} {{ $validated['invoice_city'] }}</p>
            </div>
        </div>
    @else
        <div class="card mb-4">
            <div class="card-body">
                <strong>Faktura:</strong> Nie zamówiono faktury.
            </div>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Dane do przelewu</h5>
        </div>

        <div class="card-body">
            <p class="mb-1"><strong>Odbiorca:</strong> Shopper Sp. z o.o.</p>
            <p class="mb-1"><strong>Numer rachunku:</strong> 00 0000 0000 0000 0000 0000 0000</p>
            <p class="mb-0">
    <strong>Tytuł przelewu:</strong>
    <em>W tytule przelewu prosimy wpisać numer zamówienia, który będzie widoczny po jego złożeniu.</em>
</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Rozliczenie</h5>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
                <span>Produkty – brutto</span>
                <span>{{ number_format($subtotal, 2, ',', ' ') }} zł</span>
            </div>

            <div class="d-flex justify-content-between mb-2 text-muted">
                <span>Produkty – netto</span>
                <span>{{ number_format($productsNet, 2, ',', ' ') }} zł</span>
            </div>

            <div class="d-flex justify-content-between mb-3 text-muted">
                <span>VAT od produktów (23%)</span>
                <span>{{ number_format($productsVat, 2, ',', ' ') }} zł</span>
            </div>

            <hr>

            <div class="d-flex justify-content-between mb-2">
                <span>Dostawa – brutto</span>
                <span>{{ number_format($shippingCost, 2, ',', ' ') }} zł</span>
            </div>

            <div class="d-flex justify-content-between mb-2 text-muted">
                <span>Dostawa – netto</span>
                <span>{{ number_format($shippingNet, 2, ',', ' ') }} zł</span>
            </div>

            <div class="d-flex justify-content-between mb-3 text-muted">
                <span>VAT od dostawy (23%)</span>
                <span>{{ number_format($shippingVat, 2, ',', ' ') }} zł</span>
            </div>

            <hr>

            <div class="d-flex justify-content-between mb-2">
                <strong>Łącznie netto</strong>
                <strong>{{ number_format($totalNet, 2, ',', ' ') }} zł</strong>
            </div>

            <div class="d-flex justify-content-between mb-3">
                <strong>Łącznie VAT</strong>
                <strong>{{ number_format($totalVat, 2, ',', ' ') }} zł</strong>
            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Łącznie brutto</h4>
                <h4 class="mb-0 text-success">
                    {{ number_format($total, 2, ',', ' ') }} zł
                </h4>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between flex-wrap gap-2">
        <a href="{{ route('checkout.index') }}" class="btn btn-outline-secondary">
            Wróć do formularza
        </a>

        <form method="POST" action="{{ route('order.store') }}">
            @csrf
            <button type="submit" class="btn btn-success">
                Potwierdzam i składam zamówienie
            </button>
        </form>
    </div>
</div>
@endsection