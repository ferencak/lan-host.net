<?php
$applicationController = new \App\Http\Controllers\ApplicationController();
$lang = $applicationController->languageController;
$lang->page('include_client_menu');

?>
<aside class="col-lg-3 col-md-12 col-sm-12">
   <nav role="navigation">
      <span data-toggle="tooltip" data-placement="left" title="" data-original-title="Zákaznická sleva 7%" class="label label-info client-funds" style="border-bottom:0px;">{{ $clientController->get('client_data', 'balance') }} {{ $lang->fromFile('general', 'currency') }}</span>
      <ul class="sidebar-nav list-group no-margin container-zigzag" style="background-color: transparent!important" id="sidebar-nav" style="background-color: #b3afaf!important;">
         <li class="list-group-item" style="height: 120px;">
            <img src="{{ $clientController->getAvatar() }}" class="br-2 team-member-img-top" alt="Avatar" style="max-width:100px;height: 100Px;border-radius: 150px;border: 1px solid #dfdfdf;margin: 0 auto;display: block;margin-top: -65px;">
            <div class="user-data" style="position: absolute;top: 58px;">
               <p style="color: #a3a3a3;font-weight: 400;">
                  
                  {{ $clientController->get('billing_info', 'first_name') }}
                  {{ $clientController->get('billing_info', 'last_name') }}
                  <small>(#{{ $clientController->getId() }})</small>

                  
               </p>
               <span class="client-dignity label label-info" style="margin-top:-8%">
                    {{ $clientController->composeDignity($clientController->get('client_data', 'permissions')) }}
               </span>
            </div>
         </li>

         @if($clientController->get('client_data', 'permissions') >= 1)
             <li onclick="location.href='/administration';" class="list-group-item list-group-item-menu-link" style="display: inline-block;background-color: rgb(251, 251, 251);">
                <div class="list-group-item-menu-icon">
                   <i class="fa fa-lock"></i>
                </div>
                <a>{{ $lang->get('menu->link_administration') }}</a>
             </li>
         @endif

         <li onclick="location.href='/client';" class="list-group-item list-group-item-menu-link" style="display: inline-block;@if($clientController->get('client_data', 'permissions') >= 1) margin-top:31px @endif">
            <div class="list-group-item-menu-icon">
               <i class="fa fa-home"></i>
            </div>
            <a>{{ $lang->get('menu->link_index') }}</a>
         </li>
         <li class="list-group-item list-group-item-menu-link list-group-item-menu-link-dropdown" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            <div class="list-group-item-menu-icon">
               <i class="fa fa-server"></i>
            </div>
            <a>{{ $lang->get('menu->services->title') }}</a>
            <span class="pull-right float-right" id="dropdown-icon" style="font-size: 10px;"><i class="fa fa-angle-down"></i></span>
         </li>
         <div class="collapse" id="collapseExample">
            <ul class="nav flex-column">
               <li onclick="location.href='/client/services/view/<?=(isset($_SESSION['preffered_service_draw'])) ? $_SESSION['preffered_service_draw'] : 'simple'?>';" class="list-group-item list-group-item-menu-link list-group-item-dropdown" style="background-color: rgb(251, 251, 251);padding-left: 35Px;">
                  <div class="list-group-item-menu-icon">
                     <i class="fa fa-database"></i>
                  </div>
                  <a>{{ $lang->get('menu->services->link_services') }}</a>
                  <span class="pull-right float-right" id="dropdown-icon" style="font-size: 10px;">
                  <i class="fa fa-angle-right"></i>
                  </span>
               </li>
               <li onclick="location.href='/client/services/order';" class="list-group-item list-group-item-menu-link list-group-item-dropdown" style="background-color: rgb(251, 251, 251);padding-left: 35Px;">
                  <div class="list-group-item-menu-icon">
                     <i class="fa fa-shopping-cart"></i>
                  </div>
                  <a>{{ $lang->get('menu->services->link_order_service') }}</a>
                  <span class="pull-right float-right" id="dropdown-icon" style="font-size: 10px;">
                  <i class="fa fa-angle-right"></i>
                  </span>
               </li>
               <li class="list-group-item list-group-item-menu-link list-group-item-dropdown list-group-item-dropdown-last" style="background-color: rgb(251, 251, 251);padding-left: 35Px;">
                  <div class="list-group-item-menu-icon">
                     <i class="far fa-file"></i>
                  </div>
                  <a>{{ $lang->get('menu->services->link_invoices') }}</a>
                  <span class="pull-right float-right" id="dropdown-icon" style="font-size: 10px;">
                  <i class="fa fa-angle-right"></i>
                  </span>
               </li>
            </ul>
         </div>
         <li class="list-group-item list-group-item-menu-link list-group-item-menu-link-dropdown" data-toggle="collapse" data-target="#collapseCredits" aria-expanded="false" aria-controls="collapseCredits">
            <div class="list-group-item-menu-icon">
               <i class="fa fa-eur"></i>
            </div>
            <a>{{ $lang->get('menu->credits->title') }}</a>
            <span class="pull-right float-right" id="dropdown-icon" style="font-size: 10px;"><i class="fa fa-angle-down"></i></span>
         </li>
         <div class="collapse" id="collapseCredits">
            <ul class="nav flex-column">
               <li onclick="location.href='/client/credits/purchase';" class="list-group-item list-group-item-menu-link list-group-item-dropdown" style="background-color: rgb(251, 251, 251);padding-left: 35Px;">
                  <div class="list-group-item-menu-icon">
                     <i class="fa fa-shopping-cart"></i>
                  </div>
                  <a>{{ $lang->get('menu->credits->link_buy_credits') }}</a>
                  <span class="pull-right float-right" id="dropdown-icon" style="font-size: 10px;">
                  <i class="fa fa-angle-right"></i>
                  </span>
               </li>
               <li onclick="location.href='/client/credits/send';" class="list-group-item list-group-item-menu-link list-group-item-dropdown list-group-item-dropdown-last" style="background-color: rgb(251, 251, 251);padding-left: 35Px;">
                  <div class="list-group-item-menu-icon">
                     <i class="fa fa-share"></i>
                  </div>
                  <a>{{ $lang->get('menu->credits->link_send_credits') }}</a>
                  <span class="pull-right float-right" id="dropdown-icon" style="font-size: 10px;">
                  <i class="fa fa-angle-right"></i>
                  </span>
               </li>
               <li onclick="location.href='/client/credits/overview';" class="list-group-item list-group-item-menu-link list-group-item-dropdown list-group-item-dropdown-last" style="background-color: rgb(251, 251, 251);padding-left: 35Px;">
                  <div class="list-group-item-menu-icon">
                     <i class="fas fa-chart-bar"></i>
                  </div>
                  <a>{{ $lang->get('menu->credits->link_overview_credits') }}</a>
                  <span class="pull-right float-right" id="dropdown-icon" style="font-size: 10px;">
                  <i class="fa fa-angle-right"></i>
                  </span>
               </li>
            </ul>
         </div>
         <li class="list-group-item list-group-item-menu-link">
            <div class="list-group-item-menu-icon">
               <i class="fa fa-newspaper-o"></i>
            </div>
            <a>{{ $lang->get('menu->link_news') }}</a>
         </li>
         <li class="list-group-item list-group-item-menu-link list-group-item-menu-link-dropdown" data-toggle="collapse" data-target="#collapseLogs" aria-expanded="false" aria-controls="collapseCredits">
            <div class="list-group-item-menu-icon">
               <i class="fa fa-list"></i>
            </div>
            <a>{{ $lang->get('menu->records->title') }}</a>
            <span class="pull-right float-right" id="dropdown-icon" style="font-size: 10px;"><i class="fa fa-angle-down"></i></span>
         </li>
         <div class="collapse" id="collapseLogs">
            <ul class="nav flex-column">
               <li onclick="location.href='/client/logs/account';" class="list-group-item list-group-item-menu-link list-group-item-dropdown" style="background-color: rgb(251, 251, 251);padding-left: 35Px;">
                  <div class="list-group-item-menu-icon">
                     <i class="fa fa-list-ol"></i>
                  </div>
                  <a>{{ $lang->get('menu->records->link_account_records') }}</a>
                  <span class="pull-right float-right" id="dropdown-icon" style="font-size: 10px;">
                  <i class="fa fa-angle-right"></i>
                  </span>
               </li>
               <li class="list-group-item list-group-item-menu-link list-group-item-dropdown list-group-item-dropdown-last" style="background-color: rgb(251, 251, 251);padding-left: 35Px;">
                  <div class="list-group-item-menu-icon">
                     <i class="fa fa-list-ol"></i>
                  </div>
                  <a>{{ $lang->get('menu->records->link_service_records') }}</a>
                  <span class="pull-right float-right" id="dropdown-icon" style="font-size: 10px;">
                  <i class="fa fa-angle-right"></i>
                  </span>
               </li>
            </ul>
         </div>
         <li onclick="location.href='/client/settings';" class="list-group-item list-group-item-menu-link">
            <div class="list-group-item-menu-icon">
               <i class="fa fa-cogs"></i>
            </div>
            <a>{{ $lang->get('menu->link_settings') }}</a>
         </li>
      </ul>
   </nav>
</aside>