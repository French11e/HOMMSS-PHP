@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" id="reset-form">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" id="reset-button">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                                <div id="cooldown-timer" class="mt-2 text-danger" style="display: none;">
                                    Please wait <span id="countdown">3:00</span> before requesting another reset link.
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resetForm = document.getElementById('reset-form');
        const resetButton = document.getElementById('reset-button');
        const cooldownTimer = document.getElementById('cooldown-timer');
        const countdown = document.getElementById('countdown');

        // Check if there's a stored cooldown timestamp
        const cooldownUntil = localStorage.getItem('passwordResetCooldown');

        if (cooldownUntil && new Date().getTime() < parseInt(cooldownUntil)) {
            // Still in cooldown period
            startCooldown(Math.ceil((parseInt(cooldownUntil) - new Date().getTime()) / 1000));
        }

        resetForm.addEventListener('submit', function(e) {
            // Only set cooldown if form is valid
            if (resetForm.checkValidity()) {
                // Set cooldown for 180 seconds (3 minutes)
                const cooldownTime = new Date().getTime() + (180 * 1000);
                localStorage.setItem('passwordResetCooldown', cooldownTime);

                // Allow form submission to continue
                setTimeout(() => {
                    startCooldown(180);
                }, 500);
            }
        });

        function startCooldown(seconds) {
            resetButton.disabled = true;
            cooldownTimer.style.display = 'block';

            // Format time as MM:SS
            function formatTime(secs) {
                const minutes = Math.floor(secs / 60);
                const seconds = secs % 60;
                return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            }

            countdown.textContent = formatTime(seconds);

            const interval = setInterval(() => {
                seconds--;
                countdown.textContent = formatTime(seconds);

                if (seconds <= 0) {
                    clearInterval(interval);
                    resetButton.disabled = false;
                    cooldownTimer.style.display = 'none';
                    localStorage.removeItem('passwordResetCooldown');
                }
            }, 1000);
        }
    });
</script>
@endsection