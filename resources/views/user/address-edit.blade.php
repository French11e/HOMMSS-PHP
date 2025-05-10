@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">Edit Address</h2>
        <div class="row">
            <div class="col-lg-3">
                @include('user.account-nav')
            </div>
            <div class="col-lg-9">
                <div class="page-content">
                    <form action="{{ route('user.address.update', $address->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $address->name) }}" required>
                                @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $address->phone) }}" required>
                                @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="address">Street Address</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $address->address) }}" required>
                                @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="landmark">Landmark</label>
                                <input type="text" class="form-control @error('landmark') is-invalid @enderror" id="landmark" name="landmark" value="{{ old('landmark', $address->landmark) }}" required>
                                @error('landmark')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="barangay">Barangay</label>
                                <input type="text" class="form-control @error('barangay') is-invalid @enderror" id="barangay" name="barangay" value="{{ old('barangay', $address->barangay) }}" required>
                                @error('barangay')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="city">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $address->city) }}" required>
                                @error('city')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="province">Province</label>
                                <input type="text" class="form-control @error('province') is-invalid @enderror" id="province" name="province" value="{{ old('province', $address->province) }}" required>
                                @error('province')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="region">Region</label>
                                <input type="text" class="form-control @error('region') is-invalid @enderror" id="region" name="region" value="{{ old('region', $address->region) }}" required>
                                @error('region')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="postal">Postal Code</label>
                                <input type="text" class="form-control @error('postal') is-invalid @enderror" id="postal" name="postal" value="{{ old('postal', $address->postal) }}" required>
                                @error('postal')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Update Address</button>
                            <a href="{{ route('user.addresses') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>

                    <div class="mt-4">
                        @if(!$address->isdefault)
                        <form action="{{ route('user.address.default', $address->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">Set as Default</button>
                        </form>
                        @endif

                        <form action="{{ route('user.address.delete', $address->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this address?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Address</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection