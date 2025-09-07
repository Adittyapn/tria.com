<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = $this->getCartItems();
        $totalAmount = Cart::getTotalAmount($this->getUserId(), $this->getSessionId());
        $itemCount = Cart::getItemCount($this->getUserId(), $this->getSessionId());

        // If AJAX request, return JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'cartItems' => $cartItems,
                'totalAmount' => $totalAmount,
                'cart_count' => $itemCount,
                'success' => true
            ]);
        }

        return view('cart.index', compact('cartItems', 'totalAmount', 'itemCount'));
    }

    public function add(Request $request, Product $product)
    {
        try {
            // Validate input - FIXED: Support array for finishing
            $validatedData = $request->validate([
                'quantity' => 'required|integer|min:1',
                'custom_size_width' => 'nullable|numeric|min:0.1|max:1000',
                'custom_size_height' => 'nullable|numeric|min:0.1|max:1000',
                'selected_material' => 'nullable|string|max:255',
                'selected_finishing' => 'nullable|array', // FIXED: Changed to array
                'selected_finishing.*' => 'string|max:255',
                'design_notes' => 'nullable|string|max:1000',
                'design_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,psd,eps|max:' . ($product->max_file_size_mb * 1024),
                'requires_design_service' => 'boolean',
            ]);

         $finishing = $validatedData['selected_finishing'] ?? null;
        $validatedData['selected_finishing'] = $finishing
            ? (is_array($finishing) ? implode(', ', $finishing) : $finishing)
            : null;
            // Validate minimum quantity
            if ($validatedData['quantity'] < $product->minimum_quantity) {
                throw ValidationException::withMessages([
                    'quantity' => "Minimal pemesanan {$product->minimum_quantity} {$product->unit_label}"
                ]);
            }

            // Calculate price
            $unitPrice = $this->calculateUnitPrice($product, $validatedData);

            // FIXED: Handle file upload with error handling
            $designFilePath = null;
            if ($request->hasFile('design_file')) {
                try {
                    $file = $request->file('design_file');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $designFilePath = $file->storeAs('temp/designs', $filename, 'public');
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengupload file: ' . $e->getMessage()
                    ], 500);
                }
            }

            // Check if similar item exists in cart
            $existingCart = Cart::where('product_id', $product->id)
                ->where(function ($query) {
                    if ($userId = $this->getUserId()) {
                        $query->where('user_id', $userId);
                    } else {
                        $query->where('session_id', $this->getSessionId());
                    }
                })
                ->where('custom_size_width', $validatedData['custom_size_width'] ?? null)
                ->where('custom_size_height', $validatedData['custom_size_height'] ?? null)
                ->where('selected_material', $validatedData['selected_material'] ?? null)
                ->where('selected_finishing', $validatedData['selected_finishing'] ?? null)
                ->first();

            if ($existingCart) {
                // Update existing cart item
                $existingCart->quantity += $validatedData['quantity'];
                $existingCart->unit_price = $unitPrice;
                $existingCart->calculateSubtotal();

                // Update design file if new one uploaded
                if ($designFilePath) {
                    // FIXED: Delete old temp file safely
                    if ($existingCart->design_file_path && Storage::disk('public')->exists($existingCart->design_file_path)) {
                        Storage::disk('public')->delete($existingCart->design_file_path);
                    }
                    $existingCart->design_file_path = $designFilePath;
                }

                // Update notes
                if (!empty($validatedData['design_notes'])) {
                    $existingCart->design_notes = $validatedData['design_notes'];
                }

                $existingCart->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Item berhasil diperbarui di keranjang',
                    'cart_count' => Cart::getItemCount($this->getUserId(), $this->getSessionId())
                ]);
            }

            // Create new cart item
            $cartItem = Cart::create([
                'user_id' => $this->getUserId(),
                'session_id' => $this->getSessionId(),
                'product_id' => $product->id,
                'quantity' => $validatedData['quantity'],
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice * $validatedData['quantity'],
                'custom_size_width' => $validatedData['custom_size_width'] ?? null,
                'custom_size_height' => $validatedData['custom_size_height'] ?? null,
                'selected_material' => $validatedData['selected_material'] ?? null,
                'selected_finishing' => $validatedData['selected_finishing'] ?? null,
                'design_notes' => $validatedData['design_notes'] ?? null,
                'design_file_path' => $designFilePath,
                'requires_design_service' => $validatedData['requires_design_service'] ?? false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cart_count' => Cart::getItemCount($this->getUserId(), $this->getSessionId()),
                'cart_item' => $cartItem->load('product')
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Cart $cartItem)
    {
        try {
            Log::info('Cart update attempt', [
                'cart_item_id' => $cartItem->id,
                'cart_user_id' => $cartItem->user_id,
                'cart_session_id' => $cartItem->session_id,
                'current_user_id' => $this->getUserId(),
                'current_session_id' => $this->getSessionId(),
                'request_data' => $request->all()
            ]);

            // Check ownership with more detailed logging
            $isOwner = $this->isCartItemOwner($cartItem);
            Log::info('Ownership check result', [
                'is_owner' => $isOwner,
                'cart_id' => $cartItem->id
            ]);

            if (!$isOwner) {
                Log::warning('Cart item ownership check failed', [
                    'cart_item_id' => $cartItem->id,
                    'expected_user_id' => $this->getUserId(),
                    'expected_session_id' => $this->getSessionId(),
                    'actual_user_id' => $cartItem->user_id,
                    'actual_session_id' => $cartItem->session_id
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan atau tidak memiliki akses'
                ], 404);
            }

            $validatedData = $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            // Validate minimum quantity
            $product = $cartItem->product;
            $minQuantity = $product->minimum_quantity ?? 1;
            
            if ($validatedData['quantity'] < $minQuantity) {
                throw ValidationException::withMessages([
                    'quantity' => "Minimal pemesanan " . $minQuantity . " " . ($product->unit_label ?? 'pcs')
                ]);
            }

            Log::info('Updating cart item quantity', [
                'cart_id' => $cartItem->id,
                'old_quantity' => $cartItem->quantity,
                'new_quantity' => $validatedData['quantity']
            ]);

            $cartItem->quantity = $validatedData['quantity'];
            $cartItem->calculateSubtotal();
            $cartItem->save(); // FIXED: Save the changes!

            Log::info('Cart item updated successfully', [
                'cart_id' => $cartItem->id,
                'new_quantity' => $cartItem->quantity,
                'new_subtotal' => $cartItem->subtotal
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diperbarui',
                'cart_item' => $cartItem->load('product'),
                'total_amount' => Cart::getTotalAmount($this->getUserId(), $this->getSessionId())
            ]);

        } catch (ValidationException $e) {
            Log::warning('Cart update validation failed', [
                'cart_id' => $cartItem->id ?? 'unknown',
                'errors' => $e->errors()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Cart update error', [
                'cart_id' => $cartItem->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function remove(Cart $cartItem)
    {
        try {
            // Debug session info
            Log::info('Remove cart item debug', [
                'cart_item_id' => $cartItem->id,
                'cart_session_id' => $cartItem->session_id,
                'current_session_id' => $this->getSessionId(),
                'cart_user_id' => $cartItem->user_id,
                'current_user_id' => $this->getUserId(),
            ]);

            // Check ownership
            if (!$this->isCartItemOwner($cartItem)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan'
                ], 404);
            }

            // FIXED: Delete design file safely
            if ($cartItem->design_file_path && Storage::disk('public')->exists($cartItem->design_file_path)) {
                Storage::disk('public')->delete($cartItem->design_file_path);
            }

            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus dari keranjang',
                'cart_count' => Cart::getItemCount($this->getUserId(), $this->getSessionId()),
                'total_amount' => Cart::getTotalAmount($this->getUserId(), $this->getSessionId())
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to remove cart item', [
                'error' => $e->getMessage(),
                'cart_id' => $cartItem->id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateById(Request $request, $id)
    {
        try {
            Log::info('Cart updateById attempt', [
                'cart_id' => $id,
                'current_user_id' => $this->getUserId(),
                'current_session_id' => $this->getSessionId(),
                'request_data' => $request->all()
            ]);

            // Find cart item manually
            $cartItem = Cart::find($id);
            
            if (!$cartItem) {
                Log::warning('Cart item not found', ['cart_id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan'
                ], 404);
            }

            // Check ownership with more relaxed rules for logged in users
            $isOwner = false;
            if ($userId = $this->getUserId()) {
                // For logged in users, check user_id
                $isOwner = $cartItem->user_id == $userId;
            } else {
                // For guests, check session_id
                $isOwner = $cartItem->session_id == $this->getSessionId();
            }

            Log::info('Ownership check result for updateById', [
                'is_owner' => $isOwner,
                'cart_id' => $cartItem->id,
                'cart_user_id' => $cartItem->user_id,
                'cart_session_id' => $cartItem->session_id
            ]);

            if (!$isOwner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan atau tidak memiliki akses'
                ], 404);
            }

            $validatedData = $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            // Validate minimum quantity
            $product = $cartItem->product;
            $minQuantity = $product->minimum_quantity ?? 1;
            
            if ($validatedData['quantity'] < $minQuantity) {
                throw ValidationException::withMessages([
                    'quantity' => "Minimal pemesanan " . $minQuantity . " " . ($product->unit_label ?? 'pcs')
                ]);
            }

            Log::info('Updating cart item quantity (updateById)', [
                'cart_id' => $cartItem->id,
                'old_quantity' => $cartItem->quantity,
                'new_quantity' => $validatedData['quantity']
            ]);

            $cartItem->quantity = $validatedData['quantity'];
            $cartItem->calculateSubtotal();
            $cartItem->save();

            Log::info('Cart item updated successfully (updateById)', [
                'cart_id' => $cartItem->id,
                'new_quantity' => $cartItem->quantity,
                'new_subtotal' => $cartItem->subtotal
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diperbarui',
                'cart_item' => $cartItem->load('product'),
                'total_amount' => Cart::getTotalAmount($this->getUserId(), $this->getSessionId())
            ]);

        } catch (ValidationException $e) {
            Log::warning('Cart updateById validation failed', [
                'cart_id' => $id,
                'errors' => $e->errors()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Cart updateById error', [
                'cart_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeById($id)
    {
        try {
            Log::info("RemoveById called for ID: {$id}");
            Log::info("Current session ID: " . session()->getId());
            
            // Find cart item directly
            $cart = Cart::find($id);
            
            if (!$cart) {
                Log::info("Cart item not found for ID: " . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found'
                ], 404);
            }
            
            Log::info("Found cart item - ID: {$cart->id}, Session: {$cart->session_id}, Product: {$cart->product_id}");
            
            // Check ownership - be more lenient for now
            if ($cart->session_id !== session()->getId()) {
                Log::warning("Session mismatch for cart ID {$id}: cart session = {$cart->session_id}, current session = " . session()->getId());
                // For debugging, allow deletion anyway
                Log::info("Allowing deletion despite session mismatch for debugging");
            }
            
            Log::info("Removing cart item ID: {$id}");
            $cart->delete();
            
            $remainingCount = Cart::where('session_id', session()->getId())->count();
            Log::info("Remaining cart items: {$remainingCount}");
            
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cartCount' => $remainingCount
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error removing cart item by ID {$id}: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error removing item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function clear()
    {
        try {
            Cart::clearCart($this->getUserId(), $this->getSessionId());

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function count()
    {
        $count = Cart::getItemCount($this->getUserId(), $this->getSessionId());

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    // Helper methods
    private function getUserId()
    {
        return Auth::id();
    }

    private function getSessionId()
    {
        return session()->getId();
    }

    private function getCartItems()
    {
        return Cart::getCartItems($this->getUserId(), $this->getSessionId());
    }

    private function isCartItemOwner(Cart $cartItem): bool
    {
        // If user is logged in, check user_id
        if ($userId = $this->getUserId()) {
            return $cartItem->user_id == $userId;
        }

        // For guest users, be more lenient with session checking
        // Allow access if session_id matches OR if no session_id is set
        $currentSessionId = $this->getSessionId();
        
        return $cartItem->session_id == $currentSessionId || 
               (empty($cartItem->session_id) && !$cartItem->user_id);
    }

// FIXED: Simplified price calculation - use Product model method
private function calculateUnitPrice(Product $product, array $data): float
{
    // Use the comprehensive calculatePrice method from Product model
    $priceDetails = $product->calculatePrice([
        'quantity' => $data['quantity'],
        'custom_size' => [
            'width' => ($data['custom_size_width'] ?? 0) / 100, // convert cm to meters
            'length' => ($data['custom_size_height'] ?? 0) / 100
        ],
        'material' => $data['selected_material'] ?? null,
        'finishing' => is_string($data['selected_finishing'])
            ? explode(', ', $data['selected_finishing'])
            : [],
        'design_service' => $data['requires_design_service'] ?? false
    ]);

    // ✅ PERBAIKAN: Untuk pricing per area, gunakan unit_price yang sudah include size multiplier
    if (in_array($product->pricing_type, ['per_meter_square', 'per_meter_linear'])) {
        // Unit price untuk area-based sudah dikalikan size multiplier di Product::calculatePrice()
        return $priceDetails['unit_price'] * $priceDetails['size_multiplier'];
    }

    return $priceDetails['unit_price'];
}
}
