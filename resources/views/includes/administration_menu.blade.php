<aside class="col-lg-3 col-md-12 col-sm-12">
   <nav role="navigation">
      <span class="label label-info client-funds" style="border-bottom:0px;">{{ $clientController->get('client_data', 'balance') }} {{ $lang->fromFile('general', 'currency') }}</span>
      <ul class="sidebar-nav list-group no-margin container-zigzag" style="background-color: transparent!important" id="sidebar-nav" style="background-color: #b3afaf!important;">
         <li class="list-group-item" style="height: 120px;">
            <img src="{{ $clientController->getAvatar() }}" class="br-2 team-member-img-top" alt="Avatar" style="max-width:100px;height: 100Px;border-radius: 150px;border: 1px solid #dfdfdf;margin: 0 auto;display: block;margin-top: -65px;">
            <div class="user-data" style="position: absolute;top: 58px;">
               <p style="color: #a3a3a3;font-weight: 400;">
                  
                  {{ $clientController->get('billing_info', 'first_name') }}
                  {{ $clientController->get('billing_info', 'last_name') }}
                  <small>(#{{ $clientController->getId() }})</small>

                  <span class="client-dignity label label-info">
                    {{ $clientController->composeDignity($clientController->get('client_data', 'permissions')) }}
                  </span>
               </p>
            </div>
         </li>
         <li onclick="location.href='/client';" class="list-group-item list-group-item-menu-link" style="display: inline-block;background-color: rgb(251, 251, 251);">
            <div class="list-group-item-menu-icon">
               <i class="fa fa-user"></i>
            </div>
            <a>{{ $lang->get('sidemenu->user_section') }}</a>
         </li>
         <li onclick="location.href='/administration';" class="list-group-item list-group-item-menu-link" style="display: inline-block;margin-top:31px">
            <div class="list-group-item-menu-icon">
               <i class="fa fa-info-circle" style="margin-left: 1px;"></i>
            </div>
            <a>{{ $lang->get('sidemenu->basic_info') }}</a>
         </li>
        @if($clientController->get('client_data', 'permissions') >= 1)
         <li onclick="location.href='/administration/support';" class="list-group-item list-group-item-menu-link">
            <div class="list-group-item-menu-icon">
               <i class="fa fa-life-ring"></i>
            </div>
            <a>{{ $lang->get('sidemenu->support') }}</a>
         </li>
        @endif
        @if($clientController->get('client_data', 'permissions') >= 1)
         <li onclick="location.href='/administration/services';" class="list-group-item list-group-item-menu-link">
            <div class="list-group-item-menu-icon">
               <i class="fa fa-server"></i>
            </div>
            <a>{{ $lang->get('sidemenu->services') }}</a>
         </li>
        @endif
        @if($clientController->get('client_data', 'permissions') >= 1)
         <li onclick="location.href='/administration/clients';" class="list-group-item list-group-item-menu-link">
            <div class="list-group-item-menu-icon">
               <i class="fa fa-users"></i>
            </div>
            <a>{{ $lang->get('sidemenu->users') }}</a>
         </li>
        @endif
        @if($clientController->get('client_data', 'permissions') >= 1)
         <li onclick="location.href='/administration/transactions';" class="list-group-item list-group-item-menu-link">
            <div class="list-group-item-menu-icon">
               <i class="fas fa-exchange-alt"></i>
            </div>
            <a>{{ $lang->get('sidemenu->transactions') }}</a>
         </li>
        @endif
        @if($clientController->get('client_data', 'permissions') >= 1)
         <li onclick="location.href='/administration/notes';" class="list-group-item list-group-item-menu-link">
            <div class="list-group-item-menu-icon">
               <i class="fa fa-sticky-note"></i>
            </div>
            <a>{{ $lang->get('sidemenu->comments') }}</a>
         </li>
        @endif
        @if($clientController->get('client_data', 'permissions') >= 3)
         <li onclick="location.href='/administration/blog';" class="list-group-item list-group-item-menu-link">
            <div class="list-group-item-menu-icon">
               <i class="fa fa-newspaper"></i>
            </div>
            <a>{{ $lang->get('sidemenu->blog') }}</a>
         </li>
        @endif
        @if($clientController->get('client_data', 'permissions') >= 3)
         <li onclick="location.href='/administration/settings';" class="list-group-item list-group-item-menu-link">
            <div class="list-group-item-menu-icon">
               <i class="fa fa-cogs"></i>
            </div>
            <a>{{ $lang->get('sidemenu->hosting_settings') }}</a>
         </li>
        @endif
      </ul>
   </nav>
</aside>