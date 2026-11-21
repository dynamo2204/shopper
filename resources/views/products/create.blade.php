@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="mb-4">Dodaj produkt</h4>

                <form method="POST" action="{{ route('manage.products.store') }}">
                    @csrf

                    @include('products._fields')

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dark">Dodaj</button>
                        <a href="{{ route('manage.products.index') }}" class="btn btn-outline-secondary">Anuluj</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
