@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Orders</h3>
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
                    <div class="text-tiny">All Orders</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search" method="GET" action="{{ route('admin.orders') }}" id="order-search-form">
                        <fieldset class="name">
                            <input type="text" placeholder="Search here..." class="" name="name"
                                tabindex="2" value="{{ request('name') }}" aria-required="true">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                @if(request('name'))
                <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-secondary">Clear Search</a>
                @endif
            </div>
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    <div id="loading-indicator" class="search-loading" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Searching orders...</p>
                    </div>
                    @if(Session::has('status'))
                    <p class="alert alert-success">{{Session::get('status')}}</p>
                    @endif
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Order Date</th>
                                <th class="text-center">Items</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{$order->id}}</td>
                                <td class="pname">
                                    <div class="name">
                                        <a href="{{ route('admin.user.orders', ['id' => $order->user_id]) }}" class="body-title-2">{{$order->name}}</a>
                                        <div class="text-tiny mt-3">{{$order->address}}</div>
                                    </div>
                                </td>
                                <td>{{$order->phone}}</td>
                                <td>₱{{$order->total}}</td>
                                <td>
                                    @if($order->status == 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                    @elseif($order->status == 'processing')
                                    <span class="badge bg-info">Processing</span>
                                    @elseif($order->status == 'shipped')
                                    <span class="badge bg-primary">Shipped</span>
                                    @elseif($order->status == 'canceled')
                                    <span class="badge bg-danger">Canceled</span>
                                    @else
                                    <span class="badge bg-warning">Ordered</span>
                                    @endif
                                </td>
                                <td>{{$order->created_at->format('M d, Y')}}</td>
                                <td class="text-center">{{$order->orderItems->count()}}</td>
                                <td>
                                    <div class="list-icon-function">
                                        <a href="{{route('admin.order.details', ['order_id' => $order->id])}}">
                                            <div class="item eye">
                                                <i class="icon-eye"></i>
                                            </div>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        // Show loading indicator when form is submitted
        $('#order-search-form').on('submit', function(e) {
            // Show loading indicator
            $('#loading-indicator').show();
            
            // The form will naturally submit and reload the page
        });
        
        // Show loading indicator when pagination links are clicked
        $('.pagination a').on('click', function() {
            $('#loading-indicator').show();
        });
    });
</script>
@endpush

