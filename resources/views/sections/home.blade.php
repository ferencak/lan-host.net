@extends('master')

<?php 
    if(isset($_SESSION['user'])){
        \App\Http\Controllers\ClientController::log($lang->fromFile('general', 'redirect_action') . $lang->get('page_name') . ".");
    }
?>

@section('app_title', 'Game, Voice & more...')

@section('section')

    <section class="overflow-hiden">
            <!-- MasterSlider -->
            <div id="P_masterslider" class="master-slider-parent fs-slider ms-parent-id-39" >
                <!-- MasterSlider Main -->
                <div id="masterslider" class="master-slider ms-skin-default" >
                    <div class="ms-slide fullwidth-layers" data-delay="7" data-fill-mode="fill" >
                        <img src="plugins/masterslider/images/blank.gif" alt="" title="" data-src="plugins/masterslider/images/mscrtvi-background-slide-4.jpg" />
                        <img class="ms-layer msp-cn-121-3"
                             src="plugins/masterslider/images/blank.gif"
                             data-src="plugins/masterslider/images/mscrtvi-cupinhand-slide-4.png"
                             alt=""
                             style=""
                             data-effect="t(false,35,550,n,15,n,n,n,n,n,n,n,n,n,n)"
                             data-duration="4000"
                             data-delay="1625"
                             data-ease="easeInOutQuart"
                             data-parallax="3"
                             data-type="image"
                             data-offset-x="461"
                             data-offset-y="-11"
                             data-origin="bc"
                             data-position="normal" />
                        <img class="ms-layer msp-cn-121-4"
                             src="plugins/masterslider/images/blank.gif"
                             data-src="plugins/masterslider/images/mscrtvi-book-slide-4.png"
                             alt=""
                             style=""
                             data-effect="t(true,n,-250,n,-50,n,n,n,n,n,n,n,n,n,n)"
                             data-duration="4000"
                             data-delay="600"
                             data-ease="easeOutQuart"
                             data-parallax="2"
                             data-type="image"
                             data-offset-x="109"
                             data-offset-y="-315"
                             data-origin="mc"
                             data-position="normal" />
                        <img class="ms-layer msp-cn-121-5"
                             src="plugins/masterslider/images/blank.gif"
                             data-src="plugins/masterslider/images/mscrtvi-watch-slide-4.png"
                             alt=""
                             style=""
                             data-effect="t(true,-150,n,n,3,n,n,n,n,n,n,n,n,n,n)"
                             data-duration="5000"
                             data-delay="200"
                             data-ease="easeOutQuart"
                             data-parallax="1"
                             data-type="image"
                             data-offset-x="-21"
                             data-offset-y="112"
                             data-origin="tl"
                             data-position="normal" />
                        <img class="ms-layer msp-cn-121-2"
                             src="plugins/masterslider/images/blank.gif"
                             data-src="plugins/masterslider/images/mscrtvi-handonkeyboard-slide-4.png"
                             alt=""
                             style=""
                             data-effect="t(true,-30,10,n,n,n,n,n,n,n,n,n,n,n,n)"
                             data-duration="5000"
                             data-delay="1800"
                             data-ease="easeOutQuart"
                             data-parallax="1"
                             data-type="image"
                             data-offset-x="-69"
                             data-offset-y="-30"
                             data-origin="bl"
                             data-position="normal" />
                        <div class="ms-layer font-title-55 msp-cn-121-16"
                             style=""
                             data-effect="t(true,n,-30,n,-25,n,n,n,2,2,n,n,n,n,n)"
                             data-duration="2500"
                             data-delay="1000"
                             data-ease="easeOutQuint"
                             data-parallax="3"
                             data-offset-x="0"
                             data-offset-y="2"
                             data-origin="mc"
                             data-position="normal" >{!! strtoupper($lang->fromFile('master', 'app_name')) !!}</div>
                        <div class="ms-layer font-desc-24 msp-cn-121-18"
                             style=""
                             data-effect="t(true,n,30,n,-15,n,n,n,2,2,n,n,n,n,n)"
                             data-duration="2500"
                             data-delay="1400"
                             data-ease="easeOutQuint"
                             data-parallax="2"
                             data-offset-x="0"
                             data-offset-y="59"
                             data-origin="mc"
                             data-position="normal">{{ str_replace('%1', $lang->fromFile('master', 'app_name'), $lang->get('welcome_slider->slider_1->content')) }}</div>
                    </div>
                    
                    <div class="ms-slide" data-delay="10" data-fill-mode="fill" >
                        <img src="plugins/masterslider/images/blank.gif" alt="" title="" data-src="https://i.imgur.com/nPrgIFH.jpg" />
                        <div class="ms-layer font-title-55 msp-cn-121-16"
                             style="color:#fff!important;"
                             data-effect="t(true,n,n,n,-40,n,n,n,n,n,n,n,-20,n,n)"
                             data-duration="1500"
                             data-delay="200"
                             data-ease="easeOutQuint"
                             data-hide-effect="t(true,n,-100,n,n,n,n,n,n,n,n,n,n,n,n)"
                             data-hide-duration="1500"
                             data-hide-ease="easeInQuint"
                             data-hide-time="8000"
                             data-offset-x="0"
                             data-offset-y="-140"
                             data-origin="mc"
                             data-position="normal" >{!! $lang->get('welcome_slider->slider_2->title') !!}</div>
                        <div class="ms-layer font-desc-24 msp-cn-121-1"
                             style="color:#fff"
                             data-effect="t(true,n,n,n,-40,n,n,n,n,n,n,n,110,n,n)"
                             data-duration="1800"
                             data-delay="400"
                             data-ease="easeOutQuint"
                             data-hide-effect="t(true,n,-100,n,n,n,n,n,n,n,n,n,n,n,n)"
                             data-hide-duration="1500"
                             data-hide-ease="easeInQuint"
                             data-hide-time="8400"
                             data-offset-x="0"
                             data-offset-y="-69"
                             data-origin="mc"
                             data-position="normal" >{!! $lang->get('welcome_slider->slider_2->content') !!}</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="container pt90 pb50">
            <div class="row">
                <div class="col-md-4 mb40">
                    <div class="icon-card-style1">
                        <i class="icon-shield text-muted bg-faded icon-round-60"></i>
                        <div class="overflow-hiden">
                            <h4 class="h6 font400">{{ $lang->get('why_us->container_1->title') }}</h4>
                            <p class="mb20">
                                {{ $lang->get('why_us->container_1->content') }}
                            </p>
                        </div>
                    </div>  
                </div>
                <div class="col-md-4 mb40">
                    <div class="icon-card-style1">
                        <i class="icon-happy text-muted bg-faded icon-round-60"></i>
                        <div class="overflow-hiden">
                            <h4 class="h6 font400">{{ $lang->get('why_us->container_2->title') }}</h4>
                            <p class="mb20">
                                {{ $lang->get('why_us->container_2->content') }}
                            </p>
                        </div>
                    </div>  
                </div>
                <div class="col-md-4 mb40">
                    <div class="icon-card-style1">
                        <i class="icon-chat text-muted bg-faded icon-round-60"></i>
                        <div class="overflow-hiden">
                            <h4 class="h6 font400">{{ $lang->get('why_us->container_3->title') }}</h4>
                            <p class="mb20">
                                {{ $lang->get('why_us->container_3->content') }}
                            </p>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
        <div class="container pt90 pb90">
            <div class='feature-col'>
                <div class='row align-items-center'>

                    <div class='col-lg-5 mr-auto'>
                        <h2 class="mb40" style="border-bottom: 3px solid rgb(0, 131, 255);max-width: 90px;">{{ $lang->get('about_us->title') }}</h2>
                        <div class="tabs-default tabs-icon">
                            <ul class="nav nav-tabs tabs-default mb30 justify-content-start" role="tablist">
                                <li class="nav-item" role="presentation"><a class="active nav-link" href="#tb9" aria-controls="tb9" role="tab" data-toggle="tab">{{ $lang->get('about_us->content->tab_1->title') }}</a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" href="#tb10" aria-controls="tb10" role="tab" data-toggle="tab">{{ $lang->get('about_us->content->tab_2->title') }}</a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" href="#tb11" aria-controls="tb11" role="tab" data-toggle="tab">{{ $lang->get('about_us->content->tab_3->title') }}</a></li>
                            </ul>
                            <div class="tab-content text-left mb40">
                                <div role="tabpanel" class="tab-pane active show fade" id="tb9">
                                    <p>
                                        {{ $lang->get('about_us->content->tab_1->content') }}
                                    </p>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tb10">
                                    <p>
                                        {{ $lang->get('about_us->content->tab_2->content') }}
                                    </p>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tb11">
                                    <p>
                                        {{ $lang->get('about_us->content->tab_3->content') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='col-lg-6 pt50-md'>
                        <img src='https://i.imgur.com/nUqsLip.jpg' alt='' class='img-fluid'>
                    </div>
                </div>
            </div>
        </div>
@endsection