@extends('master')

<?php 
    \App\Http\Controllers\ClientController::log($lang->fromFile('general', 'redirect_action') . $lang->get('purchase->page_name') . ".");
?>

@section('app_title', str_replace("'", "", $lang->get('purchase->page_name')))

@section('section')

<div class='container pb50  pt50-md pt100'>
    <?php  
        if($message = Session::get('error')){
          echo throw_alert('Nastala chyba!', 'Nákup kreditů byl zrušen.', 'danger');
          Session::forget('error');
        } elseif($message = Session::get('success')) {
          echo throw_alert('Úspěch!', 'Nákup kreditů byl úspěšně ukončen. Kredity Vám byly přičteny.', 'success');
          Session::forget('success');
        }

        if(isset($_POST['credit_buy'])){
            $creditController->order($_POST);
        }
    ?>

    <div class='row'>
       <div class="col-lg-12 col-md-12 col-sm-12 client-panel" style="margin-top:0px!important;">
          <div class="card">
             <div class="card-header" style="background-color: transparent;">
                {{ str_replace("'", "", $lang->get('purchase->page_name')) }}
             </div>
             <div class="card-body">
                <div class="tabs-default tabs-icon">
                   <div class="tab-content text-center">
                      <div role="tabpanel" class="tab-pane fade active show" id="tb6" aria-expanded="true">
                         <div class="container">

                            <div class="row">
                               <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12"  style="margin-bottom:30px;max-height: 140px;top: 15px;">
                                  <div class="hovereffect-gateway">
                                     <img class="img-responsive" src="https://freepngimg.com/download/paypal/2-2-paypal-logo-transparent-png.png" alt="">
                                     <div class="overlay-purchase select-gateway" data-gateway="PayPal">
                                        <h2 style="background: rgba(0, 0, 0, 0.42);top: 33%;">PayPal</h2>
                                     </div>
                                  </div>
                               </div>

                               <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="margin-bottom:30px;max-height: 140px;top: 15px;">
                                  <div class="hovereffect-gateway">
                                     <img class="img-responsive" src="https://extensions.arastta.pro/image/cache/sellers/141/g2apay_detail-900x500.png" alt="">
                                     <div class="overlay-purchase select-gateway" data-gateway="g2a">
                                        <h2 style="background: rgba(0, 0, 0, 0.42);top: 33%;">G2A PAY</h2>
                                     </div>
                                  </div>
                               </div>

                               <div class="gateway-pay col-md-12">
                                  <hr/>
                                  <div class="PayPal-info" style="display: none;margin-top: 2.5%;">
                                     <div class="row">
                                        <div class="col-md-12">
                                           <div class="col-md-6 float-left">
                                              <div class="form-group">
                                                 <div class="mb10" style="background-color: transparent;padding-left: 0px;text-align: left;font-size:13px">
                                                    <b style="border-bottom: 1px solid #dfdfdf">Základní ustanovení</b><br/><br/>
                                                    <b style="border-bottom: 1px solid #6cc3f570">Kredit</b> = Virtuální peněžní částka sloužící k platbě či nákupu služeb.<br/><br/>
                                                    Platba bude uskutečněna pomocí služby PayPal a kredity budou přičteny po úspěšném dokončení transakce.<br/>
                                                    Žádost o vrácení uhrazené částky lze podat ve lhůtě 2 dnů od úspěšného zakoupení kreditů. <br/>(Podmínky pro vrácení uhrazené částky naleznete v sekci <a href="">Všeobecné obchodní podmínky</a>)<br/><br/>
                                                    Konečná cena nákupu kreditů je včetně DPH.<br/><br/>
                                                 </div>
                                              </div>
                                           </div>
                                           <form action="/client/credits/purchase/paypal" method="POST">
                                            {{ Form::token() }}   
                                            <input type="hidden" name="gateway" class="gateway-input">
                                            <div class="col-md-6 float-right">
                                               <div class="form-group">
                                                  <div class="mb10" style="background-color: transparent;padding-left: 0px;text-align: left;font-size:13px; margin-top: 37px;">
                                                     <div class="col-md-12">
                                                        <div class="form-group" style="padding-bottom: 0px">
                                                           <label>Částka kreditu (Minimálně 25 kreditů)</label>
                                                           <input type="number" value="25" name="credits_num" id="credits_num" min="25" max="90000" class="form-control" placeholder="Zadejte velikost částky.." required>
                                                        </div>
                                                        <hr style="margin-bottom: -15px" />
                                                        <hr style="margin-bottom: 5px"/>
                                                        <span style="font-weight: 400;text-transform: uppercase;">Zúčtování</span>
                                                        <p style="display: flow-root;border-bottom: 2px dotted #eee;margin-top: 11px;margin-bottom: 4px;">Částka k zaplacení (10% sazba)<span style="border-bottom: 1px solid #eee;height: 5px;"></span> <span id="span_pay_money" class="float-right" style="text-align: right;">28 KČ</span></p>
                                                        <p style="display: flow-root;border-bottom: 2px dotted #eee;">Obdržíte kreditů <span style="border-bottom: 1px solid #eee;height: 5px;"></span> <span id="span_receive_credits" class="float-right" style="text-align;">25</span></p>
                                                        <button type="submit" name="credit_buy" class="btn btn-outline-dark float-right">Přejít k zaplacení <i class="fab fa-paypal" style="font-size: 11px;margin-left: 5px;"></i></button>
                                                     </div>
                                                     <br/>
                                                  </div>
                                               </div>
                                            </div>
                                        </div>
                                     </div>
                                  </div>

                                  <div class="g2a-info"  style="display: none;    margin-top: 2.5%;">
                                     <div class="row">
                                        <div class="col-md-12">

                                           <div class="col-md-6 float-left">
                                              <div class="form-group">
                                                 <div class="mb10" style="background-color: transparent;padding-left: 0px;text-align: left;font-size:13px">
                                                    <b style="border-bottom: 1px solid #dfdfdf">Základní ustanovení</b><br/><br/>
                                                    <b style="border-bottom: 1px solid #6cc3f570">Kredit</b> = Virtuální peněžní částka sloužící k platbě či nákupu služeb.<br/><br/>
                                                    Platba bude uskutečněna pomocí služby PayPal a kredity budou přičteny po úspěšném dokončení transakce.<br/>
                                                    Žádost o vrácení uhrazené částky lze podat ve lhůtě 2 dnů od úspěšného zakoupení kreditů. <br/>(Podmínky pro vrácení uhrazené částky naleznete v sekci <a href="">Všeobecné obchodní podmínky</a>)<br/><br/>
                                                    Konečná cena nákupu kreditů je včetně DPH.<br/><br/>
                                                 </div>
                                              </div>
                                           </div>

                                           <div class="col-md-6 float-right">
                                              <div class="form-group">
                                                 <div class="mb10" style="background-color: transparent;padding-left: 0px;text-align: left;font-size:13px">
                                                    <b style="border-bottom: 1px solid #dfdfdf">Základní ustanovení</b><br/><br/>
                                                    <b style="border-bottom: 1px solid #6cc3f570">Kredit</b> = Virtuální peněžní částka sloužící k nákupu služeb.<br/><br/>
                                                    Platba bude uskutečněna pomocí služby PayPal a kredity budou přičteny po úspěšném dokončení transakce.<br/>
                                                    Žádost o vrácení uhrazené částky lze podat ve lhůtě 2 dnů od úspěšného zakoupení kreditů. <br/>(Podmínky pro vrácení uhrazené částky naleznete v sekci <a href="">Všeobecné obchodní podmínky</a>)<br/><br/>
                                                    Konečná cena nákupu kreditů je včetně DPH.<br/><br/>
                                                 </div>
                                              </div>
                                           </div>

                                        </div>
                                     </div>
                                  </div>
                                  <script type="text/javascript" src="/js/credits.js?ver={{time()}}"></script>
                               </div>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
</div>
@endsection