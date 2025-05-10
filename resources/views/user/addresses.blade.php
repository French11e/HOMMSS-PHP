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
                <div class="wg-box">
                    <!-- Header with notice and add button -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <p class="notice mb-0 text-muted">The following addresses will be used on checkout.</p>
                        <a href="{{ route('user.address.create') }}" class="btn btn-primary btn-sm">
                            <i class="icon-plus-circle me-1"></i> Add Address
                        </a>
                    </div>

                    <!-- Addresses section -->
                    <div class="address-list">
                        <h5 class="mb-3 pb-2 border-bottom">Shipping Addresses</h5>

                        @if($addresses->count() > 0)
                        <div class="row">
                            @foreach($addresses as $address)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center p-3 border-bottom">
                                        <div>
                                            <h6 class="mb-0 fw-bold">
                                                {{ $address->name }}
                                            </h6>
                                            @if($address->isdefault)
                                            <span class="badge bg-success mt-1">Default</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('user.address.edit', $address->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="icon-edit-2"></i> Edit
                                        </a>
                                    </div>
                                    <div class="card-body p-3">
                                        <p class="mb-1 small">{{ $address->address }}</p>
                                        <p class="mb-1 small">{{ $address->landmark }}</p>
                                        <p class="mb-1 small">{{ $address->barangay }}, {{ $address->city }}</p>
                                        <p class="mb-1 small">{{ $address->province }}, {{ $address->region }}</p>
                                        <p class="mb-1 small">{{ $address->postal }}</p>
                                        <p class="mb-0 mt-2 small"><strong>Mobile:</strong> {{ $address->phone }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-4 bg-light rounded">
                            <div class="mb-3">
                                <i class="icon-map-pin" style="font-size: 36px; color: #cbd5e1;"></i>
                            </div>
                            <h6>No addresses found</h6>
                            <p class="text-muted small">You haven't added any addresses yet.</p>
                            <a href="{{ route('user.address.create') }}" class="btn btn-primary btn-sm mt-2">
                                Add Your First Address
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection