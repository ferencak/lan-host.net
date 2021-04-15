@extends('master')

<?php 
    \App\Http\Controllers\ClientController::log($lang->fromFile('general', 'redirect_action') . $lang->get('overview->page_name') . ".");
?>

@section('app_title', str_replace("'", "", $lang->get('overview->page_name')))

@section('section')

<div class='container pb50  pt50-md pt100'>
    <div class='row'>

        @include('includes.client_menu')
            
        <div class="col-lg-9 col-md-12 col-sm-12 client-panel">
            @if($limit != '*')
            <a href="/client/credits/overview/all" class="label label-info label-small float-left panel-btn-left">{{ $lang->fromFile('client_credits', 'transactions->show_all') }}</a>
            @else
            <a href="/client/credits/overview" class="label label-info label-small float-left panel-btn-left">{{ $lang->fromFile('client_credits', 'transactions->show_last_five') }}</a>
            @endif
            <div class="card">
                <div class="card-header" style="background-color: transparent;">
                    {{ str_replace("'", "", $lang->get('overview->page_name')) }} @if($limit != '*') {{ $lang->fromFile('client_credits', 'transactions->last_five') }} @endif
                    <div class="float-right pull-right">
                        <i class="fa fa-share"></i>
                    </div>
                </div>

                <span class="col-md-12 graph-text-right">{{ $lang->fromFile('client_credits', 'transactions->last_seven_days') }}</span>
                <canvas class="my-4 w-100 col-md-12" id="transactions" width="900" height="380"></canvas>
                <table class="table table-responsive col-md-12" style="    border-collapse: unset!important;">
                    <thead>
                        <tr class="tr-light" style="border-collapse:unset!important">
                            <th class="tr-light-item-first" style="width: 30%">Datum</th>
                            <th class="tr-light-item">Akce</th>
                            <th class="tr-light-item" style="padding-left: 9px;width: 10%;">Částka</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Http\Controllers\TransactionController::get($limit) as $transaction)
                            <tr>
                                <td>{{ $transaction->time }} </td>
                                <td>{{ $transaction->action }} </td>
                                <td style="text-align: center;">{!! strstr($transaction->amount, '-') ? '<span style="color:red">' . $transaction->amount . '</span>' : '<span style="color:#62b362">+' . $transaction->amount . '</span>' !!} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <script>
                    $(document).ready(function(){
                        var transactionList = [];
                        $.ajax({
                            url: 'https://lan-host.net/api/getTransactions/{{$limit}}',
                            type: 'GET',
                            async: false
                        }).done(function(callback){
                            for (var i=0; i<callback.length; i++) {
                                transactionList.push(callback[i]);
                            }
                        });
                        transactionList.reverse();
                        var transactions = document.getElementById("transactions");
                        console.log(transactionList.map(r => r.time));
                        Chart.defaults.global.defaultFontColor = '#86939a';  
                        Chart.defaults.global.defaultFontFamily = '"Montserrat", sans-serif';  

                        var myChart = new Chart(transactions, {
                            type: 'line',
                            data: {
                              labels: transactionList.map(r => r.time),
                              datasets: [{
                                fill: false,
                                data: transactionList.map(r => r.amount),
                                lineTension: 0,
                                borderColor: '#47B4F3',
                                borderWidth: 4,
                                pointBackgroundColor: '#47B4F3',
                                                                color: [
                                'red',
                                'blue',  
                                'green', 
                                'black',
                                ],
                              }]
                            },
                            options: {
                                plugins:{
                                    datalabels: {
                                        color: function(context) {
                                            var index = context.dataIndex;
                                            var value = context.dataset.data[index];
                                            return value < 0 ? 'red' :  // draw negative values in red
                                            index % 2 ? 'blue' :    // else, alternate values in blue and green
                                            'green';
                                        }
                                    }
                                },
                              scales: {
                                xAxes: [{
                                  ticks: {
                                    beginAtZero: false,
                                    minRotation: 50
                                  }
                                }]
                              },
                              legend: {
                                display: false,
                              }
                            }


                        });
                    });
                  
                </script>
            </div>
        </div>
    </div>
</div>
@endsection