<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;

@session_start();
use Illuminate\Http\Request;
use DB;

class ApplicationController extends Controller
{

    public $language;
    public $languageController;
    /**
    * Constructor
    *
    * @return void
    */
    public function __construct()
    {

        $this->language = (isset($_SESSION['language']) ? $_SESSION['language'] : 'cs');
        $this->languageController = new LanguageController();
        $this->languageController->set($this->language);

        if(!isset($_SESSION['server_captcha']))
            $_SESSION['server_captcha'] = generateCaptcha();
        
    }

    /**
    * Construct an website page
    *
    * @return Illuminate\Support\Facades\View
    */
    public function view()
    {

        return view('sections.home')
            ->with('lang', $this->languageController->page('home'));

    }

    /**
    * Construct an login page
    *
    * @return Illuminate\Support\Facades\View
    */
    public function view_login($cancel = false)
    {
        if($cancel == true)
        {
            unset($_SESSION['verify']);
            unset($_SESSION['verify2fa']);
            @session_destroy();
            return redirect('/client/login/');
        }
        if(!isset($_SESSION['user']))
            return view('sections.login')
                ->with('clientController', new ClientController())
                ->with('lang', $this->languageController->page('login'));
        else
            return redirect('/');
    }

    /**
    * Construct an register page
    *
    * @return Illuminate\Support\Facades\View
    */
    public function view_register()
    {

        if(!isset($_SESSION['user']))
            return view('sections.register')
                ->with('clientController', new ClientController())
                ->with('captcha', $_SESSION['server_captcha'])
                ->with('lang', $this->languageController->page('register'));
        else
            return redirect('/');
        
    }


    /**
    * Construct an password reset page
    *
    * @return Illuminate\Support\Facades\View
    */
    public function view_password_reset($id = false, $token = false)
    {

        if(!isset($_SESSION['user']))
            return view('sections.password_reset')
                ->with('clientController', new ClientController())
                ->with('lang', new LanguageController())
                ->with('id', $id)
                ->with('token', $token);
        else
            return redirect('/');
        
    }

    /**
    * Construct an index page for client section
    *
    * @return Illuminate\Support\Facades\View
    */
    public function view_client_index()
    {

        if(isset($_SESSION['user']))
            return view('sections.client_index')
                ->with('clientController', new ClientController())
                ->with('lang', $this->languageController->page('client_index'));
        else
            return redirect('/client/login');

    }

    /**
    * Changes language
    *
    * @param string $lang
    * @return Illuminate\Support\Facades\View
    */
    public function view_set_lang($lang)
    {

        if(!isset($lang))
            return redirect()->back();

        $_SESSION['language'] = $lang;
        return redirect()->back();

    }


    /**
    * Clear all account logs 
    * 
    * @return Illuminate\Support\Facades\View
    */
    public function view_client_logs_account_clear()
    {

        if(isset($_SESSION['user'])){
            \App\Logs::where('client', '=', \App\Http\Controllers\ClientController::getId())->delete();
            \App\Http\Controllers\ClientController::log($this->languageController->fromFile('client_logs_account', 'all_records_removed'));
            return redirect()->back();
        }
        else{
            return redirect('/client/login');
        }

    }

    /**
    * Construct an account logs page for client section
    *
    * @return Illuminate\Support\Facades\View
    */
    public function view_client_logs_account()
    {

        if(isset($_SESSION['user']))
            return view('sections.client_logs_account')
                ->with('clientController', new ClientController())
                ->with('lang', $this->languageController->page('client_logs_account'))
                ->with('logs', \App\Logs::all())
                ->with('limit', 10);
        else
            return redirect('/client/login');

    }

    /**
    * Construct an account credit page for client section
    *
    * @return Illuminate\Support\Facades\View
    */
    public function view_client_credits_send()
    {

        if(isset($_SESSION['user']))
            return view('sections.client_credits_send')
                ->with('clientController', new ClientController())
                ->with('lang', $this->languageController->page('client_credits'));
        else
            return redirect('/client/login');

    }

