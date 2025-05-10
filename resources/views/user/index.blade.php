@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">My Account</h2>
        <div class="row">
            <div class="col-lg-3">
                @include('user.account-nav')
            </div>
            <div class="col-lg-9">
                <div class="wg-box">
                    <!-- Welcome Section -->
                    <div class="user-welcome-section mb-4 p-3 border-start border-primary border-4 bg-light rounded">
                        <div class="d-flex align-items-center">
                            <div class="user-image me-3">
                                <img src="{{ Auth::user()->profile_picture ? asset('uploads/profile/'.Auth::user()->profile_picture) : 'https://www.pngall.com/wp-content/uploads/5/Profile.png' }}" alt="Profile" class="rounded-circle" style="width: 64px; height: 64px; object-fit: cover;">
                            </div>
                            <div class="user-greeting">
                                <h4 class="mb-1">Welcome back, {{ Auth::user()->name }}!</h4>
                                <p class="mb-0 text-muted small">{{ Auth::user()->bio ? Auth::user()->bio : 'Manage your account and view your orders' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="dashboard-stats row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <i class="icon-shopping-bag mb-2" style="font-size: 24px; color: #4a6cf7;"></i>
                                <h3 class="mb-0">{{ isset(Auth::user()->orders) ? Auth::user()->orders->count() : 0 }}</h3>
                                <p class="mb-0 text-muted small">Orders</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <i class="icon-map-pin mb-2" style="font-size: 24px; color: #22c55e;"></i>
                                <h3 class="mb-0">{{ isset(Auth::user()->addresses) ? Auth::user()->addresses->count() : 0 }}</h3>
                                <p class="mb-0 text-muted small">Addresses</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded text-center">
                                <i class="icon-heart mb-2" style="font-size: 24px; color: #FF5200;"></i>
                                <h3 class="mb-0">{{ isset(Auth::user()->wishlist) ? Auth::user()->wishlist->count() : 0 }}</h3>
                                <p class="mb-0 text-muted small">Wishlist</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="quick-links">
                        <h5 class="mb-3 pb-2 border-bottom">Quick Actions</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('user.orders') }}" class="btn btn-outline-primary w-100">
                                    <i class="icon-shopping-bag me-2"></i> My Orders
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('user.addresses') }}" class="btn btn-outline-primary w-100">
                                    <i class="icon-map-pin me-2"></i> My Addresses
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('user.account.details') }}" class="btn btn-outline-primary w-100">
                                    <i class="icon-user me-2"></i> Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection