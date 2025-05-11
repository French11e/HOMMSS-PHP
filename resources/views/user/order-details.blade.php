@extends('layouts.app')
@section('content')
<style>
    .pt-90 {
        padding-top: 90px !important;
    }

    .my-account {
        background-color: white;
    }

    .my-account .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 40px;
        border-bottom: 1px solid #e1e1e1;
        padding-bottom: 13px;
        color: #333;
    }

    .table> :not(caption)>tr>th {
        padding: 0.625rem 1.5rem !important;
        background-color: #f8f9fa !important;
        color: #333;
        border-bottom: 1px solid #e1e1e1;
    }

    .table>tr>td {
        padding: 0.625rem 1.5rem !important;
        background-color: white;
    }

    .table-bordered> :not(caption)>tr>th,
    .table-bordered> :not(caption)>tr>td {
        border-width: 1px;
        border-color: #e1e1e1;
    }

    .table> :not(caption)>tr>td {
        padding: .8rem 1rem !important;
        color: #333;
    }

    .bg-success {
        background-color: #40c710 !important;
        color: white;
    }

    .bg-danger {
        background-color: #f44032 !important;
        color: white;
    }

    .bg-warning {
        background-color: #f5d700 !important;
        color: #000;
    }

    .wg-table {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0px 4px 24px 2px rgba(20, 25, 38, 0.05);
        padding: 20px;
        border: 1px solid #e1e1e1;
    }

    .divider {
        height: 1px;
        background-color: #e1e1e1;
        margin: 20px 0;
    }

    .list-icon-function .item.eye {
        color: #333;
    }

    .list-icon-function .item.eye:hover {
        color: #40c710;
    }

    .table-striped>tbody>tr:nth-of-type(odd) {
        --bs-table-accent-bg: rgba(0, 0, 0, 0.02);
    }

    .wgp-pagination .pagination {
        justify-content: center;
    }

    .wgp-pagination .page-link {
        color: #333;
        border-color: #e1e1e1;
    }

    .wgp-pagination .page-item.active .page-link {
        background-color: #B9A16B;
        border-color: #B9A16B;
        color: white;
    }

    /* Enhanced order tracking styles */
    .order-tracking {
        margin: 40px auto;
        max-width: 850px;
    }

    .tracking-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #333;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .tracking-container {
        background-color: white;
        border-radius: 12px;
        padding: 35px 25px;
        border: 1px solid #e9ecef;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
    }

    .tracking-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: #2275fc;
    }

    .tracking-line {
        height: 3px;
        background: #e9ecef;
        position: absolute;
        width: 80%;
        left: 10%;
        top: 45px;
        z-index: 1;
    }

    .tracking-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        z-index: 3;
        width: 100%;
    }

    .tracking-step {
        text-align: center;
        position: relative;
        width: 20%;
    }

    .step-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        color: #adb5bd;
        font-size: 18px;
        border: 2px solid #e9ecef;
        position: relative;
        z-index: 5;
        transition: all 0.3s ease;
    }

    .step-active .step-icon {
        background: #2275fc;
        color: white;
        border-color: #2275fc;
        box-shadow: 0 0 0 5px rgba(34, 117, 252, 0.2);
        transform: scale(1.05);
    }

    .step-label {
        font-size: 15px;
        font-weight: 700;
        margin-top: 8px;
        color: #333;
        transition: all 0.3s ease;
    }

    .step-active .step-label {
        color: #2275fc;
    }

    .step-date {
        font-size: 13px;
        color: #6c757d;
        margin-top: 4px;
    }

    /* Progress connector line */
    .progress-line {
        position: absolute;
        height: 3px;
        background: #2275fc;
        top: 45px;
        left: 10%;
        z-index: 2;
        transition: width 0.5s ease;
        border-radius: 3px;
    }

    .status-ordered .progress-line {
        width: 10%;
    }

    .status-processing .progress-line {
        width: 30%;
    }

    .status-shipped .progress-line {
        width: 50%;
    }

    .status-delivered .progress-line {
        width: 70%;
    }

    /* Adjust order header layout */
    .order-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 30px;
    }

    .order-header-info {
        text-align: center;
        margin-bottom: 20px;
        padding: 15px 25px;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        width: 100%;
        max-width: 500px;
    }

    .order-header-info h5 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #333;
    }

    .order-status-badge {
        padding: 8px 20px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 30px;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-top: 5px;
        display: inline-block;
    }

    /* Status colors - solid blue instead of gradient */
    .bg-success {
        background-color: #2275fc !important;
    }

    .bg-danger {
        background-color: #f44032 !important;
        color: white;
    }

    .bg-info {
        background-color: #2196f3 !important;
    }

    .bg-primary {
        background-color: #3f51b5 !important;
    }

    .bg-warning {
        background-color: #f5d700 !important;
        color: #000;
    }
