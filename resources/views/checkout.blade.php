@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
        <h2 class="page-title">Shipping and Checkout</h2>
        <div class="checkout-steps">
            <a href="{{route('cart.index')}}" class="checkout-steps__item active">
                <span class="checkout-steps__item-number">01</span>
                <span class="checkout-steps__item-title">
                    <span>Shopping Bag</span>
                    <em>Manage Your Items List</em>
                </span>
            </a>
            <a href="javascript:void(0)" class="checkout-steps__item active">
                <span class="checkout-steps__item-number">02</span>
                <span class="checkout-steps__item-title">
                    <span>Shipping and Checkout</span>
                    <em>Checkout Your Items List</em>
                </span>
            </a>
            <a href="javascript:void(0)" class="checkout-steps__item">
                <span class="checkout-steps__item-number">03</span>
                <span class="checkout-steps__item-title">
                    <span>Confirmation</span>
                    <em>Review And Submit Your Order</em>
                </span>
            </a>
        </div>
        <form name="checkout-form" action="{{route('cart.place.an.order')}}" method="POST">
            @csrf
            <div class="checkout-form">
                <div class="billing-info__wrapper">
                    <div class="row">
                        <div class="col-6">
                            <h4>SHIPPING DETAILS</h4>
                        </div>
                        <div class="col-6">
                            @if(Auth::check())
                            <a href="{{ route('user.addresses') }}" class="btn btn-sm btn-outline-primary float-end">
                                <i class="icon-map-pin me-1"></i> Manage Addresses
                            </a>
                            @endif
                        </div>
                    </div>

                    @if(Auth::check())
                    @php
                    $userAddresses = \App\Models\Address::where('user_id', Auth::user()->id)->get();
                    @endphp

                    @if($userAddresses->count() > 0)
                    <!-- Saved Addresses Section -->
                    <div class="saved-addresses-section mb-4 mt-3">
                        <h5 class="mb-3">Select a shipping address</h5>
                        <div class="row">
                            @foreach($userAddresses as $addr)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border {{ $addr->isdefault ? 'border-primary' : '' }} shadow-sm">
                                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center p-3">
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $addr->name }}</h6>
                                            @if($addr->isdefault)
                                            <span class="badge bg-primary mt-1">Default</span>
                                            @endif
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="selected_address"
                                                id="address_{{ $addr->id }}" value="{{ $addr->id }}"
                                                {{ $addr->isdefault ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <p class="mb-1 small">{{ $addr->address }}</p>
                                        <p class="mb-1 small">{{ $addr->landmark }}</p>
                                        <p class="mb-1 small">{{ $addr->barangay }}, {{ $addr->city }}</p>
                                        <p class="mb-1 small">{{ $addr->province }}, {{ $addr->region }}</p>
                                        <p class="mb-1 small">{{ $addr->postal }}</p>
                                        <p class="mb-0 mt-2 small"><strong>Mobile:</strong> {{ $addr->phone }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-3 mb-3">
                            <button type="button" id="toggle-new-address" class="btn btn-outline-secondary btn-sm">
                                <i class="icon-plus-circle me-1"></i> Use a different address
                            </button>
                        </div>
                    </div>

                    <!-- New Address Form (initially hidden) -->
                    <div id="new-address-form" style="display: none;">
                        <h5 class="mb-3">Enter a new shipping address</h5>
                        <div class="row mt-3">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                                    <label for="name">Full Name *</label>
                                    @error('name')<span class="text-danger">{{$message}}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                    <label for="phone">Phone Number *</label>
                                    @error('phone')<span class="text-danger">{{$message}}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}">
                                    <label for="address">Street Address *</label>
                                    @error('address')<span class="text-danger">{{$message}}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('landmark') is-invalid @enderror" id="landmark" name="landmark" value="{{ old('landmark') }}">
                                    <label for="landmark">Landmark *</label>
                                    @error('landmark')<span class="text-danger">{{$message}}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('barangay') is-invalid @enderror" id="barangay" name="barangay" value="{{ old('barangay') }}">
                                    <label for="barangay">Barangay *</label>
                                    @error('barangay')<span class="text-danger">{{$message}}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}">
                                    <label for="city">City *</label>
                                    @error('city')<span class="text-danger">{{$message}}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('province') is-invalid @enderror" id="province" name="province" value="{{ old('province') }}">
                                    <label for="province">Province *</label>
                                    @error('province')<span class="text-danger">{{$message}}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('region') is-invalid @enderror" id="region" name="region" value="{{ old('region') }}">
                                    <label for="region">Region *</label>
                                    @error('region')<span class="text-danger">{{$message}}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('postal') is-invalid @enderror" id="postal" name="postal" value="{{ old('postal') }}">
                                    <label for="postal">Postal Code *</label>
                                    @error('postal')<span class="text-danger">{{$message}}</span>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="save_as_default" name="save_as_default">
                            <label class="form-check-label" for="save_as_default">
                                Save as default shipping address
                            </label>
                        </div>
                    </div>
                    @else
                    <!-- No saved addresses, show the form -->
                    <div class="alert alert-info mt-3">
                        <i class="icon-info me-2"></i> You don't have any saved addresses. Please enter your shipping details below.
                    </div>

                    <!-- Include the address form here -->
                    @endif
                    @else
                    <!-- Guest checkout - show address form -->
                    @endif
                </div>
                <div class="checkout__totals-wrapper">
                    <div class="sticky-content">
                        <div class="checkout__totals">
                            <h3>Your Order</h3>
                            <table class="checkout-cart-items">
                                <thead>
                                    <tr>
                                        <th>PRODUCT</th>
                                        <th align="right">SUBTOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(Cart::instance('cart') as $item)
                                    <tr>
                                        <td>
                                            {{$item->name}} x {{$item->qty}}
                                        </td>
                                        <td align="right">
                                            ₱{{$item->subtotal}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <table class="checkout-totals">
                                <tbody>
                                    <tr>
                                        <th>SUBTOTAL</th>
                                        <td class="text-right">₱{{Cart::instance('cart')->subtotal()}}</td>
                                    </tr>
                                    <tr>
                                        <th>SHIPPING</th>
                                        <td class="text-right">Free shipping</td>
                                    </tr>
                                    <tr>
                                        <th>VAT</th>
                                        <td class="text-right">₱{{Cart::instance('cart')->tax()}}</td>
                                    </tr>
                                    <tr>
                                        <th>TOTAL</th>
                                        <td class="text-right">₱{{Cart::instance('cart')->total()}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="checkout__payment-methods">
                            <h5 class="mb-3">Payment Method</h5>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="mode" id="mode1" value="card" required>
                                <label class="form-check-label" for="mode1">
                                    <i class="icon-credit-card me-2"></i> Debit or Credit Card
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="mode" id="mode2" value="gcash">
                                <label class="form-check-label" for="mode2">
                                    <i class="icon-paypal me-2"></i> Gcash
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="mode" id="mode3" value="cod" checked>
                                <label class="form-check-label" for="mode3">
                                    <i class="icon-truck me-2"></i> Cash on delivery
                                </label>
                            </div>
                            @error('mode')
                            <div class="text-danger mb-3">{{ $message }}</div>
                            @enderror
                            <div class="policy-text mt-3">
                                Your personal data will be used to process your order, support your experience throughout this
                                website, and for other purposes described in our <a href="{{ route('terms') }}" target="_blank">privacy policy</a>.
                            </div>
                        </div>

                        <button class="btn btn-primary btn-checkout">PLACE ORDER</button>
                    </div>
                </div>
            </div>
        </form>
    </section>
</main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggle-new-address');
        const newAddressForm = document.getElementById('new-address-form');

        if (toggleBtn && newAddressForm) {
            toggleBtn.addEventListener('click', function() {
                if (newAddressForm.style.display === 'none') {
                    newAddressForm.style.display = 'block';
                    this.innerHTML = '<i class="icon-x me-1"></i> Cancel new address';
                    this.classList.replace('btn-outline-secondary', 'btn-outline-danger');
                } else {
                    newAddressForm.style.display = 'none';
                    this.innerHTML = '<i class="icon-plus-circle me-1"></i> Use a different address';
                    this.classList.replace('btn-outline-danger', 'btn-outline-secondary');
                }
            });
        }
    });
</script>
@endpush