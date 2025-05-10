@extends('layouts.app')

@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="login-register container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Verify OTP</div>

                    <div class="card-body">
                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif

                        <form method="POST" action="{{ route('otp.verify') }}">
                            @csrf

                            <div class="form-floating mb-3">
                                <input id="otp" type="text" class="form-control form-control_gray @error('otp') is-invalid @enderror"
                                    name="otp" required autofocus>
                                <label for="otp">Enter 6-digit OTP sent to your email</label>
                                @error('otp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Verify OTP
                                    </button>

                                    <a href="{{ route('otp.resend') }}" class="btn btn-link">
                                        Resend OTP
                                    </a>
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