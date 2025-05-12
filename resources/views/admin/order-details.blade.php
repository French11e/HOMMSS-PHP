@extends('layouts.admin')
@section('content')
<style>
    .table-transaction>tbody>tr:nth-of-type(odd) {
        --bs-table-accent-bg: #fff !important;
    }

    .wg-box {
        margin-bottom: 30px;
        clear: both;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 20px;
        background: #fff;
    }

    .divider {
        margin: 20px 0;
        border-top: 1px solid #eee;
    }

    .my-account__address-item {
        margin-top: 15px;
    }

    .my-account__address-item__detail {
        transition: all 0.3s ease;
    }

    .my-account__address-item__detail:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .customer-info table {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        overflow: hidden;
    }

    .customer-info th {
        width: 40%;
        background-color: #f8f9fa !important;
        font-weight: 600;
        color: #333;
    }

    .customer-info td {
        color: #555;
    }

    h5,
    h6 {
        margin-bottom: 15px;
        color: #333;
        font-weight: 600;
    }

    /* Order Tracking Styles */
    .order-tracking {
        margin: 30px 0;
        position: relative;
        text-align: center;
    }

    .tracking-line {
        height: 4px;
        background: #e9ecef;
        position: absolute;
        width: 80%;
        margin: 0 auto;
        left: 0;
        right: 0;
        top: 25px;
        z-index: 1;
    }

    .tracking-progress {
        height: 4px;
        background: #2275fc; /* Blue progress line */
        position: absolute;
        left: 10%;
        top: 25px;
        z-index: 2;
        transition: width 0.3s ease;
    }

    .tracking-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        z-index: 3;
    }

    .tracking-step {
        width: 20%;
        text-align: center;
    }

    .step-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        color: #6c757d;
        font-size: 20px;
    }

    .step-icon i {
        font-size: 20px;
    }

    .step-active .step-icon {
        background: #2275fc; /* Changed from #B9A16B (brown) to #2275fc (blue) */
        color: white;
        border-color: #2275fc; /* Changed from #B9A16B to #2275fc */
    }

    .step-active .step-label {
        color: #2275fc; /* Changed from #B9A16B to #2275fc */
    }

    .step-label {
        font-size: 14px;
        font-weight: 600;
        margin-top: 8px;
    }

    .step-date {
        font-size: 12px;
        color: #6c757d;
    }

    .bg-success {
        background-color: #40c710 !important;
        color: white;
    }

    .bg-danger {
        background-color: #f44032 !important;
        color: white;
    }

    .bg-info {
        background-color: #17a2b8 !important;
        color: white;
    }

    .bg-primary {
        background-color: #007bff !important;
        color: white;
    }

    .bg-warning {
        background-color: #ffc107 !important;
        color: #212529;
    }

    /* Fix for table headers */
    .table th {
        white-space: nowrap;
        padding: 12px 16px !important;
        background-color: #f8f9fa !important;
        color: #333;
        border-bottom: 1px solid #e1e1e1;
        font-weight: 600;
    }

    /* Ensure consistent table styling */
    .table td {
        padding: 12px 16px !important;
    }

    /* Improve table borders */
    .table-bordered {
        border: 1px solid #e1e1e1;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #e1e1e1;
    }

    /* Ensure proper spacing in the order items table */
    .table thead th {
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.03em;
    }
