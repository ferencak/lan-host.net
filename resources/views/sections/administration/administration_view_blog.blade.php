@extends('master')

<?php 

\App\Http\Controllers\ClientController::log($lang->fromFile('general', 'redirect_action') . $lang->get('page_name') . "."); 

?>

@section('app_title', str_replace("'", "", $lang->get('page_name')))

@section('section')

<div class='container pb50  pt50-md pt100'>
  <div class='row'>

    @include('includes.administration_menu')

    <div class="col-lg-9 col-md-12 col-sm-12 client-panel">
      <a href="/administration/blog/create" class="label label-info label-small float-right panel-btn-right">Vytvorit nový blog</a>
      <div class="card">
        <div class="card-header" style="background-color: transparent;">
          Blog
          <div class="float-right pull-right">
            <i class="fa fa-newspaper">
            </i>
          </div>
        </div>
        <div class="card-body">
          <div class="container">
            <div class="row">

              @foreach($blogController->list($limit) as $id => $blog)
              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="margin-bottom:30px;max-height: 140px;">
                <div class="hovereffect" onclick="location.href='/client/services/order/minecraft';">
                  <img class="img-responsive" src="/images/order/minecraft.jpg" alt="">
                  <div class="overlay">
                    <h2>{{ $blogController->data($id + 1, 'title') }}</h2>
                  </div>
                </div>
              </div>
              @endforeach

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
