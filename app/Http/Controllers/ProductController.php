<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Data dummy untuk testing
        $products = collect([
            (object)[
                'id' => 1,
                'name' => 'Stiker Vinyl Custom',
                'slug' => 'stiker-vinyl-custom',
                'category' => 'sticker',
                'price' => 25000,
                'image' => 'https://via.placeholder.com/300x300/ff6b6b/ffffff?text=Stiker+Vinyl',
                'description' => 'Stiker vinyl berkualitas tinggi, tahan air dan cuaca',
            ],
            // Tambahkan produk lainnya sesuai kebutuhan
        ]);
        
        return view('products.index', compact('products'));
    }
    
    public function show($slug)
    {
        // Data dummy untuk testing halaman detail
        $product = (object)[
            'id' => 1,
            'name' => 'Spanduk / Banner Flexi China 280 gsm Glossy',
            'slug' => 'spanduk-banner-flexi-china',
            'category' => 'banner',
            'price' => 20000,
            'description' => 'Banner Spanduk Flexi China 280 gsm Glossy adalah pilihan terbaik untuk kebutuhan promosi outdoor dan indoor Anda.',
            'image' => 'https://via.placeholder.com/600x400/ff6b6b/ffffff?text=Spanduk+Banner+Main',
        ];
        
        // Cek apakah slug sesuai
        if ($slug !== $product->slug) {
            abort(404);
        }
        
        return view('products.show', compact('product'));
    }
}