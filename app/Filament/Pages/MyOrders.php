<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;

class MyOrders extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;
    
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static string $view = 'filament.pages.my-orders';
    
    protected static ?string $navigationLabel = 'Pesanan Saya';
    
    protected static ?string $title = 'Pesanan Saya';
    
    protected static ?int $navigationSort = 1;

    public function getOrders()
    {
        $user = Auth::user();
        $customer = $user->customer;
        
        if (!$customer) {
            return collect();
        }
        
        return Order::where('customer_id', $customer->id)
            ->with(['items.product'])
            ->latest()
            ->paginate(10);
    }

    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->roles->pluck('name')->contains('customer');
    }
    
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && Auth::user()->roles->pluck('name')->contains('customer');
    }

    public function uploadPaymentProofAction(): Action
    {
        return Action::make('uploadPaymentProof')
            ->label('Upload Bukti Pembayaran')
            ->icon('heroicon-m-camera')
            ->color('warning')
            ->modalHeading('Upload Bukti Pembayaran')
            ->modalSubmitActionLabel('Upload')
            ->form([
                FileUpload::make('payment_proof')
                    ->label('Bukti Pembayaran')
                    ->image()
                    ->required()
                    ->directory('payment-proofs')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                    ->maxSize(2048)
                    ->helperText('Upload gambar bukti transfer (JPG, PNG, max 2MB)'),
                    
                Textarea::make('notes')
                    ->label('Catatan (Opsional)')
                    ->placeholder('Tambahkan catatan jika diperlukan...')
                    ->rows(3)
            ])
            ->action(function (array $data, array $arguments) {
                $order = Order::findOrFail($arguments['orderId']);
                
                $order->update([
                    'payment_proof' => $data['payment_proof'],
                    'notes' => $data['notes'] ?? null,
                    'payment_status' => Order::PAYMENT_STATUS_PENDING
                ]);
                
                Notification::make()
                    ->title('Bukti Pembayaran Berhasil Diupload')
                    ->body('Bukti pembayaran sedang dalam proses verifikasi.')
                    ->success()
                    ->send();
            })
            ->successRedirectUrl(fn() => request()->fullUrl());
    }
}