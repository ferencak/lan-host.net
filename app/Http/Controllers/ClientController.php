<?php

namespace App\Http\Controllers;
use DB;
use Kim\Activity\Activity;
use Bitverse\Identicon\Identicon;
use Bitverse\Identicon\Color\Color;
use Bitverse\Identicon\Generator\RingsGenerator;
use Bitverse\Identicon\Preprocessor\MD5Preprocessor;

@session_start();
date_default_timezone_set('Europe/Prague');

use Illuminate\Http\Request;

class ClientController extends Controller
{
    
    private $request;
    private $isError = false;
    public $lang;
    /**
    * Constructor
    *
    * @return void
    */
    public function __construct()
    {
        $applicationController = new ApplicationController();
        $this->lang = $applicationController->languageController;

    }

    /**
    * Create a new account
    *
    * @param mixed $data Posted form data
    * @param int $captcha Randomly generated code
    * @return void
    */
    public function create($request)
    {

        $this->lang->page('register');

        $this->request = (object) $request;
        $allowed_states = array('US', 'CA', 'AF', 'AL', 'DZ', 'DS', 'AD', 'AO', 'AI', 'AQ', 'AG', 'AR', 'AM', 'AW', 'AU', 'AT', 'AZ', 'BS', 'BH', 'BD', 'BB', 'BY', 'BE', 'BZ', 'BJ', 'BM', 'BT', 'BO', 'BA', 'BW', 'BV', 'BR', 'IO', 'BN', 'BG', 'BF', 'BI', 'KH', 'CM', 'CV', 'KY', 'CF', 'TD', 'CL', 'CN', 'CX', 'CC', 'CO', 'KM', 'CG', 'CK', 'CR', 'HR', 'CU', 'CY', 'CZ', 'DK', 'DJ', 'DM', 'DO', 'TP', 'EC', 'EG', 'SV', 'GQ', 'ER', 'EE', 'ET', 'FK', 'FO', 'FJ', 'FI', 'FR', 'FX', 'GF', 'PF', 'TF', 'GA', 'GM', 'GE', 'DE', 'GH', 'GI', 'GR', 'GL', 'GD', 'GP', 'GU', 'GT', 'GN', 'GW', 'GY', 'HT', 'HM', 'HN', 'HK', 'HU', 'IS', 'IN', 'ID', 'IR', 'IQ', 'IE', 'IL', 'IT', 'CI', 'JM', 'JP', 'JO', 'KZ', 'KE', 'KI', 'KP', 'KR', 'XK', 'KW', 'KG', 'LA', 'LV', 'LB', 'LS', 'LR', 'LY', 'LI', 'LT', 'LU', 'MO', 'MK', 'MG', 'MW', 'MY', 'MV', 'ML', 'MT', 'MH', 'MQ', 'MR', 'MU', 'TY', 'MX', 'FM', 'MD', 'MC', 'MN', 'ME', 'MS', 'MA', 'MZ', 'MM', 'NA', 'NR', 'NP', 'NL', 'AN', 'NC', 'NZ', 'NI', 'NE', 'NG', 'NU', 'NF', 'MP', 'NO', 'OM', 'PK', 'PW', 'PA', 'PG', 'PY', 'PE', 'PH', 'PN', 'PL', 'PT', 'PR', 'QA', 'RE', 'RO', 'RU', 'RW', 'KN', 'LC', 'VC', 'WS', 'SM', 'ST', 'SA', 'SN', 'RS', 'SC', 'SL', 'SG', 'SK', 'SI', 'SB', 'SO', 'ZA', 'GS', 'ES', 'LK', 'SH', 'PM', 'SD', 'SR', 'SJ', 'SZ', 'SE', 'CH', 'SY', 'TW', 'TJ', 'TZ', 'TH', 'TG', 'TK', 'TO', 'TT', 'TN', 'TR', 'TM', 'TC', 'TV', 'UG', 'UA', 'AE', 'GB', 'UM', 'UY', 'UZ', 'VU', 'VA', 'VE', 'VN', 'VG', 'VI', 'WF', 'EH', 'YE', 'YU', 'ZR', 'ZM', 'ZW');
        $_SESSION['request_form'] = $request;
        $clients = DB::table('clients')->get();

        $clients->each(function($client){

            $data = json_decode($client->data, true);

            if($data['client_info']['email'] == $this->request->client_email){
               echo throw_alert($this->lang->fromFile('alert', 'danger'), $this->lang->get('error->wrong->email_already_used'), 'danger');
               $this->isError = true;
            }
        });

        $unfilled = [];

        if(!in_array($this->request->client_state, $allowed_states)){
            echo throw_alert($this->lang->fromFile('alert', 'danger'), $this->lang->get('error->wrong->invalid_state'), 'danger');
            $this->isError = true;
        }

        if(empty($this->request->client_firstname)){
            $unfilled[] = 'křestní jméno';
        }

        if(empty($this->request->client_lastname)){
            $unfilled[] = 'příjmení';
        }

        if(empty($this->request->client_email)){
            $unfilled[] = 'emailová adresa';
        }

        if(empty($this->request->client_password)){
            $unfilled[] = 'heslo';
        }

        if(empty($this->request->client_password_check)){
            $unfilled[] = 'heslo (kontrola)';
        }

        if(empty($this->request->client_city)){
            $unfilled[] = 'město/obec, ulice a číslo popisné';
        }

        if(empty($this->request->client_zip)){
            $unfilled[] = 'psč';
        }

        if(empty($this->request->tos_accept)){
            echo throw_alert($this->lang->fromFile('alert', 'danger'), $this->lang->get('error->wrong->unaccepted_tos'), 'danger');
            $this->isError = true;
        }

        if(empty($this->request->client_captcha)){
            $unfilled[] = 'captcha';
        }

        if($this->request->client_captcha != $_SESSION['server_captcha']){
            echo throw_alert($this->lang->fromFile('alert', 'danger'), $this->lang->get('error->wrong->bad_captcha'), 'danger');
            $this->isError = true;
        }

        if(str_contains_helper($this->request->client_zip, ' ')){
            $zip_parse = explode(' ', $this->request->client_zip);

            if(!is_numeric($zip_parse[0].$zip_parse[1])){
                echo throw_alert($this->lang->fromFile('alert', 'danger'), $this->lang->get('error->wrong->invalid_zip'), 'danger');
                $this->isError = true;
            }
        }else{
            if(!is_numeric($this->request->client_zip)){
                echo throw_alert($this->lang->fromFile('alert', 'danger'), $this->lang->get('error->wrong->invalid_zip'), 'danger');
                $this->isError = true;
            }
        }

        if($this->request->client_password != $this->request->client_password_check){
            echo throw_alert($this->lang->fromFile('alert', 'danger'), $this->lang->get('error->wrong->invalid_passwords'), 'danger');
            $this->isError = true;
        }

        if(!filter_var($this->request->client_email, FILTER_VALIDATE_EMAIL)){
            echo throw_alert($this->lang->fromFile('alert', 'danger'), $this->lang->get('error->wrong->invalid_email'), 'danger');
            $this->isError = true;
        }

        if(count($unfilled) > 0){
            $error_text = $this->lang->fromFile('register', 'error->unfilled->content->multiple');
            for($i=0;$i<count($unfilled);$i++){
                
                $error_text = str_replace('%1', implode(', ', $unfilled), $error_text);
            }
            echo throw_alert($this->lang->fromFile('alert', 'danger'), $error_text, 'danger');
            $this->isError = true;
        }
        $structure = json_encode([
            'client_info' => [
                'email' => $this->request->client_email,
                'password' => mysql_password($this->request->client_password)
            ],
            'client_data' => [
                'balance' => 0,
                'avatar_id' => (time()),
                'permissions' => 0,
                'ips' => [ $_SERVER['HTTP_X_FORWARDED_FOR'] ],
                'unique_id' => generateRandomString(8)
            ],
            'billing_info' => [
                'first_name' => $this->request->client_firstname,
                'last_name' => $this->request->client_lastname,
                'state' => $this->request->client_state,
                'city' => $this->request->client_city,
                'zip' => $this->request->client_zip,
                'phone' => $this->request->client_phone
            ]
        ]);

        if($this->isError == false){
            \App\Client::insert(['data' => $structure]);
            echo throw_alert($this->lang->fromFile('alert', 'success'), 'Váš uživatelský účet byl zaregistrován. Přihlásit se je možno <a href="/client/login">zde</a>.', 'success');
            unset($_SESSION['request_form']);
            unset($_SESSION['server_captcha']);
            redirect_to('/client/login', 3);
        }

    }