    /**
    * Construct an account credit overview page for client section
    *
    * @return Illuminate\Support\Facades\View
    */
    public function view_client_credits_overview($limit = false)
    {

        if(isset($_SESSION['user']))
            return view('sections.client_credits_overview')
                ->with('clientController', new ClientController())
                ->with('creditController', new CreditController())
                ->with('limit', ($limit == true) ? '*' : 10 )
                ->with('lang', $this->languageController->page('client_credits'));
        else
            return redirect('/client/login');

    }

    /**
    * Construct an account credit payment gateway for client section
    *
    * @return Illuminate\Support\Facades\View
    */
    public function view_client_credits_purchase()
    {

        if(isset($_SESSION['user']))
            return view('sections.client_credits_purchase')
                ->with('clientController', new ClientController())
                ->with('creditController', new CreditController())
                ->with('lang', $this->languageController->page('client_credits'));
        else
            return redirect('/client/login');

    }

    /**
    * Construct an account credit payment gateway for client section
    *
    * @return Illuminate\Support\Facades\View
    */
    public function view_client_settings()
    {

        if(isset($_SESSION['user']))
            return view('sections.client_settings')
                ->with('clientController', new ClientController())
                ->with('lang', $this->languageController->page('client_settings'));
        else
            return redirect('/client/login');

    }

    /**
    * Construct an account credit payment gateway for client section
    *
    * @return Illuminate\Support\Facades\View
    */
    public function view_client_credits_purchase_paypal(Request $request)
    {

        if(isset($_SESSION['user'])){
            $creditController = new CreditController();
            return $creditController->order($request);
        }
        else{
            return redirect('/client/login');
        }

    }

    /**
    * Construct an check credit payment gateway for client section
    *
    * @return Illuminate\Support\Facades\View
    */
    public function view_client_credits_purchase_paypal_check()
    {

        if(isset($_SESSION['user'])){
            $paypalGateway = new PayPalGateway();
            return $paypalGateway->check();
        }
        else{
            return redirect('/client/login');
        }

    }


    /**
    * Get all account logs 
    * 
    * @return Illuminate\Support\Facades\View
    */
    public function view_client_logs_account_all()
    {

        if(isset($_SESSION['user']))
            return view('sections.client_logs_account')
                ->with('clientController', new ClientController())
                ->with('lang', $this->languageController->page('client_logs_account'))
                ->with('logs', \App\Logs::all())
                ->with('limit', '*');
        else
            return redirect('/client/login');

    }

    /**
    * Construct an client service page
    *
    * @return Illuminate\Support\Facades\View
    */ 
    public function view_client_service($id)
    {

        if(isset($_SESSION['user']))
            return view('sections.client_service')
                ->with('service_id', $id)
                ->with('clientController', new ClientController())
                ->with('lang', new LanguageController())
                ->with('serviceController', new ServiceController());
        else
            return redirect('/client/login');

    }

    /**
    * Construct an client services page
    *
    * @return Illuminate\Support\Facades\View
    */ 
    public function view_client_services($draw, $page = null)
    {

        if(isset($_SESSION['user'])){

            if(!is_numeric($page)){
                return redirect('/client/services/view/simple/1');
                exit();
            }

            if($page < 1){
                return redirect('/client/services/view/simple/1');
            }

            if(!ctype_digit($page)){
                return redirect('/client/services/view/simple/' . ceil($page));
            }

            return view('sections.client_services')
                ->with('clientController', new ClientController())
                ->with('serviceController', new ServiceController())
                ->with('pageController', new PageController())
                ->with('lang', $this->languageController->page('client_service'))
                ->with('draw', $draw)
                ->with('page', $page);
        }
        else{
            return redirect('/client/login');
        }

    }

    /**
    * Construct an client service order page
    *
    * @return Illuminate\Support\Facades\View
    */ 
    public function view_client_services_order($service = null)
    {

        if(isset($_SESSION['user']))
            return view('sections.client_services_order')
                ->with('clientController', new ClientController())
                ->with('orderableController', new OrderableController())
                ->with('lang', $this->languageController->page('client_service'))
                ->with('selectedService', $service);
        else
            return redirect('/client/login');

    }

