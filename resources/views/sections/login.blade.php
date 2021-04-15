@extends('master')

@section('app_title', $lang->get('title'))

@section('section')
<div class="dzsparallaxer auto-init height-is-based-on-content " data-options='{   direction: "reverse"}'>
    <div class="divimage dzsparallaxer--target " style="width: 101%; height: 130%; background-image: url(/images/bg-6.jpg)">
    </div>
    <div class="container pt100">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto wow bounceIn" data-wow-delay=".2s">
                <h3 class="h3 mb30 text-center pt100 pb100 font300 text-white">{{ $lang->get('panel->title') }}</h3>
            </div>
        </div>
    </div>

</div>
<div class='container pb50  pt50-md'>
    <div class='row'>

        <div class='col-md-6 col-lg-5 mr-auto ml-auto'>
            <div class='card card-account'>
                <div class='card-body' style='padding-top: 0px!important;'>
                    <?php 
                    if(isset($_POST['login_account']))
                        {
                            $clientController->login($_POST);
                        }
                    $g = new \Google\Authenticator\GoogleAuthenticator();
                        if(isset($_POST['test-code'])){
                            if(isset($_SESSION['verify2fa']))
                            {
                                $check_this_code = $_POST['client_code'];
                                $secret = json_decode($_SESSION['verify2fa'][0]->data, true)['client_data']['2fa_secret'];
                                if ($g->checkCode($secret, $check_this_code))
                                {
                                    $_SESSION['user'] = $_SESSION['verify2fa'][0];
                                    unset($_SESSION['verify2fa']);
                                    unset($_SESSION['verify2fa']);
                                    echo throw_alert($lang->fromFile('alert', 'success'), 'Přihlášení proběhlo úspěšně. Budete přesměrován.', 'success');
                                    redirect_to('/', 3);
                                }
                            }
                            elseif($_POST['client_code'] == $_SESSION['verify'][1]) {
                                $_SESSION['user'] = $_SESSION['verify'][0];
                                $data = json_decode(json_decode(DB::table('clients')->where('data->client_info->email', '=' ,json_decode($_SESSION['verify'][0]->data, true)['client_info']['email'])->get()->toJson(), true)[0]['data'], true);
                                array_push($data['client_data']['ips'], $_SERVER['HTTP_X_FORWARDED_FOR']);
                                if(!in_array('FORCEIP', json_decode($_SESSION['verify'][0]->data, true)['client_data']['ips']))
                                DB::table('clients')->where('data->client_info->email', '=' ,json_decode($_SESSION['verify'][0]->data, true)['client_info']['email'])->update(['data' => json_encode($data)]);
                                unset($_SESSION['verify']);
                                echo throw_alert($lang->fromFile('alert', 'success'), 'Přihlášení proběhlo úspěšně. Budete přesměrován.', 'success');
                                redirect_to('/', 3);
                            }
                        }
                    ?>
                    @if(!isset($_SESSION['verify']) && !isset($_SESSION['verify2fa']))
                        <form action="" method="POST">
                            {{ Form::token() }}
                            <div class='form-group'>
                                <label>{{ $lang->get('panel->content->form->client_email->title') }}</label>
                                <input name="client_email" type='email' class='form-control' placeholder='{{ $lang->get('panel->content->form->client_email->placeholder') }}' required <?php if(isset($_SESSION['request_form_login'])){ echo 'value="'.$_SESSION['request_form_login']['client_email'].'"'; } ?>>
                            </div>
                            <div class='form-group'>
                                <label>{{ $lang->get('panel->content->form->client_password->title') }}</label>
                                <input name="client_password" type='password' class='form-control' placeholder='{{ $lang->get('panel->content->form->client_password->placeholder') }}' required <?php if(isset($_SESSION['request_form_login'])){ echo 'value="'.$_SESSION['request_form_login']['client_password'].'"'; } ?>>
                            </div>
                            {!! Form::submit($lang->get('panel->content->form->button_submit'), 
                                ['name' => 'login_account',
                                'class' => 'btn btn-block btn-dark', 
                                'style' => 'max-width: 150px;float: right;']) !!}
                        </form>
                        <br>
                        <p class='mb0 text-small'><a href='/client/register' class='btn btn-underline'>{{ $lang->get('panel->content->footer->link_register') }}</a>
                        </p>
                        <p class=' text-small mb0'>{{ $lang->get('panel->content->footer->password_reset->title') }} <a href='/client/password_reset' class='btn btn-underline'>{{ $lang->get('panel->content->footer->password_reset->link_password_reset') }}</a>
                        </p><br/>
                        @if(isset($_SESSION['request_form_login']))
                        <p class='mb0 text-small'>{{ $lang->get('panel->content->footer->wrong_form->title') }}<a href="/client/form/destroy">{{ $lang->get('panel->content->footer->wrong_form->link_destroy') }}</a></p>
                        @endif
                        <hr>
                        <div class='text-center mb20'>{{ $lang->get('panel->content->footer->social_login') }}</div>
                        <div class="social-login-list">
                            <a href='#' class="btn btn-social facebook btn-block"><i class="fab fa-facebook"></i></a>
                            <a href='#' class="btn btn-social gplus-btn btn-block"><i class="fab fa-google"></i></a>
                            <a href='#' class="btn btn-social github-btn btn-block"><i class="fab fa-github"></i></a>
                            <a href='#' class="btn btn-social twitter-btn btn-block"><i class="fab fa-twitter"></i></a>
                            
                        </div>
                    @else
                    @php
                    if(!isset($_SESSION['verify'][1]) && !isset($_SESSION['verify2fa']))
                    {
                        $_SESSION['verify'][1] = generatePIN();
                        $info = json_decode($_SESSION['verify'][0]->data, true);
                        $message = "<h1 style='text-align: center; font: 15px/1.2 'Robotica', sans-serif;'>Ověřovací kod !</h1><br/>
                        <div class='center' style='text-align: center;'>Váš ověřovací kod je " . $_SESSION['verify'][1] . "</a></div><br/>
                        <p>
                        Děkujeme, lan-host.net<br/>
                        Copyright © " . date("Y") . " lan-host.net";
                        \App\Http\Controllers\MailController::send([
                            'subject' => 'Ověřovací kod',
                            'text' => $message,
                            'receiver_email' => $info['client_info']['email'],
                            'receiver_name' => $info['billing_info']['first_name']
                        ]);
                    }
                    @endphp
                    @if(isset($_SESSION['verify2fa']))
                    Zadejte ověřovací kód z aplikace <strong style="color: black!important;">Google Authenticator</strong>
                    @elseif(isset($_SESSION['verify']))
                    Zadejte ověřovací kód který vám přišel na <strong style="color: black!important;">Email</strong>
                    @endif
                    
                        <form action="" method="POST">
                            {{ Form::token() }}
                            <div class='form-group'>
                            </br>
                                <input name="client_code" type='text' pattern='[0-9]+' class='verify-input col-md-12' onfocus="this.placeholder = ''" onblur="this.placeholder = '000000'" placeholder='000000'>
                            </div>
                            {!! Form::submit($lang->get('panel->content->form->button_submit'), 
                                ['name' => 'test-code',
                                'class' => 'btn btn-block btn-dark', 
                                'style' => 'max-width: 150px;float: right;']) !!}
                        </form>
                        <p class='mb0 text-small'><a href="/client/login/cancel" style="margin-top: 7px;" class='btn btn-underline'>Přerušit ověření</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection