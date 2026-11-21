<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $elektronika = Category::create(['name' => 'Elektronika']);
        $ksiazki = Category::create(['name' => 'Książki']);
        $dom = Category::create(['name' => 'Dom i ogród']);
        $sport = Category::create(['name' => 'Sport']);

        Product::create(['category_id' => $elektronika->id, 'name' => 'Słuchawki bezprzewodowe', 'description' => 'Słuchawki Bluetooth z mikrofonem.', 'price' => 199.99, 'stock' => 15]);
        Product::create(['category_id' => $elektronika->id, 'name' => 'Powerbank 10000 mAh', 'description' => 'Mały powerbank z dwoma portami USB.', 'price' => 89.00, 'stock' => 30]);
        Product::create(['category_id' => $ksiazki->id, 'name' => 'Programowanie w PHP', 'description' => 'Podręcznik dla początkujących.', 'price' => 59.90, 'stock' => 10]);
        Product::create(['category_id' => $ksiazki->id, 'name' => 'Algorytmy i struktury danych', 'description' => 'Klasyczny podręcznik informatyki.', 'price' => 79.50, 'stock' => 8]);
        Product::create(['category_id' => $dom->id, 'name' => 'Lampka biurkowa LED', 'description' => 'Regulowana jasność, zasilanie USB.', 'price' => 49.99, 'stock' => 20]);
        Product::create(['category_id' => $dom->id, 'name' => 'Doniczka ceramiczna', 'description' => 'Średnica 20 cm.', 'price' => 24.00, 'stock' => 25]);
        Product::create(['category_id' => $sport->id, 'name' => 'Mata do jogi', 'description' => 'Antypoślizgowa, grubość 6 mm.', 'price' => 69.00, 'stock' => 12]);
        Product::create(['category_id' => $sport->id, 'name' => 'Skakanka', 'description' => 'Regulowana długość.', 'price' => 19.99, 'stock' => 0]);
    }
}
