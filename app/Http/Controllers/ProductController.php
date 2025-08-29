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

    public function calculatePrice(Request $request, Product $product)
    {
        try {
            // Validasi input dari frontend
            $validatedData = $request->validate([
                'quantity' => 'required|integer|min:1',
                'custom_size.width' => 'nullable|numeric',
                'custom_size.length' => 'nullable|numeric',
                'material' => 'nullable|string',
                'finishing' => 'nullable|array',
                'design_service' => 'nullable|boolean',
            ]);

            // Panggil method yang sudah ada di model Product
            $priceDetails = $product->calculatePrice($validatedData);

            return response()->json([
                'success' => true,
                'data' => $priceDetails
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data input tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung harga: ' . $e->getMessage()
            ], 400);
        }
    }
}
