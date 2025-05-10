@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Edit User</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{route('admin.users')}}">
                        <div class="text-tiny">All Users</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Edit User</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="form-box">
                <form method="POST" action="{{ route('admin.user.update') }}" class="form-new-product form-style-1">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <fieldset class="name">
                        <div class="body-title mb-10">Name <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter user name" name="name" value="{{ $user->name }}" aria-required="true" required="">
                    </fieldset>
                    @error('name')<span class="alert alert-danger text-center">{{$message}}</span>@enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">Email <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="email" placeholder="Enter user email" name="email" value="{{ $user->email }}" aria-required="true" required="">
                    </fieldset>
                    @error('email')<span class="alert alert-danger text-center">{{$message}}</span>@enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">Mobile</div>
                        <input class="mb-10" type="text" placeholder="Enter user mobile number" name="mobile" value="{{ $user->mobile }}">
                    </fieldset>
                    @error('mobile')<span class="alert alert-danger text-center">{{$message}}</span>@enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">User Type <span class="tf-color-1">*</span></div>
                        <select class="mb-10" name="utype" aria-required="true" required="">
                            <option value="USR" {{ $user->utype == 'USR' ? 'selected' : '' }}>User</option>
                            <option value="ADM" {{ $user->utype == 'ADM' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </fieldset>
                    @error('utype')<span class="alert alert-danger text-center">{{$message}}</span>@enderror

                    <div class="flex items-center justify-between gap10 flex-wrap">
                        <button class="btn-primary" type="submit">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection