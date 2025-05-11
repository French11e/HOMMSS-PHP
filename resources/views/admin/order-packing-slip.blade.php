<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing Slip - Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }

        .packing-slip {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .order-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .order-info div {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="packing-slip">
        <div class="header">
            <h1>PACKING SLIP</h1>
            <h2>Order #{{ $order->id }}</h2>
        </div>

        <div class="order-info">
            <div>
                <h3>Ship To:</h3>
                <p>
                    <strong>{{ $order->name }}</strong><br>
                    {{ $order->address }}<br>
                    @if($order->landmark)
                    Landmark: {{ $order->landmark }}<br>
                    @endif
                    {{ $order->barangay }}, {{ $order->city }}<br>
                    {{ $order->province }}, {{ $order->region }}<br>
                    {{ $order->postal }}<br>
                    {{ $order->country }}<br>
                    Phone: {{ $order->phone }}
                </p>
            </div>
            <div>
                <h3>Order Information:</h3>
                <p>
                    <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}<br>
                    <strong>Order #:</strong> {{ $order->id }}
                </p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->SKU }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->options }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>This is a packing slip only. No pricing information is included.</p>
        </div>

        <div class="no-print" style="margin-top: 20px; text-align: center;">
            <button onclick="window.print();" style="padding: 10px 20px; cursor: pointer;">Print Packing Slip</button>
            <button onclick="window.close();" style="padding: 10px 20px; margin-left: 10px; cursor: pointer;">Close</button>
        </div>
    </div>
</body>

</html>