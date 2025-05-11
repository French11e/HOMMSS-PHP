@extends('layouts.app')
@section('content')
<main>

    <section class="swiper-container js-swiper-slider swiper-number-pagination slideshow" data-settings='{
        "autoplay": {
          "delay": 5000
        },
        "slidesPerView": 1,
        "effect": "fade",
        "loop": true
      }'>
        <div class="swiper-container home-slider">
            <div class="swiper-wrapper">
                <!-- Slide 1 - Tiles -->
                <div class="swiper-slide">
                    <div class="overflow-hidden position-relative h-100 bg-light">
                        <img loading="lazy" src="{{ asset('assets/images/home/hommss/wall-tiles.png') }}"
                            alt="Premium Tiles Collection"
                            class="w-100 h-100 object-fit-cover"
                            style="object-position: center 30%;">

                        <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
                            <h6 class="text_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
                                New Collection</h6>
                            <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">Luxury</h2>
                            <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-5">Wall Tiles</h2>
                            <a href="{{ route('shop.index') }}"
                                class="btn btn-primary btn-lg px-5 py-3 fw-medium animate animate_fade animate_btt animate_delay-7">Shop Now</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 - Kitchen Sinks -->
                <div class="swiper-slide">
                    <div class="overflow-hidden position-relative h-100 bg-light">
                        <img loading="lazy" src="{{ asset('assets/images/home/hommss/kitchen-sink.png') }}" alt="Modern Kitchen Sinks" class="w-100 h-100 object-fit-cover">

                        <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
                            <h6 class="text_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
                                Premium Quality</h6>
                            <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">Modern</h2>
                            <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-5">Kitchen Sinks</h2>
                            <a href="{{ route('shop.index') }}"
                                class="btn btn-primary btn-lg px-5 py-3 fw-medium animate animate_fade animate_btt animate_delay-7">Shop Now</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 - Sanitary Ware -->
                <div class="swiper-slide">
                    <div class="overflow-hidden position-relative h-100 bg-light">
                        <img loading="lazy" src="{{ asset('assets/images/home/hommss/sanitary-ware.png') }}"
                            alt="Elegant Sanitary Ware"
                            class="w-100 h-100 object-fit-cover">
                        <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
                            <h6 class="text_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
                                Modern Design</h6>
                            <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">Elegant</h2>
                            <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-5">Sanitary Ware</h2>
                            <a href="{{ route('shop.index') }}"
                                class="btn btn-primary btn-lg px-5 py-3 fw-medium animate animate_fade animate_btt animate_delay-7">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>

            <!-- Pagination dots -->
            <div class="swiper-pagination"></div>
        </div>

        <div class="container">
            <div
                class="slideshow-pagination slideshow-number-pagination d-flex align-items-center position-absolute bottom-0 mb-5">
            </div>
        </div>
    </section>
    <!-- <div class="container mw-1620 bg-white border-radius-10">
        <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>
        <section class="category-carousel container">
            <h2 class="section-title text-center mb-3 pb-xl-2 mb-xl-4">You Might Like</h2>

            <div class="position-relative">
                <div class="swiper-container js-swiper-slider" data-settings='{
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": 8,
              "slidesPerGroup": 1,
              "effect": "none",
              "loop": true,
              "navigation": {
                "nextEl": ".products-carousel__next-1",
                "prevEl": ".products-carousel__prev-1"
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 2,
                  "slidesPerGroup": 2,
                  "spaceBetween": 15
                },
                "768": {
                  "slidesPerView": 4,
                  "slidesPerGroup": 4,
                  "spaceBetween": 30
                },
                "992": {
                  "slidesPerView": 6,
                  "slidesPerGroup": 1,
                  "spaceBetween": 45,
                  "pagination": false
                },
                "1200": {
                  "slidesPerView": 8,
                  "slidesPerGroup": 1,
                  "spaceBetween": 60,
                  "pagination": false
                }
              }
            }'>
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img loading="lazy" class="w-100 h-auto mb-3" src="{{ asset('assets/images/home/demo3/category_1.png') }}" width="124"
                                height="124" alt="" />
                            <div class="text-center">
                                <a href="#" class="menu-link fw-medium">Women<br />Tops</a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img loading="lazy" class="w-100 h-auto mb-3" src="{{ asset('assets/images/home/demo3/category_2.png') }}" width="124"
                                height="124" alt="" />
                            <div class="text-center">
                                <a href="#" class="menu-link fw-medium">Women<br />Pants</a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img loading="lazy" class="w-100 h-auto mb-3" src="{{ asset('assets/images/home/demo3/category_3.png') }}" width="124"
                                height="124" alt="" />
                            <div class="text-center">
                                <a href="#" class="menu-link fw-medium">Women<br />Clothes</a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img loading="lazy" class="w-100 h-auto mb-3" src="{{ asset('assets/images/home/demo3/category_4.png') }}" width="124"
                                height="124" alt="" />
                            <div class="text-center">
                                <a href="#" class="menu-link fw-medium">Men<br />Jeans</a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img loading="lazy" class="w-100 h-auto mb-3" src="{{ asset('assets/images/home/demo3/category_5.png') }}" width="124"
                                height="124" alt="" />
                            <div class="text-center">
                                <a href="#" class="menu-link fw-medium">Men<br />Shirts</a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img loading="lazy" class="w-100 h-auto mb-3" src="{{ asset('assets/images/home/demo3/category_6.png') }}" width="124"
                                height="124" alt="" />
                            <div class="text-center">
                                <a href="#" class="menu-link fw-medium">Men<br />Shoes</a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img loading="lazy" class="w-100 h-auto mb-3" src="{{ asset('assets/images/home/demo3/category_7.png') }}" width="124"
                                height="124" alt="" />
                            <div class="text-center">
                                <a href="#" class="menu-link fw-medium">Women<br />Dresses</a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img loading="lazy" class="w-100 h-auto mb-3" src="{{ asset('assets/images/home/demo3/category_8.png') }}" width="124"
                                height="124" alt="" />
                            <div class="text-center">
                                <a href="#" class="menu-link fw-medium">Kids<br />Tops</a>
                            </div>
                        </div>
                    </div><!-- /.swiper-wrapper -->
    </div><!-- /.swiper-container js-swiper-slider -->
    </div><!-- /.position-relative -->
    </section>

    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

    <!-- Balik dito from Index1.blade.php -->


    </div>

    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

