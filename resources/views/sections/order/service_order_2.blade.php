<div id="alert-custom-package" class="alert fade show" style="border-left:1px solid rgb(245, 145, 107)!important;background-color: rgba(254, 207, 57, 0);color: rgb(134, 147, 158);border: 1px solid rgb(245, 145, 107);box-shadow: 0px 0px 10px -5px black;display: none;">
    {!! $lang->get('order->Minecraft->step2->custom_package_info') !!}
</div>

<div class="row">
    <div class="col-md-3 mb40">
        <div id="pt-1" class="pricing-card-modern pricing-primary pricing-card-modern-active" style="cursor: pointer;">
            <h5>{{ $lang->get('order->Minecraft->step2->beginner') }}</h5>
            <div class="price-tag"><sup>{{ $lang->fromFile('general', 'currency') }}</sup>{!! getFromJson($orderableController->getService($selectedService), 'service_data', 'packages')['small']['price'] !!} <sub>/ {{ $lang->get('order->Minecraft->step2->month') }}</sub></div>
        </div>
    </div>
    <div class="col-md-3 mb40">
        <div id="pt-2" class="pricing-card-modern pricing-primary" style="cursor: pointer;">
            <h5>{{ $lang->get('order->Minecraft->step2->advanced') }}</h5>
            <div class="price-tag"><sup>{{ $lang->fromFile('general', 'currency') }}</sup>{!! getFromJson($orderableController->getService($selectedService), 'service_data', 'packages')['medium']['price'] !!} <sub>/ {{ $lang->get('order->Minecraft->step2->month') }}</sub></div>
        </div>
    </div>
    <div class="col-md-3 mb40">
        <div id="pt-3" class="pricing-card-modern pricing-primary" style="cursor: pointer;">
            <h5>{{ $lang->get('order->Minecraft->step2->expert') }}</h5>
            <div class="price-tag"><sup>{{ $lang->fromFile('general', 'currency') }}</sup>{!! getFromJson($orderableController->getService($selectedService), 'service_data', 'packages')['big']['price'] !!} <sub>/ {{ $lang->get('order->Minecraft->step2->month') }}</sub></div>
        </div>
    </div>
    <div class="col-md-3 mb40">
        <div id="pt-4" class="pricing-card-modern pricing-primary" style="cursor: pointer;">
            <h5>{{ $lang->get('order->Minecraft->step2->custom') }}</h5>
            <div class="price-tag"><sup>{{ $lang->fromFile('general', 'currency') }}</sup><span id="customPrice"></span> <sub>/ {{ $lang->get('order->Minecraft->step2->month') }}</sub></div>
        </div>
    </div>
</div>

