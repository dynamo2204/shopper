<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        if ($product->stock < 1) {
            return redirect()->route('dashboard')
                ->with('error', 'Produkt jest niedostępny.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            if ($cart[$product->id]['quantity'] >= $product->stock) {
                return redirect()->route('dashboard')
                    ->with('error', 'Brak większej ilości produktu w magazynie.');
            }

            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Dodano produkt do koszyka.');
    }

        public function remove(Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Produkt usunięto z koszyka.');
    }
    public function update(Request $request, Product $product)
{
    $request->validate([
        'quantity' => 'required|integer|min:1|max:100000',
    ]);

    $quantity = $request->input('quantity');

    if ($quantity > $product->stock) {
        return redirect()->route('cart.index')
            ->with('error', 'Brak wystarczającej ilości produktu w magazynie.');
    }

    $cart = session()->get('cart', []);

    if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity'] = $quantity;
        session()->put('cart', $cart);
    }

    return redirect()->route('cart.index')
        ->with('success', 'Zmieniono ilość produktu.');
}
}