</main>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<style>
    /* Custom Slider Styles */
    .home-slider {
        width: 100%;
        height: 100vh;
        min-height: 600px;
        margin-top: 0;
        /* Remove the gap */
    }

    /* Header styling */
    .header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: #fff;
        z-index: 1030;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Adjust main content to account for fixed header */
    main {
        padding-top: 80px;
        /* Add padding to main instead of margin to slider */
    }

    .swiper-slide {
        position: relative;
        overflow: hidden;
    }

    .object-fit-cover {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }

    /* Navigation arrows */
    .swiper-button-next,
    .swiper-button-prev {
        color: white;
        background: rgba(0, 0, 0, 0.5);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        backdrop-filter: blur(2px);
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 1.5rem;
    }

    /* Pagination bullets */
    .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background: white;
        opacity: 0.6;
    }

    .swiper-pagination-bullet-active {
        background: #0056b3;
        opacity: 1;
    }

    /* Your existing animations */
    .animate_fade {
        opacity: 0;
        transition: opacity 0.8s ease;
    }

    .animate_fade.show {
        opacity: 1;
    }

    .animate_btt {
        transform: translateY(30px);
        transition: transform 0.8s ease;
    }

    .animate_btt.show {
        transform: translateY(0);
    }

    /* Delay classes */
    .animate_delay-3 {
        transition-delay: 0.3s;
    }

    .animate_delay-5 {
        transition-delay: 0.5s;
    }

    .animate_delay-7 {
        transition-delay: 0.7s;
    }

    .animate_delay-9 {
        transition-delay: 0.9s;
    }

    .animate_delay-10 {
        transition-delay: 1s;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.home-slider', {
            // Core parameters
            loop: true,
            speed: 1000,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            // Add lazy loading
            lazy: {
                loadPrevNext: true,
                loadPrevNextAmount: 2,
            },
            // Navigation
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            // Pagination
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            // Animation triggers
            on: {
                init: function() {
                    animateElements(this.slides[this.activeIndex]);
                },
                slideChange: function() {
                    animateElements(this.slides[this.activeIndex]);
                }
            }
        });

        function animateElements(slide) {
            // Reset all animations
            const elements = slide.querySelectorAll('.animate');
            elements.forEach(el => el.classList.remove('show'));

            // Trigger animations with delay
            setTimeout(() => {
                elements.forEach(el => el.classList.add('show'));
            }, 300);
        }
    });
</script>
@endpush