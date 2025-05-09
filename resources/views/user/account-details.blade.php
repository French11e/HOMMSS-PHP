@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">Account Details</h2>
        <div class="row">
            <div class="col-lg-3">
                @include('user.account-nav')
            </div>
            <div class="col-lg-9">
                <div class="page-content my-account__edit">
                    <div class="my-account__edit-form">
                        <!-- Profile Picture Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="profile-picture-container text-center mb-3">
                                    <div class="profile-picture mx-auto" style="width: 150px; height: 150px; overflow: hidden; border-radius: 50%; position: relative;">
                                        @if(Auth::user()->profile_picture)
                                        <img src="{{ asset('uploads/profile/' . Auth::user()->profile_picture) }}" alt="Profile Picture" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                        <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                        @endif
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('user.update.profile.picture') }}" enctype="multipart/form-data" class="text-center">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="file" name="profile_picture" id="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" accept="image/*">
                                        @error('profile_picture')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary btn-sm">Update Profile Picture</button>
                                </form>
                            </div>
                        </div>

                        <!-- Profile Information Form -->
                        <form method="POST" action="{{ route('user.update.profile') }}" class="needs-validation" novalidate="">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Full Name" name="name" value="{{ old('name', Auth::user()->name) }}" required="">
                                        <label for="name">Name</label>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                            placeholder="Mobile Number" name="mobile" value="{{ old('mobile', Auth::user()->mobile) }}" required="">
                                        <label for="mobile">Mobile Number</label>
                                        @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            placeholder="Email Address" name="email" value="{{ old('email', Auth::user()->email) }}" required="">
                                        <label for="account_email">Email Address</label>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <textarea class="form-control @error('bio') is-invalid @enderror"
                                            placeholder="About Me" name="bio" style="height: 100px">{{ old('bio', Auth::user()->bio) }}</textarea>
                                        <label for="bio">About Me</label>
                                        @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="my-3">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="col-md-12 mt-4">
                            <div class="my-3">
                                <h5 class="text-uppercase mb-3">Password Change</h5>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('user.change.password') }}" class="needs-validation" novalidate="">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                            id="current_password" name="current_password" placeholder="Current password" required="">
                                        <label for="current_password">Current password</label>
                                        @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="New password" required="">
                                        <label for="password">New password</label>
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="Confirm new password" required=""
                                            oninput="checkPasswordMatch(this)">
                                        <label for="password_confirmation">Confirm new password</label>
                                        <div id="password-match-feedback" class="form-text"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="my-3">
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('scripts')
<script>
    function checkPasswordMatch(confirmField) {
        const password = document.getElementById("password");
        const feedback = document.getElementById("password-match-feedback");

        if (password && confirmField.value !== password.value) {
            confirmField.setCustomValidity("Passwords don't match");
            feedback.textContent = "Passwords do not match.";
            feedback.classList.add("text-danger");
            feedback.classList.remove("text-success");
        } else {
            confirmField.setCustomValidity('');
            feedback.textContent = "Passwords match.";
            feedback.classList.remove("text-danger");
            feedback.classList.add("text-success");
        }
    }

    // Preview profile picture before upload
    document.getElementById('profile_picture').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const profilePicContainer = document.querySelector('.profile-picture img');
                profilePicContainer.src = event.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection