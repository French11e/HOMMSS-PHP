@extends('layouts.app')
@section('content')
<style>
    .pt-90 {
        padding-top: 90px !important;
    }

    .pr-6px {
        padding-right: 6px;
        text-transform: uppercase;
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

    .my-account .wg-box {
        display: flex;
        padding: 24px;
        flex-direction: column;
        gap: 24px;
        border-radius: 12px;
        background: white;
        box-shadow: 0px 4px 24px 2px rgba(20, 25, 38, 0.05);
        margin-bottom: 20px;
        border: 1px solid #e1e1e1;
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

    .table-transaction>tbody>tr:nth-of-type(odd) {
        background-color: white !important;
    }

    .table-transaction th,
    .table-transaction td {
        padding: 0.625rem 1.5rem .25rem !important;
        color: #333 !important;
        background-color: white;
    }

    .table> :not(caption)>tr>th {
        padding: 0.625rem 1.5rem .25rem !important;
        background-color: #f8f9fa !important;
        color: #333;
        border-bottom: 1px solid #e1e1e1;
    }

    .table-bordered>:not(caption)>*>* {
        border-width: inherit;
        line-height: 32px;
        font-size: 14px;
        border: 1px solid #e1e1e1;
        vertical-align: middle;
        background-color: white;
    }

    .table-striped .image {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        flex-shrink: 0;
        border-radius: 10px;
        overflow: hidden;
        background-color: white;
    }

    .table-striped td:nth-child(1) {
        min-width: 250px;
        padding-bottom: 7px;
    }

    .pname {
        display: flex;
        gap: 13px;
        align-items: center;
    }

    .table-bordered> :not(caption)>tr>th,
    .table-bordered> :not(caption)>tr>td {
        border-width: 1px;
        border-color: #e1e1e1;
    }

    .divider {
        height: 1px;
        background-color: #e1e1e1;
        margin: 20px 0;
    }

    .btn-danger {
        background-color: #f44032;
        border-color: #f44032;
    }

    .btn-danger:hover {
        background-color: #d9372a;
        border-color: #d9372a;
    }

    .my-account__address-item__detail {
        background-color: white;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e1e1e1;
    }

    .my-account__address-item__detail p {
        margin-bottom: 5px;
        color: #333;
    }

    .badge {
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: 500;
    }

    .secondary {
        background-color: #6c757d;
        color: white;
    }
</style>

<main class="pt-90" style="padding-top: 0px; background-color: white;">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container" style="background-color: white;">
        <h2 class="page-title">Order Details</h2>
        <div class="row">
            <div class="col-lg-2">
                @include('user.account-nav')
            </div>

            <div class="col-lg-10">
                <div class="wg-box">
                    <div class="flex items-center justify-between gap10 flex-wrap">
                        <div class="row">
                            <div class="col-6">
                                <h5 style="color: #333;">Ordered Details</h5>
                            </div>
                            <div class="col-6 text-right">
                                <a class="btn btn-sm btn-danger" href="{{route('user.orders')}}">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Order No</th>
                                <td>{{$order->id}}</td>
                                <th>Mobile</th>
                                <td>{{$order->phone}}</td>
                                <th>Zip Code</th>
                                <td>{{$order->postal}}</td>
                            </tr>
                            <tr>
                                <th>Order Date</th>
                                <td>{{$order->created_at}}</td>
                                <th>Delivered Date</th>
                                <td>{{$order->delivered_date}}</td>
                                <th>Canceled Date</th>
                                <td>{{$order->canceled_date}}</td>
                            </tr>
                            <tr>
                                <th>Order Status</th>
                                <td colspan="5">
                                    @if($order->status == 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                    @elseif($order->status == 'canceled')
                                    <span class="badge bg-danger">Canceled</span>
                                    @else
                                    <span class="badge bg-warning">Ordered</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="divider"></div>
                </div>

                <div class="wg-box">
                    <div class="flex items-center justify-between gap10 flex-wrap">
                        <div class="wg-filter flex-grow">
                            <h5 style="color: #333;">Ordered Items</h5>
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
                                    <th class="text-center">Return Status</th>
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
                                            <a href="{{route('shop.product.details', ['product_slug'=>$item->product->slug])}}" target="_blank" class="body-title-2" style="color: #333;">{{$item->product->name}}</a>
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
                                                <i class="icon-eye" style="color: #333;"></i>
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
                    <h5 style="color: #333;">Shipping Address</h5>
                    <div class="my-account__address-item col-md-6">
                        <div class="my-account__address-item__detail">
                            <p>{{$order->name}}</p>
                            <p>{{$order->address}}</p>
                            <p>{{$order->barangay}},</p>
                            <p>{{$order->city}}, {{$order->province}} </p>
                            <p>{{$order->region}}, {{$order->landmark}}</p>
                            <p>{{$order->postal}}</p>
                            <br>
                            <p>Mobile : {{$order->phone}}</p>
                        </div>
                    </div>
                </div>

                <div class="wg-box mt-5">
                    <h5 style="color: #333;">Transactions</h5>
                    <table class="table table-striped table-bordered table-transaction">
                        <tbody>
                            <tr>
                                <th>Subtotal</th>
                                <td>₱{{$order->subtotal}}</td>
                                <th>Tax</th>
                                <td>₱{{$order->tax}}</td>
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

                <div class="wg-box mt-5 text-right">
                    <form action="{{route('user.order.cancel')}}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="order_id" value="{{$order->id}}">
                        <!-- Changed button type to submit -->
                        <button type="submit" class="btn btn-danger cancel-order">Cancel Order</button>
                    </form>
                </div>


            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $(function() {
        // The confirmation is still triggered by the cancel-order button
        $('.cancel-order').on('click', function(e) {
            e.preventDefault(); // Prevent the default form submit behavior
            var form = $(this).closest('form');

            swal({
                title: "Are you sure?",
                text: "You want to cancel this order?",
                icon: "warning",
                buttons: ["No", "Yes"],
                dangerMode: true,
            }).then(function(result) {
                if (result) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush