
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Koszyk jest pusty.');
        }

        try {
            DB::transaction(function () use ($cart, $request) {
                $total = 0;

                foreach ($cart as $id => $item) {
                    $product = Product::whereKey($id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception(
                            'Brak wystarczającej ilości produktu: ' . $product->name
                        );
                    }

                    $total += $product->price * $item['quantity'];
                }

                $order = Order::create([
                    'user_id' => $request->user()->id,
                    'total' => $total,
                    'status' => 'pending',
                ]);

                foreach ($cart as $id => $item) {
                    $product = Product::whereKey($id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ]);

                    $product->decrement('stock', $item['quantity']);
                }
            });
        } catch (\Exception $e) {
            return redirect()->route('cart.index')
                ->with('error', $e->getMessage());
        }

        session()->forget('cart');

        return redirect()->route('dashboard')
            ->with('success', 'Zamówienie zostało złożone.');
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
