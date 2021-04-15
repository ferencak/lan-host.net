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
          Uživatelé
          <div class="float-right pull-right">
            <i class="fa fa-users">
            </i>
          </div>
        </div>

        <div class="card-body">
            <table class="table table-responsive">
                <thead>
                    <tr class="tr-light">
                        <th>#ID</th>
                        <th>Křestní jméno</th>
                        <th>Přijmění</th>
                        <th>Email</th>
                        <th>Počet kreditů</th>
                        <th>Možnosti</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($administrationController->getClients() as $client)

                        <?php 
                            $client = (array) $client; 
                            $client_data = json_decode($client['data'], true);
                        ?>

                    <tr>
                        <td><a href="/administration/client/{{ $client['id'] }}" style="color: #86939e">#{{ $client['id'] }}</a></td>
                        <td><a href="/administration/client/{{ $client['id'] }}" style="color: #86939e">{{ $client_data['billing_info']['first_name'] }}</a></td>
                        <td><a href="/administration/client/{{ $client['id'] }}" style="color: #86939e">{{ $client_data['billing_info']['last_name'] }}</a></td>
                        <td><a href="/administration/client/{{ $client['id'] }}" style="color: #86939e">{{ $client_data['client_info']['email'] }}</a></td>
                        <td><a href="/administration/client/{{ $client['id'] }}" style="color: #86939e">{{ $client_data['client_data']['balance'] }} Kč </a></td>
                        <td><i class="fa fa-cogs"></i></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
