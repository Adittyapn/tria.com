<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function show($slug)
    {
        // Cari product berdasarkan slug
        $product = Product::with(['category'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Ambil produk terkait dari kategori yang sama
        $relatedProducts = Product::with(['category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function index(Request $request)
    {
        $query = Product::with(['category'])
            ->where('is_active', true);

        // Handle search parameter with flexible matching
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = trim(strtolower($request->search));
            
            // Prepare flexible search variations
            $searchTerms = [];
            $searchTerms[] = $searchTerm;
            
            // For compound words, try different separations
            if (strpos($searchTerm, '-') === false && strpos($searchTerm, ' ') === false && strlen($searchTerm) > 2) {
                // Try adding hyphen after first character (e.g., 'xbanner' -> 'x-banner')
                if (preg_match('/^([a-zA-Z])([a-zA-Z]{2,})$/i', $searchTerm, $matches)) {
                    $searchTerms[] = $matches[1] . '-' . $matches[2];
                }
                
                // Try adding space after first character (e.g., 'xbanner' -> 'x banner')
                if (preg_match('/^([a-zA-Z])([a-zA-Z]{2,})$/i', $searchTerm, $matches)) {
                    $searchTerms[] = $matches[1] . ' ' . $matches[2];
                }
            }
            
            // Add query without spaces and hyphens for reverse matching
            $noSeparatorQuery = str_replace([' ', '-', '_'], '', $searchTerm);
            if ($noSeparatorQuery !== $searchTerm) {
                $searchTerms[] = $noSeparatorQuery;
            }
            
            // Add query with spaces replaced by hyphens and vice versa
            if (strpos($searchTerm, ' ') !== false) {
                $searchTerms[] = str_replace(' ', '-', $searchTerm);
            }
            if (strpos($searchTerm, '-') !== false) {
                $searchTerms[] = str_replace('-', ' ', $searchTerm);
            }
            
            // Remove duplicates
            $searchTerms = array_unique($searchTerms);
            
            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('name', 'LIKE', "%{$term}%")
                      ->orWhere('short_description', 'LIKE', "%{$term}%")
                      ->orWhere('sku', 'LIKE', "%{$term}%")
                      ->orWhereHas('category', function ($categoryQuery) use ($term) {
                          $categoryQuery->where('name', 'LIKE', "%{$term}%");
                      });
                }
            });
        }

        // Handle category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('category', function ($categoryQuery) use ($request) {
                $categoryQuery->where('slug', $request->category);
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderByRaw('COALESCE(promo_price, base_price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(promo_price, base_price) DESC');
                break;
            case 'name':
                $query->orderBy('name', 'ASC');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'DESC')->orderBy('created_at', 'DESC');
                break;
            default: // newest
                $query->orderBy('created_at', 'DESC');
        }

        $products = $query->paginate(12);

        // Get all categories for filter
        $categories = \App\Models\Category::where('is_active', true)
            ->withCount(['products' => function($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();

        return view('products.index', compact('products', 'categories'));
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

    /**
     * Get recommended products based on current product's category
     */
    public function getRecommendedProducts(Request $request, $categoryId = null)
    {
        try {
            // If category is not provided, try to get from current product
            if (!$categoryId) {
                $currentProductId = $request->get('current_product_id');
                if ($currentProductId) {
                    $currentProduct = Product::find($currentProductId);
                    $categoryId = $currentProduct?->category_id;
                }
            }

            // Get products from the same category
            $query = Product::with(['category'])
                ->where('is_active', true);

            if ($categoryId) {
                $query->where('category_id', $categoryId);
                
                // Exclude current product if provided
                if ($request->get('current_product_id')) {
                    $query->where('id', '!=', $request->get('current_product_id'));
                }
            }

            $products = $query->limit(6)
                ->orderBy('is_featured', 'desc')
                ->orderByRaw('RAND()')
                ->get();

            // Format products for frontend
            $formattedProducts = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => 'Rp ' . number_format($product->final_price, 0, ',', '.'),
                    'original_price' => $product->promo_price ? 'Rp ' . number_format($product->base_price, 0, ',', '.') : null,
                    'discount' => $product->discount_percentage,
                    'image' => $product->featured_image ? asset('storage/' . $product->featured_image) : asset('images/default-product.png'),
                    'rating' => '5.0', // You can implement actual rating system later
                    'sold' => rand(1, 100) . '+', // You can implement actual sold count later
                    'location' => 'Kota Administrator Jakarta Selatan', // You can make this dynamic later
                    'category' => $product->category?->name ?? 'Produk',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedProducts,
                'category_id' => $categoryId,
                'total' => $formattedProducts->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get recommended products: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat produk rekomendasi',
                'data' => [],
                'error' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Search products for autocomplete dropdown
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            if (strlen($query) < 2) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            // Clean and prepare search terms for flexible matching
            $cleanQuery = trim(strtolower($query));
            $searchTerms = [];
            
            // Add original query
            $searchTerms[] = $cleanQuery;
            
            // For compound words, try different separations
            if (strpos($cleanQuery, '-') === false && strpos($cleanQuery, ' ') === false && strlen($cleanQuery) > 2) {
                // Try adding hyphen after first character (e.g., 'xbanner' -> 'x-banner')
                if (preg_match('/^([a-zA-Z])([a-zA-Z]{2,})$/i', $cleanQuery, $matches)) {
                    $searchTerms[] = $matches[1] . '-' . $matches[2];
                }
                
                // Try adding space after first character (e.g., 'xbanner' -> 'x banner')
                if (preg_match('/^([a-zA-Z])([a-zA-Z]{2,})$/i', $cleanQuery, $matches)) {
                    $searchTerms[] = $matches[1] . ' ' . $matches[2];
                }
            }
            
            // Add query without spaces and hyphens for reverse matching
            $noSeparatorQuery = str_replace([' ', '-', '_'], '', $cleanQuery);
            if ($noSeparatorQuery !== $cleanQuery) {
                $searchTerms[] = $noSeparatorQuery;
            }
            
            // Add query with spaces replaced by hyphens and vice versa
            if (strpos($cleanQuery, ' ') !== false) {
                $searchTerms[] = str_replace(' ', '-', $cleanQuery);
            }
            if (strpos($cleanQuery, '-') !== false) {
                $searchTerms[] = str_replace('-', ' ', $cleanQuery);
            }

            // Remove duplicates
            $searchTerms = array_unique($searchTerms);

            $products = Product::with(['category'])
                ->where('is_active', true)
                ->where(function ($q) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $q->orWhere('name', 'LIKE', "%{$term}%")
                          ->orWhere('short_description', 'LIKE', "%{$term}%")
                          ->orWhere('sku', 'LIKE', "%{$term}%")
                          ->orWhereHas('category', function ($categoryQuery) use ($term) {
                              $categoryQuery->where('name', 'LIKE', "%{$term}%");
                          });
                    }
                })
                ->orderBy('is_featured', 'desc')
                ->orderBy('name', 'asc')
                ->limit(8)
                ->get();

            $formattedProducts = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => 'Rp ' . number_format($product->final_price, 0, ',', '.'),
                    'original_price' => $product->promo_price ? 'Rp ' . number_format($product->base_price, 0, ',', '.') : null,
                    'discount' => $product->discount_percentage,
                    'image' => $product->featured_image ? asset('storage/' . $product->featured_image) : asset('images/default-product.png'),
                    'category' => $product->category?->name ?? 'Produk',
                    'url' => route('products.show', $product->slug)
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedProducts,
                'query' => $query,
                'total' => $formattedProducts->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari produk',
                'data' => []
            ], 500);
        }
    }

    /**
     * Show products by category
     */
    public function categoryProducts($slug)
    {
        // Cari kategori berdasarkan slug
        $category = \App\Models\Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Ambil produk dari kategori tersebut
        $products = Product::with(['category'])
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Ambil semua kategori untuk sidebar filter
        $categories = \App\Models\Category::where('is_active', true)
            ->withCount(['products' => function($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();

        return view('categories.products', compact('category', 'products', 'categories'));
    }
}
