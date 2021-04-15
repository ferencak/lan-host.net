@extends('master')

<?php 
    \App\Http\Controllers\ClientController::log($lang->fromFile('general', 'redirect_action') . $lang->get('send->page_name') . ".");
    $creditController = new \App\Http\Controllers\CreditController();
?>

@section('app_title', str_replace("'", "", $lang->get('send->page_name')))

@section('section')

<div class='container pb50  pt50-md pt100'>
    <div class='row'>

        @include('includes.client_menu')
            
        <div class="col-lg-9 col-md-12 col-sm-12 client-panel">
            <?php  
                if(isset($_POST['send'])){
                    $creditController->send($_POST);
                }
            ?>
            <div class="card">
                <div class="card-header" style="background-color: transparent;">
                    {{ str_replace("'", "", $lang->get('send->page_name')) }}
                    <div class="float-right pull-right">
                        <i class="fa fa-share"></i>
                    </div>
                </div>
                <form action="" method="POST">
                {{ Form::token() }}
                <div class="card-body">
                    <div class="row mb20">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>* Emailová adresa příjemce</label>
                                    <input type="email" name="receiver_email" class="form-control" placeholder="Zadejte emailovou adresu příjemce..." required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>* Počet kreditů k odeslání</label>
                                    <input type="number" min="25" max="600" name="client_creditnum" class="form-control" placeholder="Zadejte počet kreditů..." required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="send" class="btn btn-outline-light label-big text-center to-center col-md-10 col-lg-4 font-100">PŘEPOSLAT KREDITY</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection