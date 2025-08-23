<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        // Cari product berdasarkan slug
        $product = Product::with('category')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Ambil produk terkait dari kategori yang sama
        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function index()
    {
        // Halaman daftar semua produk (opsional)
        $products = Product::with('category')
            ->where('is_active', true)
            ->paginate(12);

        return view('product.index', compact('products'));
    }
}
