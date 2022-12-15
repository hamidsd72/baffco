@extends('layouts.user')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/css/swiper.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{asset('user/css/swiper.csss')}}"/>
@endsection
@section('body')
    @dd(1)
    <div id="carouselExampleCaptions" class="carousel slide slider_index" data-ride="carousel">
        <div class="carousel-inner">
            @if(count($sliders))
                @foreach($sliders as $key=> $slider)
                    <div class="carousel-item {{$key==0?'active':''}}">
                        <img src="{{url($slider->photo->path)}}" class="d-block w-100"
                             alt="{{set_lang($slider,'title',app()->getLocale())}}">
                        <div class="carousel-caption d-none d-md-block slider_text">
                                @if(set_lang($slider,'title',app()->getLocale()))
                                    <span>{{set_lang($slider,'title',app()->getLocale())}}</span>
                                @endif
                            <h2>
                                {{set_lang($slider,'text1',app()->getLocale())}}
                                <br>
                                {{set_lang($slider,'text2',app()->getLocale())}}
                            </h2>
                            @if($slider->url!=route('user.index'))
                                <a href="{{$slider->url}}" class="btn btn-outline">{{__('text.read_more')}}</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="carousel-item active">
                    <img src="https://codifystudioz.com/cs-theme/brooksurgery/img/home-slider/slide-4.jpg"
                         class="d-block w-100" alt="...">
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
    {{--    category--}}
{{--    @if(count($menu_slider_downs))
        <section class="section_category">
            <div class="container">
                <div class="row">
                    @foreach($menu_slider_downs as $key=>$page)
                        <div class="col-lg-3 col-sm-6 mx-auto">
                            <a
                                    href="{{route('user.page.show',app()->getLocale()=='fa'?$page->slug:$page->slug_en)}}"
                            >
                            <div class="service__image @if(count($menu_slider_downs)>2) {{$key%2==0?'img-up-1':'img-up-2'}} @endif"
                                 style="background: url('{{$page->pic?url($page->pic):url('includes/asset/user/pic/nopic.jpg')}}')">
                                <div class="custom-border-gap bg-image" >
                                    <span class="custom-border-gap__border float-left" ></span>
                                    <span class="custom-border-gap__border float-right" ></span>
                                    <div class="service_title">
                                        <h3>{{set_lang($page,'name',app()->getLocale())}} </h3>
                                        --}}{{--                                <p>--}}{{--
                                        --}}{{--                                    surgery--}}{{--
                                        --}}{{--                                </p>--}}{{--
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    @endforeach

                </div>
--}}{{--                <div class="col-12">--}}{{--
--}}{{--                    <a href="{{route('user.product.category.index','all_category')}}"--}}{{--
--}}{{--                       class="fancy-btn">{{__('text.index.btn_view_cat')}}</a>--}}{{--
--}}{{--                </div>--}}{{--
            </div>
        </section>
    @endif--}}

    {{-- product--}}