    /**
    * Login to account
    * 
    * @param mixed $data Posted form data
    * @return void
    */
    public function login($request)
    {
        
        $this->request = (object) $request;
        $_SESSION['request_form_login'] = $request;
        $unfilled = [];

        if(empty($this->request->client_email)){
            $unfilled[] = 'emailová adresa';
        }

        if(empty($this->request->client_password)){
            $unfilled[] = 'heslo';
        }

        if(count($unfilled) > 0){
            $error_text = "{0} [{1}] {2}";
            for($i=0;$i<count($unfilled);$i++){
                if(count($unfilled) > 1){
                    $error_text = str_replace('{0}', 'Tyto políčka', $error_text);
                    $error_text = str_replace('{2}', 'nejsou vyplněná.', $error_text);
                }else{
                    $error_text = str_replace('{0}', 'Toto políčko', $error_text);
                    $error_text = str_replace('{2}', 'není vyplněné.', $error_text);
                }
                $error_text = str_replace('{1}', implode(', ', $unfilled), $error_text);
            }
            echo throw_alert($this->lang->fromFile('alert', 'danger'), $error_text, 'danger');
            $this->isError = true;
        }

        $count = DB::table('clients')->where('data->client_info->email', $this->request->client_email)->count();

        if($count > 0){
            $client = DB::table('clients')->where('data->client_info->email', $this->request->client_email)->get();
        }
        
        if($count == 0){
            echo throw_alert($this->lang->fromFile('alert', 'danger'), 'Tato emailová adresa nebyla nalezena.', 'danger');
            $this->isError = true;
        }

        if($count > 0){
            $client_data = json_decode($client[0]->data, true);
            
            if(mysql_password($this->request->client_password) != $client_data['client_info']['password']){
                echo throw_alert($this->lang->fromFile('alert', 'danger'), 'Zadané heslo je nesprávné.', 'danger');
                $this->isError = true;
            }

        }

        if($this->isError == false){
            unset($_SESSION['request_form_login']);

            if($this->getUser($this->request->client_email, 'client_data', '2fa_secret')) {
                $_SESSION['verify2fa'] = [ $client[0] ];
                @session_start();
            }

            else if(!in_array($_SERVER["HTTP_X_FORWARDED_FOR"], json_decode($client[0]->data, true)['client_data']['ips']) || in_array('FORCEIP', json_decode($client[0]->data, true)['client_data']['ips']))
            {
                if(!isset($_SESSION['verify']))
                $_SESSION['verify'] = [ $client[0] ];
                @session_start();
            } 
            else 
            {
                $_SESSION['user'] = $client[0];
                @session_start();
                echo throw_alert($this->lang->fromFile('alert', 'success'), 'Přihlášení proběhlo úspěšně. Budete přesměrován.', 'success');
                redirect_to('/', 3);
            }
        }

    }