    /**
    * Construct an administration page
    *
    * @return Illuminate\Support\Facades\View
    */ 
    public function view_administration_index()
    {

        if(isset($_SESSION['user'])){

            $clientController = new ClientController();

            if($clientController->get('client_data', 'permissions') >= 1){
                return view('sections.administration.administration_view_index')
                ->with('administrationController', new AdministrationController())
                ->with('lang', $this->languageController->page('administration'))
                ->with('clientController', $clientController);
            }else{
                return redirect()->back();
            }
        }else{
            return redirect('/client/login');
        }

    }
    
    /**
    * Construct an administration clients page
    *
    * @return Illuminate\Support\Facades\View
    */ 
    public function view_administration_clients()
    {

        if(isset($_SESSION['user'])){

            $clientController = new ClientController();

            if($clientController->get('client_data', 'permissions') >= 1){
                return view('sections.administration.administration_view_clients')
                ->with('administrationController', new AdministrationController())
                ->with('lang', $this->languageController->page('administration'))
                ->with('clientController', $clientController);
            }else{
                return redirect()->back();
            }
        }else{
            return redirect('/client/login');
        }

    }

    /**
    * Construct an administration blog page
    *
    * @return Illuminate\Support\Facades\View
    */ 
    public function view_administration_blog()
    {

        if(isset($_SESSION['user'])){

            $clientController = new ClientController();

            if($clientController->get('client_data', 'permissions') >= 1){
                return view('sections.administration.administration_view_blog')
                ->with('administrationController', new AdministrationController())
                ->with('lang', $this->languageController->page('administration'))
                ->with('blogController', new BlogController())
                ->with('limit', 10)
                ->with('clientController', $clientController);
            }else{
                return redirect()->back();
            }
        }else{
            return redirect('/client/login');
        }

    }

    /**
    * Construct an administration blog page
    *
    * @return Illuminate\Support\Facades\View
    */ 
    public function view_administration_blog_all()
    {

        if(isset($_SESSION['user'])){

            $clientController = new ClientController();

            if($clientController->get('client_data', 'permissions') >= 1){
                return view('sections.administration.administration_view_blog')
                ->with('administrationController', new AdministrationController())
                ->with('lang', $this->languageController->page('administration'))
                ->with('blogController', new BlogController())
                ->with('limit', '*')
                ->with('clientController', $clientController);
            }else{
                return redirect()->back();
            }
        }else{
            return redirect('/client/login');
        }

    }

    /**
    * Construct an administration support page
    *
    * @return Illuminate\Support\Facades\View
    */ 
    public function view_administration_support()
    {

        if(isset($_SESSION['user'])){

            $clientController = new ClientController();

            if($clientController->get('client_data', 'permissions') >= 1){
                return view('sections.administration.administration_view_support')
                ->with('administrationController', new AdministrationController())
                ->with('supportController', new SupportController())
                ->with('lang', $this->languageController->page('administration'))
                ->with('clientController', $clientController);
            }else{
                return redirect()->back();
            }
        }else{
            return redirect('/client/login');
        }

    }

    /**
    * Construct an administration solve ticket page
    *
    * @return Illuminate\Support\Facades\View
    */ 
    public function view_administration_support_solve($id)
    {

        if(isset($_SESSION['user'])){

            $clientController = new ClientController();

            if($clientController->get('client_data', 'permissions') >= 1){
                return view('sections.administration.administration_view_support_solve')
                ->with('administrationController', new AdministrationController())
                ->with('supportController', new SupportController())
                ->with('serviceController', new ServiceController())
                ->with('lang', $this->languageController->page('administration'))
                ->with('id', $id)
                ->with('clientController', $clientController);
            }else{
                return redirect()->back();
            }
        }else{
            return redirect('/client/login');
        }

    }

    /**
    * Construct an support page
    *
    * @return Illuminate\Support\Facades\View
    */ 
    public function view_support_index()
    {

        if(isset($_SESSION['user'])){
            return view('sections.support.support_view_index')
            ->with('supportController', new SupportController())
            ->with('lang', $this->languageController->page('support'));
        }else{
            return redirect('/client/login');
        }

    }

    /**
    * Construct an create ticket support page
    *
    * @return Illuminate\Support\Facades\View
    */ 
    public function view_support_create()
    {

        if(isset($_SESSION['user'])){
            return view('sections.support.support_view_create')
            ->with('supportController', new SupportController())
            ->with('lang', $this->languageController->page('support'))
            ->with('serviceController', new ServiceController());
        }else{
            return redirect('/client/login');
        }

    }

