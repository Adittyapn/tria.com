<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil produk aktif dengan kategori
        $products = Product::with('category')
            ->where('is_active', true)
            ->orderBy('is_featured', 'desc') // Produk featured di atas
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil kategori untuk filter
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('home.index', compact('products', 'categories'));
    }

    // API endpoint untuk AJAX filter (opsional)
    public function getProductsByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');

        $query = Product::with('category')->where('is_active', true);

        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        $products = $query->get();

        return response()->json($products);
    }
}
