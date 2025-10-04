<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TopProductsWidget extends BaseWidget
{
    protected static ?string $heading = 'Produk Terlaris (30 Hari Terakhir)';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->select('products.*')
                    ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
                    ->selectRaw('COALESCE(SUM(order_items.subtotal), 0) as total_revenue')
                    ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
                    ->leftJoin('orders', function($join) {
                        $join->on('order_items.order_id', '=', 'orders.id')
                             ->where('orders.payment_status', '=', 'verified')
                             ->where('orders.created_at', '>=', now()->subDays(30));
                    })
                    ->groupBy('products.id')
                    ->orderByDesc('total_sold')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Gambar')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl('/images/default-product.svg'),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('total_sold')
                    ->label('Terjual')
                    ->suffix(' pcs')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 5 ? 'warning' : 'gray')),

                Tables\Columns\TextColumn::make('total_revenue')
                    ->label('Pendapatan')
                    ->money('IDR')
                    ->sortable()
                    ->alignEnd()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Satuan')
                    ->money('IDR')
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stok')
                    ->suffix(' pcs')
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger'))
                    ->default('N/A'),
            ])
            ->defaultSort('total_sold', 'desc')
            ->striped();
    }

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && $user->roles->pluck('name')->contains('super_admin');
    }
}