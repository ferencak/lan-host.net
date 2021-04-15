@extends('master')


@section('app_title', 'Vytvoření nového účtu')

@section('section')


<div class="dzsparallaxer auto-init height-is-based-on-content " data-options='{direction: "reverse"}'>
    <div class="divimage dzsparallaxer--target " style="width: 101%; height: 130%; background-image: url(/images/bg-6.jpg)">
    </div>

    <div class="container pt100">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto wow bounceIn" data-wow-delay=".2s">
                <h3 class="h3 mb30 text-center pt100 pb100 font300 text-white">Obnovení hesla</h3>
            </div>
        </div>
    </div>

</div>
<div class='container pb50  pt50-md'>
    <div class='row'>
        <div class='col-md-6 col-lg-5 mr-auto ml-auto'>
            <div class='card card-account'>
                <div class='card-body'>

                    @if(isset($_POST['change_password']))
                        @php
                            $_POST['id'] = $id;
                            $_POST['token'] = $token;
                            $clientController->change_password($_POST);
                        @endphp
                    @endif

                    @if($clientController->check(['id' => $id, 'token' => $token]))

                    <form action="" method="POST">
                        {{ Form::token() }}
                        <div class='form-group'>
                            <label>Zadejte svoje nové heslo</label>
                            <input name="password" type='password' class='form-control' placeholder='Zadejte svoje nové heslo...' required>
                        </div>
                        <div class='form-group'>
                            <label>Zadejte svoje nové heslo znovu</label>
                            <input name="password_again" type='password' class='form-control' placeholder='Znovu zadejte svoje nové heslo...' required>
                        </div>
                        {!! Form::submit("Změnit heslo", 
                            ['name' => 'change_password',
                            'class' => 'btn btn-block btn-dark', 
                             'style' => 'margin-top: 6px; max-width: 150px;float: right;']) !!}
                    </form>

                    @endif

                    @if(isset($_POST['reset_password']))
                        @php 
                            $clientController->reset_password($_POST);
                        @endphp
                    @endif

                    @if(!$clientController->check(['id' => $id, 'token' => $token])) 

                        <form action="" method="POST">
                            {{ Form::token() }}
                            <div class='form-group'>
                                <label>Emailová adresa</label>
                                <input name="client_email" type='email' class='form-control' placeholder='Zadejte emailovou adresu...' required>
                            </div>
                            {!! Form::submit("Obnovit heslo", 
                                ['name' => 'reset_password',
                                'class' => 'btn btn-block btn-dark', 
                                'style' => 'margin-top: 6px; max-width: 150px;float: right;']) !!}
                        </form>

                    @endif

                    <p class='mb0 text-small'><a href='/client/login' style="margin-top: 10px;" class='btn btn-underline'>Přejít k příhlášení</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection