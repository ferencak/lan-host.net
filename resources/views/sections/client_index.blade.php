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
            <div class="card">
                <div class="card-header" style="background-color: transparent;">
                    {{ str_replace("'", "", $lang->get('page_name')) }}
                    
                    <div class="float-right pull-right">
                        <i class="fa fa-home"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-header" style="background-color: transparent;padding-left: 0px;">
                        {{ $lang->get('panel->content->section_legend->title') }}
                        <div class="float-right pull-right">
                            <i class="fa fa-info"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="list-group-item-menu-icon text-center"><i class="fa fa-plus"></i></div> <span class="label label-danger label-small">{{ $lang->get('panel->content->section_legend->content->label_add') }}</span><br/>
                                <div class="list-group-item-menu-icon text-center"><i class="fa fa-trash"></i></div> <span class="label label-danger label-small">{{ $lang->get('panel->content->section_legend->content->label_remove') }}</span><br/>
                                <div class="list-group-item-menu-icon text-center"><i class="fa fa-search"></i></div> <span class="label label-danger label-small">{{ $lang->get('panel->content->section_legend->content->label_search') }}</span><br/>
                                <div class="list-group-item-menu-icon text-center"><i class="fa fa-info"></i></div> <span class="label label-danger label-small">{{ $lang->get('panel->content->section_legend->content->label_info') }}</span>
                            </div>
                            <div class="col-md-3">
                                <div class="list-group-item-menu-icon text-center"><i class="fa fa-play"></i></div> <span class="label label-danger label-small">{{ $lang->get('panel->content->section_legend->content->label_start') }}</span><br/>
                                <div class="list-group-item-menu-icon text-center"><i class="fa fa-stop"></i></div> <span class="label label-danger label-small">{{ $lang->get('panel->content->section_legend->content->label_stop') }}</span><br/>
                                <div class="list-group-item-menu-icon text-center"><i class="fa fa-refresh"></i></div> <span class="label label-danger label-small">{{ $lang->get('panel->content->section_legend->content->label_restart') }}</span><br/>
                            </div>
                        </div>
                    </div>
                    <div class="card-header" style="background-color: transparent;padding-left: 0px;">
                        {{ $lang->get('panel->content->section_info->title') }}
                        <div class="float-right pull-right">
                            <i class="fa fa-info"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive table-striped table-hover">
                            <thead>
                                <th style="border-top: 0px!important;width: 20%;font-weight: 500;padding-left: 0;">{{ $lang->get('panel->content->section_info->content->table->date') }}</th>
                                <th style="border-top: 0px!important;width:70%;font-weight: 500;">{{ $lang->get('panel->content->section_info->content->table->title') }}</th>
                                <th style="border-top: 0px!important;font-weight: 500;padding-left: 21px">{{ $lang->get('panel->content->section_info->content->table->action') }}</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>27.9.2017</td>
                                    <td>Testovací informace...</td>
                                    <td style="text-align: center;"><a href=""><i class="fa fa-newspaper-o"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row" style="bottom: 0">
                        <div class="col-md-12">
                            <p class="float-left" style="
                                font-size: 11px;
                                ">{{ $lang->get('panel->content->section_info->stable_version') }} : <span class="" style="
                                    font-size: 10px;
                                    font-weight: 500;
                                ">{{ getDates() }}</span></p> &nbsp
                                <p class="col-md-6 float-right text-right" style="
                                    font-size: 11px;
                                    ">{{ $lang->get('panel->content->section_info->last_revision') }}: <span class="" style="
                                        font-size: 10px;
                                        font-weight: 500;
                                        ">
                                        <?php
                                        $mostRecentFilePath = "";
                                        $mostRecentFileMTime = 0;
                                        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("/var/www/LAN-HOST.NET/lan-host"), RecursiveIteratorIterator::CHILD_FIRST);
                                        foreach ($iterator as $fileinfo) {
                                        if ($fileinfo->isFile() && strpos($fileinfo->getPath(), "/var/www/LAN-HOST.NET/lan-host/storage/") === false && strpos($fileinfo->getPath(), "/var/www/LAN-HOST.NET/lan-host/public/") === false) {
                                        if ($fileinfo->getMTime() > $mostRecentFileMTime) {
                                        $mostRecentFileMTime = $fileinfo->getMTime();
                                        $mostRecentFilePath = $fileinfo->getPathname();
                                        }
                                        }
                                        }
                                        echo date('d.m.Y', $mostRecentFileMTime);
                                        ?>
                                    </span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection