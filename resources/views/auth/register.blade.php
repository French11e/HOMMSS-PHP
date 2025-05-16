@extends('layouts.app')

@section('content')
<main class="pt-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <h4 class="mb-4 text-center">Create Your Account</h4>

                        <form method="POST" action="{{ route('register') }}" name="register-form" class="needs-validation" novalidate>
                            @csrf

                            <!-- Honeypot (improved) -->
                            <div class="visually-hidden" aria-hidden="true" style="position: absolute; left: -9999px;">
                                <input type="text" name="honeypot" id="honeypot" value="" tabindex="-1" autocomplete="off">
                                <input type="hidden" name="timestamp" value="{{ time() }}">
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text"
                                    class="form-control rounded-3 @error('name') is-invalid @enderror"
                                    name="name"
                                    placeholder="e.g. Juan Dela Cruz"
                                    value="{{ old('name') }}"
                                    required maxlength="255"
                                    autocomplete="name">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email"
                                    class="form-control rounded-3 @error('email') is-invalid @enderror"
                                    name="email"
                                    placeholder="e.g. juan@example.com"
                                    value="{{ old('email') }}"
                                    required autocomplete="email">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile</label>
                                <input type="tel"
                                    class="form-control rounded-3 @error('mobile') is-invalid @enderror"
                                    name="mobile"
                                    placeholder="e.g. +639171234567"
                                    value="{{ old('mobile') }}"
                                    required pattern="^\+?[0-9]{10,15}$"
                                    oninput="formatPhoneNumber(this)">
                                @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password"
                                    id="password"
                                    class="form-control rounded-3 @error('password') is-invalid @enderror"
                                    name="password"
                                    placeholder="Enter a strong password"
                                    required autocomplete="new-password">
                                <div class="form-text">
                                    Must be 12+ characters and include uppercase, lowercase, number, and special character.
                                </div>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">Confirm Password</label>
                                <input type="password"
                                    class="form-control rounded-3"
                                    name="password_confirmation"
                                    placeholder="Repeat your password"
                                    required oninput="checkPasswordMatch(this)">
                                <div id="password-match-feedback" class="form-text"></div>
                            </div>

                            <div class="form-text mb-3">
                                We’ll use this info to manage your account and provide a better experience.
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-3" id="register-button">
                                Register
                            </button>

                            <div class="text-center mt-3">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-decoration-none">Login</a>
                            </div>
                        </form>

                        <hr class="my-4">

                        <a href="{{ route('google-auth') }}"
                            class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 rounded-3">
                            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google Logo" style="width:20px;">
                            <span>Continue with Google</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

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

    function formatPhoneNumber(input) {
        let numbers = input.value.replace(/\D/g, '');
        if (numbers.length > 10 && !input.value.startsWith('+')) {
            numbers = '+' + numbers;
        }
        input.value = numbers;

        const isValid = /^\+?\d{10,15}$/.test(input.value);
        input.classList.toggle('is-valid', isValid);
        input.classList.toggle('is-invalid', !isValid);
    }
</script>
@endsection

@endsection