    /**
    * Create new password for user
    *
    * @param mixed $data Posted form data
    * @return void
    */
    public function reset_password($request)
    {

        $this->request = (object) $request;
        $clients = DB::table('clients')->get();

        if(empty($this->request->client_email)){
            return throw_alert("Nastala chyba!", "Některá políčka nebyla vyplněná!", 'danger');
        }

        if(!filter_var($this->request->client_email, FILTER_VALIDATE_EMAIL)){
            return throw_alert("Nastala chyba!", "Některá políčka nebyla vyplněná podle správného formátu!", 'danger');
        }

        $count = DB::table('clients')->where('data->client_info->email', $this->request->client_email)->count();

        if($count == 0){
            return throw_alert($this->lang->fromFile('alert', 'danger'), 'Tato emailová adresa nebyla nalezena.', 'danger');
        }
        
        $token = generateRandomString(40);
        DB::table('clients')->where('data->client_info->email', '=' ,$this->request->client_email)->update(['data->client_data->token_passreset' => $token]);
        
        $message = "<h1 style='text-align: center; font: 15px/1.2 'Robotica', sans-serif;'>Obnovení účtu, " . ClientController::getUser($this->request->client_email, 'billing_info', 'first_name') . " " . ClientController::getUser($this->request->client_email, 'billing_info', 'last_name') . "!</h1><br/>
        <div class='center' style='text-align: center;'><a href='https://lan-host.net/client/password_reset/" . ClientController::getUser($this->request->client_email, 'id') . "/" . $token . "'>Resetování hesla od uživatelského účtu</a></div><br/>
        <p>
        Děkujeme, lan-host.net<br/>
        Copyright © " . date("Y") . " lan-host.net";
        \App\Http\Controllers\MailController::send([
            'subject' => 'Obnovení účtu',
            'text' => $message,
            'receiver_email' => ClientController::getUser($this->request->client_email, 'client_info', 'email'),
            'receiver_name' => ClientController::getUser($this->request->client_email, 'billing_info', 'first_name')
        ]);

        return throw_alert($this->lang->fromFile('alert', 'success'), 'Email s odkazem na změnu hesla byl odeslán !', 'success');

    }

    /**
    * Checks if token matches in database
    *
    * @param mixed $data Posted form data
    * @return boolean
    */
    public function check($request)
    {
        $this->request = (object) $request;
        $count = DB::table('clients')->where(['id' => $this->request->id])->where(['data->client_data->token_passreset' => $this->request->token])->count();

        if($count == 0){
            return false;
        } else {
            return true;
        }
    }

