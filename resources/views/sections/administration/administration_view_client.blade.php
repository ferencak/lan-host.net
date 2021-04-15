@extends('master')

<?php 

\App\Http\Controllers\ClientController::log($lang->fromFile('general', 'redirect_action') . $lang->get('order->page_name') . "."); 

$administrationController = new \App\Http\Controllers\AdministrationController();

?>

@section('app_title', str_replace("'", "", $lang->get('page_name')))

@section('section')

<div class='container pb50  pt50-md pt100'>
  <div class='row'>

    @include('includes.administration_menu')

    <div class="col-lg-9 col-md-12 col-sm-12 client-panel">
      <div class="card">
        <div class="card-header" style="background-color: transparent;">
          Informace o uživateli {{ $administrationController->getClientByID() }}
          <div class="float-right pull-right">
            <i class="fa fa-info">
            </i>
          </div>
        </div>
        <div class="card-body">
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <div class="widget-box bg-white m-b-30">
                  <div class="row d-flex align-items-center" style="position: relative;">
                    <div class="col-md-6" style="float: left;">
                      <div class="text-center">
                        <i class="fa fa-eye" style="font-size:  25px;color: #dfdfdf;">
                        </i>
                      </div>
                    </div>
                    <div class="col-md-4" style="float: right;padding:  0;/* display:  block; */">
                      <h2 class="m-0 counter">fdsfds</h2>
                      <p class="widget-box-info">Zákazníků online</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="widget-box bg-white m-b-30">
                  <div class="row d-flex align-items-center" style="position: relative;">
                    <div class="col-md-6" style="float: left;">
                      <div class="text-center">
                        <i class="fa fa-eye" style="font-size:  25px;color: #dfdfdf;">
                        </i>
                      </div>
                    </div>
                    <div class="col-md-4" style="float: right;padding:  0;/* display:  block; */">
                      <h2 class="m-0 counter">{{ $administrationController->getClientsCount() }}</h2>
                      <p class="widget-box-info">Návštěv online
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="widget-box bg-white m-b-30">
                  <div class="row d-flex align-items-center" style="position: relative;">
                    <div class="col-md-6" style="float: left;">
                      <div class="text-center">
                        <i class="fa fa-eye" style="font-size:  25px;color: #dfdfdf;">
                        </i>
                      </div>
                    </div>
                    <div class="col-md-4" style="float: right;padding:  0;/* display:  block; */margin-top: 6%;">
                      <h2 class="m-0 counter" style="font-size: 21px;">{{ $administrationController->countAllCredits() }}</h2>
                      <p class="widget-box-info">Kreditů v oběhu
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
