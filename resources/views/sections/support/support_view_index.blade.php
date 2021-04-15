@extends('master')

<?php 

\App\Http\Controllers\ClientController::log($lang->get('log'));

?>

@section('app_title', $lang->get("app_name")) 

@section('section')

<div class='container pb50 pt50-md pt100'>
  <div class='row'>
    <div class="col-lg-12 col-md-12 col-sm-12 client-panel">
      <a href="/support/create" class="label label-danger label-small float-right panel-btn-right">{{ $lang->get('new_ticket') }}</a>   
      <div class="card">
        <div class="card-header" style="background-color: transparent;">
          {{ $lang->get('app_name') }}
          <div class="float-right pull-right">
            <i class="fa fa-support"></i>
          </div>
        </div>
        <div class="card-body">
          <div class="card-body">
            <div class="row">
              <table class="table">
                <thead>
                  <tr>
                    <th>{{ $lang->get('tables->id') }}</th>
                    <th>{{ $lang->get('tables->ticket_name') }}</th>
                    <th>{{ $lang->get('tables->last_reply') }}</th>
                    <th>{{ $lang->get('tables->status') }}</th>
                    <th>{{ $lang->get('tables->actions') }}</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $data = array();
                    foreach($supportController->getTickets() as $ticket){
                      $data = json_decode($ticket->data);
                      print('<tr>
                        <th scope="row">#'.$ticket->id.'</th>
                        <td>'.$data->ticket_data->name.'</td>
                        <td>'.$supportController->getLastAnswer($ticket->id).'</td>
                        <td>'.$supportController->getStatus($ticket->id).'</td>
                        <td><a href="/support/solve/'.$ticket->id.'" style="margin-left:15%;"><i class="fas fa-pencil-alt"></i></a></td>
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
</div>
@endsection