    /**
    * Changes users password from password reset
    *
    * @param mixed $data Posted form data
    * @return void
    */
    public function change_password($request)
    {

        $this->request = (object) $request;

        if($this->request->password != $this->request->password_again) {
           return throw_alert("Nastala chyba!", "Hesla se neschodují!", 'danger');
        }

        \App\Logs::insert(['action' => 'Změna hesla uživatelského účtu', 'ip' => $_SERVER['HTTP_X_FORWARDED_FOR'], 'client' => $this->request->id, 'time' => date("d/m/Y H:i:s", time())]);
        $data = json_decode(json_decode(DB::table('clients')->where('data->client_data->token_passreset', '=' ,$this->request->token)->get()->toJson(), true)[0]['data'], true);
        unset($data['client_data']['token_passreset']);
        DB::table('clients')->where('data->client_data->token_passreset', '=' ,$this->request->token)->update(['data' => json_encode($data)]);
        DB::table('clients')->where(['id' => $this->request->id])->update(['data->client_info->password' => mysql_password($this->request->password)]);

        return throw_alert($this->lang->fromFile('alert', 'success'), 'Heslo od uživatelského účtu bylo úspešně změněno!', 'success');

    }


    /**
    * Checks users password from settings
    *
    * @param mixed $data Posted form data
    * @return void
    */
    public static function check_password_settings($request)
    {

        $unfilled = [];
        $isError = false;
        if(empty($request['old_password']))
        {
            $unfilled[] = 'Současné heslo';
        }
        if(empty($request['new_password']))
        {
            $unfilled[] = 'Nové heslo';
        }
        if(empty($request['new_password_check']))
        {
            $unfilled[] = 'Nové heslo (kontrola)';
        }
        if($request['new_password'] !== $request['new_password_check'])
        {
            echo throw_alert('Nastala chyba!', 'Hesla se neschodují!', 'danger');
            $isError = true;
        }

        if(\App\Http\Controllers\ClientController::get('client_info', 'password') !== mysql_password($request['old_password'])) 
        {
            echo throw_alert('Nastala chyba!', 'Heslo se neschoduje se současním heslem v databasi!', 'danger');
            $isError = true;
        }

        if(count($unfilled) > 0){
            $error_text = 'Tyto pole: <b><strong>%1</strong></b> nejsou vyplněné!';
            for($i=0;$i<count($unfilled);$i++){
                
                $error_text = str_replace('%1', implode(', ', $unfilled), $error_text);
            }
            echo throw_alert('Nastala chyba!', $error_text, 'danger');
            $isError = true;
        }
        return $isError;
    }


