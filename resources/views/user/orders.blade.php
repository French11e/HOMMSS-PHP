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
        background-color: #40c710;
        border-color: #40c710;
        color: white;
    }

    /* Override scrollbar styles specifically for this page */
    .wg-table::-webkit-scrollbar,
    .table-container::-webkit-scrollbar {
        width: 12px !important;
        height: 12px !important;
        display: block !important;
    }

    .wg-table::-webkit-scrollbar-track,
    .table-container::-webkit-scrollbar-track {
        background-color: #f1f1f1 !important;
        border-radius: 6px !important;
    }

    .wg-table::-webkit-scrollbar-thumb,
    .table-container::-webkit-scrollbar-thumb {
        background-color: #888 !important;
        border-radius: 6px !important;
        border: 2px solid #f1f1f1 !important;
    }

    .wg-table::-webkit-scrollbar-thumb:hover,
    .table-container::-webkit-scrollbar-thumb:hover {
        background-color: #555 !important;
    }
    
    /* Ensure table has proper overflow */
    .table-responsive {
        overflow-x: auto;
    }
    
    /* Add a class to ensure the table wrapper has visible scrollbars */
    .orders-table-wrapper {
        overflow-x: auto;
        max-width: 100%;
        margin-bottom: 20px;
    }
    
    /* Custom styling for user orders table */
    .orders-table-wrapper {
        overflow-x: auto;
        max-width: 100%;
        margin-bottom: 20px;
    }
    
    /* Normal visible scrollbar for user orders */
    .orders-table-wrapper::-webkit-scrollbar {
        width: 12px;
        height: 12px;
        display: block;
    }
    
    .orders-table-wrapper::-webkit-scrollbar-track {
        background-color: #f1f1f1;
        border-radius: 6px;
    }
    
    .orders-table-wrapper::-webkit-scrollbar-thumb {
        background-color: #888;
        border-radius: 6px;
        border: 2px solid #f1f1f1;
    }
    
    .orders-table-wrapper::-webkit-scrollbar-thumb:hover {
        background-color: #555;
    }
    
    /* Override any Discord-inspired scrollbar styles */
    .table-responsive::-webkit-scrollbar,
    .wg-table::-webkit-scrollbar {
        display: block !important;
        width: 12px !important;
        height: 12px !important;
    }
    
    /* Ensure the pagination has proper spacing */
    .wgp-pagination {
        margin-top: 15px;
    }
</style>

<main class="pt-90" style="padding-top: 0px; background-color: white;">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container" style="background-color: white;">
        <h2 class="page-title">Orders</h2>
        <div class="row">
            <div class="col-lg-2">
                @include('user.account-nav')
            </div>

            <div class="col-lg-10">
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 80px">OrderNo</th>
                                    <th>Name</th>
                                    <th class="text-center">Phone</th>
                                    <th class="text-center">Subtotal</th>
                                    <th class="text-center">Tax</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Order Date</th>
                                    <th class="text-center">Items</th>
                                    <th class="text-center">Delivered On</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                <tr>
                                    <td class="text-center">{{$order->id}}</td>
                                    <td>{{$order->name}}</td>
                                    <td class="text-center">{{$order->phone}}</td>
                                    <td class="text-center">₱{{$order->subtotal}}</td>
                                    <td class="text-center">₱{{$order->tax}}</td>
                                    <td class="text-center">₱{{$order->total}}</td>
                                    <td class="text-center">
                                        @if($order->status == 'delivered')
                                        <span class="badge bg-success">Delivered</span>
                                        @elseif($order->status == 'canceled')
                                        <span class="badge bg-danger">Canceled</span>
                                        @else
                                        <span class="badge bg-warning">Ordered</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{$order->created_at}}</td>
                                    <td class="text-center">{{$order->orderItems->count()}}</td>
                                    <td class="text-center">{{$order->delivered_date ?? 'N/A'}}</td>
                                    <td class="text-center">
                                        <a href="{{route('user.order.details', ['order_id' => $order->id])}}">
                                            <div class="list-icon-function view-icon">
                                                <div class="item eye">
                                                    <i class="fa fa-eye"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{$orders->links('pagination::bootstrap-5')}}
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

