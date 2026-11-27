<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopper</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">Shopper</a>
        <div class="ms-auto d-flex align-items-center gap-3">
            @auth
                <span class="text-white-50">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                <a href="{{ route('cart.index') }}" class="btn btn-sm btn-outline-light">Koszyk</a>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-light">Moje zamówienia</a>
                @if(auth()->user()->isAdmin() || auth()->user()->isModerator())
                     <a href="{{ route('manage.products.index') }}" class="btn btn-sm btn-outline-light">Produkty</a>
                @endif
                
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-light">Panel admina</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger">Wyloguj</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">Zaloguj</a>
                <a href="{{ route('register') }}" class="btn btn-sm btn-light">Rejestracja</a>
            @endauth
        </div>
    </div>
</nav>

<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @yield('content')
</div>

</body>
</html>