    /**
    * Checks users personal info from settings
    *
    * @param mixed $data Posted form data
    * @return void
    */
    public function check_personal_info_settings($request)
    {

        $unfilled = [];
        $isError = false;
        if(empty($request['first_last_name']))
        {
            $unfilled[] = 'Křestní jméno a příjmení';
        }
        if(empty($request['client_state']))
        {
            $unfilled[] = 'Stát';
        }
        if(empty($request['city']))
        {
            $unfilled[] = 'Město';
        }
        if(empty($request['zip_code']))
        {
            $unfilled[] = 'PSČ';
        }
        $allowed_states = array('US', 'CA', 'AF', 'AL', 'DZ', 'DS', 'AD', 'AO', 'AI', 'AQ', 'AG', 'AR', 'AM', 'AW', 'AU', 'AT', 'AZ', 'BS', 'BH', 'BD', 'BB', 'BY', 'BE', 'BZ', 'BJ', 'BM', 'BT', 'BO', 'BA', 'BW', 'BV', 'BR', 'IO', 'BN', 'BG', 'BF', 'BI', 'KH', 'CM', 'CV', 'KY', 'CF', 'TD', 'CL', 'CN', 'CX', 'CC', 'CO', 'KM', 'CG', 'CK', 'CR', 'HR', 'CU', 'CY', 'CZ', 'DK', 'DJ', 'DM', 'DO', 'TP', 'EC', 'EG', 'SV', 'GQ', 'ER', 'EE', 'ET', 'FK', 'FO', 'FJ', 'FI', 'FR', 'FX', 'GF', 'PF', 'TF', 'GA', 'GM', 'GE', 'DE', 'GH', 'GI', 'GR', 'GL', 'GD', 'GP', 'GU', 'GT', 'GN', 'GW', 'GY', 'HT', 'HM', 'HN', 'HK', 'HU', 'IS', 'IN', 'ID', 'IR', 'IQ', 'IE', 'IL', 'IT', 'CI', 'JM', 'JP', 'JO', 'KZ', 'KE', 'KI', 'KP', 'KR', 'XK', 'KW', 'KG', 'LA', 'LV', 'LB', 'LS', 'LR', 'LY', 'LI', 'LT', 'LU', 'MO', 'MK', 'MG', 'MW', 'MY', 'MV', 'ML', 'MT', 'MH', 'MQ', 'MR', 'MU', 'TY', 'MX', 'FM', 'MD', 'MC', 'MN', 'ME', 'MS', 'MA', 'MZ', 'MM', 'NA', 'NR', 'NP', 'NL', 'AN', 'NC', 'NZ', 'NI', 'NE', 'NG', 'NU', 'NF', 'MP', 'NO', 'OM', 'PK', 'PW', 'PA', 'PG', 'PY', 'PE', 'PH', 'PN', 'PL', 'PT', 'PR', 'QA', 'RE', 'RO', 'RU', 'RW', 'KN', 'LC', 'VC', 'WS', 'SM', 'ST', 'SA', 'SN', 'RS', 'SC', 'SL', 'SG', 'SK', 'SI', 'SB', 'SO', 'ZA', 'GS', 'ES', 'LK', 'SH', 'PM', 'SD', 'SR', 'SJ', 'SZ', 'SE', 'CH', 'SY', 'TW', 'TJ', 'TZ', 'TH', 'TG', 'TK', 'TO', 'TT', 'TN', 'TR', 'TM', 'TC', 'TV', 'UG', 'UA', 'AE', 'GB', 'UM', 'UY', 'UZ', 'VU', 'VA', 'VE', 'VN', 'VG', 'VI', 'WF', 'EH', 'YE', 'YU', 'ZR', 'ZM', 'ZW');

        if(!in_array($request['client_state'], $allowed_states)){
            echo throw_alert($this->lang->fromFile('alert', 'danger'), 'Neplatný stát', 'danger');
            $isError = true;
        }

        if(strlen($request['first_last_name']) > 30)
        {
            echo throw_alert($this->lang->fromFile('alert', 'danger'), 'Pole <b><strong>Křestní jméno a příjmení</strong></b> je moc dlouhý!', 'danger');
            $isError = true;
        }
        if(strlen($request['first_last_name']) < 5)
        {
            echo throw_alert($this->lang->fromFile('alert', 'danger'), 'Pole <b><strong>Křestní jméno a příjmení</strong></b> je moc kratký!', 'danger');
            $isError = true;
        }
        if(strlen($request['city']) < 7)
        {
            echo throw_alert($this->lang->fromFile('alert', 'danger'), 'Pole <b><strong>Město / Obec, ulice a číslo popisné</strong></b> je moc krátký!', 'danger');
            $isError = true;
        }
        if(strlen($request['city']) > 60)
        {
            echo throw_alert($this->lang->fromFile('alert', 'danger'), 'Pole <b><strong>Město / Obec, ulice a číslo popisné</strong></b> je moc dlohý!', 'danger');
            $isError = true;
        }
        if(strlen(str_replace(" ", "", $request['zip_code'])) < 5)
        {
            echo throw_alert($this->lang->fromFile('alert', 'danger'), 'Pole <b><strong>PSČ</strong></b> je moc krátký!', 'danger');
            $isError = true;
        }
        if(strlen($request['zip_code']) > 6)
        {
            echo throw_alert($this->lang->fromFile('alert', 'danger'), 'Pole <b><strong>PSČ</strong></b> je moc dlouhý!', 'danger');
            $isError = true;
        }
        $first_last_name = explode(' ', $request['first_last_name']);
        if(count($first_last_name) < 2)
        {
            echo throw_alert($this->lang->fromFile('alert', 'danger'), 'Pole <b><strong>Křestní jméno a příjmení</strong></b> není ve správném formátu (Jméno Příjmení)', 'danger');
            $isError = true;
        }
        if(!is_numeric(str_replace(" ", "", $request['zip_code'])))
        {
            echo throw_alert($this->lang->fromFile('alert', 'danger'), 'Pole <b><strong>PSČ</strong></b> není ve správném formátu (000 00)', 'danger');
            $isError = true;
        }

        if(count($unfilled) > 0){
            $error_text = 'Tyto pole: <b><strong>%1</strong></b> nejsou vyplněné!';
            for($i=0;$i<count($unfilled);$i++){
                
                $error_text = str_replace('%1', implode(', ', $unfilled), $error_text);
            }
            echo throw_alert($this->lang->fromFile('alert', 'danger'), $error_text, 'danger');
            $isError = true;
        }
        return $isError;
    }



