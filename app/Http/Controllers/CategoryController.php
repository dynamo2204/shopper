<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

// Zarządzanie kategoriami (moderator i administrator)
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name', 'regex:/^[\p{L}\p{N}\s\-]+$/u'],
        ], [
            'name.required' => 'Podaj nazwę kategorii.',
            'name.unique' => 'Taka kategoria już istnieje.',
            'name.regex' => 'Nazwa zawiera niedozwolone znaki.',
            'name.max' => 'Nazwa może mieć maksymalnie 100 znaków.',
        ]);

        Category::create(['name' => $request->name]);

        return redirect()->route('manage.categories.index')->with('success', 'Kategoria została dodana.');
    }

    public function destroy(Category $category)
    {
        // nie usuwamy kategorii, w której są produkty
        if ($category->products()->exists()) {
            return redirect()->route('manage.categories.index')
                ->withErrors(['name' => 'Nie można usunąć kategorii, w której są produkty.']);
        }

        $category->delete();

        return redirect()->route('manage.categories.index')->with('success', 'Kategoria została usunięta.');
    }
}
