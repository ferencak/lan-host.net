@extends('master')

<?php 
    \App\Http\Controllers\ClientController::log($lang->fromFile('general', 'redirect_action') . $lang->get('order->page_name') . ".");
    if($selectedService != null) {
        if(!$orderableController->checkService($selectedService)) {
            header('Location: /'); 
            exit();
        }
    }

    if($selectedService == null) {
        $orderStep = 1;
    } elseif($selectedService != null) {
        $orderStep = 2;
    }

    $step = $orderableController->getOrderStep($orderStep);

?>

@section('app_title', $step)

@section('section')

<div class='container pb50  pt50-md pt100'>
    <div class='row'>
        
        <div class="col-lg-12 col-md-12 col-sm-12 client-panel" style="margin-top:0px!important;">
            <?php

            if(isset($_POST['check_order'])){

                $orderResponse = explode('|', $orderableController->create($_POST, $selectedService));

                if($orderResponse[0] == 'success'){
                    echo throw_alert($lang->fromFile('alert', 'success'), $lang->get('order->service_created'), 'success');
                    redirect_to('/client/service/' . $orderResponse[1], 3);

                } elseif($orderResponse[0] == 'error'){
                    echo throw_alert($lang->fromFile('alert', 'danger'), $lang->get('order->service_create_fail') . $orderResponse[1], 'danger');

                } elseif($orderResponse[0] == 'unknown'){
                    echo throw_alert($lang->fromFile('alert', 'danger'), $lang->fromFile('errors', 'unknown_error'), 'danger');

                }

            }

            ?> 
            <div class="card">
                <div class="card-header" style="background-color: transparent;">
                    <?php

                        if($orderStep == 2) 
                            echo '<span class="label label-out-blue label-small mr10">'. ucfirst($selectedService) .'</span> <span class="step-text-middle">' . $step . '</span>';
                        else
                            echo $step;

                    ?>
                    <div class="float-right pull-right">
                        <span class="label label-out label-small">{{ ucfirst($lang->fromFile('general', 'step')) . " " .  $orderStep }}/2</span>
                    </div>
                </div>
                <div class="card-body">

                    @include('/sections/order/service_order_'.$orderStep)

                </div>
            </div>

        </div>

    </div>
</div>
@endsection