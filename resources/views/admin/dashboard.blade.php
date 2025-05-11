<!-- Recent orders section with scrollbar -->
<div class="recent-orders">
    <div class="header">
        <h3>Recent orders</h3>
        <a href="{{ route('admin.orders') }}" class="view-all">View all</a>
    </div>
    <div class="table-container">
        <div class="wg-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>ORDER&nbsp;NO</th>
                        <th>NAME</th>
                        <th>PHONE</th>
                        <th>TOTAL</th>
                        <th>STATUS</th>
                        <th>ORDER&nbsp;DATE</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Your order rows here -->
                    @foreach($recentOrders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->phone }}</td>
                        <td>₱{{ $order->total }}</td>
                        <td>
                            @if($order->status == 'delivered')
                            <span class="badge bg-success">Delivered</span>
                            @elseif($order->status == 'canceled')
                            <span class="badge bg-danger">Canceled</span>
                            @else
                            <span class="badge bg-warning">Ordered</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>