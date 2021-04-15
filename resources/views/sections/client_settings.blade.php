@extends('master')


<?php
    \App\Http\Controllers\ClientController::log($lang->fromFile('general', 'redirect_action') . $lang->get('page_name') . ".");
?>

@section('app_title', str_replace("'", "", $lang->get('page_name')))

@section('section')

<div class='container pb50  pt50-md pt100'>
    <div class='row'>
        @include('includes.client_menu')
        @php
            $db_data = json_decode(json_decode(DB::table('clients')->where('id', '=' , $clientController->getID())->get()->toJson(), true)[0]['data'], true);
            $personal_info = $db_data['billing_info'];
            $g = new \Google\Authenticator\GoogleAuthenticator();
            if($clientController->get('client_data', '2fa_secret')) 
            {
                $security = 3;

            } elseif (in_array('FORCEIP', $clientController->get('client_data', 'ips')))
            {
                $security = 2;
            }
            else
            {
                $security = 1;
            }
        @endphp
        @if(isset($_POST['check_pass_change']) || isset($_POST['check_email_change']) || isset($_POST['newloc-btn']) || isset($_POST['always-email-btn']) || isset($_POST['2fa-btn']) || isset($_POST['check_personal_info_change']))
            <form action="" method="POST">
            {{ Form::token() }}   
            @php
                $_SESSION['settings_post'] = $_POST;
                if(isset($_POST['check_pass_change'])) {
                    $hiddenvalue = 'set';
                    $btncheck = 'pass_change';
                }
                if(isset($_POST['check_email_change'])) {
                    $hiddenvalue = 'set';
                    $btncheck = 'mail_change';
                }
                if(isset($_POST['check_personal_info_change'])) {
                    $hiddenvalue = 'set';
                    $btncheck = 'personal_info_change';
                }
                if(isset($_POST['newloc-btn'])) {
                    $hiddenvalue = 'secure_level_change-newloc-btn';
                    $btncheck = 'secure_level_change';
                }
                if(isset($_POST['always-email-btn'])) {
                    $hiddenvalue = 'secure_level_change-always-email-btn';
                    $btncheck = 'secure_level_change';
                }
                if(isset($_POST['2fa-btn'])) {
                    $hiddenvalue = 'secure_level_change-2fa-btn';
                    $btncheck = 'secure_level_change';
                    echo "<input type='hidden' value='{$_POST['token']}' name='token' value='3487'>";
                }
            @endphp
                <!-- Modal -->
                <div class="modal fade" id="2fa-modal" tabindex="-1" role="dialog" aria-labelledby="2fa-modal" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content" style="margin-top: 60%;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="2fa-modal">Dvoufázové ověření</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @if($security == 3)
                                    <center>Zadejte ověřovací kód z aplikace <strong style="color: black!important;">Google Authenticator</strong></center>
                                @else
                                    <center>Zadejte ověřovací kód který vám přišel na <strong style="color: black!important;">Email</strong></center>
                                @endif
                                    <input name="client_code" type='text' required pattern='[0-9]+' class='verify-input col-md-12' onfocus="this.placeholder = ''" onblur="this.placeholder = '000000'" placeholder='000000'>
                                    <input type="hidden" value="{{ $hiddenvalue }}" name="hiddenvalue">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Zrušit</button>
                                <button type="submit" name="{{ $btncheck }}" id="{{ $btncheck }}" class="btn btn-outline-danger float-right">Změnit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!--modal-->
        @endif
        <div class="col-lg-9 col-md-12 col-sm-12 client-panel">
            @php
                if(isset($_POST['pass_change']))
                {
                    //dd($_POST);
                    if($security == 3)
                    {
                        $check_this_code = $_POST['client_code'];
                        $secret = $clientController->get('client_data', '2fa_secret');
                        if ($g->checkCode($secret, $check_this_code))
                        {
                            $clientController->change_password_settings($_SESSION['settings_post']);
                        }
                        else
                        {
                            echo throw_alert($lang->fromFile('alert', 'danger'), 'Zadaný kód byl neplatný!', 'danger');
                        }
                    } else
                    {
                        if(isset($_SESSION['code_email']) && $_POST['client_code'] == $_SESSION['code_email']) {
                            unset($_SESSION['code_email']);
                            $clientController->change_password_settings($_SESSION['settings_post']);
                        } else
                        {
                            echo throw_alert($lang->fromFile('alert', 'danger'), 'Zadaný kód byl neplatný!', 'danger');
                        }
                    }
                }

                if(isset($_POST['personal_info_change']))
                {
                    if($security == 3)
                    {
                        $check_this_code = $_POST['client_code'];
                        $secret = $clientController->get('client_data', '2fa_secret');
                        if ($g->checkCode($secret, $check_this_code))
                        {
                            $clientController->change_personal_info_settings($_SESSION['settings_post']);
                        }
                        else
                        {
                            echo throw_alert($lang->fromFile('alert', 'danger'), 'Zadaný kód byl neplatný!', 'danger');
                        }
                    } else
                    {
                        if(isset($_SESSION['code_email']) && $_POST['client_code'] == $_SESSION['code_email']) {
                            unset($_SESSION['code_email']);
                            $clientController->change_personal_info_settings($_SESSION['settings_post']);
                        } else
                        {
                            echo throw_alert($lang->fromFile('alert', 'danger'), 'Zadaný kód byl neplatný!', 'danger');
                        }
                    }
                }


                if(isset($_POST['mail_change']))
                {
                    //dd($_POST);
                    if($security == 3)
                    {
                        $check_this_code = $_POST['client_code'];
                        $secret = $clientController->get('client_data', '2fa_secret');
                        if ($g->checkCode($secret, $check_this_code))
                        {
                            $clientController->change_email_settings($_SESSION['settings_post']);
                        }
                        else
                        {
                            echo throw_alert($lang->fromFile('alert', 'danger'), 'Zadaný kód byl neplatný!', 'danger');
                        }
                    } else
                    {
                        if(isset($_SESSION['code_email']) && $_POST['client_code'] == $_SESSION['code_email']) {
                            unset($_SESSION['code_email']);
                            $clientController->change_email_settings($_SESSION['settings_post']);
                        } else
                        {
                            echo throw_alert($lang->fromFile('alert', 'danger'), 'Zadaný kód byl neplatný!', 'danger');
                        }
                    }
                }

                if(isset($_POST['check_pass_change']))
                {
                    if(!$clientController->check_password_settings($_POST))
                    echo "<script> $('#2fa-modal').modal('show') </script>";
                    if($security !== 3 && !isset($_SESSION['code_email'])) {
                        $_SESSION['code_email'] = generatePIN();
                        $message = "<h1 style='text-align: center; font: 15px/1.2 'Robotica', sans-serif;'>Ověřovací kod !</h1><br/>
                        <div class='center' style='text-align: center;'>Váš ověřovací kod je " . $_SESSION['code_email'] . "</a></div><br/>
                        <p>
                        Děkujeme, lan-host.net<br/>
                        Copyright © " . date("Y") . " lan-host.net";
                        \App\Http\Controllers\MailController::send([
                            'subject' => 'Ověřovací kod',
                            'text' => $message,
                            'receiver_email' => $clientController->get('client_info', 'email'),
                            'receiver_name' => $clientController->get('billing_info', 'first_name')
                        ]);
                    }
                }

                if(isset($_POST['check_personal_info_change']))
                {
                    if(!$clientController->check_personal_info_settings($_POST))
                    echo "<script> $('#2fa-modal').modal('show') </script>";
                    if($security !== 3 && !isset($_SESSION['code_email'])) {
                        $_SESSION['code_email'] = generatePIN();
                        $message = "<h1 style='text-align: center; font: 15px/1.2 'Robotica', sans-serif;'>Ověřovací kod !</h1><br/>
                        <div class='center' style='text-align: center;'>Váš ověřovací kod je " . $_SESSION['code_email'] . "</a></div><br/>
                        <p>
                        Děkujeme, lan-host.net<br/>
                        Copyright © " . date("Y") . " lan-host.net";
                        \App\Http\Controllers\MailController::send([
                            'subject' => 'Ověřovací kod',
                            'text' => $message,
                            'receiver_email' => $clientController->get('client_info', 'email'),
                            'receiver_name' => $clientController->get('billing_info', 'first_name')
                        ]);
                    }
                }

                if(isset($_POST['check_email_change']))
                {
                    if(!$clientController->check_email_settings($_POST)) {
                    echo "<script> $('#2fa-modal').modal('show') </script>";
                    }
                    if($security !== 3 && !isset($_SESSION['code_email'])) {
                        $_SESSION['code_email'] = generatePIN();
                        $message = "<h1 style='text-align: center; font: 15px/1.2 'Robotica', sans-serif;'>Ověřovací kod !</h1><br/>
                        <div class='center' style='text-align: center;'>Váš ověřovací kod je " . $_SESSION['code_email'] . "</a></div><br/>
                        <p>
                        Děkujeme, lan-host.net<br/>
                        Copyright © " . date("Y") . " lan-host.net";
                        \App\Http\Controllers\MailController::send([
                            'subject' => 'Ověřovací kod',
                            'text' => $message,
                            'receiver_email' => $clientController->get('client_info', 'email'),
                            'receiver_name' => $clientController->get('billing_info', 'first_name')
                        ]);
                    }
                }

                if(isset($_POST['newloc-btn']))
                {
                    echo "<script> $('#2fa-modal').modal('show') </script>";
                    if($security !== 3 && !isset($_SESSION['code_email'])) {
                        $_SESSION['code_email'] = generatePIN();
                        $message = "<h1 style='text-align: center; font: 15px/1.2 'Robotica', sans-serif;'>Ověřovací kod !</h1><br/>
                        <div class='center' style='text-align: center;'>Váš ověřovací kod je " . $_SESSION['code_email'] . "</a></div><br/>
                        <p>
                        Děkujeme, lan-host.net<br/>
                        Copyright © " . date("Y") . " lan-host.net";
                        \App\Http\Controllers\MailController::send([
                            'subject' => 'Ověřovací kod',
                            'text' => $message,
                            'receiver_email' => $clientController->get('client_info', 'email'),
                            'receiver_name' => $clientController->get('billing_info', 'first_name')
                        ]);
                    }
                }

                if(isset($_POST['secure_level_change']))
                {
                    if($security == 3)
                    {
                        $check_this_code = $_POST['client_code'];
                        $secret = $clientController->get('client_data', '2fa_secret');
                        if ($g->checkCode($secret, $check_this_code))
                        {
                            $clientController->change_security_level_settings($_POST, $security);
                        }
                        else
                        {
                            echo throw_alert($lang->fromFile('alert', 'danger'), 'Zadaný kód byl neplatný!', 'danger');
                        }
                    } else
                    {
                        if(isset($_SESSION['code_email']) && $_POST['client_code'] == $_SESSION['code_email']) {
                            unset($_SESSION['code_email']);
                            $clientController->change_security_level_settings($_POST, $security);
                        } else
                        {
                            echo throw_alert($lang->fromFile('alert', 'danger'), 'Zadaný kód byl neplatný!', 'danger');
                        }
                    }
                }

                if(isset($_POST['always-email-btn']))
                {
                    echo "<script> $('#2fa-modal').modal('show') </script>";
                    if($security !== 3 && !isset($_SESSION['code_email'])) {
                        $_SESSION['code_email'] = generatePIN();
                        $message = "<h1 style='text-align: center; font: 15px/1.2 'Robotica', sans-serif;'>Ověřovací kod !</h1><br/>
                        <div class='center' style='text-align: center;'>Váš ověřovací kod je " . $_SESSION['code_email'] . "</a></div><br/>
                        <p>
                        Děkujeme, lan-host.net<br/>
                        Copyright © " . date("Y") . " lan-host.net";
                        \App\Http\Controllers\MailController::send([
                            'subject' => 'Ověřovací kod',
                            'text' => $message,
                            'receiver_email' => $clientController->get('client_info', 'email'),
                            'receiver_name' => $clientController->get('billing_info', 'first_name')
                        ]);
                    }
                }

                if(isset($_POST['2fa-btn']))
                {

                    $code = $_POST['client_code'];
                    $secret = $_POST['token'];
                    if ($g->checkCode($secret, $code)) {
                    echo "<script> $('#2fa-modal').modal('show') </script>";
                    if($security !== 3 && !isset($_SESSION['code_email'])) {
                        $_SESSION['code_email'] = generatePIN();
                        $message = "<h1 style='text-align: center; font: 15px/1.2 'Robotica', sans-serif;'>Ověřovací kod !</h1><br/>
                        <div class='center' style='text-align: center;'>Váš ověřovací kod je " . $_SESSION['code_email'] . "</a></div><br/>
                        <p>
                        Děkujeme, lan-host.net<br/>
                        Copyright © " . date("Y") . " lan-host.net";
                        \App\Http\Controllers\MailController::send([
                            'subject' => 'Ověřovací kod',
                            'text' => $message,
                            'receiver_email' => $clientController->get('client_info', 'email'),
                            'receiver_name' => $clientController->get('billing_info', 'first_name')
                        ]);
                    }
                    } else {
                      throw_alert("Nastala chyba!", "kód byl neplatný, zkuste znova naskenovat QR kod, nebo manuálně opsat tajný kód", 'danger');
                    }
                }
            @endphp

            <div class="card">
                <div class="card-header" style="background-color: transparent;">
                    {{ str_replace("'", "", $lang->get('page_name')) }}
                    <div class="float-right pull-right">
                        <i class="fa fa-cogs"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tabs-default tabs-icon">
                        <ul class="nav nav-tabs tabs-default mb30 justify-content-center" role="tablist">
                            <li class="nav-item" role="presentation"><a class="nav-link active" href="#tb6" aria-controls="tb6" role="tab" data-toggle="tab" aria-expanded="true">Uživatelské údaje</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link" href="#tb7" aria-controls="tb7" role="tab" data-toggle="tab" aria-expanded="false">Zabezpečení účtu</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link" href="#tb8" aria-controls="tb8" role="tab" data-toggle="tab">Ochrana údajů</a></li>                       
                        </ul>
                        <div class="tab-content mb40">
                            <div role="tabpanel" class="tab-pane fade active show col-md-12" id="tb6" aria-expanded="true">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12 float-left" style="padding-left: 0!important">
                                            <div class="card-header col-md-5 float-left" style="background-color: transparent;padding-left: 0px;max-width: 43%!important;">
                                                Změna uživatelského hesla
                                                <div class="float-right pull-right">
                                                    <i class="fa fa-lock"></i>
                                                </div>
                                            </div>
                                            <div class="card-header col-md-5 float-right" style="background-color: transparent;padding-left: 0px;max-width: 43%!important">
                                                Změna emailové adresy
                                                <div class="float-right pull-right">
                                                    <i class="fa fa-at"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 float-left" style="padding-left:0!important;">
                                            <form action="" method="POST">
                                                {{ Form::token() }}
                                                <div class="col-md-11"  style="margin: 30px 0 0 0%;">
                                                    <div class="form-group">
                                                        <div class="col-md-10" style="background-color: transparent;padding-left: 0px;">
                                                              Současné heslo 
                                                        </div>
                                                        <input type="password" id="old_password" name="old_password" class="form-control" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-11">
                                                    <div class="form-group">
                                                        <div class="col-md-10" style="background-color: transparent;padding-left: 0px;">
                                                              Nové heslo
                                                        </div>
                                                        <input type="password" id="new_password" name="new_password" class="form-control" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-11">
                                                    <div class="form-group">
                                                        <div class="col-md-10" style="background-color: transparent;padding-left: 0px;">
                                                              Nove heslo (kontrola)
                                                        </div>
                                                        <input type="password" id="new_password_check" name="new_password_check" class="form-control" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-11">
                                                    <button name="check_pass_change" type="submit" class="btn btn-outline-danger float-right">Změnit</button>
                                                </div>
                                            </form>
                                        </div>
                                    
                                        <div class="col-md-6 float-right" style="padding-right:0!important;">
                                            <form accept="" method="POST">
                                                {{ Form::token() }}
                                                <div class="col-md-11 float-right"  style="margin: 30px 0 0 0%;">
                                                    <div class="form-group">
                                                        <div class="col-md-10" style="background-color: transparent;padding-left: 0px;">
                                                              Současná emailová adresa 
                                                        </div>
                                                        <input type="email" id="old_mail" name="old_mail" class="form-control" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-11 float-right">
                                                    <div class="form-group">
                                                        <div class="col-md-10" style="background-color: transparent;padding-left: 0px;">
                                                              Nová emailová adresa
                                                        </div>
                                                        <input type="email" id="new_mail" name="new_mail" class="form-control" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-11 float-right">
                                                    <div class="form-group">
                                                        <div class="col-md-10" style="background-color: transparent;padding-left: 0px;">Nová emailová adresa (kontrola)
                                                        </div>
                                                        <input type="email" id="new_mail_check" name="new_mail_check" class="form-control" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-11 float-right">
                                                    <button name="check_email_change" type="submit" class="btn btn-outline-danger float-right">Změnit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top:30px!important">
                                        <div class="col-md-12" style="padding-left: 0!important">
                                            <div class="card-header col-md-12" style="background-color: transparent;padding-left: 0px;">
                                                Změna osobních údajů
                                                <div class="float-right pull-right">
                                                    <i class="fa fa-users"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 float-left" style="padding-left:0!important;">
                                            <form accept="" method="POST">
                                                {{ Form::token() }}
                                                <div class="col-md-11"  style="margin: 30px 0 0 0%;">
                                                    <div class="form-group">
                                                        <div class="col-md-10" style="background-color: transparent;padding-left: 0px;">
                                                              Křestní jméno a příjmení
                                                        </div>
                                                        <input type="text" id="first_last_name" name="first_last_name" class="form-control" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-11">
                                                    <div class="form-group">
                                                        <div class="col-md-10" style="background-color: transparent;padding-left: 0px;">
                                                              Stát
                                                        </div>
                                                        <select id="client_state" name="client_state" required class="form-control" >
                                                            <optgroup label="Vyhodnoceno">
                                                            </optgroup>
                                                            <optgroup label="Nejčastější">
                                                                <option value="CZ">Česká republika</option>
                                                                <option value="SK">Slovenská republika</option>
                                                            </optgroup>
                                                            <optgroup label="Všechny">
                                                                <option value="US">United States</option>
                                                                <option value="CA">Canada</option>
                                                                <option value="AF">Afghanistan</option>
                                                                <option value="AL">Albania</option>
                                                                <option value="DZ">Algeria</option>
                                                                <option value="DS">American Samoa</option>
                                                                <option value="AD">Andorra</option>
                                                                <option value="AO">Angola</option>
                                                                <option value="AI">Anguilla</option>
                                                                <option value="AQ">Antarctica</option>
                                                                <option value="AG">Antigua and/or Barbuda</option>
                                                                <option value="AR">Argentina</option>
                                                                <option value="AM">Armenia</option>
                                                                <option value="AW">Aruba</option>
                                                                <option value="AU">Australia</option>
                                                                <option value="AT">Austria</option>
                                                                <option value="AZ">Azerbaijan</option>
                                                                <option value="BS">Bahamas</option>
                                                                <option value="BH">Bahrain</option>
                                                                <option value="BD">Bangladesh</option>
                                                                <option value="BB">Barbados</option>
                                                                <option value="BY">Belarus</option>
                                                                <option value="BE">Belgium</option>
                                                                <option value="BZ">Belize</option>
                                                                <option value="BJ">Benin</option>
                                                                <option value="BM">Bermuda</option>
                                                                <option value="BT">Bhutan</option>
                                                                <option value="BO">Bolivia</option>
                                                                <option value="BA">Bosnia and Herzegovina</option>
                                                                <option value="BW">Botswana</option>
                                                                <option value="BV">Bouvet Island</option>
                                                                <option value="BR">Brazil</option>
                                                                <option value="IO">British lndian Ocean Territory</option>
                                                                <option value="BN">Brunei Darussalam</option>
                                                                <option value="BG">Bulgaria</option>
                                                                <option value="BF">Burkina Faso</option>
                                                                <option value="BI">Burundi</option>
                                                                <option value="KH">Cambodia</option>
                                                                <option value="CM">Cameroon</option>
                                                                <option value="CV">Cape Verde</option>
                                                                <option value="KY">Cayman Islands</option>
                                                                <option value="CF">Central African Republic</option>
                                                                <option value="TD">Chad</option>
                                                                <option value="CL">Chile</option>
                                                                <option value="CN">China</option>
                                                                <option value="CX">Christmas Island</option>
                                                                <option value="CC">Cocos (Keeling) Islands</option>
                                                                <option value="CO">Colombia</option>
                                                                <option value="KM">Comoros</option>
                                                                <option value="CG">Congo</option>
                                                                <option value="CK">Cook Islands</option>
                                                                <option value="CR">Costa Rica</option>
                                                                <option value="HR">Croatia (Hrvatska)</option>
                                                                <option value="CU">Cuba</option>
                                                                <option value="CY">Cyprus</option>
                                                                <option value="CZ">Česká republika</option>
                                                                <option value="DK">Denmark</option>
                                                                <option value="DJ">Djibouti</option>
                                                                <option value="DM">Dominica</option>
                                                                <option value="DO">Dominican Republic</option>
                                                                <option value="TP">East Timor</option>
                                                                <option value="EC">Ecuador</option>
                                                                <option value="EG">Egypt</option>
                                                                <option value="SV">El Salvador</option>
                                                                <option value="GQ">Equatorial Guinea</option>
                                                                <option value="ER">Eritrea</option>
                                                                <option value="EE">Estonia</option>
                                                                <option value="ET">Ethiopia</option>
                                                                <option value="FK">Falkland Islands (Malvinas)</option>
                                                                <option value="FO">Faroe Islands</option>
                                                                <option value="FJ">Fiji</option>
                                                                <option value="FI">Finland</option>
                                                                <option value="FR">France</option>
                                                                <option value="FX">France, Metropolitan</option>
                                                                <option value="GF">French Guiana</option>
                                                                <option value="PF">French Polynesia</option>
                                                                <option value="TF">French Southern Territories</option>
                                                                <option value="GA">Gabon</option>
                                                                <option value="GM">Gambia</option>
                                                                <option value="GE">Georgia</option>
                                                                <option value="DE">Germany</option>
                                                                <option value="GH">Ghana</option>
                                                                <option value="GI">Gibraltar</option>
                                                                <option value="GR">Greece</option>
                                                                <option value="GL">Greenland</option>
                                                                <option value="GD">Grenada</option>
                                                                <option value="GP">Guadeloupe</option>
                                                                <option value="GU">Guam</option>
                                                                <option value="GT">Guatemala</option>
                                                                <option value="GN">Guinea</option>
                                                                <option value="GW">Guinea-Bissau</option>
                                                                <option value="GY">Guyana</option>
                                                                <option value="HT">Haiti</option>
                                                                <option value="HM">Heard and Mc Donald Islands</option>
                                                                <option value="HN">Honduras</option>
                                                                <option value="HK">Hong Kong</option>
                                                                <option value="HU">Hungary</option>
                                                                <option value="IS">Iceland</option>
                                                                <option value="IN">India</option>
                                                                <option value="ID">Indonesia</option>
                                                                <option value="IR">Iran (Islamic Republic of)</option>
                                                                <option value="IQ">Iraq</option>
                                                                <option value="IE">Ireland</option>
                                                                <option value="IL">Israel</option>
                                                                <option value="IT">Italy</option>
                                                                <option value="CI">Ivory Coast</option>
                                                                <option value="JM">Jamaica</option>
                                                                <option value="JP">Japan</option>
                                                                <option value="JO">Jordan</option>
                                                                <option value="KZ">Kazakhstan</option>
                                                                <option value="KE">Kenya</option>
                                                                <option value="KI">Kiribati</option>
                                                                <option value="KP">Korea, Democratic People's Republic of</option>
                                                                <option value="KR">Korea, Republic of</option>
                                                                <option value="XK">Kosovo</option>
                                                                <option value="KW">Kuwait</option>
                                                                <option value="KG">Kyrgyzstan</option>
                                                                <option value="LA">Lao People's Democratic Republic</option>
                                                                <option value="LV">Latvia</option>
                                                                <option value="LB">Lebanon</option>
                                                                <option value="LS">Lesotho</option>
                                                                <option value="LR">Liberia</option>
                                                                <option value="LY">Libyan Arab Jamahiriya</option>
                                                                <option value="LI">Liechtenstein</option>
                                                                <option value="LT">Lithuania</option>
                                                                <option value="LU">Luxembourg</option>
                                                                <option value="MO">Macau</option>
                                                                <option value="MK">Macedonia</option>
                                                                <option value="MG">Madagascar</option>
                                                                <option value="MW">Malawi</option>
                                                                <option value="MY">Malaysia</option>
                                                                <option value="MV">Maldives</option>
                                                                <option value="ML">Mali</option>
                                                                <option value="MT">Malta</option>
                                                                <option value="MH">Marshall Islands</option>
                                                                <option value="MQ">Martinique</option>
                                                                <option value="MR">Mauritania</option>
                                                                <option value="MU">Mauritius</option>
                                                                <option value="TY">Mayotte</option>
                                                                <option value="MX">Mexico</option>
                                                                <option value="FM">Micronesia, Federated States of</option>
                                                                <option value="MD">Moldova, Republic of</option>
                                                                <option value="MC">Monaco</option>
                                                                <option value="MN">Mongolia</option>
                                                                <option value="ME">Montenegro</option>
                                                                <option value="MS">Montserrat</option>
                                                                <option value="MA">Morocco</option>
                                                                <option value="MZ">Mozambique</option>
                                                                <option value="MM">Myanmar</option>
                                                                <option value="NA">Namibia</option>
                                                                <option value="NR">Nauru</option>
                                                                <option value="NP">Nepal</option>
                                                                <option value="NL">Netherlands</option>
                                                                <option value="AN">Netherlands Antilles</option>
                                                                <option value="NC">New Caledonia</option>
                                                                <option value="NZ">New Zealand</option>
                                                                <option value="NI">Nicaragua</option>
                                                                <option value="NE">Niger</option>
                                                                <option value="NG">Nigeria</option>
                                                                <option value="NU">Niue</option>
                                                                <option value="NF">Norfork Island</option>
                                                                <option value="MP">Northern Mariana Islands</option>
                                                                <option value="NO">Norway</option>
                                                                <option value="OM">Oman</option>
                                                                <option value="PK">Pakistan</option>
                                                                <option value="PW">Palau</option>
                                                                <option value="PA">Panama</option>
                                                                <option value="PG">Papua New Guinea</option>
                                                                <option value="PY">Paraguay</option>
                                                                <option value="PE">Peru</option>
                                                                <option value="PH">Philippines</option>
                                                                <option value="PN">Pitcairn</option>
                                                                <option value="PL">Poland</option>
                                                                <option value="PT">Portugal</option>
                                                                <option value="PR">Puerto Rico</option>
                                                                <option value="QA">Qatar</option>
                                                                <option value="RE">Reunion</option>
                                                                <option value="RO">Romania</option>
                                                                <option value="RU">Russian Federation</option>
                                                                <option value="RW">Rwanda</option>
                                                                <option value="KN">Saint Kitts and Nevis</option>
                                                                <option value="LC">Saint Lucia</option>
                                                                <option value="VC">Saint Vincent and the Grenadines</option>
                                                                <option value="WS">Samoa</option>
                                                                <option value="SM">San Marino</option>
                                                                <option value="ST">Sao Tome and Principe</option>
                                                                <option value="SA">Saudi Arabia</option>
                                                                <option value="SN">Senegal</option>
                                                                <option value="RS">Serbia</option>
                                                                <option value="SC">Seychelles</option>
                                                                <option value="SL">Sierra Leone</option>
                                                                <option value="SG">Singapore</option>
                                                                <option value="SK">Slovenská republika</option>
                                                                <option value="SI">Slovenia</option>
                                                                <option value="SB">Solomon Islands</option>
                                                                <option value="SO">Somalia</option>
                                                                <option value="ZA">South Africa</option>
                                                                <option value="GS">South Georgia South Sandwich Islands</option>
                                                                <option value="ES">Spain</option>
                                                                <option value="LK">Sri Lanka</option>
                                                                <option value="SH">St. Helena</option>
                                                                <option value="PM">St. Pierre and Miquelon</option>
                                                                <option value="SD">Sudan</option>
                                                                <option value="SR">Suriname</option>
                                                                <option value="SJ">Svalbarn and Jan Mayen Islands</option>
                                                                <option value="SZ">Swaziland</option>
                                                                <option value="SE">Sweden</option>
                                                                <option value="CH">Switzerland</option>
                                                                <option value="SY">Syrian Arab Republic</option>
                                                                <option value="TW">Taiwan</option>
                                                                <option value="TJ">Tajikistan</option>
                                                                <option value="TZ">Tanzania, United Republic of</option>
                                                                <option value="TH">Thailand</option>
                                                                <option value="TG">Togo</option>
                                                                <option value="TK">Tokelau</option>
                                                                <option value="TO">Tonga</option>
                                                                <option value="TT">Trinidad and Tobago</option>
                                                                <option value="TN">Tunisia</option>
                                                                <option value="TR">Turkey</option>
                                                                <option value="TM">Turkmenistan</option>
                                                                <option value="TC">Turks and Caicos Islands</option>
                                                                <option value="TV">Tuvalu</option>
                                                                <option value="UG">Uganda</option>
                                                                <option value="UA">Ukraine</option>
                                                                <option value="AE">United Arab Emirates</option>
                                                                <option value="GB">United Kingdom</option>
                                                                <option value="UM">United States minor outlying islands</option>
                                                                <option value="UY">Uruguay</option>
                                                                <option value="UZ">Uzbekistan</option>
                                                                <option value="VU">Vanuatu</option>
                                                                <option value="VA">Vatican City State</option>
                                                                <option value="VE">Venezuela</option>
                                                                <option value="VN">Vietnam</option>
                                                                <option value="VG">Virgin Islands (British)</option>
                                                                <option value="VI">Virgin Islands (U.S.)</option>
                                                                <option value="WF">Wallis and Futuna Islands</option>
                                                                <option value="EH">Western Sahara</option>
                                                                <option value="YE">Yemen</option>
                                                                <option value="YU">Yugoslavia</option>
                                                                <option value="ZR">Zaire</option>
                                                                <option value="ZM">Zambia</option>
                                                                <option value="ZW">Zimbabwe</option>
                                                            </optgroup>                                        
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-11">
                                                    <div class="form-group">
                                                        <div class="col-md-10" style="background-color: transparent;padding-left: 0px;">
                                                              Město / Obec, ulice a číslo popisné
                                                        </div>
                                                        <input type="text" required id="city" name="city" class="form-control" placeholder="" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-11">
                                                    <div class="form-group">
                                                        <div class="col-md-10" style="background-color: transparent;padding-left: 0px;">
                                                              PSČ
                                                        </div>
                                                        <input type="text" required id="zip_code" name="zip_code" class="form-control" placeholder="" required>
                                                    </div>
                                                </div>
                                            </div>
                                    

                                            <div class="col-md-6 float-right" style="padding-right:0!important;">
                                                    <div class="col-md-11 float-right"  style="margin: 30px 0 0 0%;">
                                                        <div class="form-group">
                                                            <div class="col-md-10" style="background-color: transparent;padding-left: 0px;">
                                                                  Identifikační číslo osoby
                                                                  <span class="float-right" style="font-size: 9px">Nepovinné</span>
                                                            </div>
                                                            <input type="text" id="ICO" name="ICO" class="form-control" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-11 float-right">
                                                        <div class="form-group">
                                                            <div class="col-md-10" style="background-color: transparent;padding-left: 0px;">
                                                                  Daňové identifikační číslo
                                                                  <span class="float-right" style="font-size: 9px">Nepovinné</span>
                                                            </div>
                                                            <input type="text" id="DIC" name="DIC" class="form-control" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-11 float-right">
                                                        <button name="check_personal_info_change" type="submit" class="btn btn-outline-danger float-right">Změnit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            <!-- 2 -->
                            <div role="tabpanel" class="tab-pane fade" id="tb7" aria-expanded="false">
                                <div class="container">
                                    <div class="row" style="border: 1px solid #eee;width: 70.3%;display: block;margin-left: 112px;margin-top: -30px;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;">

                                        <div class="col-md-12" style="    text-align: center;margin-top: 7px;margin-bottom: 7px;">
                                            <span class="col-md-12">Úroveň zabezpečení uživatelského účtu</span></br>
                                            <input name="account_security_level" id="account_security_level" class="range-slider__range range-slider__range-ssd col-md-7" type="range" min="1" step="1" value="1" max="3" required="">
                                        </div>
                                    </div>
                                </div>

                                <hr/>
                                <div class="newloc" style="display: none;    margin-top: 2.5%;">
                                    <div class="row">
                                        <div class="col-md-12">
                                           <div class="col-md-6 float-left">
                                                <div class="form-group">
                                                    <div class="mb10" style="background-color: transparent;padding-left: 0px;text-align: left;font-size:13px">
                                                        <b style="border-bottom: 1px solid #dfdfdf">Dvoufázové ověření přes <strong>Email (Pouze při změně lokace)</strong><br/><br/></b>
                                                        Při prvním úspěšném přihlášení na Váš účet z jiné lokace<br/> budeme vyžadovat 6-ti místný, náhodně vygenerovaný kód který vám pošleme na <strong>email</strong>.<br/><br/>
                                                    </div>
                                                </div>
                                            </div>
                                            <form action="" method="POST">
                                            {{ Form::token() }}   
                                                <input type="hidden" name="gateway" class="gateway-input">
                                                <div class="col-md-6 float-right">
                                                    <div class="form-group">
                                                        @if($security == 1)
                                                        <div class="mb10" style="background-color: transparent;padding-left: 0px;text-align: left;font-size:13px;">
                                                        @else
                                                        <div class="mb10" style="background-color: transparent;padding-left: 0px;text-align: left;font-size:13px; margin-top: 37px;">
                                                        @endif
                                                            <div class="col-md-12">
                                                                @php
                                                                //echo(join($clientController->get('client_data', 'ips'), '\n '));
                                                                @endphp
                                                                @if($security == 1)
                                                                    <b><strong>Dvoufázové ověření přes Email<br/>je již nastaveno
                                                                    a neni možno ho znovu nastavit.<br/>Pokud si přejete vypnout dvoufázové ověření přes email, budete muset změnit úroveň Vašeho zabezpečení.</strong></b>
                                                                    <hr/>
                                                                @else
                                                                    <button type="submit" name="newloc-btn" id="newloc-btn" class="btn btn-outline-dark float-right">Aktivovat</button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($security == 1)
                                                <table class="table table-responsive table-striped table-hover">
                                                    <thead>
                                                        <th style="border-top: 0px!important;width: 15%;font-weight: 500;">IP ADRESA</th>
                                                        <th style="border-top: 0px!important;width: 25%;font-weight: 500;">LOKACE</th>
                                                        <th style="border-top: 0px!important;width: 10%;font-weight: 500;">STATUS</th>
                                                        <th style="border-top: 0px!important;width:5%;font-weight: 500;">AKCE</th>
                                                    </thead>
                                                    <tbody>
                                                        {!! $clientController->getAccessIPs() !!}
                                                    </tbody>
                                                </table>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                

                                <div class="always-email" style="display: none;    margin-top: 2.5%;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6 float-left">
                                                <div class="form-group">
                                                    <div class="mb10" style="background-color: transparent;padding-left: 0px;text-align: left;font-size:13px">
                                                        <b style="border-bottom: 1px solid #dfdfdf">Dvoufázové ověření přes <strong>Email</strong><br/><br/></b>
                                                        Při každém úspěšném přihlášení na Váš účet<br/> budeme vyžadovat 6-ti místný, náhodně vygenerovaný kód který vám pošleme na <strong>Email</strong>.<br/><br/>
                                                    </div>
                                                </div>
                                            </div>
                                            <form action="" method="POST">
                                                {{ Form::token() }}   
                                                <input type="hidden" name="gateway" class="gateway-input">
                                                <div class="col-md-6 float-right">
                                                    <div class="form-group">
                                                      @if($security == 2)
                                                        <div class="mb10" style="background-color: transparent;padding-left: 0px;text-align: left;font-size:13px;">
                                                        @else
                                                        <div class="mb10" style="background-color: transparent;padding-left: 0px;text-align: left;font-size:13px; margin-top: 37px;">
                                                        @endif
                                                            <div class="col-md-12">
                                                                @if($security == 2)
                                                                    <b><strong>Dvoufázové ověření přes Email<br/>je již nastaveno
                                                                    a neni možno ho znovu nastavit.<br/>Pokud si přejete vypnout dvoufázové ověření přes email, budete muset změnit úroveň Vašeho zabezpečení.</strong></b>
                                                                    <hr/>
                                                                    @else
                                                                    <button type="submit" name="always-email-btn" id="always-email-btn" class="btn btn-outline-dark float-right">Aktivovat</button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>  

                                  

                                <div class="2fa" style="display: none;    margin-top: 2.5%;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-6 float-left">
                                                <div class="form-group">
                                                    <div class="mb10" style="background-color: transparent;padding-left: 0px;text-align: left;font-size:13px">
                                                        <b style="border-bottom: 1px solid #dfdfdf">Dvoufázové ověření přes <strong>Google Authenticator</strong><br/><br/></b>
                                                        Při každém úspěšném přihlášení na Váš účet<br/> budeme vyžadovat 6-ti místný, náhodně vygenerovaný kód z aplikace <strong>Google Authenticator</strong>.<br/>Tato aplikace slouží jako dvou-fázové ověření Vašeho účtu.<br/><br/>
                                                        <a href="https://goo.gl/UigjGw"><img src="/images/apple_store.png" style="width: 120px; vertical-align: baseline!important;"/> <a href="https://goo.gl/QomLaS"><img src="/images/google_play.png" style="width: 120px;margin-left:10px;vertical-align: baseline!important;"/></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <form action="" method="POST">
                                                {{ Form::token() }}   
                                                <input type="hidden" name="gateway" class="gateway-input">
                                                <div class="col-md-6 float-right">
                                                    <div class="form-group">
                                                    @if($clientController->get('client_data', '2fa_secret'))
                                                        <div class="mb10" style="background-color: transparent;padding-left: 0px;text-align: left;font-size:13px">
                                                    @else
                                                        <div class="mb10" style="background-color: transparent;padding-left: 0px;text-align: left;font-size:13px; margin-top: 37px;">
                                                    @endif
                                                        <div class="col-md-12">
                                                            @php
                                                                if(!isset($_SESSION['2fa_secret_QR']))
                                                                {
                                                                    $_SESSION['2fa_secret_QR'] = $g->generateSecret();
                                                                }
                                                                $username = explode('@', $clientController->get('client_info', 'email'))[0];
                                                                $mail = explode('@', $clientController->get('client_info', 'email'))[1];
                                                                //echo $secret;
                                                            @endphp
                                                            @if(!$clientController->get('client_data', '2fa_secret'))
                                                                <img style="width: 130px;margin-top: -40px; margin-bottom: 15px; margin-left:32%" src="{{$g->getURL($username, $mail, $_SESSION['2fa_secret_QR']) }}" />
                                                                <input name="token" type="hidden" value="{{ $_SESSION['2fa_secret_QR'] }}"/>
                                                                <div class="form-group col-md-12 float-right" style="padding-bottom: 0px;padding-right: 0!important">
                                                                    <label style="margin-left: 17%!important">Zadejte ověřovací kód z aplikace</label>
                                                                    <input name="client_code" type='text' required pattern='[0-9]+' class='verify-input col-md-12' placeholder="000000" onfocus="this.placeholder = ''" onblur="this.placeholder = '000000'" style="background: #fff!important;">
                                                                </div>
                                                                <button type="submit" name="2fa-btn" id="2fa-btn" class="btn btn-outline-dark float-right">Aktivovat</button>
                                                            @else
                                                                <b><strong>Dvoufázové ověření přes Google Authenticator<br/>je již nastaveno
                                                                a neni možno ho znovu nastavit.<br/>Pokud si přejete vypnout dvoufázové ověření, budete muset změnit úroveň Vašeho zabezpečení.</strong></b>
                                                                <hr/>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>     
                                    </div>
                                </div>      
                            </div>   
                        </div>


                            <!-- 3 -->
                            <div role="tabpanel" class="tab-pane fade" id="tb8">
                                <div class="container">
                                    <div class="row">
                                    
                                    

                                    </div>
                                </div>
                            </div>

                            <script type="text/javascript">

                                function remove_client_code()
                                {
                                    $.ajax({
                                        url: `https://lan-host.net/api-administrator/__client_code__`,
                                        type: 'GET',
                                  });
                                }


                                function remove_access_ip(ip)
                                {
                                    $.ajax({
                                        url: `https://lan-host.net/api-administrator/__remove_access_ip__`,
                                        type: 'POST',
                                        data: {"_token": "{{ csrf_token() }}" ,arguments: ip}
                                    });
                                }

                                $(document).ready(function()
                                {
                                    $("#first_last_name").val("{{ $personal_info['first_name'].' '.$personal_info['last_name'] }}");
                                    $("#city").val("{{ $personal_info['city'] }}");
                                    $("#zip_code").val("{{ $personal_info['zip'] }}");
                                    states = $("#client_state");
                                    state_name = states.find("option[value='{{ $personal_info['state'] }}']").first().text();
                                    states.find("optgroup[label='Vyhodnoceno']").append("<option value='{{ $personal_info['state'] }}' selected> " + state_name + " </option>");


                                    $('#2fa-modal').on('hidden.bs.modal', function (){ 
                                        remove_client_code();
                                    });
                                    var active;
                                    var account_security_level_current = {{ $security }};
                                    $("#account_security_level").val(account_security_level_current);
                                    process();
                                    $("#account_security_level").on('input', function() {
                                        account_security_level_current = $("#account_security_level").val()
                                        process();
                                    })
                                    function process() {
                                        if(account_security_level_current == 1) {
                                            $("#account_security_level").removeClass('greenslider');
                                            $("#account_security_level").removeClass('orangeslider');
                                            $("#account_security_level").addClass('redslider');
                                            $(active).hide();
                                            $(`.newloc`).fadeToggle();
                                            active = ".newloc";
                                        } else if(account_security_level_current == 2) {
                                            $("#account_security_level").removeClass('greenslider');
                                            $("#account_security_level").removeClass('redslider');
                                            $("#account_security_level").addClass('orangeslider');
                                            $(active).hide();
                                            $(`.always-email`).fadeToggle();
                                            active = ".always-email";
                                        } else {
                                            $("#account_security_level").removeClass('orangeslider');
                                            $("#account_security_level").removeClass('redslider');
                                            $("#account_security_level").addClass('greenslider');
                                            $(active).hide();
                                            $(`.2fa`).fadeToggle();
                                            active = ".2fa";
                                        }
                                    }
                                })
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection