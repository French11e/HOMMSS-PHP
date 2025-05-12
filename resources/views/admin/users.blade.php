@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Users</h3>
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
                    <div class="text-tiny">All Users</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search" id="user-search-form">
                        <fieldset class="name">
                            <input type="text" placeholder="Search here..." class="" name="name"
                                tabindex="2" value="" aria-required="true" required="">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    <div id="loading-indicator" class="search-loading" style="display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="6" x2="12" y2="12"></line>
                        </svg>
                        <p>Loading users...</p>
                    </div>
                    @if(Session::has('status'))
                    <p class="alert alert-success">{{Session::get('status')}}</p>
                    @endif
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th class="text-center">Total Orders</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td class="pname">
                                    <div class="image">
                                        <img src="{{ $user->profile_picture ? asset('uploads/profile/'.$user->profile_picture) : 'https://www.pngall.com/wp-content/uploads/5/Profile.png' }}" alt="{{$user->name}}" class="image">
                                    </div>
                                    <div class="name">
                                        <a href="#" class="body-title-2">{{$user->name}}</a>
                                        <div class="text-tiny mt-3">{{$user->utype == 'ADM' ? 'Admin' : 'User'}}</div>
                                    </div>
                                </td>
                                <td>{{$user->mobile ?? 'N/A'}}</td>
                                <td>{{$user->email}}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.user.orders', ['id' => $user->id]) }}" target="_blank">
                                        {{ $user->orders->count() ?? 0 }}
                                    </a>
                                </td>
                                <td>
                                    <div class="list-icon-function">
                                        <a href="{{ route('admin.user.edit', ['id' => $user->id]) }}">
                                            <div class="item edit">
                                                <i class="icon-edit-3"></i>
                                            </div>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        // Show loading indicator when form is submitted
        $('#user-search-form').on('submit', function(e) {
            // Show loading indicator
            $('#loading-indicator').show();
            
            // The form will naturally submit and reload the page
        });
        
        // Show loading indicator when pagination links are clicked
        $('.pagination a').on('click', function() {
            $('#loading-indicator').show();
        });
    });
</script>
@endpush

