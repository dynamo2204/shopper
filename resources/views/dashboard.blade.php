@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col">
        <h4>Witaj, {{ auth()->user()->name }}!</h4>
        <p class="text-muted">Rola: <strong>{{ auth()->user()->role }}</strong></p>

        <div class="card mt-3">
            <div class="card-body">
                <p>Tu będą produkty sklepu (zrobi Osoba 2).</p>
            </div>
        </div>
    </div>
</div>
@endsection

