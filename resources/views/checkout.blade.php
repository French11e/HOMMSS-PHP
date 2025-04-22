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
        <form name="checkout-form" action="">
            <div class="checkout-form">
                <div class="billing-info__wrapper">
                    <div class="row">
                        <div class="col-6">
                            <h4>SHIPPING DETAILS</h4>
                        </div>
                        <div class="col-6">
                        </div>
                    </div>
                    @if($address)

                    <div class="row">
                        <div class="col-md-12">
                            <div class="my-account__address-list">
                                <div class="my-account__address-list-item">
                                    <div class="my-account__address-item__details">
                                        <p>{{ $address->name }}</p>
                                        <p>{{ $address->address }}</p>
                                        <p>{{ $address->landmark }}</p>
                                        <p>{{ $address->barangay }}, {{ $address->city }}</p>
                                        <p>{{ $address->province }}, {{ $address->region }}</p>
                                        <p>{{ $address->postal }}</p>
                                        <br>
                                        <p>{{ $address->phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row mt-5">
                        <div class="col-md-6">
                            <div class="form-floating my-3">
                                <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') }}">
                                <label for="name">Full Name *</label>
                                @error('name')<span class="text-danger">{{$messsage}}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating my-3">
                                <input type="text" class="form-control" id="phone" name="phone" required value="{{ old('phone') }}">
                                <label for="phone">Mobile Number (e.g. 09XX-XXX-XXXX) *</label>
                                @error('phone')<span class="text-danger">{{$messsage}}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating my-3">
                                <input type="text" class="form-control" id="postal" name="postal" required value="{{ old('postal') }}">
                                <label for="postal">Postal Code *</label>
                                @error('postal')<span class="text-danger">{{$messsage}}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mt-3 mb-3">
                                <input type="text" class="form-control" id="barangay" name="barangay" required value="{{ old('barangay') }}">
                                <label for="barangay">Barangay *</label>
                                @error('barangay')<span class="text-danger">{{$messsage}}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating my-3">
                                <input type="text" class="form-control" id="city" name="city" required value="{{ old('city') }}">
                                <label for="city">City / Municipality *</label>
                                @error('city')<span class="text-danger">{{$messsage}}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating my-3">
                                <input type="text" class="form-control" id="province" name="province" required value="{{ old('province') }}">
                                <label for="province">Province *</label>
                                @error('province')<span class="text-danger">{{$messsage}}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating my-3">
                                <input type="text" class="form-control" id="region" name="region" required value="{{ old('region') }}">
                                <label for="region">Region *</label>
                                @error('region')<span class="text-danger">{{$messsage}}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating my-3">
                                <input type="text" class="form-control" id="address" name="address" required value="{{ old('address') }}">
                                <label for="address">Street, Building, House No. *</label>
                                @error('address')<span class="text-danger">{{$messsage}}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating my-3">
                                <input type="text" class="form-control" id="landmark" name="landmark" required value="{{ old('landmark') }}">
                                <label for="landmark">Nearest Landmark *</label>
                                @error('landmark')<span class="text-danger">{{$messsage}}</span>@enderror
                            </div>
                        </div>
                    </div>

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
                            <div class="form-check">
                                <input class="form-check-input form-check-input_fill" type="radio" name="checkout_payment_method"
                                    id="checkout_payment_method_1" checked>
                                <label class="form-check-label" for="checkout_payment_method_1">
                                    Direct bank transfer
                                    <p class="option-detail">
                                        Make your payment directly into our bank account. Please use your Order ID as the payment
                                        reference.Your order will not be shipped until the funds have cleared in our account.
                                    </p>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input form-check-input_fill" type="radio" name="checkout_payment_method"
                                    id="checkout_payment_method_2">
                                <label class="form-check-label" for="checkout_payment_method_2">
                                    Check payments
                                    <p class="option-detail">
                                        Phasellus sed volutpat orci. Fusce eget lore mauris vehicula elementum gravida nec dui. Aenean
                                        aliquam varius ipsum, non ultricies tellus sodales eu. Donec dignissim viverra nunc, ut aliquet
                                        magna posuere eget.
                                    </p>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input form-check-input_fill" type="radio" name="checkout_payment_method"
                                    id="checkout_payment_method_3">
                                <label class="form-check-label" for="checkout_payment_method_3">
                                    Cash on delivery
                                    <p class="option-detail">
                                        Phasellus sed volutpat orci. Fusce eget lore mauris vehicula elementum gravida nec dui. Aenean
                                        aliquam varius ipsum, non ultricies tellus sodales eu. Donec dignissim viverra nunc, ut aliquet
                                        magna posuere eget.
                                    </p>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input form-check-input_fill" type="radio" name="checkout_payment_method"
                                    id="checkout_payment_method_4">
                                <label class="form-check-label" for="checkout_payment_method_4">
                                    Paypal
                                    <p class="option-detail">
                                        Phasellus sed volutpat orci. Fusce eget lore mauris vehicula elementum gravida nec dui. Aenean
                                        aliquam varius ipsum, non ultricies tellus sodales eu. Donec dignissim viverra nunc, ut aliquet
                                        magna posuere eget.
                                    </p>
                                </label>
                            </div>
                            <div class="policy-text">
                                Your personal data will be used to process your order, support your experience throughout this
                                website, and for other purposes described in our <a href="terms.html" target="_blank">privacy
                                    policy</a>.
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