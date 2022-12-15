@extends('layouts.user')
@section('css_style')
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/css/swiper.min.css'>
    <link rel="stylesheet" type="text/css" href="{{ asset('user/front/style.css') }}"/>
    <style>
        @media (min-width: 1200px) {
            .container {
                max-width: 1120px;
            }
        }

        .swiper-slide {
            max-width: 409px !important;
            background-size: 409px 435px;
        }
    </style>
@endsection
@section('body')
    <link rel="stylesheet" type="text/css" href="{{asset('user/front/index.css')}}"/>
    <div id="carouselExampleCaptions" class="carousel slide slider_index carousel-fade" data-interval="7000"
         data-ride="carousel">
        <div class="carousel-inner">
            @if(count($sliders))
                @foreach($sliders as $key=> $slider)
                    <div class="carousel-item {{$key==0?'active':''}}">
                        <a href="{{$slider->url}}">
                            <img src="{{$slider->photo && is_file($slider->photo->path)?url($slider->photo->path):asset('user/pic/SL-02.jpg')}}"
                                 class="d-block w-100"
                                 alt="{{set_lang($slider,'title',app()->getLocale())}}">
                        </a>

                        {{--@if(is_file($slider->img_top))
                            <div class="animated_img">
                                <img src="{{url($slider->img_top)}}">
                            </div>
                        @endif--}}
                    </div>
                @endforeach
            @else
                <div class="carousel-item active">
                    <img src="{{asset('user/pic/SL-02.jpg')}}"
                         class="d-block w-100" alt="فناوری فردا">
                </div>
            @endif
        </div>
        <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="container">
        <div class="about_section">
            <div class="line d-flex pt-lg-5 pr-3 pr-lg-2">
                <h2>
                    {{__('text.page_name.about')}}
                </h2>
            </div>
            <p class="" style="text-align: justify;">{!!  str_limit(set_lang($about,'head_text'),580) !!}  </p>
{{--            <p class="" style="text-align: justify;">{{__('text.site_description')}}</p>--}}
            <a class="btn_more" href="{{route('user.employment.show')}}"> {{__('text.continue')}}...</a>
        </div>

    </div>


    {{--    category--}}
    <section id="menu_slider_downs" class="why_choose bg-white teams pb-0 mt-2">
        <div class="container ">
            <div class="title">
                <h3 class="text-bold"> {{strtoupper(__('text.power transmission'))}}</h3>
            </div>
            <div class="row">

                @foreach($ProductCategory->children_orderBy->reverse() as $index=>$item)
                    <div class="col-lg-{{$index<3?'4 mt-3':'3'}} ">
                        <div class="card4">
                            <a href="{{route('user.product.category.index',['power-transfer',$item->slug])}}">
                                @if($item->photo)
                                    <img alt="انتقال قدرت" class="card1_img" style="border-radius: 6px;"
                                         src="{{$item->photo->path}}">
                                @endif
                            </a>
                            {{$item->short_text}}
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>

    <section id="menu_slider_downs" class="why_choose bg-white teams pb-0 mt-2">
        <div class="container ">
            <div class="title">
                <h3>
                    {{strtoupper(__('text.Material handling'))}}
                </h3>
            </div>
            <div class="row">

                @foreach($ProductCategory2->children_orderBy as $index=>$item)
                
                    <div class="col-lg-{{$index<2?'6':'4 mt-3'}}">
                        <div class="card4">
                            @if($item->slug == 'ریل-آلومینیومی')
                                <a href="{{route('user.product.show','ریل-آلومینیومی')}}">
                                    @else
                                        <a href="{{route('user.product.category.index',['material-handling',$item->slug])}}">

                                            @endif
                                            @if($item->photo)
                                                <img alt="{{$item->name}}" class="card1_img" style="border-radius: 6px;"
                                                     src="{{$item->photo->path}}">
                                            @endif
                                        </a>
                            {{$item->short_text}}
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    <section id="menu_slider_downs" class="why_choose bg-white teams pb-0 mt-2">
        <div class="container ">
            <div class="title">
                <h3>
                    {{strtoupper(__('text.Letter representation'))}}
                </h3>
            </div>
            <section class="swiper-container loading">
                <div class="swiper-wrapper">

                    @foreach($certs as $index=>$item)
                        @if($item->pic != null)
                            <div class="swiper-slide" style="background-image:url('{{$item->pic}}');width: 253.333px;">
                                <img src="{{$item->pic}}" class="entity-img"/>
                                <div class="content">
                                </div>
                            </div>
                        @endif

                    @endforeach

                </div>

                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>
                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev swiper-button-white"></div>
                <div class="swiper-button-next swiper-button-white"></div>
            </section>
        </div>
    </section>

    @if(app()->getLocale()=='fa')
        <section id="blog_section" class="why_choose bg-white teams pb-0 mt-2" style="margin-bottom: 20px;">
            <div class="container ">

                <div class="title">

                    <h3>{{__('text.page_name.article')}}</h3>
                </div>
                <div class="row">

                    @foreach($blogs as $blog)
                        <div class="col-lg-4">
                            <div class="card4">
                                <a href="{{route('user.blog.show',$blog->slug)}}">
                                    @if($blog->photo() != null)
                                        <img alt="" class="card1_img" style="border-radius: 6px;"
                                             src="{{$blog->photo()->first()->path}}">
                                    @endif

                                </a>
                            </div>
                        </div>
                    @endforeach


                    <a class="btn_more" href="{{route('user.blog.index','article')}}">{{__('text.view all')}}</a>
                </div>
            </div>
        </section>
    @endif

@endsection

@section('js')
    <script src='https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.1/js/swiper.min.js'></script>
    <script>
        // Params
        var sliderSelector = '.swiper-container',
            options = {
                init: false,
                loop: true,
                speed: 800,
                slidesPerView: 2, // or 'auto'
                // spaceBetween: 10,
                centeredSlides: true,
                effect: 'coverflow', // 'cube', 'fade', 'coverflow',
                coverflowEffect: {
                    rotate: 50, // Slide rotate in degrees
                    depth: 0, // Depth offset in px (slides translate in Z axis)
                    modifier: 1, // Effect multipler
                    slideShadows: true, // Enables slides shadows
                },
                grabCursor: true,
                parallax: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    1023: {
                        slidesPerView: 1,
                        spaceBetween: 0
                    }
                },
                // Events
                on: {
                    imagesReady: function () {
                        this.el.classList.remove('loading');
                    }
                }
            };
        var mySwiper = new Swiper(sliderSelector, options);

        // Initialize slider
        mySwiper.init();
    </script>
@endsection