</style>
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Order Details</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Order Details</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Ordered Details</h5>
                </div>
                <div class="flex gap-2">
                    <a class="tf-button style-2" href="{{ route('admin.order.packing-slip', ['order_id' => $order->id]) }}" target="_blank">
                        <i class="icon-printer mr-1"></i>Print Packing Slip
                    </a>
                    <a class="tf-button style-1" href="{{route('admin.orders')}}">Back</a>
                </div>
            </div>
            <div class="table-responsive">
                @if(Session::has('status'))
                <p class="alert alert-success">{{Session::get('status')}}</p>
                @endif
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Order&nbsp;No</th>
                        <td>{{$order->id}}</td>
                        <th>Mobile</th>
                        <td>{{$order->phone}}</td>
                        <th>Zip&nbsp;Code</th>
                        <td>{{$order->postal}}</td>
                    </tr>
                    <tr>
                        <th>Order&nbsp;Date</th>
                        <td>{{$order->created_at}}</td>
                        <th>Delivered&nbsp;Date</th>
                        <td>{{$order->delivered_date}}</td>
                        <th>Canceled&nbsp;Date</th>
                        <td>{{$order->canceled_date}}</td>
                    </tr>
                    <tr>
                        <th>Order&nbsp;Status</th>
                        <td colspan="5">
                            @if($order->status == 'delivered')
                            <span class="badge bg-success">Delivered</span>
                            @elseif($order->status == 'canceled')
                            <span class="badge bg-danger">Canceled</span>
                            @elseif($order->status == 'processing')
                            <span class="badge bg-info">Processing</span>
                            @elseif($order->status == 'shipped')
                            <span class="badge bg-primary">Shipped</span>
                            @else
                            <span class="badge bg-warning">Ordered</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Order Tracking Section -->
            <div class="order-tracking">
                <div class="tracking-line"></div>
                <?php
                $progressWidth = '0%';
                if ($order->status == 'ordered') {
                    $progressWidth = '20%';
                } elseif ($order->status == 'processing') {
                    $progressWidth = '40%';
                } elseif ($order->status == 'shipped') {
                    $progressWidth = '60%';
                } elseif ($order->status == 'delivered') {
                    $progressWidth = '80%';
                } elseif ($order->status == 'canceled') {
                    $progressWidth = '0%';
                }
                ?>
                <div class="tracking-progress" style="width: <?php echo $progressWidth; ?>"></div>

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

            <div class="divider"></div>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <h5>Ordered Items</h5>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">SKU</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Brand</th>
                            <th class="text-center">Options</th>
                            <th class="text-center">Return&nbsp;Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderItems as $item)
                        <tr>
                            <td class="pname">
                                <div class="image">
                                    <img src="{{asset('uploads/products/thumbnails')}}/{{$item->product->image}}" alt="{{$item->product->name}}" class="image">
                                </div>
                                <div class="name">
                                    <a href="{{route('shop.product.details', ['product_slug'=>$item->product->slug])}}" target="_blank" class="body-title-2">{{$item->product->name}}</a>
                                </div>
                            </td>
                            <td class="text-center">₱{{$item->price}}</td>
                            <td class="text-center">{{$item->quantity}}</td>
                            <td class="text-center">{{$item->product->SKU}}</td>
                            <td class="text-center">{{$item->product->category->name}}</td>
                            <td class="text-center">{{$item->product->brand->name}}</td>
                            <td class="text-center">{{$item->options}}</td>
                            <td class="text-center">{{$item->rstatus == 0 ? "No":"Yes"}}</td>
                            <td class="text-center">
                                <div class="list-icon-function view-icon">
                                    <div class="item eye">
                                        <i class="icon-eye"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{$orderItems->links('pagination::bootstrap-5')}}
            </div>
        </div>

        <div class="wg-box mt-5">
            <h5>Customer Information</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="customer-info">
                        <h6>Account Details</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th>Customer Name</th>
                                <td>{{$order->name}}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{$order->user->email}}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{$order->phone}}</td>
                            </tr>
                            <tr>
                                <th>Customer Since</th>
                                <td>{{$order->user->created_at->format('M d, Y')}}</td>
                            </tr>
                            <tr>
                                <th>Total Orders</th>
                                <td>{{$order->user->orders->count()}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="shipping-info">
                        <h6>Shipping Address</h6>
                        <div class="my-account__address-item">
                            <div class="my-account__address-item__detail p-4" style="background-color: #fff; border-radius: 8px; border: 1px solid #e1e1e1; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                <h6 class="mb-3" style="font-weight: 600; color: #333;">{{$order->name}}</h6>

                                <div class="mb-3">
                                    <p class="mb-1" style="color: #555;"><strong>Address:</strong> {{$order->address}}</p>
                                    <p class="mb-1" style="color: #555;"><strong>Barangay:</strong> {{$order->barangay}}</p>
                                    <p class="mb-1" style="color: #555;"><strong>City/Province:</strong> {{$order->city}}, {{$order->province}}</p>
                                    <p class="mb-1" style="color: #555;"><strong>Region:</strong> {{$order->region}}</p>
                                    <p class="mb-1" style="color: #555;"><strong>Landmark:</strong> {{$order->landmark}}</p>
                                    <p class="mb-1" style="color: #555;"><strong>Postal Code:</strong> {{$order->postal}}</p>
                                </div>

                                <div class="pt-2 mt-3" style="border-top: 1px solid #eee;">
                                    <p class="mb-0" style="color: #555;"><strong>Mobile:</strong> {{$order->phone}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="wg-box mt-5">
            <h5>Transactions</h5>
            <table class="table table-striped table-bordered table-transaction">
                <tbody>
                    <tr>
                        <th>Subtotal</th>
                        <td>₱{{$order->subtotal}}</td>
                        <th>Tax</th>
                        <td>₱{{$order->tax}}</td>
                        <!-- <th>Discount</th>
                <td>0.00</td> -->
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>₱{{$order->total}}</td>
                        <th>Payment Mode</th>
                        <td>
                            @if($transaction)
                            {{$transaction->mode}}
                            @else
                            Not available
                            @endif
                        </td>
                        <th>Status</th>
                        <td>
                            @if($transaction && $transaction->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                            @elseif($transaction && $transaction->status == 'declined')
                            <span class="badge bg-danger">Declined</span>
                            @elseif($transaction && $transaction->status == 'refunded')
                            <span class="badge secondary">Refunded</span>
                            @else
                            <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="wg-box mt-5">
            <h5>Update Order Status</h5>
            <form action="{{route('admin.order.status.update')}}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="order_id" value="{{$order->id}}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="select">
                            <select id="order_status" name="order_status">
                                <option value="ordered" {{$order->status == 'ordered' ? "selected":""}}>Ordered</option>
                                <option value="processing" {{$order->status == 'processing' ? "selected":""}}>Processing</option>
                                <option value="shipped" {{$order->status == 'shipped' ? "selected":""}}>Shipped</option>
                                <option value="delivered" {{$order->status == 'delivered' ? "selected":""}}>Delivered</option>
                                <option value="canceled" {{$order->status == 'canceled' ? "selected":""}}>Canceled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary tf-button w208">Update Status</button>
                    </div>
                </div>
            </form>

        </div>

    </div>
</div>
@endsection
