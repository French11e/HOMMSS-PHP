<!DOCTYPE html>
<html>

<head>
    <title>Your Order Has Been Shipped</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .order-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            background-color: #2275fc;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Your Order Has Been Shipped!</h1>
    </div>

    <p>Dear {{ $order->name }},</p>

    <p>Great news! Your order #{{ $order->id }} has been shipped and is on its way to you.</p>

    <div class="order-info">
        <h3>Order Details:</h3>
        <p><strong>Order Number:</strong> {{ $order->id }}</p>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
        <p><strong>Shipping Address:</strong><br>
            {{ $order->address }}<br>
            @if($order->landmark)
            Landmark: {{ $order->landmark }}<br>
            @endif
            {{ $order->barangay }}, {{ $order->city }}<br>
            {{ $order->province }}, {{ $order->region }} {{ $order->postal }}
        </p>
    </div>

    <p>You can track your order status by visiting your account dashboard:</p>

    <a href="{{ route('user.order.details', ['order_id' => $order->id]) }}" class="button">View Order Details</a>

    <p>If you have any questions about your order, please don't hesitate to contact our customer service team.</p>

    <p>Thank you for shopping with us!</p>

    <div class="footer">
        <p>This is an automated email, please do not reply to this message.</p>
    </div>
</body>

</html>