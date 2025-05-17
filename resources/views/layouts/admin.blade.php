<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>HOMMSS</title>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="surfside media" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('font/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('icon/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">

    <!-- Roboto Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Discord-inspired CSS -->
    <style>
        /* Discord Light Theme Colors */
        :root {
            --discord-bg: #ffffff;
            --discord-sidebar: #f2f3f5;
            --discord-hover: #e3e5e8;
            --discord-text: #2e3338;
            --discord-muted: #747f8d;
            --discord-accent: #5865f2;
            --discord-green: #3ba55c;
            --discord-divider: #e3e5e8;
            --discord-card: #f8f9fa;
            --discord-shadow: rgba(0, 0, 0, 0.08);
        }

        /* Global Styles */
        body.body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--discord-bg);
            color: var(--discord-text);
        }

        /* Sidebar Styling */
        .section-menu-left {
            background-color: var(--discord-sidebar);
            border-right: 1px solid var(--discord-divider);
        }

        .box-logo {
            padding: 12px 16px;
            border-bottom: 1px solid var(--discord-divider);
        }

        .center-heading {
            color: var(--discord-muted);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 24px 16px 8px;
            letter-spacing: 0.02em;
        }

        .menu-list .menu-item a {
            border-radius: 4px;
            margin: 2px 8px;
            padding: 8px 12px;
            transition: background-color 0.15s ease;
        }

        .menu-list .menu-item a:hover {
            background-color: var(--discord-hover);
        }

        .menu-list .menu-item a .icon i {
            color: var(--discord-muted);
        }

        .menu-list .menu-item a .text {
            font-weight: 500;
        }

        .menu-list .menu-item.has-children .sub-menu {
            background-color: transparent;
            padding-left: 12px;
        }

        .menu-list .menu-item.has-children .sub-menu .sub-menu-item a {
            padding: 6px 12px;
            margin-left: 24px;
        }

        /* Header Styling */
        .header-dashboard {
            background-color: var(--discord-bg);
            border-bottom: 1px solid var(--discord-divider);
            padding: 0;
            box-shadow: 0 1px 3px var(--discord-shadow);
        }

        .header-dashboard .wrap {
            padding: 12px 16px;
        }

        .form-search {
            background-color: var(--discord-sidebar);
            border-radius: 4px;
            overflow: hidden;
        }

        .form-search input {
            background-color: var(--discord-sidebar);
            border: none;
            padding: 8px 12px;
            color: var(--discord-text);
        }

        .form-search .button-submit button {
            color: var(--discord-muted);
        }

        /* Content Area */
        .main-content {
            padding: 24px;
            background-color: var(--discord-bg);
        }

        /* Cards and Widgets */
        .wg-chart-default,
        .wg-box {
            background-color: var(--discord-card);
            border-radius: 8px;
            border: 1px solid var(--discord-divider);
            box-shadow: 0 2px 10px var(--discord-shadow);
            padding: 16px;
            margin-bottom: 16px;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--discord-accent);
            border-color: var(--discord-accent);
        }

        .btn-primary:hover {
            background-color: #4752c4;
            border-color: #4752c4;
        }

        .btn-success {
            background-color: var(--discord-green);
            border-color: var(--discord-green);
        }

        /* Tables */
        .table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .table th {
            background-color: var(--discord-sidebar);
            color: var(--discord-text);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.03em;
        }

        .table td,
        .table th {
            padding: 12px 16px;
            border-bottom: 1px solid var(--discord-divider);
        }

        /* Footer */
        .bottom-page {
            border-top: 1px solid var(--discord-divider);
            padding: 16px 24px;
            color: var(--discord-muted);
            font-size: 14px;
        }

        /* Discord-style Icon Improvements */
        /* Icon Base Styling */
        [class^="icon-"],
        [class*=" icon-"] {
            color: var(--discord-muted);
            font-size: 18px;
            transition: color 0.15s ease;
        }

        /* Menu Icons */
        .menu-list .menu-item a .icon i {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            margin-right: 8px;
            color: var(--discord-muted);
        }

        .menu-list .menu-item a:hover .icon i {
            color: var(--discord-text);
        }

        .menu-list .menu-item.active a .icon i {
            color: var(--discord-accent);
        }

        /* Action Icons */
        .list-icon-function .item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 4px;
            background-color: transparent;
            transition: background-color 0.15s ease;
        }

        .list-icon-function .item:hover {
            background-color: var(--discord-hover);
        }

        .list-icon-function .item i {
            font-size: 16px;
        }

        .list-icon-function .item.edit i {
            color: var(--discord-accent);
        }

        .list-icon-function .item.delete i {
            color: #ed4245;
            /* Discord red */
        }

        /* Status Icons */
        .icon-check,
        .icon-check-circle {
            color: var(--discord-green);
        }

        .icon-alert-circle,
        .icon-alert-triangle {
            color: #faa61a;
            /* Discord yellow/warning */
        }

        .icon-x,
        .icon-x-circle {
            color: #ed4245;
            /* Discord red */
        }

        /* Notification Icons */
        .icon-bell {
            color: var(--discord-muted);
        }

        /* Interactive Icons */
        .icon-plus,
        .icon-edit-3,
        .icon-trash-2 {
            transition: transform 0.15s ease;
        }

        .icon-plus:hover,
        .icon-edit-3:hover,
        .icon-trash-2:hover {
            transform: scale(1.1);
        }

        /* Header Icons */
        .header-dashboard .icon {
            color: var(--discord-text);
            font-size: 20px;
        }

        /* Button Icons */
        .btn i {
            margin-right: 6px;
            font-size: 16px;
            vertical-align: text-bottom;
        }

        /* Form Icons */
        .form-group .icon {
            color: var(--discord-muted);
        }

        /* Table Scrollbar Styling */
        .wg-table {
            overflow-x: auto;
            max-width: 100%;
        }

        /* Discord-style scrollbar */
        .wg-table::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .wg-table::-webkit-scrollbar-track {
            background-color: var(--discord-sidebar);
            border-radius: 4px;
        }

        .wg-table::-webkit-scrollbar-thumb {
            background-color: #c7ccd1;
            border-radius: 4px;
        }

        .wg-table::-webkit-scrollbar-thumb:hover {
            background-color: #a0a6ad;
        }

        /* Fix for ORDER NO header specifically */
        .wg-table th:first-child {
            white-space: nowrap !important;
            min-width: 120px !important;
            /* Increased width */
            padding-left: 16px;
            padding-right: 16px;
            text-align: center;
        }

        /* Ensure all table headers have proper spacing and don't wrap */
        .wg-table th {
            white-space: nowrap !important;
            padding: 12px 16px;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            background-color: var(--discord-sidebar);
            border-bottom: 1px solid var(--discord-divider);
            overflow: visible !important;
        }

        /* Ensure table doesn't collapse columns */
        .wg-table table {
            table-layout: fixed;
            width: 100%;
            min-width: 800px;
            /* Ensure minimum width for all content */
        }

        /* Ensure horizontal scrolling works properly */
        .table-container {
            max-height: 400px;
            overflow-y: auto;
            border-radius: 0 0 8px 8px;
        }

        .wg-table {
            overflow-x: auto;
            max-width: 100%;
        }

        /* Recent orders section styling */
        .recent-orders {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--discord-divider);
            margin-bottom: 24px;
        }

        .recent-orders .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            background-color: var(--discord-card);
            border-bottom: 1px solid var(--discord-divider);
        }

        .recent-orders .header h3 {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        .recent-orders .table-container {
            max-height: 400px;
            overflow-y: auto;
        }

        /* Table row styling */
        .wg-table tbody tr {
            transition: background-color 0.15s ease;
        }

        .wg-table tbody tr:hover {
            background-color: var(--discord-hover);
        }

        /* View all link */
        .view-all {
            color: var(--discord-accent);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
        }

        .view-all:hover {
            text-decoration: underline;
        }
    </style>

    @stack('styles')
</head>

<body class="body">
    <div id="wrapper">
        <div id="page" class="">
            <div class="layout-wrap">

                <!-- <div id="preload" class="preload-container">
    <div class="preloading">
        <span></span>
    </div>
</div> -->

                <div class="section-menu-left">
                    <div class="box-logo">
                        <a href="{{ route('admin.index') }}
                        " id="site-logo-inner">
                            <img class="" id="logo_header" alt="" src="{{ asset('images/logo/logo.png') }}"
                                data-light="{{ asset('images/logo/logo.png') }}" data-dark="{{ asset('images/logo/logo.png') }}">
                        </a>
                        <div class="button-show-hide">
                            <i class="icon-menu-left"></i>
                        </div>
                    </div>
                    <div class="center">
                        <div class="center-item">
                            <div class="center-heading">Main Home</div>
                            <ul class="menu-list">
                                <li class="menu-item">
                                    <a href="{{ route('admin.index') }}" class="">
                                        <div class="icon"><i class="icon-home"></i></div>
                                        <div class="text">Dashboard</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="center-item">
                            <ul class="menu-list">
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-package"></i></div>
                                        <div class="text">Products</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="{{route('admin.product.add')}}" class="">
                                                <div class="text">Add Product</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{route('admin.products')}}" class="">
                                                <div class="text">Products</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-tag"></i></div>
                                        <div class="text">Brand</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="{{route('admin.brand.add')}}" class="">
                                                <div class="text">New Brand</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{route('admin.brands')}}" class="">
                                                <div class="text">Brands</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-folder"></i></div>
                                        <div class="text">Category</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="{{route('admin.category.add')}}" class="">
                                                <div class="text">New Category</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="{{route('admin.categories')}}" class="">
                                                <div class="text">Categories</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="menu-item has-children">
                                    <a href="javascript:void(0);" class="menu-item-button">
                                        <div class="icon"><i class="icon-shopping-bag"></i></div>
                                        <div class="text">Order</div>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="sub-menu-item">
                                            <a href="{{route('admin.orders')}}" class="">
                                                <div class="text">Orders</div>
                                            </a>
                                        </li>
                                        <li class="sub-menu-item">
                                            <a href="order-tracking.html" class="">
                                                <div class="text">Order tracking</div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="menu-item">
                                    <a href="{{ route('admin.users') }}" class="">
                                        <div class="icon"><i class="icon-users"></i></div>
                                        <div class="text">Users</div>
                                    </a>
                                </li>

                                <li class="menu-item">
                                    <a href="{{ route('admin.settings') }}" class="">
                                        <div class="icon"><i class="icon-settings"></i></div>
                                        <div class="text">Settings</div>
                                    </a>
                                </li>

                                <li class="menu-item">
                                    <form method="POST" action="{{route('logout')}}" id="logout-form">
                                        @csrf
                                        <a href="{{route('logout')}}" class="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <div class="icon"><i class="icon-log-out"></i></div>
                                            <div class="text">Logout</div>
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="section-content-right">

                    <div class="header-dashboard">
                        <div class="wrap">
                            <div class="header-left">
                                <!-- <a href="index-2.html">
                                    <img class="" id="logo_header_mobile" alt="" src="{{ asset('images/logo/logo.png') }}"
                                        data-light="{{ asset('images/logo/logo.png') }}" data-dark="{{ asset('images/logo/logo.png') }}"
                                        data-width="154px" data-height="52px" data-retina="{{ asset('images/logo/logo.png') }}">
                                </a> -->
                                <div class="button-show-hide">
                                    <i class="icon-menu-left"></i>
                                </div>


                                <form class="form-search flex-grow">
                                    <fieldset class="name">
                                        <input type="text" placeholder="Search here..." class="show-search" name="name"
                                            tabindex="2" value="" aria-required="true" required="">
                                    </fieldset>
                                    <div class="button-submit">
                                        <button class="" type="submit"><i class="icon-search"></i></button>
                                    </div>
                                    <!-- <div class="box-content-search" id="box-content-search">
                                        <ul class="mb-24">
                                            <li class="mb-14">
                                                <div class="body-title">Top selling product</div>
                                            </li>
                                            <li class="mb-14">
                                                <div class="divider"></div>
                                            </li>
                                            <li>
                                                <ul>
                                                    <li class="product-item gap14 mb-10">
                                                        <div class="image no-bg">
                                                            <img src="images/products/17.png" alt="">
                                                        </div>
                                                        <div class="flex items-center justify-between gap20 flex-grow">
                                                            <div class="name">
                                                                <a href="product-list.html" class="body-text">Dog Food
                                                                    Rachael Ray Nutrish®</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="mb-10">
                                                        <div class="divider"></div>
                                                    </li>
                                                    <li class="product-item gap14 mb-10">
                                                        <div class="image no-bg">
                                                            <img src="images/products/18.png" alt="">
                                                        </div>
                                                        <div class="flex items-center justify-between gap20 flex-grow">
                                                            <div class="name">
                                                                <a href="product-list.html" class="body-text">Natural
                                                                    Dog Food Healthy Dog Food</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="mb-10">
                                                        <div class="divider"></div>
                                                    </li>
                                                    <li class="product-item gap14">
                                                        <div class="image no-bg">
                                                            <img src="images/products/19.png" alt="">
                                                        </div>
                                                        <div class="flex items-center justify-between gap20 flex-grow">
                                                            <div class="name">
                                                                <a href="product-list.html" class="body-text">Freshpet
                                                                    Healthy Dog Food and Cat</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                        <ul class="">
                                            <li class="mb-14">
                                                <div class="body-title">Order product</div>
                                            </li>
                                            <li class="mb-14">
                                                <div class="divider"></div>
                                            </li>
                                            <li>
                                                <ul>
                                                    <li class="product-item gap14 mb-10">
                                                        <div class="image no-bg">
                                                            <img src="images/products/20.png" alt="">
                                                        </div>
                                                        <div class="flex items-center justify-between gap20 flex-grow">
                                                            <div class="name">
                                                                <a href="product-list.html" class="body-text">Sojos
                                                                    Crunchy Natural Grain Free...</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="mb-10">
                                                        <div class="divider"></div>
                                                    </li>
                                                    <li class="product-item gap14 mb-10">
                                                        <div class="image no-bg">
                                                            <img src="images/products/21.png" alt="">
                                                        </div>
                                                        <div class="flex items-center justify-between gap20 flex-grow">
                                                            <div class="name">
                                                                <a href="product-list.html" class="body-text">Kristin
                                                                    Watson</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="mb-10">
                                                        <div class="divider"></div>
                                                    </li>
                                                    <li class="product-item gap14 mb-10">
                                                        <div class="image no-bg">
                                                            <img src="images/products/22.png" alt="">
                                                        </div>
                                                        <div class="flex items-center justify-between gap20 flex-grow">
                                                            <div class="name">
                                                                <a href="product-list.html" class="body-text">Mega
                                                                    Pumpkin Bone</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="mb-10">
                                                        <div class="divider"></div>
                                                    </li>
                                                    <li class="product-item gap14">
                                                        <div class="image no-bg">
                                                            <img src="images/products/23.png" alt="">
                                                        </div>
                                                        <div class="flex items-center justify-between gap20 flex-grow">
                                                            <div class="name">
                                                                <a href="product-list.html" class="body-text">Mega
                                                                    Pumpkin Bone</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div> -->
                                </form>

                            </div>
                            <div class="header-grid">

                                <div class="popup-wrap message type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-item">
                                                <span class="text-tiny">1</span>
                                                <i class="icon-bell"></i>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton2">
                                            <li>
                                                <h6>Notifications</h6>
                                            </li>
                                            <!-- <li>
                                                <div class="message-item item-1">
                                                    <div class="image">
                                                        <i class="icon-noti-1"></i>
                                                    </div>
                                                    <div>
                                                        <div class="body-title-2">Discount available</div>
                                                        <div class="text-tiny">Morbi sapien massa, ultricies at rhoncus
                                                            at, ullamcorper nec diam</div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="message-item item-2">
                                                    <div class="image">
                                                        <i class="icon-noti-2"></i>
                                                    </div>
                                                    <div>
                                                        <div class="body-title-2">Account has been verified</div>
                                                        <div class="text-tiny">Mauris libero ex, iaculis vitae rhoncus
                                                            et</div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="message-item item-3">
                                                    <div class="image">
                                                        <i class="icon-noti-3"></i>
                                                    </div>
                                                    <div>
                                                        <div class="body-title-2">Order shipped successfully</div>
                                                        <div class="text-tiny">Integer aliquam eros nec sollicitudin
                                                            sollicitudin</div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="message-item item-4">
                                                    <div class="image">
                                                        <i class="icon-noti-4"></i>
                                                    </div>
                                                    <div>
                                                        <div class="body-title-2">Order pending: <span>ID 305830</span>
                                                        </div>
                                                        <div class="text-tiny">Ultricies at rhoncus at ullamcorper</div>
                                                    </div>
                                                </div>
                                            </li> -->
                                            <li><a href="#" class="tf-button w-full">View all</a></li>
                                        </ul>
                                    </div>
                                </div>




                                <div class="popup-wrap user type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-user wg-user">
                                                <span class="image">
                                                    <img src="https://www.pngall.com/wp-content/uploads/5/Profile.png" alt="">
                                                </span>
                                                <span class="flex flex-column">
                                                    <span class="body-title mb-2">{{ Auth::user()->name }}</span>
                                                    <span class="text-tiny">Admin</span>
                                                </span>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton3">
                                            <li>
                                                <a href="#" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-user"></i>
                                                    </div>
                                                    <div class="body-title-2">Account</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-mail"></i>
                                                    </div>
                                                    <div class="body-title-2">Inbox</div>
                                                    <div class="number">27</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-file-text"></i>
                                                    </div>
                                                    <div class="body-title-2">Taskboard</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-headphones"></i>
                                                    </div>
                                                    <div class="body-title-2">Support</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('logout') }}" class="user-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <div class="icon">
                                                        <i class="icon-log-out"></i>
                                                    </div>
                                                    <div class="body-title-2">Log out</div>
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="main-content">
                        @yield('content')

                        <div class="bottom-page">
                            <div class="body-text">Copyright © III-CINS</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>