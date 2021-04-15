@extends('master')

<?php 

    \App\Http\Controllers\ClientController::log($lang->fromFile('general', 'redirect_action') . $lang->get('support->page_name') . "."); 

$administrationController = new \App\Http\Controllers\AdministrationController();

?>

@section('app_title', $lang->get('support->title')) 

@section('section')

<div class='container pb50  pt50-md pt100'>
  <div class='row'>

    @include('includes.administration_menu')

    <div class="col-lg-9 col-md-12 col-sm-12 client-panel">
      <div class="card">
        <div class="card-header" style="background-color: transparent;">
          Tikety
          <div class="float-right pull-right">
            <i class="fa fa-users">
            </i>
          </div>
        </div>
        <div class="card-body">
          <div class="card-body">
            <table class="table">
                <thead>
                  <tr>
                      <th>ID</th>
                      <th>Název tiketu</th>
                      <th>Vytvořil</th>
                      <th style="width: 25%;">Stav</th>
                      <th style="width: 5%">Možnosti</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach($supportController->getTickets("support") as $ticket){
                      $data = json_decode($ticket->data);
                      $client = json_decode($administrationController->getClientByID($data->ticket_data->creator)->data, true);
                      print('<tr>
                        <th scope="row">#'.$ticket->id.'</th>
                        <td>'.$data->ticket_data->name.'</td>
                        <td>'.$client['billing_info']['first_name'].' '.$client['billing_info']['last_name'].'</td>
                        <td>'.$supportController->getStatus($ticket->id, "return", "support").'</td>
                        <td style="padding-left: 4%;"><a href="/administration/support/solve/'.$ticket->id.'" style="margin-left:15%;"><i class="fas fa-pencil-alt"></i></a></td>
                    </tr>');
                    }
                  ?>
                </tbody>
              </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
