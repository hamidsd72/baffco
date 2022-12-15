@extends('layouts.user')
@section('css_style') @endsection
@section('body')
    <link rel="stylesheet" type="text/css" href="{{asset('user/front/index.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('user/front/contact.css')}}"/>

    <nav aria-label="topbreadcrumb" class="topbreadcrumb">
        <div class="container">
            <ul class="breadcrumb p-2" style="background-color: #f8f8f8;">
                <li class="breadcrumb-item"><a href="{{url('/')}}">خانه</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
            </ul>
        </div>
    </nav>

    <div class="sec_middle_title" style="background-color: #eeeeee;">
        <div class="py-5">
            <div class="d-flex justify-content-center">
                <h1>{{$title}}</h1>
            </div>
        </div>
    </div>

    <section class="contact_us_area mt-lg-4">
        <div class="container">
            <div class="contact_us_inner container-fluid">

                {{-- اسلایدر --}}
                <section id="menu_slider_downs" class="why_choose bg-white teams pb-0">
                    <div class="container ">
                        <div class="title">
                            <h3>فرصت های شغلی</h3>
                        </div>
                        <section class="swiper-container loading">
                            <div class="swiper-wrapper">

                                {{--          @foreach($certs as $index=>$item)--}}
                                {{--           @if($item->pic != null)--}}
                                <div class="swiper-slide" style="background-image:url('/includes/asset/img/news/1.png');width: 253.333px;">
                                    <img src="/includes/asset/img/news/1.png" class="entity-img"/>
                                    <div class="content"></div>
                                </div>
                                <div class="swiper-slide" style="background-image:url('/includes/asset/img/news/2.png');width: 253.333px;">
                                    <img src="/includes/asset/img/news/2.png" class="entity-img"/>
                                    <div class="content"></div>
                                </div>
                                <div class="swiper-slide" style="background-image:url('/includes/asset/img/news/3.png');width: 253.333px;">
                                    <img src="/includes/asset/img/news/3.png" class="entity-img"/>
                                    <div class="content"></div>
                                </div>
                                <div class="swiper-slide" style="background-image:url('/includes/asset/img/news/4.png');width: 253.333px;">
                                    <img src="/includes/asset/img/news/4.png" class="entity-img"/>
                                    <div class="content"></div>
                                </div>
                                <div class="swiper-slide" style="background-image:url('/includes/asset/img/news/5.png');width: 253.333px;">
                                    <img src="/includes/asset/img/news/5.png" class="entity-img"/>
                                    <div class="content"></div>
                                </div>
                                <div class="swiper-slide" style="background-image:url('/includes/asset/img/news/6.png');width: 253.333px;">
                                    <img src="/includes/asset/img/news/6.png" class="entity-img"/>
                                    <div class="content"></div>
                                </div>

                                {{--@endif--}}

                                {{--@endforeach--}}

                            </div>

                            <!-- If we need pagination -->
                            <div class="swiper-pagination"></div>
                            <!-- If we need navigation buttons -->
                            <div class="swiper-button-prev swiper-button-white"></div>
                            <div class="swiper-button-next swiper-button-white"></div>
                        </section>
                    </div>
                </section>
                {{-- پایان اسلایدر --}}

                
                <div class="row mt-5">
                    <div class="col-lg contact_us_details text-center">
                        @if($item->employ_pic_active=='active' && $item->employ_pic)
                            <div class="col-10 mx-auto my-5">
                                <img src="{{url($item->employ_pic)}}" alt="{{$title}}">
                            </div>
                        @endif
                        <div class="h3-24">
                            {!! set_lang($item,'employ_text',app()->getLocale()) !!}
                        </div>
                    </div>
                    {{-- فرم --}}
                    <form id="hafez_form" class="contact_us_form col-lg" action="{{route('user.employment.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group col-12 px-0">
                            <input type="text" class="form-control {{$errors->has('name')?'error_border':''}}" id="name" name="name"
                                   placeholder="* {{__('text.employment.frm_name')}}" value="{{old('name')}}" required>
                        </div>
                        <div class="form-group col-12 px-0">
                            <input type="text" class="form-control d-ltr {{$errors->has('mobile')?'error_border':''}}" id="mobile" name="mobile"
                                   placeholder="{{__('text.employment.frm_mobile')}} *" value="{{old('mobile')}}" required>
                        </div>
                        <div class="form-group col-12 px-0">
                            <input type="email" class="form-control d-ltr {{$errors->has('email')?'error_border':''}}" id="email" name="email"
                                   placeholder="{{__('text.employment.frm_email')}}" value="{{old('email')}}">
                        </div>
                        @if(count($item->exploade_unit(set_lang($item,'employ_type',app()->getLocale()))))
                            <div class="form-group col-12 px-0">
                                <select class="form-control {{$errors->has('employ_type')?'error_border':''}}" id="employ_type" name="employ_type" required>
                                    <option value="">* نوع همکاری</option>
                                    @foreach($item->exploade_unit(set_lang($item,'employ_type',app()->getLocale())) as $unit)
                                        <option value="{{$unit}}" {{old('employ_type')==$unit?'selected':''}}>{{$unit}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        @if(count($item->exploade_unit(set_lang($item,'employ_unit',app()->getLocale()))))
                        <div class="form-group col-12 px-0">
                            <select class="form-control {{$errors->has('unit')?'error_border':''}}" id="unit" name="unit" required>
                                <option value="">* {{__('text.employment.frm_unit')}}</option>
                                @foreach($item->exploade_unit(set_lang($item,'employ_unit',app()->getLocale())) as $unit)
                                    <option value="{{$unit}}" {{old('unit')==$unit?'selected':''}}>{{$unit}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @if(count($item->exploade_unit(set_lang($item,'employ_know',app()->getLocale()))))
                            <div class="form-group col-12 px-0">
                                <select class="form-control {{$errors->has('employ_know')?'error_border':''}}" id="employ_know" name="employ_know">
                                    <option value="">نحوه آشنایی</option>
                                    @foreach($item->exploade_unit(set_lang($item,'employ_know',app()->getLocale())) as $unit)
                                        <option value="{{$unit}}" {{old('employ_know')==$unit?'selected':''}}>{{$unit}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group col-12 px-0">
                            <textarea rows="3" name="short_text" id="short_text" class="form-control {{$errors->has('short_text')?'error_border':''}}" placeholder="* خلاصه ای از من" 
                                required>{{old('short_text')}}</textarea>
                        </div>
                        <div class="form-group col-12 p-0 file">
                            <label style="background: #f2f2f2;border-top: 1px solid #dedede;border-right: 1px solid #dedede;border-left: 1px solid #dedede;" 
                                class="pt-2 d-block {{app()->getLocale()=='fa'?'d-rtl text-right':'d-ltr text-left'}}">
                                * {{__('text.employment.frm_resome')}} ( pdf, doc, docx, حداکثر اندازه فایل: 10 MB.)
                            </label>
                            <input style="border-top: 1px solid #f2f2f2;" type="file" class="py-2 form-control {{$errors->has('file')?'error_border':''}}" 
                            id="file" name="file" accept=".pdf,.doc,.docx">
                        </div>
                        <div class="form-group col-12 px-0 my-2">
                            <button type="submit" value="submit" class="sub-btn btn btn-primary col-12" >{{__('text.contact.frm_btn')}}</button>
                        </div>
                    </form>
                    {{-- پایان فرم --}}
                </div>

            </div>
        </div>
    </section>

@endsection
@section('js')
 <script src='https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.4.1/js/swiper.min.js'></script>
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
                 depth: 0, // Depth offset in px (slides translate in Z axis)
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