    /**
    * Checks users email from settings
    *
    * @param mixed $data Posted form data
    * @return void
    */
    public function check_email_settings($request)
    {

        $unfilled = [];
        $isError = false;
        if(empty($request['old_mail']))
        {
            $unfilled[] = 'Současný email';
        }
        if(empty($request['new_mail']))
        {
            $unfilled[] = 'Nový email';
        }
        if(empty($request['new_mail_check']))
        {
            $unfilled[] = 'Nový email (kontrola)';
        }
        if($request['new_mail'] !== $request['new_mail_check'])
        {
            echo throw_alert($this->lang->fromFile('alert', 'danger'), 'Emaily se neschodují!', 'danger');
            $isError = true;
        }

        if(\App\Http\Controllers\ClientController::get('client_info', 'email') !== $request['old_mail']) 
        {
            echo throw_alert($this->lang->fromFile('alert', 'danger'), 'Email se neschoduje se současním heslem v databasi!', 'danger');
            $isError = true;
        }
        if(!filter_var($request['new_mail'], FILTER_VALIDATE_EMAIL)){
            echo throw_alert($this->lang->fromFile('alert', 'danger'), 'Neplatný formát emailové adresy', 'danger');
            $isError = true;
        }



        if(count($unfilled) > 0){
            $error_text = 'Tyto pole: <b><strong>%1</strong></b> nejsou vyplněné!';
            for($i=0;$i<count($unfilled);$i++){
                
                $error_text = str_replace('%1', implode(', ', $unfilled), $error_text);
            }
            echo throw_alert($this->lang->fromFile('alert', 'danger'), $error_text, 'danger');
            $isError = true;
        }
        return $isError;
    }


    /**
    * Changes users email from settings
    *
    * @param mixed $data Posted form data
    * @return void
    */
    public function change_email_settings($request)
    {
        $this->set('client_info', 'email', $_SESSION['settings_post']['new_mail']);
        $data = json_decode($_SESSION['user']->data, true);
        $data['client_info']['email'] = $_SESSION['settings_post']['new_mail'];
        $_SESSION['user']->data = json_encode($data);
        echo throw_alert($this->lang->fromFile('alert', 'success'), 'Email byl úspěšně změněn, budete <b><strong>odhlášen</b></strong>!', 'success');
        @session_destroy();
        return redirect_to('/', 5);
    }

     /**
    * Changes users personal info from settings
    *
    * @param mixed $data Posted form data
    * @return void
    */
    public function change_personal_info_settings($request)
    {
        $first_last_name = explode(' ', $_SESSION['settings_post']['first_last_name']);
        $data = json_decode(json_decode(DB::table('clients')->where('data->client_info->email', '=' , json_decode($_SESSION['user']->data, true)['client_info']['email'])->get()->toJson(), true)[0]['data'], true);

        $data['billing_info']['first_name'] = $first_last_name[0];
        $data['billing_info']['last_name'] = $first_last_name[1];
        $data['billing_info']['state'] = $_SESSION['settings_post']['client_state'];
        $data['billing_info']['city'] = $_SESSION['settings_post']['city'];
        $data['billing_info']['zip'] = $_SESSION['settings_post']['zip_code'];
        DB::table('clients')->where('data->client_info->email', '=' ,json_decode($_SESSION['user']->data, true)['client_info']['email'])->update(['data' => json_encode($data)]);
        $_SESSION['user']->data = json_encode($data);
        echo throw_alert($this->lang->fromFile('alert', 'success'), 'Osobní údaje byly úspěšně změněny!', 'success');
        redirect_to('/client/settings', 1);
    }


    /**
    * Changes users password from settings
    *
    * @param mixed $data Posted form data
    * @return void
    */
    public function change_password_settings($request)
    {
        $this->set('client_info', 'password', mysql_password($_SESSION['settings_post']['new_password']));
        $data = json_decode($_SESSION['user']->data, true);
        $data['client_info']['password'] = mysql_password($_SESSION['settings_post']['new_password']);
        $_SESSION['user']->data = json_encode($data);
        echo throw_alert($this->lang->fromFile('alert', 'success'), 'Heslo bylo úspěšně změněno, budete <b><strong>odhlášen</b></strong>!', 'success');
        @session_destroy();
        return redirect_to('/', 5);
    }

