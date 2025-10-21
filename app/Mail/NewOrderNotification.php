<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        // Eager load relationships to avoid N+1 queries
        $this->order = $order->load(['customer', 'items.product']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🛒 Pesanan Baru #{' . $this->order->order_number . '} - Tria.com',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.new-order-admin',
            with: [
                'order' => $this->order,
                'whatsappUrl' => $this->generateWhatsAppUrl(),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Generate WhatsApp URL for admin to contact customer
     * Message: Reminder untuk upload bukti pembayaran
     */
    private function generateWhatsAppUrl(): string
    {
        $customer = $this->order->customer;
        
        // Message dari Admin ke Customer untuk reminder pembayaran
        $message = "Halo *{$customer->name}* 👋\n\n";
        $message .= "Terima kasih telah berbelanja di *Tria Digital Printing*! 🎉\n\n";
        
        $message .= "Pesanan Anda telah kami terima dengan detail:\n\n";
        
        $message .= "� *Detail Pesanan:*\n";
        $message .= "Order ID: *#{$this->order->order_number}*\n";
        $message .= "Tanggal: " . $this->order->created_at->format('d M Y, H:i') . " WIB\n\n";
        
        $message .= "📦 *Produk yang Dipesan:*\n";
        foreach ($this->order->items as $index => $item) {
            $productName = $item->product ? $item->product->name : 'Produk';
            $message .= ($index + 1) . ". {$productName} ({$item->quantity}x)\n";
            if ($item->custom_size_width && $item->custom_size_height) {
                $width = $item->custom_size_width / 100;
                $height = $item->custom_size_height / 100;
                $message .= "   Ukuran: {$width}m x {$height}m\n";
            }
        }
        $message .= "\n";
        
        $message .= "💰 *Total Pembayaran:*\n";
        $message .= "*Rp " . number_format($this->order->total_amount, 0, ',', '.') . "*\n\n";
        
        $message .= "─────────────────────\n\n";
        
        $message .= "⚠️ *LANGKAH SELANJUTNYA:*\n\n";
        $message .= "Untuk memproses pesanan Anda, mohon segera melakukan:\n\n";
        
        $message .= "1️⃣ *Transfer pembayaran* ke rekening kami:\n";
        $message .= "   Bank: [NAMA BANK]\n";
        $message .= "   No. Rek: [NOMOR REKENING]\n";
        $message .= "   Atas Nama: [NAMA PEMILIK]\n\n";
        
        $message .= "2️⃣ *Upload bukti transfer* melalui:\n";
        $message .= "   🔗 " . ($this->order->hasSecureTracking() 
            ? $this->order->getSecureTrackingUrl() 
            : route('orders.show', $this->order->order_number)) . "\n\n";
        
        $message .= "3️⃣ Setelah pembayaran kami verifikasi, pesanan akan segera kami proses ⚡\n\n";
        
        $message .= "─────────────────────\n\n";
        
        $message .= "� *Tips:*\n";
        $message .= "• Upload bukti transfer paling lambat 1x24 jam\n";
        $message .= "• Pastikan nominal transfer sesuai dengan total di atas\n";
        $message .= "• Simpan link tracking untuk cek status pesanan\n\n";
        
        $message .= "Ada pertanyaan? Silakan balas pesan ini! 😊\n\n";
        
        $message .= "Terima kasih,\n";
        $message .= "*Tria Digital Printing Team* 🖨️";
        
        // Customer's phone number (default to empty if not set)
        $customerPhone = $customer->phone ? preg_replace('/[^0-9]/', '', $customer->phone) : '';
        
        // If phone doesn't start with 62, add it
        if ($customerPhone && !str_starts_with($customerPhone, '62')) {
            if (str_starts_with($customerPhone, '0')) {
                $customerPhone = '62' . substr($customerPhone, 1);
            } else {
                $customerPhone = '62' . $customerPhone;
            }
        }
        
        $encodedMessage = urlencode($message);
        
        // Use customer phone or fallback to empty (will show contact selector)
        return "https://wa.me/{$customerPhone}?text={$encodedMessage}";
    }
}
