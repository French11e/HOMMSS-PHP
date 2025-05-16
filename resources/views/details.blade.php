@extends('layouts.app')
@section('content')

<style>
  .filled-heart svg {
    color: red;
  }

  /* Fix navbar overlap */
  main.pt-90 {
    padding-top: 120px !important;
    /* Increase from 90px to prevent overlap */
  }

  @media (max-width: 768px) {
    main.pt-90 {
      padding-top: 100px !important;
      /* Slightly less on mobile */
    }
  }
</style>

@push('scripts')
<script src="{{ asset('js/review.js') }}"></script>
<script>
  // Dynamic adjustment for navbar height
  $(document).ready(function() {
    function adjustMainPadding() {
      const headerHeight = $('.header').outerHeight();
      $('main.pt-90').css('padding-top', (headerHeight + 20) + 'px');
    }

    // Run on page load and window resize
    adjustMainPadding();
    $(window).on('resize', adjustMainPadding);
  });
</script>
@endpush

<main class="pt-90">
  <div class="mb-md-1 pb-md-3"></div>
  <section class="product-single container">
    <div class="row">
      <div class="col-lg-7">
        <div class="product-single__media" data-media-type="vertical-thumbnail">
          <div class="product-single__image">
            <div class="swiper-container">
              <div class="swiper-wrapper">

                <div class="swiper-slide product-single__image-item">
                  <img loading="lazy" class="h-auto" src="{{asset('uploads/products')}}/{{$product->image}}" width="674" height="674" alt="" />
                  <a data-fancybox="gallery" href="{{asset('uploads/products')}}/{{$product->image}}" data-bs-toggle="tooltip" data-bs-placement="left" title="Zoom">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_zoom" />
                    </svg>
                  </a>
                </div>

                @foreach (explode(',', $product->images) as $gimg)
                <div class="swiper-slide product-single__image-item">
                  <img loading="lazy" class="h-auto" src="{{asset('uploads/products')}}/{{$gimg}}" width="674" height="674" alt="" />
                  <a data-fancybox="gallery" href="{{asset('uploads/products')}}/{{$gimg}}" data-bs-toggle="tooltip" data-bs-placement="left" title="Zoom">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_zoom" />
                    </svg>
                  </a>
                </div>
                @endforeach
              </div>
              <div class="swiper-button-prev"><svg width="7" height="11" viewBox="0 0 7 11"
                  xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_prev_sm" />
                </svg></div>
              <div class="swiper-button-next"><svg width="7" height="11" viewBox="0 0 7 11"
                  xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_next_sm" />
                </svg></div>
            </div>
          </div>
          <div class="product-single__thumbnail">
            <div class="swiper-container">
              <div class="swiper-wrapper">
                <div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto" src="{{asset('uploads/products/thumbnails')}}/{{$product->image}}" width="104" height="104" alt="" /></div>
                @foreach (explode(',', $product->images) as $gimg)
                <div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto" src="{{asset('uploads/products/thumbnails')}}/{{$gimg}}" width="104" height="104" alt="" /></div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="d-flex justify-content-between mb-4 pb-md-2">
          <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
            <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">The Shop</a>
          </div><!-- /.breadcrumb -->

          <div
            class="product-single__prev-next d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
            <a href="#" class="text-uppercase fw-medium"><svg width="10" height="10" viewBox="0 0 25 25"
                xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_prev_md" />
              </svg><span class="menu-link menu-link_us-s">Prev</span></a>
            <a href="#" class="text-uppercase fw-medium"><span class="menu-link menu-link_us-s">Next</span><svg
                width="10" height="10" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_next_md" />
              </svg></a>
          </div><!-- /.shop-acs -->
        </div>
        <h1 class="product-single__name">{{$product->name}}</h1>
        <div class="product-single__rating">
          <div class="reviews-group d-flex">
            @php
            $avgRating = $product->getAverageRatingAttribute();
            $fullStars = floor($avgRating);
            $halfStar = $avgRating - $fullStars > 0.4;
            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
            @endphp

            @for($i = 1; $i <= $fullStars; $i++)
              <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg" style="fill: #ffc107;">
              <use href="#icon_star" />
              </svg>
              @endfor

              @if($halfStar)
              <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg" style="fill: url(#half-star-gradient);">
                <defs>
                  <linearGradient id="half-star-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="50%" style="stop-color:#ffc107" />
                    <stop offset="50%" style="stop-color:#ccc" />
                  </linearGradient>
                </defs>
                <use href="#icon_star" />
              </svg>
              @endif

              @for($i = 1; $i <= $emptyStars; $i++)
                <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_star" />
                </svg>
                @endfor
          </div>
          <span class="reviews-note text-lowercase text-secondary ms-1">
            {{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }}
          </span>
        </div>
        <div class="product-single__price">
          <span class="current-price">
            @if($product->sale_price)
            <s>{{$product->regular_price}} </s> ₱{{$product->sale_price}}
            @else
            ₱{{$product->regular_price}}
            @endif
          </span>
        </div>
        <div class="product-single__short-desc">
          <p>{{$product->short_description}}</p>
        </div>
        @if(Cart::instance('cart')->content()->where('id',$product->id)->count() > 0)
        <a href="{{route('cart.index')}}" class="btn btn-warning mb-3">Go to Cart</a>
        @else
        <form name="addtocart-form" method="post" action="{{ route('cart.add', ['product_slug' => $product->slug]) }}">
          @csrf
          <div class="product-single__addtocart">
            <div class="qty-control position-relative">
              <input type="number" name="quantity" value="1" min="1" class="qty-control__number text-center">
              <div class="qty-control__reduce">-</div>
              <div class="qty-control__increase">+</div>
            </div><!-- .qty-control -->
            <input type="hidden" name="id" value="{{$product->id}}" />
            <input type="hidden" name="name" value="{{$product->name}}" />
            <input type="hidden" name="price" value="{{$product->sale_price == '' ?  $product->regular_price : $product->sale_price}}" />
            <button type="submit" class="btn btn-primary btn-addtocart" data-aside="cartDrawer">Add to Cart</button>
          </div>
        </form>
        @endif
        <div class="product-single__addtolinks">
          @if(Cart::instance('wishlist')->content()->where('id', $product->id)->count() > 0)
          <form method="POST" action="{{ route('wishlist.item.remove', ['rowId' => Cart::instance('wishlist')->content()->where('id', $product->id)->first()->rowId]) }}" id="frm-remove-item">
            @csrf
            @method('DELETE')
            <a href="javascript:void(0)" class="menu-link menu-link_us-s add-to-wishlist filled-heart" onclick="document.getElementById('frm-remove-item').submit();">
              <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_heart" />
              </svg>
              <span>Remove from Wishlist</span>
            </a>
          </form>
          @else
          <form method="POST" action="{{ route('wishlist.add') }}" id="wishlist-form">
            @csrf
            <input type="hidden" name="id" value="{{ $product->id }}" />
            <input type="hidden" name="name" value="{{ $product->name }}" />
            <input type="hidden" name="price" value="{{ $product->sale_price ?: $product->regular_price }}" />
            <input type="hidden" name="quantity" value="1" />
            <a href="javascript:void(0)" class="menu-link menu-link_us-s add-to-wishlist" onclick="document.getElementById('wishlist-form').submit();">
              <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_heart" />
              </svg>
              <span>Add to Wishlist</span>
            </a>
          </form>
          @endif
        </div>



        <share-button class="share-button">
          <button class="menu-link menu-link_us-s to-share border-0 bg-transparent d-flex align-items-center">
            <svg width="16" height="19" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg">
              <use href="#icon_sharing" />
            </svg>
            <span>Share</span>
          </button>
          <details id="Details-share-template__main" class="m-1 xl:m-1.5" hidden="">
            <summary class="btn-solid m-1 xl:m-1.5 pt-3.5 pb-3 px-5">+</summary>
            <div id="Article-share-template__main"
              class="share-button__fallback flex items-center absolute top-full left-0 w-full px-2 py-4 bg-container shadow-theme border-t z-10">
              <div class="field grow mr-4">
                <label class="field__label sr-only" for="url">Link</label>
                <input type="text" class="field__input w-full" id="url"
                  value="https://uomo-crystal.myshopify.com/blogs/news/go-to-wellness-tips-for-mental-health"
                  placeholder="Link" onclick="this.select();" readonly="">
              </div>
              <button class="share-button__copy no-js-hidden">
                <svg class="icon icon-clipboard inline-block mr-1" width="11" height="13" fill="none"
                  xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" viewBox="0 0 11 13">
                  <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M2 1a1 1 0 011-1h7a1 1 0 011 1v9a1 1 0 01-1 1V1H2zM1 2a1 1 0 00-1 1v9a1 1 0 001 1h7a1 1 0 001-1V3a1 1 0 00-1-1H1zm0 10V3h7v9H1z"
                    fill="currentColor"></path>
                </svg>
                <span class="sr-only">Copy link</span>
              </button>
            </div>
          </details>
        </share-button>
        <script src="js/details-disclosure.html" defer="defer"></script>
        <script src="js/share.html" defer="defer"></script>
      </div>
      <div class="product-single__meta-info">
        <div class="meta-item">
          <label>SKU:</label>
          <span>{{$product->SKU}}</span>
        </div>
        <div class="meta-item">
          <label>Categories:</label>
          <span>{{$product->category->name}}</span>
        </div>
        <div class="meta-item">
          <label>Tags:</label>
          <span>NA</span>
        </div>
      </div>
    </div>
    </div>
    <div class="product-single__details-tab">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link_underscore active" id="tab-description-tab" data-bs-toggle="tab"
            href="#tab-description" role="tab" aria-controls="tab-description" aria-selected="true">Description</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link nav-link_underscore" id="tab-reviews-tab" data-bs-toggle="tab" href="#tab-reviews"
            role="tab" aria-controls="tab-reviews" aria-selected="false">Reviews</a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-description" role="tabpanel"
          aria-labelledby="tab-description-tab">
          <div class="product-single__description">
            {{$product->description}}
          </div>
        </div>
        <div class="tab-pane fade" id="tab-reviews" role="tabpanel" aria-labelledby="tab-reviews-tab">
          <h2 class="product-single__reviews-title">Reviews</h2>

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

          <div class="product-single__reviews-list">
            @if($product->reviews->count() > 0)
            @foreach($product->reviews as $review)
            <div class="product-single__reviews-item">
              <div class="customer-avatar">
                <img loading="lazy" src="{{ asset('assets/images/avatar.jpg') }}" alt="" />
              </div>
              <div class="customer-review">
                <div class="customer-name">
                  <h6>{{ $review->user->name }}</h6>
                  <div class="reviews-group d-flex">
                    @for($i = 1; $i <= 5; $i++)
                      <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg"
                      @if($i <=$review->rating) style="fill: #ffc107;" @endif>
                      <use href="#icon_star" />
                      </svg>
                      @endfor
                  </div>
                </div>
                <div class="review-date">{{ $review->created_at->format('F d, Y') }}</div>
                <div class="review-text">
                  <p>{{ $review->comment }}</p>
                </div>
              </div>
            </div>
            @endforeach
            @else
            <p>No reviews yet. Be the first to review this product!</p>
            @endif
          </div>

          <div class="product-single__review-form">
            @if(Auth::check())
            @if(!$product->reviews->where('user_id', Auth::id())->count())
            <form name="customer-review-form" method="POST" action="{{ route('product.review.store', $product->id) }}">
              @csrf
              <h5>Write a review for "{{ $product->name }}"</h5>
              <p>Your email address will not be published. Required fields are marked *</p>
              <div class="select-star-rating">
                <label>Your rating *</label>
                <span class="star-rating">
                  @for($i = 1; $i <= 5; $i++)
                    <svg class="star-rating__star-icon rating-star" data-rating="{{ $i }}" width="12" height="12" fill="#ccc" viewBox="0 0 12 12"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.988 5.21c-.052-.275-.27-.488-.545-.534l-3.604-.6L6.63.455C6.542.184 6.287 0 6 0s-.542.184-.632.456L4.16 4.076l-3.603.6c-.275.046-.493.26-.545.533-.052.273.072.55.312.694L3.2 7.63l-.705 3.71c-.052.274.07.553.308.7.115.07.247.106.38.106.148 0 .294-.046.417-.134L6 9.52l2.4 1.49c.123.09.27.135.417.135.134 0 .266-.036.38-.107.238-.147.36-.426.308-.7l-.704-3.71 2.876-1.725c.24-.144.364-.42.312-.694z" />
                    </svg>
                    @endfor
                    <input type="hidden" id="form-input-rating" name="rating" value="" required />
                </span>
                @error('rating')
                <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <div class="mb-4">
                <textarea id="form-input-review" name="comment" class="form-control form-control_gray" placeholder="Your Review"
                  cols="30" rows="8" required></textarea>
                @error('comment')
                <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
              <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
            @else
            <div class="alert alert-info">
              You have already reviewed this product. Thank you for your feedback!
            </div>
            @endif
            @else
            <div class="alert alert-info">
              Please <a href="{{ route('login') }}">login</a> to leave a review.
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="products-carousel container">
    <h2 class="h3 text-uppercase mb-4 pb-xl-2 mb-xl-4">Related <strong>Products</strong></h2>

    <div id="related_products" class="position-relative">
      <div class="swiper-container js-swiper-slider" data-settings='{
            "autoplay": false,
            "slidesPerView": 4,
            "slidesPerGroup": 4,
            "effect": "none",
            "loop": true,
            "pagination": {
              "el": "#related_products .products-pagination",
              "type": "bullets",
              "clickable": true
            },
            "navigation": {
              "nextEl": "#related_products .products-carousel__next",
              "prevEl": "#related_products .products-carousel__prev"
            },
            "breakpoints": {
              "320": {
                "slidesPerView": 2,
                "slidesPerGroup": 2,
                "spaceBetween": 14
              },
              "768": {
                "slidesPerView": 3,
                "slidesPerGroup": 3,
                "spaceBetween": 24
              },
              "992": {
                "slidesPerView": 4,
                "slidesPerGroup": 4,
                "spaceBetween": 30
              }
            }
          }'>
        <div class="swiper-wrapper">
          @foreach ($rproducts as $rproduct)
          <div class="swiper-slide product-card">
            <div class="pc__img-wrapper">
              <a href="{{ route('shop.product.details', ['product_slug' => $rproduct->slug]) }}">
                <img loading="lazy" src="{{ asset('uploads/products/' . $rproduct->image) }}" width="330" height="400" alt="{{ $rproduct->name }}" class="pc__img">

                @foreach (explode(',', $rproduct->images) as $gimg)
                <img loading="lazy" src="{{ asset('uploads/products/' . $gimg) }}" width="330" height="400" alt="{{ $rproduct->name }}" class="pc__img pc__img-second">
                @endforeach
              </a>

              @if(Cart::instance('cart')->content()->where('id',$rproduct->id)->count() > 0)
              <a href="{{route('cart.index')}}" class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium btn-warning mb-3">Go to Cart</a>
              @else
              <form name="addtocart-form" method="post" action="{{route('cart.add', ['product_slug' => $product->slug])}}">
                @csrf
                <input type="hidden" name="id" value="{{$rproduct->id}}" />
                <input type="hidden" name="quantity" value="1" />
                <input type="hidden" name="name" value="{{$rproduct->name}}" />
                <input type="hidden" name="price" value="{{$rproduct->sale_price == '' ?  $rproduct->regular_price : $rproduct->sale_price}}" />
                <button type="submit" class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium" data-aside="cartDrawer" title="Add To Cart">Add To Cart</button>
              </form>
              @endif
            </div>
          </div>
          @endforeach


          <button class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist"
            title="Add To Wishlist">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <use href="#icon_heart" />
            </svg>
          </button>
        </div>
      </div>



    </div><!-- /.swiper-wrapper -->
    </div><!-- /.swiper-container js-swiper-slider -->

    <div class="products-carousel__prev position-absolute top-50 d-flex align-items-center justify-content-center">
      <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
        <use href="#icon_prev_md" />
      </svg>
    </div><!-- /.products-carousel__prev -->
    <div class="products-carousel__next position-absolute top-50 d-flex align-items-center justify-content-center">
      <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
        <use href="#icon_next_md" />
      </svg>
    </div><!-- /.products-carousel__next -->

    <div class="products-pagination mt-4 mb-5 d-flex align-items-center justify-content-center"></div>
    <!-- /.products-pagination -->
    </div><!-- /.position-relative -->

  </section><!-- /.products-carousel container -->
</main>
@endsection