@extends('master')

<?php 
    \App\Http\Controllers\ClientController::log($lang->fromFile('general', 'redirect_action') . $lang->get('services->page_name') . "."); 

    if($draw != 'simple' && $draw != 'detailed'){
        header('Location: /');
        exit();
    } else {
        $_SESSION['preffered_service_draw'] = $draw;
    }
?>

@section('app_title', str_replace("'", "", $lang->get('services->page_name')))

@section('section')


<div class='container pb50  pt50-md pt100'>
    <div class='row'>

        @include('includes.client_menu')
            
        <div class="col-lg-9 col-md-12 col-sm-12 client-panel">
            @if($draw != 'simple')
            <a href="/client/services/view/simple" class="label label-info label-small float-left panel-btn-left">{{ $lang->get('services->show_simple_list') }}</a>
            @else
            <a href="/client/services/view/detailed" class="label label-info label-small float-left panel-btn-left">{{ $lang->get('services->show_detailed_list') }}</a>       
            @endif     
            <a href="/client/services/order" class="label label-info label-small float-right panel-btn-right">{{ $lang->get('services->order_service') }}</a>       
            <div class="card">
                <div class="card-header" style="background-color: transparent;">
                    {{ str_replace("'", '', $lang->get('services->page_name')) }}
                    <div class="float-right pull-right">
                        <i class="fa fa-database"></i>
                    </div>
                </div>
                <div class="card-body">
                    
                    <div class="col-md-12 row masonry-grid mb10">

                    @if($draw == 'detailed')

                        @forelse($serviceController->getServices() as $service)

                        @php
                            $service = (array) $service; 
                            $service_data = json_decode($service['data'], true);
                        @endphp

                        <div class="col-md-12 card mb10 card-post" style="max-height: 200px;padding-left: 0px;margin-left: 10px;right: -5px;margin-top: 10px;">
                            <span class="label label-info label-small float-right server-expiration-title">{{ $lang->get('services->services->table->expiration_date') }}</span>
                            <div class="row align-items-center" style="max-height: 200px;">
                                <div class="col-12 col-md-5 align-self-center server-image">
                                    <a href="blog-single.html"><img class="img-fluid" src="https://maxcdn.icons8.com/office/PNG/512/Gaming/minecraft_logo-512.png" style="height: 100%; max-height:200px;">
                                    </a>
                                    <span class="label label-danger  server-status-label">{{ $lang->fromFile('general', 'status-off') }}</span>
                                </div>
                                <div class="col-12 col-md-8">
                                    <div class="card-body">
                                        <h4 class="card-title font400 server-name-title">TEST 1 <small class="server-name-title-id">(#1)</small></h4>
                                    </div>
                                    <div class="card-bottom col-md-12" style="position: relative;bottom: -5px;right: -50px;top: 47px;">
                                        <ul class="list-inline float-right" style="padding-bottom: 11px;">
                                            <li class="list-inline-item" style="margin-right: -5px;margin-bottom: 4px;">
                                                <a class="font600 btn btn-underline" style="color: #adadad;border: 0px;margin-bottom: -15px;">{{ $lang->get('services->table-control_service') }} &nbsp;<i class="fa fa-angle-right"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @empty
                            <h2 class="text-center to-center text-alert-big">{{ $lang->get('services->tables->no_service_owned') }}</h2>
                        @endforelse

                    @else
                        @if(count($serviceController->getServices()) > 0)
                        <table class="table table-responsive table-centered">
                            <thead>
                                <tr class="tr-light">
                                    <th class="tr-light-item-first">{{ $lang->get('services->table->type') }}</th>
                                    <th class="tr-light-item">{{ $lang->get('services->table->name') }}</th>
                                    <th class="tr-light-item" style="padding-left: 9px;width: 20%;">{{ $lang->get('services->table->expiration') }}</th>
                                    <th class="tr-light-item" style="padding-left: 33px;width: 26%;">{{ $lang->get('services->table->ip_address') }}</th>
                                    <th class="tr-light-item-last" style="width: 12%;">{{ $lang->get('services->table->control') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                        @else
                            <h2 class="text-center to-center text-alert-big">{{ $lang->get('services->table->no_service_owned') }}</h2>
                        @endif
                    @php 
                        $services = object_to_array($serviceController->getServices($page * 5, 5, 'asc'));
                        rsort($services);
                        $services = array_slice($services, -5);
                    @endphp
                    @foreach($services as $service)
                    @php
                        $service_data = json_decode($service['data'], true);
                        $master_data = json_decode($serviceController->getMasterById($service_data['service_data']['master_id']), true);
                    @endphp
                        
                            <tr class="tr-light-small">
                                <td><img draggable="false" src="/images/icons/services/{{ $service_data['service_info']['service'] }}-icon.svg?ver=<?=time();?>" class="service-icon-small"></td>
                                <td>
                                    @if(!empty($service_data['service_info']['paid'])) 
                                        <a href='/' class="service-open-link"> 
                                    @endif {{ $service_data['service_info']['name'] }} 

                                    @if(!empty($service_data['service_info']['paid'])) 
                                        </a> 
                                    @endif 
                                </td>
                                @if(empty($service_data['service_info']['paid']))
                                    <td><img draggable="false" src="/gifs/loading.gif" style="width: 42px;margin-left: 5.5px;"></td>
                                    <td><img draggable="false" src="/gifs/loading.gif" style="width: 42px;margin-left: 31.5px;"></td>
                                    <td class="text-center">
                                        <a class="service-pay" href=""><i class="fa fa-credit-card"></i></a>
                                    </td>
                                @else
                                    <td><span class="label label-success label-small upper-case" style="position: relative;left: -9px;">zbývá 25 dnů</span></td>
                                    <td class="service-address" onclick="copy(this)">{{ $master_data['master_data']['host'] }}:25565</td>
                                    <td class="text-center">
                                        <a class="service-manage-icon" style="margin-left:0px!important;" href=""><i class="fa fa-pause"></i></a>
                                        <a class="service-manage-icon" href=""><i class="fa fa-refresh"></i></a>
                                    </td>
                                @endif
                            </tr>

                    @endforeach
                        </tbody>
                    </table>

                        @if(count($serviceController->getServices()) > 5)
                        
                        <nav class="clearfix pt60 page-list" style="display: block;margin:0 auto;">  
                            @if($page < $serviceController->getPageCount('simple'))
                            <li>
                                <a class="btn btn-outline-light page-list-button" href="/client/services/view/simple/{{ $page + 1 }}">{{ $lang->get('services->table->next') }} &nbsp <i class="fa fa-angle-right"></i></a>
                            </li> 
                            
                            <li>
                                <a class="btn btn-outline-light page-list-button" href="/client/services/view/simple/{{ $serviceController->getPageCount('simple') }}">{{ $serviceController->getPageCount('simple') }}</a>
                            </li>            
                            <li>
                                <i class="fa fa-ellipsis-v fa-reversed" style="position: relative;left: 33.45px!important;top: 7.5px;"></i>
                            </li>
                            @endif


                                
                            @if($page > $serviceController->getPageCount('simple'))
                                @php
                                    header('Location: /client/services/view/' . $draw);
                                    exit();
                                @endphp
                            @endif
                

                            @if(is_numeric($page) && $page > 0)


                                @if($page <= 1 && $serviceController->getPageCount('simple') >= 3)
                                    <li><a class="btn btn-outline-light page-list-button" href="{{ $page + 2 }}">{{ $page + 2 }}</a></li>
                                @endif

                                @if(($page + 1) <= $serviceController->getPageCount('simple'))
                                    <li><a class="btn btn-outline-light page-list-button" href="{{ $page + 1 }}">{{ $page + 1 }}</a></li>
                                @endif

                                <li><a class="btn btn-outline-light page-list-button service-link-active" href="{{ $page }}">{{ $page }}</a></li>

                                @if($page > 1)
                                    <li><a class="btn btn-outline-light page-list-button" href="{{ $page - 1 }}">{{ $page - 1 }}</a></li>
                                @endif

                                @if($page == $serviceController->getPageCount('simple') && $page >= 3)
                                    <li><a class="btn btn-outline-light page-list-button" href="{{ $page - 2 }}">{{ $page - 2 }}</a></li>
                                @endif

                            @endif

                            @if(is_numeric($page) && $page > 1)
                                <li>
                                    <a class="btn btn-outline-light page-list-button" href="/client/services/view/simple/{{ $page - 1 }}"><i class="fa fa-angle-left"></i> &nbsp {{ $lang->get('services->table->back') }}</a>
                                </li> 
                            @endif
                        </nav>
                    @endif

                    @endif
                    </div>
                    @if(count($serviceController->getServices()) < 1)
                        <a href="/client/services/order" class="btn btn-outline-light label-big text-center to-center col-md-3 col-lg-4 font-100">{{ mb_strtoupper($lang->get('services->order_service') , 'UTF-8')}}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection