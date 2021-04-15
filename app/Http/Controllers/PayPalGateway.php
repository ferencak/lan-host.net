<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
/** All Paypal Details class **/
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Redirect;
use Session;
use URL;
use Store;

class PayPalGateway extends Controller
{

    private $_api_context;

    /**
    * Prepare PayPalGateway class
    *
    * @param Request $request
    * @return PayPalGateway->bake
    */
    public function prepare()
    {

        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);

        return $this;

    }

    /**
    * Bake and make
    *
    * @param Request $request
    * @return Redirect
    */
    public function bake(Request $request)
    {

        Session::put('credits', $request->get('credits_num'));

        $total = round((10 / 100) * $request->get('credits_num')) + $request->get('credits_num');
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName('Dobití kreditu (' . $request->get('credits_num') . ' kreditů)')->setCurrency('CZK')->setQuantity(1)->setPrice($total);
        $item_list = new ItemList();
        $item_list->setItems(array(
            $item_1
        ));
        $amount = new Amount();
        $amount->setCurrency('CZK')->setTotal($total);
        $transaction = new Transaction();
        $transaction->setAmount($amount)->setItemList($item_list);
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('client/credits/purchase/paypal/check'))->setCancelUrl(URL::to('client/credits/purchase/paypal/check'));
        $payment = new Payment();
        $payment->setIntent('Sale')->setPayer($payer)->setRedirectUrls($redirect_urls)->setTransactions(array(
            $transaction
        ));
        try {
            $payment->create($this->_api_context);
        }

        catch(PayPalExceptionPPConnectionException $ex) {
            if(Config::get('app.debug')) {
                Session::put('error', 'Connection timeout[');
                return Redirect::to('/');
            } else {
                Session::put('error', 'Some error occur, sorry for inconvenient');
                return Redirect::to('/');
            }
        }

        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        Session::put('paypal_payment_id', $payment->getId());

        if(isset($redirect_url)) {
            return redirect_to($redirect_url, 0);
        }

        Session::put('error', 'Unknown error occurred');
        return redirect()->back();

    }

    public function check()
    {
        $this->prepare();
        $payment_id = Session::get('paypal_payment_id');
        // Session::forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
 
            \Session::put('error', 'Payment failed');
            return redirect('/client/credits/purchase');
 
        }
 
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));
    
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        if($result->getState() == 'approved') {
 
            \Session::put('success', 'Payment success');
            CreditController::add();
            $message = "
            <center>
            <img src='http://80.211.216.219/sharex/images-uploaded/mspaint_2018-10-19_19-19-07.png' style='width:250px;height:250px;'>
            </center><br/>
            <h1 style='text-align: center; font: 15px/1.2 'Robotica', sans-serif;'>Úspěšné zakoupení kreditů !</h1><br/>
            Dobrý den, <br/><br/>
            <p>Na váš účet bylo přičteno " . Session::get('credits') . " kreditů<br/>
            Děkujeme, lan-host.net<br/>
            Copyright © " . date("Y") . " lan-host.net";
            MailController::send([
                'subject' => 'Zakoupení kreditů',
                'text' => $message,
                'receiver_email' => 'whistikjegod@seznam.cz',
                'receiver_name' => 'XD'
            ]);
            return redirect('/client/credits/purchase');
        }
 
        \Session::put('error', 'Payment failed');
        return redirect('/client/credits/purchase');
 
    }
}