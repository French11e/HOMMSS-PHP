@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">Account Details</h2>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row">
            <div class="col-lg-3">
                @include('user.account-nav')
            </div>
            <div class="col-lg-9">
                <div class="wg-box">
                    <div class="col-lg-12">
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
                                            <button type="submit" class="btn btn-primary">Update Profile Picture</button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Profile Information Form -->
                                <form method="POST" action="{{ route('user.update.profile') }}" class="form-new-product form-style-1 needs-validation" novalidate="">
                                    @csrf

                                    <fieldset class="name">
                                        <div class="body-title">Name <span class="tf-color-1">*</span></div>
                                        <input class="flex-grow" type="text" placeholder="Full Name"
                                            name="name" tabindex="0" value="{{ old('name', Auth::user()->name) }}" aria-required="true"
                                            required="">
                                    </fieldset>
                                    @error('name')<span class="alert alert-danger text-center">{{$message}}</span>@enderror

                                    <fieldset class="name">
                                        <div class="body-title">Mobile Number <span class="tf-color-1">*</span></div>
                                        <input class="flex-grow" type="text" placeholder="Mobile Number"
                                            name="mobile" tabindex="0" value="{{ old('mobile', Auth::user()->mobile) }}" aria-required="true"
                                            required="">
                                    </fieldset>
                                    @error('mobile')<span class="alert alert-danger text-center">{{$message}}</span>@enderror

                                    <fieldset class="name">
                                        <div class="body-title">Email Address <span class="tf-color-1">*</span></div>
                                        <input class="flex-grow" type="email" placeholder="Email Address"
                                            name="email" tabindex="0" value="{{ old('email', Auth::user()->email) }}" aria-required="true"
                                            required="">
                                    </fieldset>
                                    @error('email')<span class="alert alert-danger text-center">{{$message}}</span>@enderror

                                    <fieldset class="name">
                                        <div class="body-title">About Me</div>
                                        <textarea class="flex-grow" name="bio" placeholder="About Me" style="height: 100px">{{ old('bio', Auth::user()->bio) }}</textarea>
                                    </fieldset>
                                    @error('bio')<span class="alert alert-danger text-center">{{$message}}</span>@enderror

                                    <div class="col-md-12">
                                        <div class="my-3">
                                            <button type="submit" class="btn btn-primary tf-button w208">Save Changes</button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Password Management Section -->
                                <div id="password-management-section">
                                    @php
                                    $user = Auth::user();
                                    $hasGoogleId = !empty($user->google_id);
                                    $hasPassword = !empty($user->password);
                                    @endphp

                                    @if($hasGoogleId && !$hasPassword)
                                    <!-- Google user without password -->
                                    <div class="col-md-12 mt-4">
                                        <div class="alert alert-info">
                                            <h5 class="text-uppercase mb-2">Google Account</h5>
                                            <p>You're signed in with Google. You can set a password to also log in directly with your email.</p>

                                            <button class="btn btn-outline-primary mt-2" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#setPasswordForm" aria-expanded="false" aria-controls="setPasswordForm">
                                                Set Password
                                            </button>

                                            <div class="collapse mt-3" id="setPasswordForm">
                                                <form method="POST" action="{{ route('user.set.password') }}" class="form-new-product form-style-1 needs-validation" novalidate>
                                                    @csrf
                                                    <fieldset class="name">
                                                        <div class="body-title">New password <span class="tf-color-1">*</span></div>
                                                        <input class="flex-grow" type="password" placeholder="New password"
                                                            id="password" name="password" aria-required="true" required>
                                                    </fieldset>
                                                    @error('password')<span class="alert alert-danger text-center d-block">{{$message}}</span>@enderror

                                                    <fieldset class="name">
                                                        <div class="body-title">Confirm new password <span class="tf-color-1">*</span></div>
                                                        <input class="flex-grow" type="password" placeholder="Confirm new password"
                                                            id="password_confirmation" name="password_confirmation" aria-required="true" required
                                                            oninput="checkPasswordMatch(this)">
                                                        <div id="password-match-feedback" class="form-text"></div>
                                                    </fieldset>

                                                    <div class="col-md-12">
                                                        <div class="my-3">
                                                            <button type="submit" class="btn btn-primary tf-button">Set Password</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($hasGoogleId && $hasPassword)
                                    <!-- Google user with password -->
                                    <div class="col-md-12 mt-4">
                                        <div class="alert alert-info">
                                            <h5 class="text-uppercase mb-2">PASSWORD CHANGE</h5>
                                            <p>You can log in with either Google or your email/password.</p>
                                        </div>

                                        <form method="POST" action="{{ route('user.change.password') }}" class="form-new-product form-style-1 needs-validation" novalidate="">
                                            @csrf
                                            <fieldset class="name">
                                                <div class="body-title">Current password <span class="tf-color-1">*</span></div>
                                                <input class="flex-grow" type="password" placeholder="Current password"
                                                    id="current_password" name="current_password" aria-required="true" required="">
                                            </fieldset>
                                            @error('current_password')<span class="alert alert-danger text-center">{{$message}}</span>@enderror

                                            <fieldset class="name">
                                                <div class="body-title">New password <span class="tf-color-1">*</span></div>
                                                <input class="flex-grow" type="password" placeholder="New password"
                                                    id="password" name="password" aria-required="true" required="">
                                            </fieldset>
                                            @error('password')<span class="alert alert-danger text-center">{{$message}}</span>@enderror

                                            <fieldset class="name">
                                                <div class="body-title">Confirm new password <span class="tf-color-1">*</span></div>
                                                <input class="flex-grow" type="password" placeholder="Confirm new password"
                                                    id="password_confirmation" name="password_confirmation" aria-required="true" required=""
                                                    oninput="checkPasswordMatch(this)">
                                                <div id="password-match-feedback" class="form-text"></div>
                                            </fieldset>

                                            <div class="col-md-12">
                                                <div class="my-3">
                                                    <button type="submit" class="btn btn-primary tf-button">Change Password</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    @else
                                    <!-- Regular user -->
                                    <div class="col-md-12 mt-4">
                                        <div class="alert alert-info">
                                            <h5 class="text-uppercase mb-2">PASSWORD CHANGE</h5>
                                        </div>

                                        <form method="POST" action="{{ route('user.change.password') }}" class="form-new-product form-style-1 needs-validation" novalidate="">
                                            @csrf
                                            <fieldset class="name">
                                                <div class="body-title">Current password <span class="tf-color-1">*</span></div>
                                                <input class="flex-grow" type="password" placeholder="Current password"
                                                    id="current_password" name="current_password" aria-required="true" required="">
                                            </fieldset>
                                            @error('current_password')<span class="alert alert-danger text-center">{{$message}}</span>@enderror

                                            <fieldset class="name">
                                                <div class="body-title">New password <span class="tf-color-1">*</span></div>
                                                <input class="flex-grow" type="password" placeholder="New password"
                                                    id="password" name="password" aria-required="true" required="">
                                            </fieldset>
                                            @error('password')<span class="alert alert-danger text-center">{{$message}}</span>@enderror

                                            <fieldset class="name">
                                                <div class="body-title">Confirm new password <span class="tf-color-1">*</span></div>
                                                <input class="flex-grow" type="password" placeholder="Confirm new password"
                                                    id="password_confirmation" name="password_confirmation" aria-required="true" required=""
                                                    oninput="checkPasswordMatch(this)">
                                                <div id="password-match-feedback" class="form-text"></div>
                                            </fieldset>

                                            <div class="col-md-12">
                                                <div class="my-3">
                                                    <button type="submit" class="btn btn-primary tf-button">Change Password</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Account Section -->
                <div class="wg-box mt-4">
                    <div class="col-lg-12">
                        <div class="page-content">
                            <h5 class="text-uppercase mb-3 text-danger">Delete Account</h5>
                            <p class="mb-4">Once you delete your account, there is no going back. Please be certain.</p>

                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                Delete My Account
                            </button>

                            <!-- Delete Account Modal -->
                            <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-danger">Warning: This action cannot be undone. All your data will be permanently deleted.</p>
                                            <form method="POST" action="{{ route('user.account.delete') }}" id="delete-account-form">
                                                @csrf
                                                @method('DELETE')

                                                <div class="mb-3">
                                                    <label for="delete-confirmation" class="form-label">Type "DELETE" to confirm</label>
                                                    <input type="text" class="form-control" id="delete-confirmation" required>
                                                </div>

                                                @if(!Auth::user()->google_id || (Auth::user()->google_id && Auth::user()->password))
                                                <div class="mb-3">
                                                    <label for="password-confirmation" class="form-label">Enter your password</label>
                                                    <input type="password" class="form-control" id="password-confirmation" name="password" required>
                                                </div>
                                                @endif
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-danger" id="confirm-delete-btn" disabled>Delete Account</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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

    // Delete account functionality
    document.addEventListener('DOMContentLoaded', function() {
        const deleteConfirmationInput = document.getElementById('delete-confirmation');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
        const deleteAccountForm = document.getElementById('delete-account-form');

        if (deleteConfirmationInput && confirmDeleteBtn && deleteAccountForm) {
            deleteConfirmationInput.addEventListener('input', function() {
                confirmDeleteBtn.disabled = this.value !== 'DELETE';
            });

            confirmDeleteBtn.addEventListener('click', function() {
                if (deleteConfirmationInput.value === 'DELETE') {
                    deleteAccountForm.submit();
                }
            });
        }
    });
</script>
@endsection