    /**
    * Changes users security level from settings
    *
    * @param mixed $data Posted form data
    * @return void
    */
    public function change_security_level_settings($request, $security)
    {
        if($request['hiddenvalue'] == 'secure_level_change-newloc-btn')
        {
            if($security == 1)
            {
                throw_alert($this->lang->fromFile('alert', 'danger'), "Potvrzení kodu přes email při změně lokaci je již nastaveno!", 'danger');
            }
            else
            {
                $data = json_decode(json_decode(DB::table('clients')->where('data->client_info->email', '=' , $this->get('client_info', 'email'))->get()->toJson(), true)[0]['data'], true);
                $data['client_data']['ips'] = [];
                if($security == 3)
                    unset($data['client_data']['2fa_secret']);

                DB::table('clients')->where('data->client_info->email', '=', $this->get('client_info', 'email'))->update(['data' => json_encode($data)]);
                throw_alert($this->lang->fromFile('alert', 'success'), 'Potvrzení kodu přes email při změně lokaci bylo úspěšně nastaveno, budete <b><strong>odhlášen</b></strong>!', 'success');
                @session_destroy();
                return redirect_to('/', 5);
            }
        } elseif($request['hiddenvalue'] == 'secure_level_change-always-email-btn')
        {
            if($security == 2)
            {
                throw_alert($this->lang->fromFile('alert', 'danger'), "Dvoufázové ověření přes email je již nastaveno!", 'danger');
            }
            else
            {
                $data = json_decode(json_decode(DB::table('clients')->where('data->client_info->email', '=' , $this->get('client_info', 'email'))->get()->toJson(), true)[0]['data'], true);
                $data['client_data']['ips'] = ["FORCEIP"];
                if($security == 3)
                    unset($data['client_data']['2fa_secret']);

                DB::table('clients')->where('data->client_info->email', '=', $this->get('client_info', 'email'))->update(['data' => json_encode($data)]);
                throw_alert('Úspěch', 'Dvoufázové ověření přes email bylo úspěšně nastaveno, budete <b><strong>odhlášen</b></strong>!', 'success');
                @session_destroy();
                redirect_to('/', 5);
            }
        } elseif($request['hiddenvalue'] == 'secure_level_change-2fa-btn') {
            if($security == 3) {
                throw_alert($this->lang->fromFile('alert', 'danger'), "Dvoufázové ověření je již nastaveno!", 'danger');
                return redirect_to('/', 5);
            }
            $secret = $request['token'];
            throw_alert($this->lang->fromFile('alert', 'success'), 'Dvoufázové ověření bylo úspěšně nastaveno, budete <b><strong>odhlášen</b></strong>!', 'success');
            unset($_SESSION['2fa_secret_QR']);
            $data = json_decode(json_decode(DB::table('clients')->where('data->client_info->email', '=' , $this->get('client_info', 'email'))->get()->toJson(), true)[0]['data'], true);
            $data['client_data']['2fa_secret'] = $secret;
            DB::table('clients')->where('data->client_info->email', '=', $this->get('client_info', 'email'))->update(['data' => json_encode($data)]);
            @session_destroy();
            redirect_to('/', 5);
        }
    }


    /**
    * Logout client
    *
    * @return Illuminate\Support\Facades\View
    */
    public static function logout()
    {
        @session_destroy();
        return redirect_to('/', 0);

    }

    /**
    * Get selected logged client data's from DB
    * 
    * @param mixed $data Selected client data
    * @return mixed
    */
    public static function get($selector, $selected)
    {

        $email = (array) json_decode($_SESSION['user']->data);
        $email = $email['client_info']->email;

        $client = DB::table('clients')->where('data->client_info->email', $email)->get();

        $data = json_decode($client[0]->data, true);
        if(isset($data[$selector][$selected]))
        return $data[$selector][$selected];

    
    }

/*
    /**
    * edit selected logged client data's from DB
    * 
    * @param mixed $data Selected client data
    * @return mixed
    */
    public static function set($selector, $selected, $replacewith)
    {

        $email = (array) json_decode($_SESSION['user']->data);
        $email = $email['client_info']->email;

        $client = DB::table('clients')->where('data->client_info->email', $email)->get();

        $data = json_decode($client[0]->data, true);
        if(isset($data[$selector][$selected]))
        {
            $data[$selector][$selected] =  $replacewith;
            DB::table('clients')->where('data->client_info->email', '=', \App\Http\Controllers\ClientController::get('client_info', 'email'))->update(['data' => json_encode($data)]);
            return true;
        } else
        {
            return false;
        }

    }


    /**
    * Get selected client data's from DB by user email
    * 
    * @param mixed $data Selected client data
    * @return mixed
    */
    public static function getUser($email = false, $selector, $selected = false)
    {

        $client = DB::table('clients')->where('data->client_info->email', $email)->get();

        $data = json_decode($client[0]->data, true);

        if($selected) {
            if(isset($data[$selector][$selected]))
            return $data[$selector][$selected];
        } else {
            $client = DB::table('clients')->where('data->client_info->email', $email)->get()[0]->id;
            json_decode($client, true);
            return $client;
        }

    }