<form id="order_form" action="" method="POST">
    {{ Form::token() }}
    <div class="card-header" style="background-color: transparent;padding-left: 0px;">
        {{ $lang->get('order->Minecraft->step2->settings_and_parameters') }}
        <div class="float-right pull-right">
            <i class="fa fa-cogs"></i>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6 float-left">
                <div class="form-group">
                    <div class="range-slider range-slider-ram" style="margin: 30px 0 0 0%!important;">
                        <div class="col-md-12 mb10" style="background-color: transparent;padding-left: 0px;">
                            {{ $lang->get('order->Minecraft->step2->memory') }}
                        </div>
                        <input name="service_ram" class="range-slider__range range-slider__range-ram col-md-7" type="range" min="128" step="4" value="{!! getFromJson($orderableController->getService($selectedService), 'service_data', 'packages')['small']['params']['ram'] !!}" min="{!! getFromJson($orderableController->getService($selectedService), 'service_data', 'packages')['small']['params']['ram'] !!}" max="16384" required>
                        <span class="range-slider__value range-slider__value-ram col-md-4">0</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 float-right" style="margin: 30px 0 0 0%;">
                <div class="form-group">
                    <div class="col-md-12 mb10" style="background-color: transparent;padding-left: 0px;">
                        {{ $lang->get('order->Minecraft->step2->service_name') }}
                    </div>
                    <input type="text" id="service_name" name="service_name" class="form-control" placeholder="{{ $lang->get('order->Minecraft->step2->service_name_placeholder') }}" required>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="range-slider range-slider-ssd"  style="margin: 0px 0 0 0%!important;">
                        <div class="col-md-12 mb10" style="background-color: transparent;padding-left: 0px;">
                            {{ $lang->get('order->Minecraft->step2->ssd_space') }}
                        </div>
                        <input name="service_ssd" class="range-slider__range range-slider__range-ssd col-md-7" type="range" min="1000" step="4" value="{!! getFromJson($orderableController->getService($selectedService), 'service_data', 'packages')['small']['params']['ssd'] !!}" min="{!! getFromJson($orderableController->getService($selectedService), 'service_data', 'packages')['small']['params']['ssd'] !!}" max="20000" required="">
                        <span class="range-slider__value range-slider__value-ssd col-md-4">0</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 float-right" style="margin: -77px 0 0 0%;">
                <div class="form-group">
                    <div class="col-md-12 mb10" style="background-color: transparent;padding-left: 0px;">
                        {{ $lang->get('order->Minecraft->step2->slots_count') }} <span id="recomended" class="float-right">({{ $lang->get('order->Minecraft->step2->recommended') }} <span id="recomended-slots"></span>)</span>
                    </div>
                    <input type="number" id="service_slots" name="service_slots" min="1" max="100000" class="form-control" placeholder="{{ $lang->get('order->Minecraft->step2->slots_placeholder') }}" required>
                </div>
            </div>
            <div class="col-md-6 group-alone">
                <div class="form-group">
                    <div class="col-md-12 mb10" style="background-color: transparent;padding-left: 0px;padding-right: 0px;">
                        {{ $lang->get('order->Minecraft->step2->java_version') }}
                    </div> 
                    <select name="service_vm" class="form-control form-control-sm" style="color: #8d979c;">
                        <option value="java_7">Java 7</option>
                        <option value="java_8" selected>Java 8 ({{ $lang->get('order->Minecraft->step2->recommended') }})</option>
                        <option value="java_9">Java 9</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 group-alone">
                <div class="form-group">
                    <div class="col-md-12 mb10" style="background-color: transparent;padding-left: 0px;padding-right: 0px;">
                        {{ $lang->get('order->Minecraft->step2->service_version') }}
                    </div> 
                    <select name="service_core" class="form-control form-control-sm" style="color: #8d979c;">
                        <optgroup label="CRAFTBUKKIT">
                            <option>CraftBukkit 1.5.2</option>
                            <option>CraftBukkit 1.6.4</option>
                            <option>CraftBukkit 1.7.2</option>
                            <option>CraftBukkit 1.7.5</option>
                            <option>CraftBukkit 1.7.9</option>
                            <option>CraftBukkit 1.7.10</option>
                            <option>CraftBukkit 1.8</option>
                            <option>CraftBukkit 1.8.1</option>
                            <option>CraftBukkit 1.8.4</option>
                            <option>CraftBukkit 1.8.5</option>
                            <option>CraftBukkit 1.8.3</option>
                            <option>CraftBukkit 1.8.6</option>
                            <option>CraftBukkit 1.8.7</option>
                            <option>CraftBukkit 1.8.8</option>
                            <option>CraftBukkit 1.9</option>
                            <option>CraftBukkit 1.9.2</option>
                            <option>CraftBukkit 1.9.4</option>
                            <option>CraftBukkit 1.10</option>
                            <option>CraftBukkit 1.10.2</option>
                            <option>CraftBukkit 1.11</option>
                            <option>CraftBukkit 1.11.2</option>
                            <option>CraftBukkit 1.12</option>
                            <option>CraftBukkit 1.12.1</option>
                            <option>CraftBukkit 1.12.2</option>
                        </optgroup>
                        <optgroup label="SPIGOT">
                            <option>Spigot 1.5.2</option>
                            <option>Spigot 1.6.4</option>
                            <option>Spigot 1.7.2</option>
                            <option>Spigot 1.7.5</option>
                            <option>Spigot 1.7.9</option>
                            <option>Spigot 1.7.10</option>
                            <option>Spigot 1.8</option>
                            <option>Spigot 1.8.1</option>
                            <option>Spigot 1.8.4</option>
                            <option>Spigot 1.8.5</option>
                            <option>Spigot 1.8.3</option>
                            <option>Spigot 1.8.6</option>
                            <option>Spigot 1.8.7</option>
                            <option>Spigot 1.8.8</option>
                            <option>Spigot 1.9</option>
                            <option>Spigot 1.9.2</option>
                            <option>Spigot 1.9.4</option>
                            <option>Spigot 1.10</option>
                            <option>Spigot 1.10.2</option>
                            <option>Spigot 1.11</option>
                            <option>Spigot 1.11.2</option>
                            <option>Spigot 1.12</option>
                            <option>Spigot 1.12.1</option>
                            <option selected>Spigot 1.12.2 ({{ $lang->get('order->Minecraft->step2->last_version') }})</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-md-12" style="margin: 60px 0 0 0%;">
                <label class="float-right custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
                    <input name="service_extensions" type="checkbox" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">{{ $lang->get('order->Minecraft->step2->preinstall_service') }} <a style="font-weight: 100;font-size: 13px;" href="">{{ $lang->get('order->Minecraft->step2->addon_list') }}</a></span>
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 pt50">
            <a href="/client/services/order" class="btn btn-outline-danger"> <i class="fa fa-angle-left"></i> &nbsp{{ $lang->get('order->Minecraft->step2->back') }}</a>
            <span id="advanced-settings" class="btn btn-outline-light" style="margin-left:10px">Pokročilé nastavení</span>
            <button name="check_order" type="submit" class="btn btn-outline-dark float-right">{{ $lang->get('order->Minecraft->step2->order_service') }} &nbsp<i class="fa fa-angle-right"></i></button>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#advanced-settings").on('click', function(){
                if($("#advanced-settings").hasClass('btn-outline-light')){
                    $("#advanced-settings").removeClass('btn-outline-light').addClass('btn-outline-primary');
                } else {
                    $("#advanced-settings").removeClass('btn-outline-primary').addClass('btn-outline-light');
                }
            })
        })
    </script>
</form>