    /**
    * Construct an solve ticket support page
    *
    * @return Illuminate\Support\Facades\View
    */ 
    public function view_support_solve($id)
    {

        if(isset($_SESSION['user'])){
            return view('sections.support.support_view_solve')
            ->with('supportController', new SupportController())
            ->with('clientController', new ClientController())
            ->with('administrationController', new AdministrationController())
            ->with('lang', $this->languageController->page('support'))
            ->with('id', $id);
        }else{
            return redirect('/client/login');
        }

    }

    /**
    * Destroy an sessions for badly submitted forms
    *
    * @return Illuminate\Support\Facades\View
    */
    public function form_destroy()
    {

        unset($_SESSION['request_form']);
        unset($_SESSION['request_form_login']);
        unset($_SESSION['server_captcha']);
        return redirect()->back();
        
    }


    /**
    * TEMPORARY ROUTES
    */

    public function removeServicesTemp()
    {

        DB::table('services')->truncate();
        return redirect()->back();

    }

    public function calculatePrice($ram, $ssd){
        return view('sections.calculatePrice')
                ->with('orderableController', new OrderableController())
                ->with('params', [$ram, $ssd]);
    }
    /**
    * END OF ~ TEMPORARY ROUTES
    */

    /**
    * Client api handler
    * 
    * @param string $action
    * @param string $argument
    */
    public function api($action, $argument=false)
    {

        if(isset($_SESSION['user'])){
            switch($action){

                case 'getServiceData':
                    return \App\Orderable::where('data->service_info->name_url', '=', $argument)->value('data');
                break;

                case 'getTransactions':
                    return TransactionController::get($argument);
                break;

                case 'createSession':
                    $args = explode('|', $argument);
                    $sessionName = $args[0];
                    $sessionValue = $args[1];
                    $_SESSION[$sessionName] = $sessionValue;
                    return;
                break;

                default: 
                    return redirect()->back();
                break;

            }
        }else{
            return redirect('/client/login');
        }

    }

    /**
    * Administrator api handler
    *
    * @param string $action
    * @param string $argument
    */
    public function administratorAPI($action, $argument=false)
    {

        if(isset($_SESSION['user'])){
            $clientController = new ClientController();
            if($clientController->get('client_data', 'permissions') >= 1){
                switch ($action) {
                    case '__set_lang__':
                        return DB::table('settings')->where('property', '=', 'lang')->update(['value' => $argument]);
                    break;
                    case '__remove_error__':
                        $str = file_get_contents('/var/log/apache2/error.log');
                        $hash = base64_decode(Input::all()["arguments"]);
                        $str = str_replace($hash, " ", $str);
                        file_put_contents('/var/log/apache2/error.log', $str); 
                        echo $str;
                        return;
                    break;
                    case '__client_code__':
                        unset($_SESSION['code_email']);
                        return;
                    case '__remove_access_ip__':
                        $data = $clientController->get('client_data', 'ips');
                        $arg = Input::all()["arguments"];
                        if (($key = array_search($arg, $data)) !== false) {
                        unset($data[$key]);
                        }
                        $clientController->set('client_data', 'ips', array_values($data));
                        $random_string = generateRandomString(8);
                        $clientController->set('client_data', 'unique_id', $random_string);
                        $session_data = json_decode($_SESSION['user']->data, true);
                        $session_data['client_data']['unique_id'] = $random_string;
                        $_SESSION['user']->data = json_encode($session_data);
                        return;
                    break;
                    default:
                        return redirect()->back();
                    break;
                }
            }else{
                return redirect()->back();
            }
        }else{
            return redirect('/client/login');
        }

    }

    public function test_paypal_success()
    {
        DB::table("invoices")->insert(
        [
            'creator' => '11',
            'amount' => '22',
            'gateway' => 'paypal',
            'status' => 'success', // pending, paid, failed
        ]);
    }

    public function test_paypal_ipn()
    {
        DB::table("invoices")->insert(
        [
            'creator' => '11',
            'amount' => '22',
            'gateway' => 'paypal',
            'status' => 'ipn', // pending, paid, failed
        ]);
    }

}
