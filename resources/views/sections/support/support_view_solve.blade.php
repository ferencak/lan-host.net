@extends('master')

<?php 

\App\Http\Controllers\ClientController::log("Přechod na stránku 'Řešení tiketu - #".$id."'.");

if(empty(\App\Support::findOrFail($id))){
  header('Location: /support');
  exit();
}


if(!\App\Support::where([['data->ticket_data->creator', '=', \App\Http\Controllers\ClientController::getId()], ['id', '=', $id]])->firstOrFail()){
  header('Location: /support');
  exit();
}

$ticket = $supportController->get($id);
$ticket_data = json_decode($supportController->get($id)->data, false);

?>

@section('app_title', 'Řešení tiketu - #'.$id) 

@section('section')

<div class='container pb50 pt50-md pt100'>
  <div class='row'>
    <div class="col-lg-12 col-md-12 col-sm-12 client-panel"> 
      <div class="float-right panel-btn-right" style="top: -18px!important">{{ $supportController->getStatus($id, "print") }}</div>
      <div class="card">
        <div class="card-header" style="background-color: transparent;">
          Řešení ticketu (#{{ $id }}) - {{ $ticket_data->ticket_data->name }}
          <div class="float-right pull-right">
            <i class="fa fa-support"></i>
          </div>
        </div>
        <div class="card-body">
          @if($ticket_data->ticket_status->status != "closed")
          <?php
            if(isset($_POST['reply_submit'])){
              $supportController->addReply($id, $_POST, "client");
            }
          ?>
          <form action="" method="POST">
          {{ Form::token() }}
            <div class="form-group col-md-12 row">
              <textarea class="form-control" name="reply_message" placeholder="Napište odpověď tiketu" rows="8" style="max-width: 85%;"></textarea>
              {{ Form::submit('Odeslat', 
                      ['name' => 'reply_submit',
                      'class' => 'btn btn-block btn-dark', 
                      'style' => 'max-width: 15%;float: right;max-height: fit-content;position: relative;left: 3%;']) }}
            </div>
          </form>
          @endif
          <ul class="cbp_tmtimeline">
            <?php
              foreach($supportController->getReplies($id) as $reply){
                $data = json_decode($reply->data);
                $client = json_decode($administrationController->getClientByID($data->reply_data->creator)->data, true);
                if($data->reply_data->from == "support"){
                  print('<li>
                           <div class="cbp_tmicon-support fa fa-user-circle"></div>
                           <div class="cbp_tmlabel-support" style="margin-bottom: 25px;">
                              <p>'.ucfirst($data->reply_data->message).'</p>
                              <p class="reply-from">Podpora ('.$client['billing_info']['first_name'].' '.$client['billing_info']['last_name'].') '. $data->reply_data->time_created . '</p>
                           </div>
                        </li>');
                }else{
                  print('<li>
                           <div class="cbp_tmicon fa fa-user"></div>
                           <div class="cbp_tmlabel" style="margin-bottom: 25px;">
                            <p>'.ucfirst($data->reply_data->message).'</p>
                            <p class="reply-from">Zákazník ('.$client['billing_info']['first_name'].' '.$client['billing_info']['last_name'].') '. $data->reply_data->time_created . '</p>
                           </div>
                        </li>');
                }
              }
            ?>
            <li>
               <div class="cbp_tmicon fa fa-user"></div>
               <div class="cbp_tmlabel">
                <p>{{ ucfirst($ticket_data->ticket_data->text) }}</p>
                <p class="reply-from">Zákazník ({{ $clientController->get("billing_info", "first_name") }} {{ $clientController->get("billing_info", "last_name") }}) {{ $ticket_data->ticket_data->time_created }}</p>
               </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
