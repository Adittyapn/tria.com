<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class TrackOrder extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static string $view = 'filament.pages.track-order';
    
    protected static ?string $navigationLabel = 'Lacak Pesanan';
    
    protected static ?string $title = 'Lacak Pesanan';
    
    protected static ?int $navigationSort = 2;

    public ?string $orderNumber = null;
    public ?Order $order = null;
    public bool $showTracking = false;

    public function mount(): void
    {
        if (request()->has('order')) {
            $this->orderNumber = request()->get('order');
            $this->trackOrder();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('orderNumber')
                    ->label('Nomor Pesanan')
                    ->placeholder('Contoh: DP-20241002-001')
                    ->required()
                    ->suffixAction(
                        \Filament\Forms\Components\Actions\Action::make('track')
                            ->label('Lacak')
                            ->icon('heroicon-m-magnifying-glass')
                            ->action('trackOrder')
                    ),
            ])
            ->statePath('data');
    }

    public function trackOrder(): void
    {
        $this->validate([
            'orderNumber' => 'required|string',
        ]);

        $user = Auth::user();
        
        // Cari order berdasarkan order_number dan user_id
        $order = Order::where('order_number', $this->orderNumber)
            ->where('user_id', $user->id)
            ->first();

        if (!$order) {
            Notification::make()
                ->title('Pesanan Tidak Ditemukan')
                ->body('Nomor pesanan yang Anda masukkan tidak ditemukan atau bukan milik Anda.')
                ->danger()
                ->send();
                
            $this->showTracking = false;
            return;
        }

        $this->order = $order;
        $this->showTracking = true;

        Notification::make()
            ->title('Pesanan Ditemukan')
            ->body('Menampilkan detail tracking pesanan ' . $this->orderNumber)
            ->success()
            ->send();
    }

    public function getTrackingSteps(): array
    {
        if (!$this->order) {
            return [];
        }

        $steps = [
            [
                'status' => Order::STATUS_PENDING_PAYMENT,
                'label' => 'Menunggu Pembayaran',
                'description' => 'Pesanan menunggu pembayaran',
                'icon' => 'heroicon-m-clock',
                'color' => 'warning',
                'completed' => in_array($this->order->status, [
                    Order::STATUS_PAID,
                    Order::STATUS_PROCESSING,
                    Order::STATUS_READY,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED
                ]),
                'active' => $this->order->status === Order::STATUS_PENDING_PAYMENT,
                'date' => $this->order->created_at,
            ],
            [
                'status' => Order::STATUS_PAID,
                'label' => 'Pembayaran Terverifikasi',
                'description' => 'Pembayaran telah dikonfirmasi',
                'icon' => 'heroicon-m-check-circle',
                'color' => 'success',
                'completed' => in_array($this->order->status, [
                    Order::STATUS_PROCESSING,
                    Order::STATUS_READY,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED
                ]),
                'active' => $this->order->status === Order::STATUS_PAID,
                'date' => $this->order->payment_status === Order::PAYMENT_STATUS_PAID ? $this->order->updated_at : null,
            ],
            [
                'status' => Order::STATUS_PROCESSING,
                'label' => 'Dalam Produksi',
                'description' => 'Pesanan sedang diproduksi',
                'icon' => 'heroicon-m-cog-6-tooth',
                'color' => 'info',
                'completed' => in_array($this->order->status, [
                    Order::STATUS_READY,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED
                ]),
                'active' => $this->order->status === Order::STATUS_PROCESSING,
                'date' => null,
            ],
            [
                'status' => Order::STATUS_READY,
                'label' => 'Siap Dikirim',
                'description' => 'Pesanan siap untuk dikirim',
                'icon' => 'heroicon-m-archive-box',
                'color' => 'primary',
                'completed' => in_array($this->order->status, [
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED
                ]),
                'active' => $this->order->status === Order::STATUS_READY,
                'date' => null,
            ],
            [
                'status' => Order::STATUS_SHIPPED,
                'label' => 'Dalam Pengiriman',
                'description' => 'Pesanan sedang dalam perjalanan',
                'icon' => 'heroicon-m-truck',
                'color' => 'warning',
                'completed' => $this->order->status === Order::STATUS_COMPLETED,
                'active' => $this->order->status === Order::STATUS_SHIPPED,
                'date' => $this->order->shipped_at,
            ],
            [
                'status' => Order::STATUS_COMPLETED,
                'label' => 'Selesai',
                'description' => 'Pesanan telah sampai',
                'icon' => 'heroicon-m-check-badge',
                'color' => 'success',
                'completed' => $this->order->status === Order::STATUS_COMPLETED,
                'active' => $this->order->status === Order::STATUS_COMPLETED,
                'date' => $this->order->delivered_at,
            ],
        ];

        return $steps;
    }

    public static function canAccess(): bool
    {
        return false; // Disable this page
    }
    
    public static function shouldRegisterNavigation(): bool
    {
        return false; // Hide from navigation
    }
}
