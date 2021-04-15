@extends('master')

<?php
\App\Http\Controllers\ClientController::log($lang->fromFile('general', 'redirect_action') . $lang->get('support->support_solve->page_name') . $id . ".");

$administrationController = new \App\Http\Controllers\AdministrationController();

if(empty(\App\Support::findOrFail($id))){
  header('Location: /administraton/support');
  exit();
}

$ticket = $supportController->get($id);
$ticket_data = json_decode($supportController->get($id)->data, false);
?>

@section('app_title', $lang->get('support->title')) 

@section('section')

<div class='container pb50  pt50-md pt100'>
  <div class='row'>

    @include('includes.administration_menu')

    <div class="col-lg-9 col-md-12 col-sm-12 client-panel">
      <div class="float-right panel-btn-right" style="top: -18px!important">{{ $supportController->getStatus($id, "print", "support") }}</div>
      @if($ticket_data->ticket_data->server != "0")
      <div class="float-left panel-btn-left" style="top: -18px!important"><a class="label label-success label-small" target="_blank" href="/client/service/{{ $ticket_data->ticket_data->server }}">Server: TEST (#{{ $ticket_data->ticket_data->server }})</a></div>
      @endif
      <div class="card">
        <div class="card-header" style="background-color: transparent;">
          Řešení tiketu (#{{ $id }}) - {{ $ticket_data->ticket_data->name }}
          <div class="float-right pull-right">
            <form method="POST">
              {{ Form::token() }}
              <select class="form-control" onchange="this.form.submit()" name="update_status" style="height: 32px!important;margin-left: -17%;margin-top: -5px;position: absolute;width: 133px;font-size: 10px;">
                <option selected disabled>Aktualizovat stav</option>
                <option value="closed">Uzavřít tiket</option>
                <option value="in_solution">V řešení</option>
                <option value="solved">Vyřešit tiket</option>
              </select>
            </form>
          </div>
        </div>
        <div class="card-body">
          <?php
            if(isset($_POST['reply_submit'])){
              $supportController->addReply($id, $_POST, "support");
            }
            if(isset($_POST['update_status'])){
              $supportController->updateStatus($id, $_POST['update_status']);
            }
          ?>
          <form action="" method="POST">
          {{ Form::token() }}
            <div class="form-group col-md-12 row">
              <textarea class="form-control" name="reply_message" placeholder="Napište odpověď tiketu" rows="5" style="max-width: 85%;"></textarea>
              {{ Form::submit('Odeslat', 
                      ['name' => 'reply_submit',
                      'class' => 'btn btn-block btn-dark', 
                      'style' => 'max-width: 15%;float: right;max-height: fit-content;position: relative;left: 3%;']) }}
            </div>
          </form> 
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
