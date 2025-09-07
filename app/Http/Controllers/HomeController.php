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

        // Ambil product types untuk filter
        $productTypes = Product::where('is_active', true)
            ->whereNotNull('product_type')
            ->select('product_type')
            ->distinct()
            ->orderBy('product_type')
            ->pluck('product_type')
            ->map(function ($type) {
                return [
                    'slug' => $type,
                    'name' => ucwords(str_replace(['-', '_'], ' ', $type))
                ];
            });

        // Ambil produk featured untuk carousel
        $featuredProducts = Product::with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->limit(6)
            ->get();

        // Ambil data carousel slides dengan produk
        $carouselSlides = $this->buildCarouselSlides($featuredProducts);

        // Ambil kategori untuk categories section
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('home.index', compact('products', 'productTypes', 'carouselSlides', 'categories'));
    }

    private function buildCarouselSlides($featuredProducts)
    {
        $baseSlides = config('carousel');
        
        // Jika tidak ada featured products, buat dummy products
        if ($featuredProducts->isEmpty()) {
            $dummyProducts = collect();
            for ($i = 0; $i < 12; $i++) {
                $dummyProducts->push((object)[
                    'id' => $i + 1,
                    'name' => "Produk Digital Print " . ($i + 1),
                    'slug' => 'produk-' . ($i + 1),
                    'image_url' => null,
                    'final_price' => null,
                    'discount_percentage' => null,
                    'category' => (object)['name' => 'Digital Print']
                ]);
            }
            $featuredProducts = $dummyProducts;
        }
        
        // Tambahkan produk ke setiap slide
        foreach ($baseSlides as $index => &$slide) {
            // Ambil 4 produk untuk setiap slide
            $slideProducts = $featuredProducts->skip($index * 4)->take(4);
            
            // Map rightContent structure properly
            $slide['rightContent'] = [
                'title' => $slide['rightContent']['header']['title'] ?? 'Produk Unggulan',
                'subtitle' => $slide['rightContent']['header']['subtitle'] ?? 'Kualitas terbaik, harga terjangkau',
                'products' => $slideProducts->map(function ($product, $productIndex) use ($index) {
                    // Generate unique Picsum images for each product
                    $imageId = 400 + ($index * 4) + $productIndex;
                    $dummyImage = "https://picsum.photos/400/300?random={$imageId}";
                    
                    return [
                        'id' => $product->id ?? rand(1, 1000),
                        'name' => $product->name ?? "Produk Digital Print " . ($productIndex + 1),
                        'slug' => $product->slug ?? 'product-' . ($productIndex + 1),
                        'image' => $product->image_url ?? $dummyImage,
                        'price' => $product->final_price ?? 'Rp ' . number_format(rand(50000, 500000), 0, ',', '.'),
                        'category' => $product->category->name ?? 'Digital Print',
                        'discount' => ($product->discount_percentage ?? rand(10, 30)) . '%',
                    ];
                })->toArray()
            ];
        }
        
        return $baseSlides;
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

    // API endpoint untuk filter berdasarkan product type
    public function getProductsByType(Request $request)
    {
        $productType = $request->get('product_type');

        $query = Product::with('category')->where('is_active', true);

        if ($productType && $productType !== 'all') {
            $query->where('product_type', $productType);
        }

        $products = $query->get();

        return response()->json($products);
    }
}
