@extends('master')

<?php 

\App\Http\Controllers\ClientController::log($lang->fromFile('general', 'redirect_action') . $lang->get('support_create->page_name') . ".");
?>

@section('app_title', str_replace("'", "", $lang->get('support_create->page_name')))

@section('section')

<div class='container pb50 pt50-md pt100'>
  <div class='row'>
    <div class="col-lg-12 col-md-12 col-sm-12 client-panel">
      <div class="card">
        <div class="card-header" style="background-color: transparent;">
          {{ $lang->get('support_create->support') }}
          <div class="float-right pull-right">
            <i class="fa fa-support"></i>
          </div>
        </div>
        <div class="card-body">
          <div class="card-body">
            <?php
            if(isset($_POST['create_ticket'])){
              if(strlen($_POST['ticket_name']) <= 60){
                $supportController->createTicket($_POST);
              }else{
                echo throw_alert($lang->fromFile('alert', 'danger'), $lang->get('support_create->ticket_name_too_long'), 'danger');
              }

            }
            ?>
            <form action="" method="POST">
              {{ Form::token() }}
              <div class="col-md-12">
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-4">
                      <label>{{ $lang->get('support_create->ticket_name') }}</label>
                      <input type="text" name="ticket_name" max="60" class="form-control" placeholder="{{ $lang->get('support_create->ticket_name_placeholder') }}" required>
                    </div>
                    <div class="col-md-4">
                      <label>{{ $lang->get('support_create->ticket_issue') }}</label>
                      <select class="form-control" name="ticket_problem_type" required>
                        <optgroup label="Zákaznická podpora">
                          <option value="1_1">{{ $lang->get('support_create->user_support->job_request') }}</option>
                          <option value="2_1">{{ $lang->get('support_create->user_support->account_activation') }}</option>
                          <option value="3_1">{{ $lang->get('support_create->user_support->web_not_loading_properly') }}</option>
                          <option value="4_1">{{ $lang->get('support_create->user_support->cannot_change_user_details') }}</option>
                          <option value="5_1">{{ $lang->get('support_create->user_support->cannot_buy_credits') }}</option>
                          <option value="6_1">{{ $lang->get('support_create->user_support->cannot_buy_service') }}</option>
                          <option value="7_1">{{ $lang->get('support_create->user_support->sending_credits') }}</option>
                          <option value="8_1">{{ $lang->get('support_create->user_support->service_refund') }}</option>
                          <option value="9_1">{{ $lang->get('support_create->user_support->other_issue') }}</option>
                        </optgroup>
                        <optgroup label="Technická podpora">
                          <option value="1_2">{{ $lang->get('support_create->technical_support->service_backup') }}</option>
                          <option value="2_2">{{ $lang->get('support_create->technical_support->service_failed_at_creating') }}</option>
                          <option value="3_2">{{ $lang->get('support_create->technical_support->service_not_working') }}</option>
                          <option value="4_2">{{ $lang->get('support_create->technical_support->service_not_extending') }}</option>
                          <option value="5_2">{{ $lang->get('support_create->technical_support->connectivity_issue') }}</option>
                          <option value="6_2">{{ $lang->get('support_create->technical_support->setting_up_shared_control') }}</option>
                          <option value="7_2">{{ $lang->get('support_create->technical_support->forgotten_account_details') }}</option>
                          <option value="8_2">{{ $lang->get('support_create->technical_support->bug_report') }}</option>
                          <option value="9_2">{{ $lang->get('support_create->technical_support->other_issue') }}</option>
                        </optgroup> 
                      </select>
                    </div>
                    <div class="col-md-4">
                      <label>* Server</label>
                      <select class="form-control" name="ticket_server" required>
                        <option value="0" selected>{{ $lang->get('support_create->nothing') }}</option>
                        <?php
                        foreach($serviceController->getServices() as $service){
                          $data = json_decode($service->data);
                          print('<option value="'.$service->id.'">(#'.$service->id.') '.$data->service_info->name.'</option>');
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                    <label>{{ $lang->get('support_create->ticket_description') }}</label>
                    <textarea rows="10" name="ticket_text" class="form-control" placeholder="{{ $lang->get('support_create->ticket_description_placeholder') }}" required></textarea>
                </div>
              </div>
              <div class="col-md-6 mb20 float-right">
                  {{ Form::submit($lang->get('support_create->create_ticket'), 
                      ['name' => 'create_ticket',
                      'class' => 'btn btn-block btn-dark', 
                      'style' => 'max-width: 150px;float: right;']) }}
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