{{--    certificate--}}
    @if(count($certs))
    <section class="section_certificate">
        <div class="container">
            <div class="sec_middle_title">
                <h1 class="uppercase">{{__('text.index.cert_title')}}</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    <!-- Swiper -->
                    <div class="swiper-container swiper-cert">
                        <div class="swiper-wrapper">
                            @foreach($certs as $key=>$cert)
                                    <div class="swiper-slide" href="{{$cert->pic?url($cert->pic):url('includes/asset/user/pic/nopic.jpg')}}"
                                         data-fancybox="cert_gallery">
                                        <div class="img_box">
                                            <img src="{{$cert->pic?url($cert->pic):url('includes/asset/user/pic/nopic.jpg')}}"
                                                 alt="{{set_lang($cert,'title',app()->getLocale())}}">
                                        </div>
                                        <div class="text_box">
                                            <h5>{{set_lang($cert,'title',app()->getLocale())}}</h5>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next cert">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                        <div class="swiper-button-prev cert">
                            <i class="fas fa-chevron-left"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <section class="hero-section">
        <div class="container">
            <div class="sec_middle_title">
                <h1>حوزه‌های فعالیت</h1>
            </div>
            <div class="card-grid">
                <a class="card" href="#">
                    <div class="card__background" style="background-image: url(https://lhllaw.co.za/wp-content/uploads/2022/01/istockphoto-1291177121-170667a.jpg)"></div>
                    <div class="card__content">
                        <p class="card__category">دسته بندی</p>
                        <h3 class="card__heading"> لورم ایپسوم </h3>
                    </div>
                </a>

                <a class="card" href="#">
                    <div class="card__background" style="background-image: url(https://euratex.eu/wp-content/uploads/shutterstock_377626975-scaled.jpg)"></div>
                    <div class="card__content">
                        <p class="card__category">دسته بندی</p>
                        <h3 class="card__heading"> لورم ایپسوم </h3>
                    </div>
                </a>
                <a class="card" href="#">
                    <div class="card__background" style="background-image: url(http://en.people.cn/NMediaFile/2016/0929/FOREIGN201609291445000034349506673.jpg)"></div>
                    <div class="card__content">
                        <p class="card__category">دسته بندی</p>
                        <h3 class="card__heading"> لورم ایپسوم </h3>
                    </div>
                </a>
                <a class="card" href="#">
                    <div class="card__background" style="background-image: url(https://www.artsprofessional.co.uk/sites/artsprofessional.co.uk/files/the_factory_0.jpg)"></div>
                    <div class="card__content">
                        <p class="card__category">دسته بندی</p>
                        <h3 class="card__heading"> لورم ایپسوم </h3>
                    </div>
                </a>
        </div>
            <div>
            </div>
        </div>
    </section>


    <section id="mtz-info-box-image-energiewende">
        <div class="info-box-image">

            <span class="media-queries-helper js-media-queries-helper"></span>


            <div class="info-box-image__grid-container">
                <div class="info-box-image__info-container js-info-box-image__info-container">
                    <div class="info-box-image__info-container-inner">
                        <h2 class="heading heading-1 heading--curtain js-title">درباره ما</h2>
                        <p class="paragraph info-box-image__info-text js-text">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده ا هدف بهبود ابزارهای کاربردی می باشد کتابهای زیادی در شصت و سه درصد گذشته حال و آینده شناخت فراوان جامعه و متخصصان را می طلبد </p>
                        <div class="js-info-box-image__subtitle-container">
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
            <img class="info-box-image__background js-picturefill js-image" data-src-s="https://d3o3d9viu00ouz.cloudfront.net/abb.com/custom-content/@dcom/mission-to-zero-en/v1.0.16/media/energiewende-s.7m4o3s7r.jpg" data-src-l="https://d3o3d9viu00ouz.cloudfront.net/abb.com/custom-content/@dcom/mission-to-zero-en/v1.0.16/media/energiewende-l.a8yvd3ul.jpg" src="https://d3o3d9viu00ouz.cloudfront.net/abb.com/custom-content/@dcom/mission-to-zero-en/v1.0.16/media/energiewende-s.7m4o3s7r.jpg">

            <div class="button button--primary info-box-image__video-close-btn-container js-close" src="https://d3o3d9viu00ouz.cloudfront.net/abb.com/custom-content/@dcom/mission-to-zero-en/v1.0.16/media/close.ez6pbvmd.svg"></div>
        </div>
    </section>

    <section class="Honors-section">
        <div class="container">
            <div class="sec_middle_title">
                <h1> ویترین افتخارات</h1>
            </div>
            <section class="swiper-container loading">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" style="background-image:url(https://mobinec.ir/wp-content/uploads/2021/01/certificate-image-5.jpg)">
                        <img src="https://mobinec.ir/wp-content/uploads/2021/01/certificate-image-5.jpg" class="entity-img" />

                    </div>
                    <div class="swiper-slide" style="background-image:url(https://mobinec.ir/wp-content/uploads/2021/01/certificate-image-5.jpg)">
                        <img src="https://mobinec.ir/wp-content/uploads/2021/01/certificate-image-5.jpg" class="entity-img" />

                    </div>
                    <div class="swiper-slide" style="background-image:url(https://mobinec.ir/wp-content/uploads/2021/01/certificate-image-5.jpg)">
                        <img src="https://mobinec.ir/wp-content/uploads/2021/01/certificate-image-5.jpg" class="entity-img" />

                    </div>
                    <div class="swiper-slide" style="background-image:url(https://mobinec.ir/wp-content/uploads/2021/01/certificate-image-5.jpg)">
                        <img src="https://mobinec.ir/wp-content/uploads/2021/01/certificate-image-5.jpg" class="entity-img" />

                    </div>

                </div>

                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>
                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev swiper-button-white"></div>
                <div class="swiper-button-next swiper-button-white"></div>
            </section>
        </div>
    </section>
@endsection
@section('js')
    <script>
        var mc = new Hammer(document.getElementById("carouselproducts"));

        //mouse pointer state
        var pointerXCoord = 0;
        //image lefting
        var imageLeftCord = 0;
        //carousel width
        var carouselWidth = $(".carousel-inner").width();
        //lastmove
        var lastMove = "";

        $("#carouselproducts").carousel(
            {
                //interval: false
            }
        );

        function initialize() {
            $("#carouselproducts").carousel("cycle");
            carouselWidth = $(".carousel-inner").width();
            imageLeftCord = 0;
            pointerXCoord = 0;
            lastMove = "";
        }

        function snapLeft() {
            $(".carousel-item").css({ transition: "", transform: "" });
            $(".carousel-item").removeClass("prev");
            $(".carousel-item").removeClass("next");
            $("#carouselproducts").carousel("prev");
            setTimeout(function() {
                initialize();
            }, 600);
        }

        function snapRight() {
            $(".carousel-item").css({ transition: "", transform: "" });
            $(".carousel-item").removeClass("prev");
            $(".carousel-item").removeClass("next");
            $("#carouselproducts").carousel("next");
            setTimeout(function() {
                initialize();
            }, 600);
        }

        $(".carousel-control.left").click(function() {
            snapLeft();
        });

        $(".carousel-control.right").click(function() {
            snapRight();
        });

        //CATCH PANNING EVENTS
        mc.on("panstart panend panleft panright", function(ev) {
            //PAUSE THE CAROUSEL
            $("#carouselproducts").carousel("pause");

            //set next and prev with circular searching
            var prev = $(".carousel-item.active").prev();
            if (prev[0] === undefined) {
                prev = $(".carousel-inner").children().last();
            }
            prev.addClass("prev");

            var next = $(".carousel-item.active").next();
            if (next[0] === undefined) {
                next = $(".carousel-inner").children().first();
            }
            next.addClass("next");

            //if is panstart set position of cursor for calculationg different positions
            if (ev.type === "panstart") {
                pointerXCoord = ev.pointers[0].pageX;
                return 0;
            }

            //MOVING
            if (pointerXCoord !== ev.pointers[0].pageX) {
                //set last action [left-right]
                lastMove = ev.type;

                //how much do you move?
                var diff = ev.pointers[0].pageX - pointerXCoord;
                $(".carousel-item.active").css({
                    transition: "initial",
                    transform: "translate3d(" + (imageLeftCord + diff) + "px, 0, 0)"
                });
                $(".carousel-item.next").css({
                    transition: "initial",
                    transform:
                        "translate3d(" + (imageLeftCord + diff + carouselWidth) + "px, 0, 0)"
                });
                $(".carousel-item.prev").css({
                    transition: "initial",
                    transform:
                        "translate3d(" + (imageLeftCord + diff - carouselWidth) + "px, 0, 0)"
                });

                //set variables for next turn
                imageLeftCord += diff;
                pointerXCoord = ev.pointers[0].pageX;
            }

            //end
            if (ev.type === "panend") {
                if (imageLeftCord > carouselWidth / 2) {
                    if (lastMove === "panright") {
                        snapLeft();
                        return 0;
                    }
                }

                if (imageLeftCord < -(carouselWidth / 2)) {
                    if (lastMove === "panleft") {
                        snapRight();
                        return 0;
                    }
                }

                //return to the start position
                $(".carousel-item").css({ transition: "", transform: "" });
                initialize();
            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.1/js/swiper.min.js"></script>
    <script>
        // Params
        var sliderSelector = '.swiper-container',
            options = {
                init: false,
                loop: true,
                speed:800,
                slidesPerView: 2, // or 'auto'
                // spaceBetween: 10,
                centeredSlides : true,
                effect: 'coverflow', // 'cube', 'fade', 'coverflow',
                coverflowEffect: {
                    rotate: 50, // Slide rotate in degrees
                    stretch: 0, // Stretch space between slides (in px)
                    depth: 100, // Depth offset in px (slides translate in Z axis)
                    modifier: 1, // Effect multipler
                    slideShadows : true, // Enables slides shadows
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
                    imagesReady: function(){
                        this.el.classList.remove('loading');
                    }
                }
            };
        var mySwiper = new Swiper(sliderSelector, options);

        // Initialize slider
        mySwiper.init();
    </script>
@endsection
