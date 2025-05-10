@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">Addresses</h2>
        <div class="row">
            <div class="col-lg-3">
                @include('user.account-nav')
            </div>
            <div class="col-lg-9">
                <div class="page-content my-account__address">
                    <div class="row">
                        <div class="col-6">
                            <p class="notice">The following addresses will be used on the checkout page by default.</p>
                        </div>
                        <div class="col-6 text-right">
                            <a href="{{ route('user.address.create') }}" class="btn btn-sm btn-info">Add New</a>
                        </div>
                    </div>
                    <div class="my-account__address-list row">
                        <h5>Shipping Address</h5>
                        @if($addresses->count() > 0)
                        @foreach($addresses as $address)
                        <div class="my-account__address-item col-md-6">
                            <div class="my-account__address-item__title">
                                <h5>{{ $address->name }} @if($address->isdefault)<i class="fa fa-check-circle text-success"></i>@endif</h5>
                                <a href="{{ route('user.address.edit', $address->id) }}">Edit</a>
                            </div>
                            <div class="my-account__address-item__detail">
                                <p>{{ $address->address }}</p>
                                <p>{{ $address->landmark }}</p>
                                <p>{{ $address->barangay }}, {{ $address->city }}</p>
                                <p>{{ $address->province }}, {{ $address->region }}</p>
                                <p>{{ $address->postal }}</p>
                                <br>
                                <p>Mobile: {{ $address->phone }}</p>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="col-12">
                            <p>No addresses found. Please add a new address.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection