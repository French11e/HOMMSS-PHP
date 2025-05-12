@extends('layouts.app')
@section('content')
<style>
    .brand-list li,
    .category-list li {
        line-height: 40px
    }

    .brand-list li .chk-brand,
    .category-list li .chk-category {
        width: 1rem;
        height: 1rem;
        color: #e4e4e4;
        border: 0.125rem solid currentColor;
        border-radius: 0;
        margin-right: 0.75rem;
    }

    .filled-heart {
        color: red
    }

    /* Remove the pt-90 class as we're handling this in the main layout */
    .shop-main {
        padding-top: 1rem;
        display: flex;
        gap: 30px;
        /* Add space between sidebar and content */
    }

    .shop-sidebar {
        position: sticky;
        top: 90px;
        /* Adjust based on your header height */
        height: calc(100vh - 90px);
        overflow-y: auto;
        background-color: #fff;
        padding: 20px;
        border-right: 1px solid #eee;
        width: 280px;
        /* Fixed width */
        flex-shrink: 0;
        /* Prevent shrinking */
        z-index: 1020;
    }

    .shop-list {
        flex: 1;
        /* Take remaining space */
        min-width: 0;
        /* Fix for flexbox overflow issues */
    }

    /* Ensure clear separation on mobile */
    @media (max-width: 991px) {
        .shop-main {
            display: block;
            /* Stack on mobile */
        }

        .shop-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 1050;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            border-right: none;
        }

        .shop-sidebar.aside_visible {
            transform: translateX(0);
        }

        .shop-list {
            width: 100%;
            padding-left: 0;
        }
    }

    /* Scrollbar styling */
    .shop-sidebar::-webkit-scrollbar {
        width: 5px;
    }

    .shop-sidebar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .shop-sidebar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 5px;
    }
</style>

<main>
    <section class="shop-main container">
        <!-- Sidebar with filters -->
        <div class="shop-sidebar bg-body" id="shopFilter">
            <div class="aside-header d-flex d-lg-none align-items-center">
                <h3 class="text-uppercase fs-6 mb-0">Filter By</h3>
                <button class="btn-close-lg js-close-aside btn-close-aside ms-auto"></button>
            </div>

            <div class="pt-4 pt-lg-0">
                <!-- Categories section -->
                <div class="accordion" id="categories-list">
                    <div class="accordion-item mb-4 pb-3">
                        <h5 class="accordion-header" id="accordion-heading-1">
                            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
                                data-bs-target="#accordion-filter-1" aria-expanded="true" aria-controls="accordion-filter-1">
                                Product Categories
                                <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                                    <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                                        <path
                                            d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                                    </g>
                                </svg>
                            </button>
                        </h5>
                        <div id="accordion-filter-1" class="accordion-collapse collapse show border-0" aria-labelledby="accordion-heading-1" data-bs-parent="#categories-list">
                            <div class="accordion-body px-0 pb-0 pt-3 category-list">
                                <ul class="list list-inline mb-0">
                                    @php
                                    // Replace any backslashes in category names or descriptions
                                    $categories = $categories->map(function($category) {
                                    $category->name = str_replace('\\', '/', $category->name);
                                    $category->description = str_replace('\\', '/', $category->description);
                                    return $category;
                                    });
                                    @endphp
                                    @foreach($categories as $category)
                                    <li class="list-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <input type="checkbox" class="chk-category" name="categories[]" value="{{ $category->id }}"
                                                @if(in_array($category->id,explode(',',$f_categories))) checked="checked" @endif
                                            />
                                            {{ $category->name }}
                                        </span>
                                        <span class="text-right">{{ $category->products->count() }}</span>
                                    </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion" id="size-filters">
                <div class="accordion-item mb-4 pb-3">
                    <h5 class="accordion-header" id="accordion-heading-size">
                        <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
                            data-bs-target="#accordion-filter-size" aria-expanded="true" aria-controls="accordion-filter-size">
                            Sizes
                            <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                                <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                                    <path
                                        d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                                </g>
                            </svg>
                        </button>
                    </h5>
                    <div id="accordion-filter-size" class="accordion-collapse collapse show border-0"
                        aria-labelledby="accordion-heading-size" data-bs-parent="#size-filters">
                        <div class="accordion-body px-0 pb-0">
                            <div class="d-flex flex-wrap">
                                <a href="#" class="swatch-size btn btn-sm btn-outline-light mb-3 me-3 js-filter">12x12</a>
                                <a href="#" class="swatch-size btn btn-sm btn-outline-light mb-3 me-3 js-filter">16x16</a>
                                <a href="#" class="swatch-size btn btn-sm btn-outline-light mb-3 me-3 js-filter">24x24</a>
                                <a href="#" class="swatch-size btn btn-sm btn-outline-light mb-3 me-3 js-filter">12x24</a>
                                <a href="#" class="swatch-size btn btn-sm btn-outline-light mb-3 me-3 js-filter">48x48</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="accordion" id="brand-filters">
                <div class="accordion-item mb-4 pb-3">
                    <h5 class="accordion-header" id="accordion-heading-brand">
                        <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
                            data-bs-target="#accordion-filter-brand" aria-expanded="true" aria-controls="accordion-filter-brand">
                            Brands
                            <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                                <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                                    <path
                                        d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                                </g>
                            </svg>
                        </button>
                    </h5>
                    <div id="accordion-filter-brand" class="accordion-collapse collapse show border-0"
                        aria-labelledby="accordion-heading-brand" data-bs-parent="#brand-filters">
                        <div class="search-field multi-select accordion-body px-0 pb-0">
                            <ul class="list list-inline mb-0 brand-list">
                                @foreach($brands as $brand)
                                <li class="list-item">
                                    <span class="menu-link py-1">
                                        <input type="checkbox" name="brands" value="{{ $brand->id }}" class="chk-brand"
                                            @if(in_array($brand->id,explode(',',$f_brands))) checked="checked" checked @endif>
                                        {{ $brand->name }}
                                    </span>
                                    <span class="text-right float-end">
                                        {{ $brand->products->count() }}
                                    </span>
                                </li>
                                @endforeach


                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion" id="price-filters">
                <div class="accordion-item mb-4">
                    <h5 class="accordion-header mb-2" id="accordion-heading-price">
                        <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
                            data-bs-target="#accordion-filter-price" aria-expanded="true" aria-controls="accordion-filter-price">
                            Price Range
                            <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                                <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                                    <path d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                                </g>
                            </svg>
                        </button>
                    </h5>
                    <div id="accordion-filter-price" class="accordion-collapse collapse show border-0"
                        aria-labelledby="accordion-heading-price" data-bs-parent="#price-filters">
                        <div class="price-range d-flex align-items-center">
                            <select class="form-select form-select-sm me-2" id="price-range-select">
                                <option value="">All Prices</option>
                                <option value="0-500" {{($min_price == 0 && $max_price == 500) ? 'selected' : ''}}>Under ₱500</option>
                                <option value="500-1000" {{($min_price == 500 && $max_price == 1000) ? 'selected' : ''}}>₱500 - ₱1,000</option>
                                <option value="1000-2000" {{($min_price == 1000 && $max_price == 2000) ? 'selected' : ''}}>₱1,000 - ₱2,000</option>
                                <option value="2000-5000" {{($min_price == 2000 && $max_price == 5000) ? 'selected' : ''}}>₱2,000 - ₱5,000</option>
                                <option value="5000-10000" {{($min_price == 5000 && $max_price == 10000) ? 'selected' : ''}}>₱5,000 - ₱10,000</option>
                                <option value="10000-999999" {{($min_price == 10000 && $max_price == 999999) ? 'selected' : ''}}>Over ₱10,000</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="shop-list">
            <div class="swiper-container js-swiper-slider slideshow slideshow_small slideshow_split" style="height: 200px;" data-settings='{
                        "autoplay": {
                          "delay": 5000
                        },
                        "slidesPerView": 1,
                        "effect": "fade",
                        "loop": true,
                        "pagination": {
                          "el": ".slideshow-pagination",
                          "type": "bullets",
                          "clickable": true
                        }
                      }'>
                <div class="swiper-wrapper">
                    <!-- Additional slide for kitchen sinks -->
                    <div class="swiper-slide">
                        <div class="slide-split h-100 d-block d-md-flex overflow-hidden">
                            <div class="slide-split_text position-relative d-flex align-items-center"
                                style="background-color: #F49BAB;">
                                <div class="slideshow-text container p-2 p-xl-3" style="position: absolute; top: 10px; left: 10px; text-align: left; max-width: 75%;">
                                    <h2 class="text-uppercase section-title fw-normal mb-1 animate animate_fade animate_btt animate_delay-2" style="font-size: 1.2rem; line-height: 1.3;">
                                        Premium <strong>SANITARY WARE</strong></h2>
                                    <p class="mb-0 animate animate_fade animate_btt animate_delay-5" style="font-size: 0.9rem; line-height: 1.2;">Elegant fixtures for modern bathrooms.</p>
                                </div>
                            </div>
                            <div class="slide-split_media position-relative">
                                <div class="slideshow-bg" style="background-color: #9B7EBD;">
                                    <img loading="lazy" src="{{ asset('images\products\h001.jpg') }}" width="630" height="450"
                                        alt="Premium kitchen sinks collection" class="slideshow-bg__img object-fit-cover" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="slide-split h-100 d-block d-md-flex overflow-hidden">
                            <div class="slide-split_text position-relative d-flex align-items-center"
                                style="background-color: #E9F5BE;">
                                <div class="slideshow-text container p-2 p-xl-3" style="position: absolute; top: 10px; left: 10px; text-align: left; max-width: 75%;">
                                    <h2 class="text-uppercase section-title fw-normal mb-1 animate animate_fade animate_btt animate_delay-2" style="font-size: 1.2rem; line-height: 1.3;">
                                        Designer <strong>KITCHEN SINKS</strong></h2>
                                    <p class="mb-0 animate animate_fade animate_btt animate_delay-5" style="font-size: 0.9rem; line-height: 1.2;">Durable & stylish centerpieces for your kitchen.</p>
                                </div>
                            </div>
                            <div class="slide-split_media position-relative">
                                <div class="slideshow-bg" style="background-color: #e8f4f8;">
                                    <img loading="lazy" src="{{ asset('images\products\sink.png') }}" width="630" height="450"
                                        alt="Premium kitchen sinks collection" class="slideshow-bg__img object-fit-cover" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional slide for tiles -->
                    <div class="swiper-slide">
                        <div class="slide-split h-100 d-block d-md-flex overflow-hidden">
                            <div class="slide-split_text position-relative d-flex align-items-center"
                                style="background-color: #FF8282;">
                                <div class="slideshow-text container p-2 p-xl-3" style="position: absolute; top: 10px; left: 10px; text-align: left; max-width: 75%;">
                                    <h2 class="text-uppercase section-title fw-normal mb-1 animate animate_fade animate_btt animate_delay-2" style="font-size: 1.2rem; line-height: 1.3;">
                                        Luxury <strong>WALL TILES</strong></h2>
                                    <p class="mb-0 animate animate_fade animate_btt animate_delay-5" style="font-size: 0.9rem; line-height: 1.2;">Transform spaces with our exquisite designs.</p>
                                </div>
                            </div>
                            <div class="slide-split_media position-relative">
                                <div class="slideshow-bg" style="background-color: #f5f0e6;">
                                    <img loading="lazy" src="{{ asset('images\products\walltiles.png') }}" width="630" height="450"
                                        alt="Premium tile collections" class="slideshow-bg__img object-fit-cover" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="slideshow-pagination d-flex align-items-center position-absolute bottom-0 end-0 mb-2 me-2"></div>
                </div>
            </div>

            <div class="mb-3 pb-2 pb-xl-3"></div>

            <div class="d-flex justify-content-between mb-4 pb-md-2">
                <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
                    <a href="{{route('home.index')}}" class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
                    <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
                    <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">The Shop</a>
                </div>

                <div class="shop-acs d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
                    <select class="shop-acs__select form-select w-auto border-0 py-0 order-1 order-md-0" aria-label="Page Size" id="pagesize" name="pagesize" style="margin-right:20px">
                        <option value="12" {{ $size==12 ? 'selected':''}}>Show</option>
                        <option value="24" {{ $size==24 ? 'selected':''}}>24</option>
                        <option value="48" {{ $size==48 ? 'selected':''}}>48</option>
                        <option value="102" {{ $size==102 ? 'selected':''}}>102</option>
                    </select>

                    <select class="shop-acs__select form-select w-auto border-0 py-0 order-1 order-md-0" aria-label="Sort Items" name="orderby" id="orderby">
                        <option value="-1" {{ $order == -1 ? 'selected' : '' }}>Default</option>
                        <option value="1" {{ $order == 1 ? 'selected' : '' }}>Price, New to Old</option>
                        <option value="2" {{ $order == 2 ? 'selected' : '' }}>Date, Old to New</option>
                        <option value="3" {{ $order == 3 ? 'selected' : '' }}>Price, Low to High</option>
                        <option value="4" {{ $order == 4 ? 'selected' : '' }}>Price, High to Low</option>
                    </select>

                    <div class="shop-asc__seprator mx-3 bg-light d-none d-md-block order-md-0"></div>

                    <div class="col-size align-items-center order-1 d-none d-lg-flex">
                        <span class="text-uppercase fw-medium me-2">View</span>
                        <button class="btn-link fw-medium me-2 js-cols-size" data-target="products-grid" data-cols="2">2</button>
                        <button class="btn-link fw-medium me-2 js-cols-size" data-target="products-grid" data-cols="3">3</button>
                        <button class="btn-link fw-medium js-cols-size" data-target="products-grid" data-cols="4">4</button>
                    </div>

                    <div class="shop-filter d-flex align-items-center order-0 order-md-3 d-lg-none">
                        <button class="btn-link btn-link_f d-flex align-items-center ps-0 js-open-aside" data-aside="shopFilter">
                            <svg class="d-inline-block align-middle me-2" width="14" height="10" viewBox="0 0 14 10" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_filter" />
                            </svg>
                            <span class="text-uppercase fw-medium d-inline-block align-middle">Filter</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="products-grid row row-cols-2 row-cols-md-3" id="products-grid">
                @if($products->count() > 0)
                @foreach($products as $product)
                <div class="product-card-wrapper">
                    <div class="product-card mb-3 mb-md-4 mb-xxl-5">
                        <div class="pc__img-wrapper">
                            <div class="swiper-container background-img js-swiper-slider" data-settings='{"resizeObserver": true}'>
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <a href="{{route('shop.product.details',['product_slug'=>$product->slug])}}"
                                            aria-label="View details of {{$product->name}}">
                                            <img loading="lazy"
                                                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 330 400'%3E%3C/svg%3E"
                                                data-src="{{asset('uploads/products')}}/{{$product->image}}"
                                                width="330" height="400" alt="{{$product->name}}" class="pc__img lazy">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pc__info position-relative">
                            <h6 class="pc__title">
                                <a href="{{route('shop.product.details',['product_slug'=>$product->slug])}}">{{$product->name}}</a>
                            </h6>

                            <!-- Add product rating -->
                            <div class="product-rating d-flex align-items-center mb-2">
                                @php
                                $avgRating = $product->getAverageRatingAttribute();
                                $fullStars = floor($avgRating);
                                $halfStar = $avgRating - $fullStars > 0.4;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                @endphp

                                <div class="reviews-group d-flex">
                                    @for($i = 1; $i <= $fullStars; $i++)
                                        <svg class="review-star" width="9" height="9" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg" style="fill: #ffc107;">
                                        <use href="#icon_star" />
                                        </svg>
                                        @endfor

                                        @if($halfStar)
                                        <svg class="review-star" width="9" height="9" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg" style="fill: url(#half-star-gradient-{{$product->id}});">
                                            <defs>
                                                <linearGradient id="half-star-gradient-{{$product->id}}" x1="0%" y1="0%" x2="100%" y2="0%">
                                                    <stop offset="50%" style="stop-color:#ffc107" />
                                                    <stop offset="50%" style="stop-color:#ccc" />
                                                </linearGradient>
                                            </defs>
                                            <use href="#icon_star" />
                                        </svg>
                                        @endif

                                        @for($i = 1; $i <= $emptyStars; $i++)
                                            <svg class="review-star" width="9" height="9" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_star" />
                                            </svg>
                                            @endfor
                                </div>

                                @if($product->reviews->count() > 0)
                                <span class="reviews-note text-lowercase text-secondary ms-1">
                                    ({{ $product->reviews->count() }})
                                </span>
                                @endif
                            </div>

                            <div class="product__price d-flex">
                                <span class="money price">
                                    @if($product->sale_price)
                                    <s>₱{{$product->regular_price}}</s> ₱{{$product->sale_price}}
                                    @else
                                    ₱{{$product->regular_price}}
                                    @endif
                                </span>
                            </div>
                            <button type="button"
                                class="pc__btn-wl position-absolute bg-transparent border-0 p-0"
                                aria-label="Add {{$product->name}} to wishlist">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_heart"></use>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="col-12">
                    <p class="text-center">No products found</p>
                </div>
                @endif
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination ">
                {{$products->withQueryString()->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </section>
</main>

<form id="frmfilter" method="GET" action="{{ route('shop.index') }}">
    <input type="hidden" name="page" value="1"> <!-- Always reset to page 1 on filter change -->
    <input type="hidden" id="size" name="size" value="{{ $size }}" />
    <input type="hidden" id="orderby" name="orderby" value="{{ $order }}" /> <!-- Changed name to match controller -->
    <input type="hidden" name="brands" id="hdnBrands" value="{{ $f_brands }}" />
    <input type="hidden" name="categories" id="hdnCategories" value="{{ $f_categories }}" />
    <input type="hidden" name="min" id="hdnMinPrice" value="{{ $min_price }}" />
    <input type="hidden" name="max" id="hdnMaxPrice" value="{{ $max_price }}" />
</form>

@endsection

@push('scripts')
<script>
    $(function() {
        // Mobile sidebar toggle
        $('.js-open-aside[data-aside="shopFilter"]').on('click', function(e) {
            e.preventDefault();
            $('.shop-sidebar').addClass('aside_visible');
            $('.page-overlay').addClass('page-overlay_visible');
        });

        $('.js-close-aside, .page-overlay').on('click', function() {
            $('.shop-sidebar').removeClass('aside_visible');
            $('.page-overlay').removeClass('page-overlay_visible');
        });

        // Ensure sidebar stays in place when scrolling
        $(window).on('scroll', function() {
            // This prevents any other scripts from changing the sidebar position
            $('.shop-sidebar').css('position', 'sticky');
        });

        // Filter handlers
        // ...
    });
</script>
@endpush