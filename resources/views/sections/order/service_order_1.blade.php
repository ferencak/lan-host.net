<div class="tabs-default tabs-icon">
    <ul class="nav nav-tabs tabs-default mb30 justify-content-center" role="tablist">
        <li class="nav-item" role="presentation"><a class="nav-link active" href="#tb6" aria-controls="tb6" role="tab" data-toggle="tab" aria-expanded="true">{{ $lang->get('order->step1->game_service') }}</a></li>
        <li class="nav-item" role="presentation"><a class="nav-link" href="#tb7" aria-controls="tb7" role="tab" data-toggle="tab" aria-expanded="false">{{ $lang->get('order->step1->communication_service') }}</a></li>
        <li class="nav-item" role="presentation"><a class="nav-link" href="#tb8" aria-controls="tb8" role="tab" data-toggle="tab">{{ $lang->get('order->step1->other_service') }}</a></li>                       
    </ul>
    <div class="tab-content text-center mb40">
    <div role="tabpanel" class="tab-pane fade active show" id="tb6" aria-expanded="true">
        <div class="container">
            <div class="row">

                @foreach($orderableController->getServices('game') as $service)
                
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="margin-bottom:30px;max-height: 140px;"> 
                    <div class="hovereffect" @if(getValue($service, 'service_data', 'available') == 'true') onclick="location.href='/client/services/order/{{ createLink(getValue($service, 'service_info', 'name_url')) }}';" @endif>
                        <img class="img-responsive" src="/images/{!! getValue($service, 'service_info', 'image_url') !!}" alt="">
                        <div class="overlay">
                           <h2>{{ getValue($service, 'service_info', 'name') }}</h2>
                        </div>
                    </div>
                </div>

                @endforeach

            </div>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane fade" id="tb7" aria-expanded="false">
        <div class="container">
            <div class="row">
                
                @foreach($orderableController->getServices('voice') as $service)
                
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="margin-bottom:30px;max-height: 140px;"> 
                    <div class="hovereffect" @if(getValue($service, 'service_data', 'available') == 'true') onclick="location.href='/client/services/order/{{ createLink(getValue($service, 'service_info', 'name_url')) }}';" @endif>
                        <img class="img-responsive" src="/images/{!! getValue($service, 'service_info', 'image_url') !!}" alt="">
                        <div class="overlay">
                           <h2>{!! str_replace("@", "<font size='11'>", getValue($service, 'service_info', 'name')) !!}</h2>
                        </div>
                    </div>
                </div>

                @endforeach

            </div>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane fade" id="tb8">
        <div class="container">
            <div class="row">
                
                @foreach($orderableController->getServices('other') as $service)
                
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="margin-bottom:30px;max-height: 140px;"> 
                    <div class="hovereffect" @if(getValue($service, 'service_data', 'available') == 'true') onclick="location.href='/client/services/order/{{ createLink(getValue($service, 'service_info', 'name_url')) }}';" @endif>
                        <img class="img-responsive" src="/images/{!! getValue($service, 'service_info', 'image_url') !!}" alt="">
                        <div class="overlay">
                           <h2>{{ getValue($service, 'service_info', 'name') }}</h2>
                        </div>
                    </div>
                </div>

                @endforeach

            </div>
        </div>
    </div>
    </div>
</div>