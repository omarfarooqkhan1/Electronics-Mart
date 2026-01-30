<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $previousStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $previousStatus = null)
    {
        $this->order = $order;
        $this->previousStatus = $previousStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusMessages = [
            'pending' => 'Order Received',
            'processing' => 'Order Processing',
            'shipped' => 'Order Shipped',
            'delivered' => 'Order Delivered',
            'cancelled' => 'Order Cancelled',
        ];

        $subject = $statusMessages[$this->order->status] ?? 'Order Status Update';
        
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS', 'noreply@electronicsmart.com'), env('MAIL_FROM_NAME', 'Electronics Mart')),
            subject: $subject . ' - #' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status-updated',
            with: [
                'order' => $this->order,
                'previousStatus' => $this->previousStatus,
                'customerName' => $this->order->shipping_name,
                'orderNumber' => $this->order->order_number,
                'currentStatus' => $this->order->status,
                'trackingNumber' => $this->order->tracking_number,
                'shippingService' => $this->order->shipping_service,
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
}