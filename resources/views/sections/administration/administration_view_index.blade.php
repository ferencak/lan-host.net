@extends('master')

<?php 

\App\Http\Controllers\ClientController::log($lang->fromFile('general', 'redirect_action') . $lang->get('page_name') . ".");

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
          Základní informace
          <div class="float-right pull-right">
            <i class="fa fa-info">
            </i>

          </div>
        </div>

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
                      <h2 class="m-0 counter">{{ $administrationController->countOnlineUsers() }}</h2>
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
                      <p class="widget-box-info">Uživatelů
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
            Zapnout/Vypnout jazyk
            <label style="margin-top: 30px;" class="switch">
            <input type="checkbox" id="langonbtn">
            <span class="slider"></span>
        </label>

          <?php
            if(DB::table('settings')->where("property", '=', "lang")->get()[0]->value == 1) {
              echo '<script>$("#langonbtn").prop("checked", true);</script>';
            }
          ?>
          <script>
              $('#langonbtn').change(function() {
                if($(this).is(":checked")){
                  $.ajax({
                    url: 'https://lan-host.net/api-administrator/__set_lang__/1',
                    type: 'GET',
                    success: function(){
                      window.location.reload(1);
                    }
                  });
                 
                } else { 
                  $.ajax({
                    url: 'https://lan-host.net/api-administrator/__set_lang__/0',
                    type: 'GET',
                    success: function(){
                      window.location.reload(1);
                    }
                  });
                } 
              });
          </script>
            <div class="row" style="bottom: 0">
            </div>
            <div class="col-md-12">
              <p class="col-md-6 float-right text-right" style="font-size: 11px;">Poslední revize: <span class="" style="font-size: 10px;font-weight: 500;">
                      <?php
                      $mostRecentFilePath = "";
                      $mostRecentFileMTime = 0;
                      $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("/var/www/LAN-HOST.NET/lan-host"), RecursiveIteratorIterator::CHILD_FIRST);
                      foreach ($iterator as $fileinfo) {
                        if ($fileinfo->isFile() && strpos($fileinfo->getPath(), "/var/www/LAN-HOST.NET/lan-host/storage/") === false && strpos($fileinfo->getPath(), "/var/www/LAN-HOST.NET/lan-host/public/") === false)
                        {
                          if ($fileinfo->getMTime() > $mostRecentFileMTime)
                          {
                            $mostRecentFileMTime = $fileinfo->getMTime();
                            $mostRecentFilePath = $fileinfo->getPathname();
                          }
                        }
                      }
                      echo date('d.m.Y  |  H:i:s', $mostRecentFileMTime);
                      ?>
                  </span></p>
              </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
