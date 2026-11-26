<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // wyszukiwanie po nazwie
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        // filtrowanie po kategorii
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('dashboard', compact('products', 'categories'));
    }
}
