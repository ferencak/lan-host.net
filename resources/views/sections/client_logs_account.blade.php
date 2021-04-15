@extends('master')


<?php 
    \App\Http\Controllers\ClientController::log($lang->fromFile('general', 'redirect_action') . $lang->get('page_name') . ".");
?>

@section('app_title', str_replace("'", "", $lang->get('page_name')))

@section('section')


<div class='container pb50  pt50-md pt100'>
    <div class='row'>

        @include('includes.client_menu')
            
        <div class="col-lg-9 col-md-12 col-sm-12 client-panel">
            @if($limit != '*')
                <a href="/client/logs/account/all" class="label label-info label-small float-left panel-btn-left">{{ $lang->get('panel->buttons->show_all') }}</a>
            @else
                <a href="/client/logs/account" class="label label-info label-small float-left panel-btn-left">{{ $lang->get('panel->buttons->show_last_ten') }}</a> 
            @endif
            <a href="/client/logs/account/clear" class="label label-danger label-small float-right panel-btn-right">{{ $lang->get('panel->buttons->delete_records') }}</a>
            <div class="card">
                <div class="card-header" style="background-color: transparent;">
                    {{ $lang->get('panel->account_logs') }} @if($limit != '*') ({{ $lang->get('panel->rows_count') }} {{ $limit }}) @endif
                    <div class="float-right pull-right">
                        <i class="fa fa-list-ol"></i>
                    </div>
                </div>
                <div class="card-body">

                    <table class="table table-responsive table-striped table-hover">
                        <thead>
                            <th style="border-top: 0px!important;width: 30%;font-weight: 500;">{{ $lang->get('panel->tables->execute_date') }}</th>
                            <th style="border-top: 0px!important;width:60%;font-weight: 500;">{{ $lang->get('panel->tables->execute_action') }}</th>
                            <th style="border-top: 0px!important;font-weight: 500;padding-left: 12px">IP</th>
                        </thead>
                        <tbody>
                            {!! $clientController->getLogs($limit) !!}
                        </tbody>
                    </table>

                </div>
            </div>

        </div>



    </div>
</div>
@endsection