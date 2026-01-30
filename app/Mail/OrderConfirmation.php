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

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS', 'noreply@electronicsmart.com'), env('MAIL_FROM_NAME', 'Electronics Mart')),
            subject: 'Order Confirmation - #' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Load order items with their variant and product relationships
        $orderItems = $this->order->items()->with([
            'variant.product.images',
            'variant.main_images'
        ])->get();

        // Add product image to each order item
        $orderItems = $orderItems->map(function ($item) {
            $productImage = null;
            
            if ($item->variant) {
                // Try to get variant's main image first
                if ($item->variant->main_images && $item->variant->main_images->count() > 0) {
                    $productImage = $item->variant->main_images->first()->url;
                }
                // Fallback to product's first image
                elseif ($item->variant->product && $item->variant->product->images && $item->variant->product->images->count() > 0) {
                    $productImage = $item->variant->product->images->first()->url;
                }
            }
            
            $item->product_image = $productImage;
            return $item;
        });

        return new Content(
            view: 'emails.order-confirmation',
            with: [
                'order' => $this->order,
                'customerName' => $this->order->shipping_name,
                'orderNumber' => $this->order->order_number,
                'orderTotal' => $this->order->total,
                'orderItems' => $orderItems,
                'shippingAddress' => [
                    'name' => $this->order->shipping_name,
                    'address' => $this->order->shipping_address,
                    'city' => $this->order->shipping_city,
                    'state' => $this->order->shipping_state,
                    'postal_code' => $this->order->shipping_postal_code,
                    'country' => $this->order->shipping_country,
                ],
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
