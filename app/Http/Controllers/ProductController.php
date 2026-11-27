<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Zarządzanie produktami (moderator i administrator)
class ProductController extends Controller
{
    // Reguły walidacji używane przy dodawaniu i edycji
    private function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            // wyrażenie regularne: litery, cyfry, spacje i podstawowe znaki
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\p{N}\s\-\.\,\/\(\)]+$/u'],
            'description' => 'nullable|string|max:1000',
            // wyrażenie regularne: liczba z maksymalnie 2 miejscami po kropce
            'price' => ['required', 'numeric', 'min:0.01', 'max:99999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            'stock' => 'required|integer|min:0|max:100000',
        ];
    }

    private function messages(): array
    {
        return [
            'category_id.required' => 'Wybierz kategorię.',
            'category_id.exists' => 'Wybrana kategoria nie istnieje.',
            'name.required' => 'Podaj nazwę produktu.',
            'name.regex' => 'Nazwa zawiera niedozwolone znaki.',
            'name.max' => 'Nazwa może mieć maksymalnie 255 znaków.',
            'description.max' => 'Opis może mieć maksymalnie 1000 znaków.',
            'price.required' => 'Podaj cenę.',
            'price.numeric' => 'Cena musi być liczbą.',
            'price.min' => 'Cena musi być większa od zera.',
            'price.regex' => 'Cena może mieć maksymalnie 2 miejsca po kropce (np. 19.99).',
            'stock.required' => 'Podaj ilość w magazynie.',
            'stock.integer' => 'Ilość musi być liczbą całkowitą.',
            'stock.min' => 'Ilość nie może być ujemna.',
        ];
    }

    public function index()
    {
        $products = Product::with('category')->orderBy('id', 'desc')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', ['product' => new Product(), 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        Product::create($data);

        return redirect()->route('manage.products.index')->with('success', 'Produkt został dodany.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate($this->rules(), $this->messages());

        $product->update($data);

        return redirect()->route('manage.products.index')->with('success', 'Produkt został zaktualizowany.');
    }

    public function destroy(Product $product)
    {
        // produktu, który był już zamówiony, nie usuwamy - zniknęłaby historia zamówień
        if (Schema::hasTable('order_items') && DB::table('order_items')->where('product_id', $product->id)->exists()) {
            return redirect()->route('manage.products.index')
                ->with('error', 'Nie można usunąć produktu, który był już zamówiony. Ustaw jego ilość w magazynie na 0.');
        }

        $product->delete();

        return redirect()->route('manage.products.index')->with('success', 'Produkt został usunięty.');
    }
}