    /**
    * Get selected client data's from DB by user id
    * 
    * @param mixed $data Selected client data
    * @return mixed
    */
    public static function getUserID($id, $selector, $selected)
    {

        $client = DB::table('clients')->where('id', $id)->get();
        $data = json_decode($client[0]->data, true);
        return $data[$selector][$selected];

    }



    /**
    * Get an logged client id
    * 
    * @return int
    */
    public static function getId()
    {

        $email = (array) json_decode($_SESSION['user']->data);
        $email = $email['client_info']->email;

        $client = DB::table('clients')->where('data->client_info->email', $email)->get();

        return $client[0]->id;

    }

    /**
    * Generate new client avatar
    *
    * @return void
    */
    public static function generateAvatar()
    {

        $generator = new RingsGenerator();
        $generator->setBackgroundColor(Color::parseHex('#FFFFFF'));

        $identicon = new Identicon(new MD5Preprocessor(), $generator);

        $icon = $identicon->getIcon(\App\Http\Controllers\ClientController::get('client_data', 'avatar_id'));

        file_put_contents('/var/www/LAN-HOST.NET/lan-host/public/images/avatars/'.\App\Http\Controllers\ClientController::getId().'.svg', $icon);

    }

    /** 
    * Get an client avatar
    *
    * @return path
    */
    public function getAvatar()
    {

        return "/images/avatars/".$this->getId().".svg?ver=".time();

    }

    /**
    * Create client log
    * 
    * @param string $action Client action
    * @return void
    */
    public static function log($action)
    {

        \App\Logs::insert(['action' => $action, 'ip' => $_SERVER['HTTP_X_FORWARDED_FOR'], 'client' => \App\Http\Controllers\ClientController::getId(), 'time' => date("d/m/Y H:i:s", time())]);

    }

    /**
    * Get client logs
    * 
    * @param int $limit Log count limitations
    * @return string
    */
    public function getLogs($limit)
    {

        $logs = \App\Logs::where('client', '=', \App\Http\Controllers\ClientController::getId())->orderBy('id', 'desc')->limit(($limit == '*') ? 0 : $limit)->get();
        
        $parsed = '';

        foreach ($logs as $log) {
            $parsed .= "<tr><td>{$log['time']}</td><td>{$log['action']}</td><td style='text-align:center'>{$log['ip']}</td></tr>";
            
        }

        return $parsed;

    }

    /**
    * Get client accessed ips
    * 
    * @param int $limit Log count limitations
    * @return string
    */
    public function getAccessIPs()
    {

        $ips = \App\Http\Controllers\ClientController::get('client_data', 'ips');
        $parsed = '';

        foreach ($ips as $ip) {
            if($ip !== 'FORCEIP') {
                $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
                $loc = $details->city.', '.$details->region;
                if(\App\TimeSessions::where('ip_address', $ip)->value('last_activity') > time())
                {
                    $status = ' style="color:green"><i class="fas fa-circle" style="color:green; margin-left:-13px; width:"></i> ONLINE';  
                } else {
                    $status = ' style="color:red"><i class="fas fa-circle" style="color:red; margin-left:-13px"></i> OFFLINE';
                }
                $parsed .= "<tr><td>{$ip}</td><td>{$loc}</td><td{$status}</td><td onclick='remove_access_ip(`{$ip}`); ((this.parentNode).parentNode).removeChild(this.parentNode);' style='padding-left:23px'><i class='far fa-trash-alt hoverred'></i></td></tr>";
            }
        }

        return $parsed;

    }


    /**
    * Compose an user dignity
    *
    * @return string
    */
    public function composeDignity($permissions)
    {

        switch($permissions){
            case 1:
                $dignity = $this->lang->fromFile('dignity', 'dignity->0');
            break;
            case 2:
                $dignity = $this->lang->fromFile('dignity', 'dignity->1');
            break;
            case 3:
                $dignity = $this->lang->fromFile('dignity', 'dignity->2');
            break;
            case 4:
                $dignity = $this->lang->fromFile('dignity', 'dignity->3');
            break;
            case 5:
                $dignity = $this->lang->fromFile('dignity', 'dignity->4');
            break;
            case 6:
                $dignity = $this->lang->fromFile('dignity', 'dignity->5');
            break;
            default:
                $dignity = $this->lang->fromFile('dignity', 'dignity->6');
            break;
        }

        return $dignity;

    }

}
