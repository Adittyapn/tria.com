<?php

namespace App\Notifications;

use App\Models\Order;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Notifications\Actions\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Order $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title('🛒 Pesanan Baru Masuk!')
            ->body("Order #{$this->order->order_number} dari {$this->order->customer->name}")
            ->icon('heroicon-o-shopping-cart')
            ->iconColor('success')
            ->actions([
                Action::make('view')
                    ->label('Lihat Detail')
                    ->button()
                    ->url(\Filament\Facades\Filament::getPanel('admin')->getUrl() . '/resources/orders/' . $this->order->getRouteKey())
                    ->openUrlInNewTab(),
                Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(\App\Filament\Resources\OrderResource::generateWhatsAppUrl($this->order))
                    ->openUrlInNewTab(),
            ])
            ->getDatabaseMessage();
    }
}