</style>

<main class="pt-90" style="padding-top: 0px; background-color: white;">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container" style="background-color: white;">
        <h2 class="page-title">Order Details</h2>
        <div class="row">
            <div class="col-12">
                <!-- Order Header -->
                <div class="wg-table">
                    <div class="order-header">
                        <div class="order-header-info">
                            <h5 class="mb-1">Order #{{ $order->id }}</h5>
                            <p class="text-muted mb-2">Placed on {{ date('F d, Y', strtotime($order->created_at)) }}</p>

                            @if($order->status == 'delivered')
                            <span class="order-status-badge bg-success">Delivered</span>
                            @elseif($order->status == 'canceled')
                            <span class="order-status-badge bg-danger">Canceled</span>
                            @elseif($order->status == 'processing')
                            <span class="order-status-badge bg-info">Processing</span>
                            @elseif($order->status == 'shipped')
                            <span class="order-status-badge bg-primary">Shipped</span>
                            @else
                            <span class="order-status-badge bg-warning">Ordered</span>
                            @endif
                        </div>

                        <!-- Order Tracking -->
                        <div class="order-tracking">
                            <h5 class="tracking-title">Order Status</h5>
                            <div class="tracking-container status-{{ $order->status }}">
                                <div class="tracking-line"></div>
                                <div class="progress-line"></div>

                                <div class="tracking-steps">
                                    <div class="tracking-step {{ $order->status != 'canceled' ? 'step-active' : '' }}">
                                        <div class="step-icon">
                                            <i class="fa fa-shopping-cart"></i>
                                        </div>
                                        <div class="step-label">Ordered</div>
                                        <div class="step-date">{{ date('M d, Y', strtotime($order->created_at)) }}</div>
                                    </div>

                                    <div class="tracking-step {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'step-active' : '' }}">
                                        <div class="step-icon">
                                            <i class="fa fa-cog"></i>
                                        </div>
                                        <div class="step-label">Processing</div>
                                        <div class="step-date">{{ $order->processing_date ? date('M d, Y', strtotime($order->processing_date)) : 'Pending' }}</div>
                                    </div>

                                    <div class="tracking-step {{ in_array($order->status, ['shipped', 'delivered']) ? 'step-active' : '' }}">
                                        <div class="step-icon">
                                            <i class="fa fa-truck"></i>
                                        </div>
                                        <div class="step-label">Shipped</div>
                                        <div class="step-date">{{ $order->shipped_date ? date('M d, Y', strtotime($order->shipped_date)) : 'Pending' }}</div>
                                    </div>

                                    <div class="tracking-step {{ $order->status == 'delivered' ? 'step-active' : '' }}">
                                        <div class="step-icon">
                                            <i class="fa fa-check"></i>
                                        </div>
                                        <div class="step-label">Delivered</div>
                                        <div class="step-date">{{ $order->delivered_date ? date('M d, Y', strtotime($order->delivered_date)) : 'Pending' }}</div>
                                    </div>

                                    <div class="tracking-step {{ $order->status == 'canceled' ? 'step-active' : '' }}">
                                        <div class="step-icon">
                                            <i class="fa fa-times"></i>
                                        </div>
                                        <div class="step-label">Canceled</div>
                                        <div class="step-date">{{ $order->canceled_date ? date('M d, Y', strtotime($order->canceled_date)) : 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>

                    <!-- Order Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">Shipping Information</h5>
                            <p class="mb-1"><strong>{{ $order->firstname }} {{ $order->lastname }}</strong></p>
                            <p class="mb-1">{{ $order->line1 }}</p>
                            @if($order->line2)
                            <p class="mb-1">{{ $order->line2 }}</p>
                            @endif
                            <p class="mb-1">{{ $order->city }}, {{ $order->province }} {{ $order->postal }}</p>
                            <p class="mb-1">{{ $order->country }}</p>
                            <p class="mb-1">Phone: {{ $order->phone }}</p>
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-3">Order Summary</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-end">₱{{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Tax</td>
                                    <td class="text-end">₱{{ number_format($order->tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Shipping</td>
                                    <td class="text-end">₱{{ number_format($order->shipping ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <th class="text-end">₱{{ number_format($order->total, 2) }}</th>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <h5 class="mb-3">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('assets/images/products') }}/{{ $item->product->image }}" alt="{{ $item->product->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; margin-right: 15px;">
                                            <div>
                                                <p class="mb-1 fw-bold">{{ $item->product->name }}</p>
                                                @if($item->options)
                                                <p class="mb-0 text-muted small">{{ $item->options }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="divider"></div>

                    <!-- Back Button -->
                    <div class="text-center">
                        <a href="{{ route('user.orders') }}" class="back-button">
                            <i class="fa fa-arrow-left me-2"></i> Back to Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection