<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Srmklive\PayPal\Services\ExpressCheckout;
use Srmklive\PayPal\Services\AdaptivePayments;

class CreditController extends Controller
{
    
    private $languageController;
    private $request;

    public function __construct()
    {

      $applicationController = new ApplicationController();
      $this->languageController = $applicationController->languageController;
      $this->languageController->page('client_credits');

    }

    /**
    * Send credits to user
    *
    * @param array $request
    */
    public function send($request)
    {

        $this->request = (object) $request;;

        $email = (array) json_decode($_SESSION['user']->data);
        $email = $email['client_info']->email;

        if(!is_numeric($this->request->client_creditnum) 
          || $this->request->client_creditnum < 25 
          || $this->request->client_creditnum > 600
          || $this->request->receiver_email == $email) { 
          echo redirect_to($_SERVER['REDIRECT_URL'], 3);
          return throw_alert($this->languageController->fromFile('alert', 'danger'), $this->languageController->get('controller->fields_wrong_format'), 'danger');
        }

        if(ClientController::get('client_data', 'balance') < $this->request->client_creditnum){ 
          return throw_alert($this->languageController->fromFile('alert', 'danger'), $this->languageController->get('controller->not_enough_credits'), 'danger');
        }

        if(!filter_var($this->request->receiver_email, FILTER_VALIDATE_EMAIL)){ 
          return throw_alert($this->languageController->fromFile('alert', 'danger'), $this->languageController->get('controller->email_wrong_format'), 'danger');
        }

        if(DB::table('clients')->where('data->client_info->email', '=', $this->request->receiver_email)->exists()) {
            DB::table('clients')->where('data->client_info->email', '=', $this->request->receiver_email)->update(['data->client_data->balance' => $this->getCredits($this->request->receiver_email) + $this->request->client_creditnum]);
            DB::table('clients')->where('data->client_info->email', '=', $email)->update(['data->client_data->balance' => ClientController::get('client_data', 'balance') - $this->request->client_creditnum]);
            $this->request->receiver = DB::table('clients')->where('data->client_info->email', '=', $this->request->receiver_email)->get()[0]->id;
            $this->request->sender = ClientController::getId();

            TransactionController::create($this->request->receiver, $this->request->client_creditnum, $this->languageController->get('transactions->actions->get_credits'));
            TransactionController::create($this->request->sender, $this->request->client_creditnum * -1, $this->languageController->get('transactions->actions->give_credits'));

            echo throw_alert($this->languageController->fromFile('alert', 'success'), $this->languageController->get('controller->credit_sent'), 'success');
        } else {
            return throw_alert($this->languageController->fromFile('alert', 'danger'), $this->languageController->get('controller->email_not_found'), 'danger');
        }

        ClientController::log(str_replace(['%1', '%2'], [$this->request->client_creditnum, $this->request->receiver_email], $this->languageController->get('log')));
        return redirect_to($_SERVER['REDIRECT_URL'], 3);
    }

    /**
    * Get user credits count
    *
    * @param email $email
    * @return int
    */
    public function getCredits($email)
    {

        return json_decode(DB::table('clients')->where('data->client_info->email', '=', $email)->get()[0]->data, true)['client_data']['balance'];

    }

    /**
    * Add credits to user
    *
    * @return int
    */
    public static function add()
    {

        DB::table('clients')->where('id', '=', ClientController::getId())->update(['data->client_data->balance' => ClientController::get('client_data', 'balance') + \Session::get('credits')]);
        TransactionController::create(ClientController::getId(), \Session::get('credits'), 'Nákup kreditů');

    }

     /**
    * Order credits
    *
    * @param Request $request
    */
    public function order(Request $request)
    {
        $gate = '\\App\\Http\\Controllers\\' . $request->get('gateway') . 'Gateway';

        if(!class_exists($gate))
            return redirect()->back();

        $gateway = new $gate();
        $gateway->prepare()->bake($request); 
                
     }

}