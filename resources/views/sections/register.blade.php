@extends('master')

@section('app_title', 'Vytvoření nového účtu')

@section('section')
<div class="dzsparallaxer auto-init height-is-based-on-content " data-options='{direction: "reverse"}'>
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
        <div class='col-md-6 col-lg-12 mr-auto ml-auto'>
            <div class='card card-account'>
                <div class='card-body'>
                    <?php
                    echo throw_alert($lang->get('error->registration_disabled->title'), $lang->get('error->registration_disabled->content'), 'danger'); 

                    if(isset($_POST['create_account'])){ 
                        //$clientController->create($_POST);
                    }
                    ?>
                    <form action="" method="POST">
                        {{ Form::token() }}
                        <div class="row mb40">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $lang->get('panel->content->form->client_firstname->title') }}</label>
                                    <input type="text" required name="client_firstname" class="form-control" placeholder="{{ $lang->get('panel->content->form->client_firstname->placeholder') }}"  <?php if(isset($_SESSION['request_form'])){ echo 'value="'.$_SESSION['request_form']['client_firstname'].'"'; } ?>>
                                </div>
                            </div><!--input col-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $lang->get('panel->content->form->client_lastname->title') }}</label>
                                    <input type="text" required name="client_lastname" class="form-control" placeholder="{{ $lang->get('panel->content->form->client_lastname->placeholder') }}"  <?php if(isset($_SESSION['request_form'])){ echo 'value="'.$_SESSION['request_form']['client_lastname'].'"'; } ?>>
                                </div>
                            </div><!--input col-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $lang->get('panel->content->form->client_password->title') }}</label>
                                    <input type="password" required name="client_password" class="form-control" placeholder="{{ $lang->get('panel->content->form->client_password->placeholder') }}"  <?php if(isset($_SESSION['request_form'])){ echo 'value="'.$_SESSION['request_form']['client_password'].'"'; } ?>>
                                </div>
                            </div><!--input col-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $lang->get('panel->content->form->client_password_check->title') }}</label>
                                    <input type="password" required name="client_password_check" class="form-control" placeholder="{{ $lang->get('panel->content->form->client_password_check->placeholder') }}"  <?php if(isset($_SESSION['request_form'])){ echo 'value="'.$_SESSION['request_form']['client_password_check'].'"'; } ?>>
                                </div>
                            </div><!--input col-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $lang->get('panel->content->form->client_email->title') }}</label>
                                    <input type="text" required name="client_email" class="form-control" placeholder="{{ $lang->get('panel->content->form->client_email->placeholder') }}"  <?php if(isset($_SESSION['request_form'])){ echo 'value="'.$_SESSION['request_form']['client_email'].'"'; } ?>>
                                </div>
                            </div><!--input col-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $lang->get('panel->content->form->client_phone->title') }}</label>
                                    <input type="text" name="client_phone" class="form-control" placeholder="{{ $lang->get('panel->content->form->client_phone->placeholder') }}" <?php if(isset($_SESSION['request_form'])){ echo 'value="'.$_SESSION['request_form']['client_phone'].'"'; } ?>>
                                </div>
                            </div><!--input col-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $lang->get('panel->content->form->client_state->title') }}</label>
                                    <select required name="client_state" class="form-control" >
                                        <optgroup label="{{ $lang->get('panel->content->form->client_state->optgroup_1') }}">
                                            <option value="CZ" selected>Česká republika</option>
                                        </optgroup>
                                        <optgroup label="{{ $lang->get('panel->content->form->client_state->optgroup_2') }}">
                                            <option value="CZ">Česká republika</option>
                                            <option value="SK">Slovenská republika</option>
                                        </optgroup>
                                        <optgroup label="{{ $lang->get('panel->content->form->client_state->optgroup_3') }}">
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
                            </div><!--input col-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $lang->get('panel->content->form->client_city->title') }}</label>
                                    <input type="text" required name="client_city" class="form-control" placeholder="{{ $lang->get('panel->content->form->client_city->placeholder') }}"  <?php if(isset($_SESSION['request_form'])){ echo 'value="'.$_SESSION['request_form']['client_city'].'"'; } ?>>
                                </div>
                            </div><!--input col-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $lang->get('panel->content->form->client_zip->title') }}</label>
                                    <input type="text" required name="client_zip" class="form-control" placeholder="{{ $lang->get('panel->content->form->client_zip->placeholder') }}"  <?php if(isset($_SESSION['request_form'])){ echo 'value="'.$_SESSION['request_form']['client_zip'].'"'; } ?>>
                                </div>
                            </div><!--input col-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ $lang->get('panel->content->form->client_type->title') }}</label>
                                    <select name="client_type" class="form-control">
                                        <option>{{ $lang->get('panel->content->form->client_type->option_1') }}</option>
                                        <option>{{ $lang->get('panel->content->form->client_type->option_2') }}</option>
                                    </select>
                                </div>
                            </div><!--input col-->
                            <div class="col-md-12">
                                <div class="col-md-6 float-right form-group" style="padding-right: 0px;">
                                    <label>{{ $lang->get('panel->content->form->client_captcha->title') }}</label>
                                    <div class="input-group">
                                          <span class="input-group-addon input-captcha-code" id="captcha-code"><?php if(isset($_SESSION['request_form'])){ echo $_SESSION['server_captcha']; }else{ echo $captcha; } ?></span>
                                          <input type="text" required name="client_captcha" class="form-control" placeholder="{{ $lang->get('panel->content->form->client_captcha->placeholder') }}" aria-describedby="captcha-code"  <?php if(isset($_SESSION['request_form'])){ echo 'value="'.$_SESSION['request_form']['client_captcha'].'"'; } ?>>
                                        </div>
                                </div>
                            </div><!--input col-->

                            <div class="col-md-6 mb20">
                                <label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
                                    <input required type="checkbox" name="tos_accept" class="custom-control-input" >
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description" style="font-size: 13px">{{ $lang->get('panel->content->form->tos_accept->title') }} <a href="">{{ $lang->get('panel->content->form->tos_accept->link_vop') }}</a>.</span>
                                </label>
                            </div>
                            <div class="col-md-6 mb20 float-right">
                                {{ Form::submit($lang->get('panel->content->form->button_submit'), 
                                    ['name' => 'create_account',
                                    'class' => 'btn btn-block btn-dark', 
                                    'style' => 'max-width: 150px;float: right;']) }}
                            </div>
                        </div>
                    </form>
                    <br>
                    <p class='mb0 text-small'><a href='/client/login' class='btn btn-underline'>{{ $lang->get('panel->content->footer->link_login') }}</a>
                    </p>
                    <br/>
                    @if(isset($_SESSION['request_form']))
                    <p class='mb0 text-small'>{{ $lang->get('panel->content->footer->wrong_form->title') }} <a href="/client/form/destroy">{{ $lang->get('panel->content->footer->wrong_form->link_destroy') }}</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection