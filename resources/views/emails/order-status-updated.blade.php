<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Update - {{ $orderNumber }}</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        background: #f5f5f5;
        color: #333;
        margin: 0;
        padding: 20px;
        line-height: 1.6;
      }
      .container {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
      }
      .header {
        background: #2563eb;
        color: #fff;
        padding: 20px;
        text-align: center;
      }
      .header h1 {
        margin: 0;
        font-size: 1.5rem;
      }
      .brand {
        font-size: 0.9rem;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
      }
      .content {
        padding: 20px;
      }
      .status-box {
        background: #f8f9fa;
        border: 2px solid #2563eb;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
        text-align: center;
      }
      .status-badge {
        background: #2563eb;
        color: #fff;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 10px;
      }
      .status-badge.confirmed { background: #2563eb; }
      .status-badge.processing { background: #f59e0b; }
      .status-badge.shipped { background: #10b981; }
      .status-badge.delivered { background: #059669; }
      .status-badge.cancelled { background: #ef4444; }
      .order-number {
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
      }
      .tracking-box {
        background: #e0f2fe;
        border: 1px solid #0284c7;
        border-radius: 6px;
        padding: 15px;
        margin: 15px 0;
      }
      .tracking-number {
        font-family: 'Courier New', monospace;
        background: #fff;
        padding: 8px 12px;
        border-radius: 4px;
        font-weight: 600;
        color: #333;
        display: inline-block;
        margin: 5px 0;
        border: 1px solid #ddd;
      }
      .footer {
        background: #2563eb;
        color: #fff;
        padding: 15px;
        text-align: center;
        font-size: 0.9rem;
      }
      .footer a {
        color: #fff;
        text-decoration: underline;
      }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="brand">Electronics Mart</div>
            <h1>Order Update</h1>
        </div>
        
        <!-- Content -->
        <div class="content">
            <p>Hello {{ $customerName }},</p>
            
            <p>Your order status has been updated:</p>
            
            <!-- Status Update -->
            <div class="status-box">
                <div class="order-number">Order #{{ $orderNumber }}</div>
                <div class="status-badge {{ $currentStatus }}">
                    @switch($currentStatus)
                        @case('processing')
                            Processing
                            @break
                        @case('confirmed')
                            Confirmed
                            @break
                        @case('shipped')
                            Shipped
                            @break
                        @case('delivered')
                            Delivered
                            @break
                        @case('cancelled')
                            Cancelled
                            @break
                        @default
                            {{ ucfirst($currentStatus) }}
                    @endswitch
                </div>
                <p style="margin: 8px 0 0 0; color: #666; font-size: 0.9rem;">
                    Updated on {{ $order->updated_at->format('M j, Y \a\t g:i A') }}
                </p>
            </div>

            @if($currentStatus === 'processing')
                <p>Your order is being processed. For bank transfer orders, we're awaiting payment confirmation. For other payment methods, we're preparing your items for shipment.</p>
            @elseif($currentStatus === 'confirmed')
                <p>Great news! Your order has been confirmed and is being prepared for shipment. We'll notify you once it's shipped with tracking details.</p>
            @elseif($currentStatus === 'shipped')
                <p>Great news! Your order has been shipped.</p>
                
                @if($trackingNumber)
                <div class="tracking-box">
                    <p><strong>📦 Tracking Details:</strong></p>
                    <div class="tracking-number">{{ $trackingNumber }}</div>
                    @if($shippingService)
                    <p><strong>Courier:</strong> {{ $shippingService }}</p>
                    @endif
                    <p style="color: #0284c7; font-size: 0.9rem; margin-top: 10px;">
                        Expected delivery: 7-14 business days
                    </p>
                </div>
                @endif
            @elseif($currentStatus === 'delivered')
                <p>🎉 Your order has been delivered! We hope you enjoy your new electronics.</p>
                <p>Thank you for choosing Electronics Mart!</p>
            @elseif($currentStatus === 'cancelled')
                <p>Your order has been cancelled. Any payment made will be refunded within 3-5 business days.</p>
                <p>Contact us if you have any questions.</p>
            @endif
            
            <p style="margin-top: 20px;">
                <strong>Questions?</strong> Contact us at 
                <a href="mailto:support@electronicsmart.com">support@electronicsmart.com</a> 
                or call +91-1800-123-4567.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>Electronics Mart</strong> - Your trusted electronics partner</p>
            <p>© {{ date('Y') }} Electronics Mart. All rights reserved.</p>
        </div>
    </div>
</body>
</html>