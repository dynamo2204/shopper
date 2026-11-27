@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Dane do zamówienia</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Popraw błędy w formularzu:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.prepare') }}" novalidate>
        @csrf

        <h4 class="mb-3">Dane odbiorcy</h4>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Imię *</label>
                <input type="text" name="shipping_first_name"
                    class="form-control @error('shipping_first_name') is-invalid @enderror"
                    value="{{ old('shipping_first_name', $checkoutData['shipping_first_name'] ?? '') }}"
                    pattern="[A-Za-zĄąĆćĘęŁłŃńÓóŚśŹźŻż -]+"
                    required>
                @error('shipping_first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Nazwisko *</label>
                <input type="text" name="shipping_last_name"
                    class="form-control @error('shipping_last_name') is-invalid @enderror"
                    value="{{ old('shipping_last_name', $checkoutData['shipping_last_name'] ?? '') }}"
                    pattern="[A-Za-zĄąĆćĘęŁłŃńÓóŚśŹźŻż -]+"
                    required>
                @error('shipping_last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Telefon *</label>
                <input type="tel" name="shipping_phone"
                    class="form-control @error('shipping_phone') is-invalid @enderror"
                    value="{{ old('shipping_phone', $checkoutData['shipping_phone'] ?? '') }}"
                    inputmode="numeric"
                    maxlength="9"
                    pattern="[0-9]{9}"
                    placeholder="123456789"
                    required>
                <small class="text-muted">Podaj dokładnie 9 cyfr.</small>
                @error('shipping_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">E-mail *</label>
                <input type="email" name="shipping_email"
                    class="form-control @error('shipping_email') is-invalid @enderror"
                    value="{{ old('shipping_email', $checkoutData['shipping_email'] ?? '') }}"
                    required>
                @error('shipping_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <label class="form-label">Adres (ulica i numer) *</label>
                <input type="text" name="shipping_address"
                    class="form-control @error('shipping_address') is-invalid @enderror"
                    value="{{ old('shipping_address', $checkoutData['shipping_address'] ?? '') }}"
                    placeholder="np. Słowiańska 12/4"
                    required>
                @error('shipping_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Kod pocztowy *</label>
                <input type="text" name="shipping_postal_code"
                    class="form-control @error('shipping_postal_code') is-invalid @enderror"
                    value="{{ old('shipping_postal_code', $checkoutData['shipping_postal_code'] ?? '') }}"
                    pattern="[0-9]{2}-[0-9]{3}"
                    maxlength="6"
                    placeholder="64-100"
                    required>
                @error('shipping_postal_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-8 mb-3">
                <label class="form-label">Miejscowość *</label>
                <input type="text" name="shipping_city"
                    class="form-control @error('shipping_city') is-invalid @enderror"
                    value="{{ old('shipping_city', $checkoutData['shipping_city'] ?? '') }}"
                    pattern="[A-Za-zĄąĆćĘęŁłŃńÓóŚśŹźŻż -]+"
                    required>
                @error('shipping_city')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <hr class="my-4">

        <h4 class="mb-3">Dostawa i płatność</h4>

        <div class="card p-3 mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" checked disabled>
                <label class="form-check-label">
                    <strong>Kurier</strong> – 14,00 zł
                </label>
            </div>
        </div>

        <div class="card p-3 mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" checked disabled>
                <label class="form-check-label">
                    <strong>Przelew tradycyjny</strong>
                </label>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Uwagi do zamówienia (opcjonalnie)</label>
            <textarea name="customer_notes" class="form-control" rows="3"
                maxlength="1000">{{ old('customer_notes', $checkoutData['customer_notes'] ?? '') }}</textarea>
        </div>

        <hr class="my-4">

        <h4 class="mb-3">Faktura</h4>

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="invoice_requested"
                name="invoice_requested" value="1"
                {{ old('invoice_requested', $checkoutData['invoice_requested'] ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="invoice_requested">
                Chcę otrzymać fakturę
            </label>
        </div>

        <div id="invoiceFields" style="display: none;">
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label">Nazwa firmy *</label>
                    <input type="text" name="invoice_company"
                        class="form-control"
                        value="{{ old('invoice_company', $checkoutData['invoice_company'] ?? '') }}">
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">NIP *</label>
                    <input type="text" name="invoice_nip"
                        class="form-control"
                        value="{{ old('invoice_nip', $checkoutData['invoice_nip'] ?? '') }}"
                        maxlength="10"
                        inputmode="numeric"
                        pattern="[0-9]{10}"
                        placeholder="10 cyfr">
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Adres faktury *</label>
                    <input type="text" name="invoice_address"
                        class="form-control"
                        value="{{ old('invoice_address', $checkoutData['invoice_address'] ?? '') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Kod pocztowy *</label>
                    <input type="text" name="invoice_postal_code"
                        class="form-control"
                        value="{{ old('invoice_postal_code', $checkoutData['invoice_postal_code'] ?? '') }}"
                        pattern="[0-9]{2}-[0-9]{3}"
                        maxlength="6"
                        placeholder="64-100">
                </div>

                <div class="col-md-8 mb-3">
                    <label class="form-label">Miejscowość *</label>
                    <input type="text" name="invoice_city"
                        class="form-control"
                        value="{{ old('invoice_city', $checkoutData['invoice_city'] ?? '') }}"
                        pattern="[A-Za-zĄąĆćĘęŁłŃńÓóŚśŹźŻż -]+">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between flex-wrap gap-2 mt-4">
            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                Wróć do koszyka
            </a>

            <button type="submit" class="btn btn-primary">
                Przejdź do podsumowania
            </button>
        </div>
    </form>
</div>

<script>
    const invoiceCheckbox = document.getElementById('invoice_requested');
    const invoiceFields = document.getElementById('invoiceFields');

    function toggleInvoiceFields() {
        const enabled = invoiceCheckbox.checked;

        invoiceFields.style.display = enabled ? 'block' : 'none';

        invoiceFields.querySelectorAll('input').forEach(input => {
            input.required = enabled;
            input.disabled = !enabled;

            if (!enabled) {
                input.value = '';
            }
        });
    }

    invoiceCheckbox.addEventListener('change', toggleInvoiceFields);
    toggleInvoiceFields();
</script>
@endsection