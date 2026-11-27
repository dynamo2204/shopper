<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Koszyk jest pusty.');
        }

        $checkoutData = session()->get('checkout_data', []);

        return view('checkout.index', compact('cart', 'checkoutData'));
    }

    public function prepare(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Koszyk jest pusty.');
        }

        $validated = $request->validate([
    'shipping_first_name' => ['required', 'string', 'max:100', 'regex:/^[\p{L} -]+$/u'],
    'shipping_last_name' => ['required', 'string', 'max:100', 'regex:/^[\p{L} -]+$/u'],
    'shipping_phone' => ['required', 'regex:/^[0-9]{9}$/'],
    'shipping_email' => ['required', 'email', 'max:255'],
    'shipping_address' => ['required', 'string', 'max:255'],
    'shipping_postal_code' => ['required', 'regex:/^[0-9]{2}-[0-9]{3}$/'],
    'shipping_city' => ['required', 'string', 'max:100', 'regex:/^[\p{L} -]+$/u'],
    'customer_notes' => ['nullable', 'string', 'max:1000'],

    'invoice_requested' => ['nullable', 'boolean'],
    'invoice_company' => ['required_if:invoice_requested,1', 'nullable', 'string', 'max:255'],
    'invoice_nip' => ['required_if:invoice_requested,1', 'nullable', 'regex:/^[0-9]{10}$/'],
    'invoice_address' => ['required_if:invoice_requested,1', 'nullable', 'string', 'max:255'],
    'invoice_postal_code' => ['required_if:invoice_requested,1', 'nullable', 'regex:/^[0-9]{2}-[0-9]{3}$/'],
    'invoice_city' => ['required_if:invoice_requested,1', 'nullable', 'string', 'max:100', 'regex:/^[\p{L} -]+$/u'],
], [
    // Imię i nazwisko
    'shipping_first_name.required' => 'Podaj imię odbiorcy.',
    'shipping_first_name.regex' => 'Imię może zawierać tylko litery, spacje i myślniki.',
    'shipping_last_name.required' => 'Podaj nazwisko odbiorcy.',
    'shipping_last_name.regex' => 'Nazwisko może zawierać tylko litery, spacje i myślniki.',

    // Telefon
    'shipping_phone.required' => 'Podaj numer telefonu.',
    'shipping_phone.regex' => 'Numer telefonu musi zawierać dokładnie 9 cyfr.',

    // E-mail
    'shipping_email.required' => 'Podaj adres e-mail.',
    'shipping_email.email' => 'Podaj poprawny adres e-mail, np. klient@email.pl.',

    // Adres
    'shipping_address.required' => 'Podaj ulicę i numer budynku.',
    'shipping_postal_code.required' => 'Podaj kod pocztowy.',
    'shipping_postal_code.regex' => 'Kod pocztowy wpisz w formacie 00-000.',
    'shipping_city.required' => 'Podaj miejscowość.',
    'shipping_city.regex' => 'Miejscowość może zawierać tylko litery, spacje i myślniki.',

    // Uwagi
    'customer_notes.max' => 'Uwagi mogą mieć maksymalnie 1000 znaków.',

    // Faktura
    'invoice_company.required_if' => 'Podaj nazwę firmy do faktury.',
    'invoice_nip.required_if' => 'Podaj NIP firmy.',
    'invoice_nip.regex' => 'NIP musi zawierać dokładnie 10 cyfr.',
    'invoice_address.required_if' => 'Podaj adres do faktury.',
    'invoice_postal_code.required_if' => 'Podaj kod pocztowy do faktury.',
    'invoice_postal_code.regex' => 'Kod pocztowy faktury wpisz w formacie 00-000.',
    'invoice_city.required_if' => 'Podaj miejscowość do faktury.',
    'invoice_city.regex' => 'Miejscowość faktury może zawierać tylko litery, spacje i myślniki.',
]);

        $validated['invoice_requested'] = $request->boolean('invoice_requested');
        if (!$validated['invoice_requested']) {
    unset(
        $validated['invoice_company'],
        $validated['invoice_nip'],
        $validated['invoice_address'],
        $validated['invoice_postal_code'],
        $validated['invoice_city']
    );
}
        $validated['shipping_method'] = 'Kurier';
        $validated['shipping_cost'] = 14.00;
        $validated['payment_method'] = 'Przelew tradycyjny';

        session()->put('checkout_data', $validated);

        $products = [];

        foreach ($cart as $id => $item) {
            $product = Product::find($id);

            if (!$product || $product->stock < $item['quantity']) {
                return redirect()->route('cart.index')
                    ->with('error', 'Jeden z produktów nie jest już dostępny w wymaganej ilości.');
            }

            $products[] = [
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $item['quantity'],
            ];
        }

        $subtotal = round(collect($products)->sum(
            fn ($item) => $item['price'] * $item['quantity']
        ), 2);

        $shippingCost = 14.00;
        $total = round($subtotal + $shippingCost, 2);

        // Kwoty brutto zawierają VAT 23%.
        $vatRate = 0.23;

        $productsNet = round($subtotal / (1 + $vatRate), 2);
        $productsVat = round($subtotal - $productsNet, 2);

        $shippingNet = round($shippingCost / (1 + $vatRate), 2);
        $shippingVat = round($shippingCost - $shippingNet, 2);

        $totalNet = round($productsNet + $shippingNet, 2);
        $totalVat = round($productsVat + $shippingVat, 2);

        return view('checkout.summary', compact(
            'validated',
            'products',
            'subtotal',
            'shippingCost',
            'total',
            'productsNet',
            'productsVat',
            'shippingNet',
            'shippingVat',
            'totalNet',
            'totalVat'
        ));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        $checkoutData = session()->get('checkout_data');

        if (empty($cart) || empty($checkoutData)) {
            return redirect()->route('cart.index')
                ->with('error', 'Brak danych zamówienia. Przejdź ponownie do formularza.');
        }

        try {
            $order = DB::transaction(function () use ($cart, $checkoutData, $request) {
                $subtotal = 0;
                $products = [];

                foreach ($cart as $id => $item) {
                    $product = Product::whereKey($id)->lockForUpdate()->firstOrFail();

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception('Brak wystarczającej ilości produktu: ' . $product->name);
                    }

                    $subtotal += $product->price * $item['quantity'];

                    $products[] = [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                    ];
                }

                $shippingCost = 14.00;
                $total = round($subtotal + $shippingCost, 2);

                $order = Order::create([
                    'user_id' => $request->user()->id,
                    'total' => $total,
                    'status' => 'pending',

                    'shipping_first_name' => $checkoutData['shipping_first_name'],
                    'shipping_last_name' => $checkoutData['shipping_last_name'],
                    'shipping_phone' => $checkoutData['shipping_phone'],
                    'shipping_email' => $checkoutData['shipping_email'],
                    'shipping_address' => $checkoutData['shipping_address'],
                    'shipping_postal_code' => $checkoutData['shipping_postal_code'],
                    'shipping_city' => $checkoutData['shipping_city'],
                    'shipping_method' => 'Kurier',
                    'shipping_cost' => $shippingCost,

                    'customer_notes' => $checkoutData['customer_notes'] ?? null,

                    'invoice_requested' => $checkoutData['invoice_requested'],
                    'invoice_company' => $checkoutData['invoice_company'] ?? null,
                    'invoice_nip' => $checkoutData['invoice_nip'] ?? null,
                    'invoice_address' => $checkoutData['invoice_address'] ?? null,
                    'invoice_postal_code' => $checkoutData['invoice_postal_code'] ?? null,
                    'invoice_city' => $checkoutData['invoice_city'] ?? null,

                    'payment_method' => 'Przelew tradycyjny',
                ]);

                foreach ($products as $item) {
                    $product = $item['product'];

                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ]);

                    $product->decrement('stock', $item['quantity']);
                }

                return $order;
            });
        } catch (\Exception $e) {
            return redirect()->route('cart.index')
                ->with('error', $e->getMessage());
        }

        session()->forget('cart');
        session()->forget('checkout_data');

        return redirect()->route('orders.index')
            ->with('success', 'Zamówienie #' . $order->id . ' zostało złożone.');
    }

    public function index(Request $request)
    {
        $orders = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }
}