<?php include("../server/connection.php") ?>
<!DOCTYPE html>
<html lang="en">


<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Registration - <?php echo  $sitename ?> </title>
    <link rel="icon" type="image/x-icon" href="./asset/img/favicon.ico" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&amp;display=swap" rel="stylesheet">
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="./asset/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="./extra/formcss.css" rel="stylesheet" type="text/css" />

    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="./asset/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="./asset/css/forms/switches.css">


    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="./asset/css/scrollspyNav.css" rel="stylesheet" type="text/css" />
    <link href="./plugins/animate/animate.css" rel="stylesheet" type="text/css" />
    <link href="./plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="./asset/css/elements/alert.css">
    <script src="./plugins/sweetalerts/promise-polyfill.js"></script>
    <link href="./plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="./plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="./asset/css/components/custom-sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="./asset/js/libs/jquery-3.1.1.min.js"></script>
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    <!-- jQuery (required by Toastr) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <style>
        #Div2 {
            display: none;
        }

        #nextShow {
            display: none;
        }

        #Div4 {
            display: none;
        }

        #Button11 {
            display: none;
        }

        .container-div {
            height: 100%;
        }

        canvas {
            height: 100px;
            border-style: solid;
            border-width: 1px;
            border-color: black;
        }


        .hidden {
            display: none !important;
        }
    </style>
</head>

<body>


    <section class="wizard-section">
        <div class="row no-gutters">
            <div class="col-lg-6 col-md-6 container-div">
                <div class="wizard-content-left d-flex justify-content-center align-items-center">
                    <h1>Create Your Bank Account</h1>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 container-div">
                <div class="form-wizard">
                    <form method="post" role="form" enctype="multipart/form-data" id="regForm">

                        <input type="text" name="url" id="url" hidden>

                        <div class="form-wizard-header">
                            <p>Fill all form field to go next step</p>
                            <ul class="list-unstyled form-wizard-steps clearfix">
                                <li class="active"><span>1</span></li>
                                <li><span>2</span></li>
                                
                                <li><span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-check">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg></span></li>
                            </ul>
                        </div>
                        <fieldset class="wizard-fieldset show">

                            <h5>Personal Info</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control wizard-required" id="fname"
                                            name="firstname">
                                        <label for="fname" class="wizard-form-text-label">First Name*</label>
                                        <div class="wizard-form-error"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control wizard-required" id="lname"
                                            name="lastname">
                                        <label for="lname" class="wizard-form-text-label">Last Name*</label>
                                        <div class="wizard-form-error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" class="form-control wizard-required" id="Email"
                                            name="acct_email">
                                        <label for="Email" class="wizard-form-text-label">Email</label>
                                        <div class="wizard-form-error"></div>
                                    </div>
                                </div>




                                <div class="col-md-6">

                                    <div class="form-group">
                                        <select class="form-control" name="country" required>
                                            <option selected="selected">Select Country</option>
                                            <option value="Afganistan">Afghanistan</option>
                                            <option value="Albania">Albania</option>
                                            <option value="Algeria">Algeria</option>
                                            <option value="American Samoa">American Samoa</option>
                                            <option value="Andorra">Andorra</option>
                                            <option value="Angola">Angola</option>
                                            <option value="Anguilla">Anguilla</option>
                                            <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                            <option value="Argentina">Argentina</option>
                                            <option value="Armenia">Armenia</option>
                                            <option value="Aruba">Aruba</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Austria">Austria</option>
                                            <option value="Azerbaijan">Azerbaijan</option>
                                            <option value="Bahamas">Bahamas</option>
                                            <option value="Bahrain">Bahrain</option>
                                            <option value="Bangladesh">Bangladesh</option>
                                            <option value="Barbados">Barbados</option>
                                            <option value="Belarus">Belarus</option>
                                            <option value="Belgium">Belgium</option>
                                            <option value="Belize">Belize</option>
                                            <option value="Benin">Benin</option>
                                            <option value="Bermuda">Bermuda</option>
                                            <option value="Bhutan">Bhutan</option>
                                            <option value="Bolivia">Bolivia</option>
                                            <option value="Bonaire">Bonaire</option>
                                            <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                            <option value="Botswana">Botswana</option>
                                            <option value="Brazil">Brazil</option>
                                            <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                            <option value="Brunei">Brunei</option>
                                            <option value="Bulgaria">Bulgaria</option>
                                            <option value="Burkina Faso">Burkina Faso</option>
                                            <option value="Burundi">Burundi</option>
                                            <option value="Cambodia">Cambodia</option>
                                            <option value="Cameroon">Cameroon</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Canary Islands">Canary Islands</option>
                                            <option value="Cape Verde">Cape Verde</option>
                                            <option value="Cayman Islands">Cayman Islands</option>
                                            <option value="Central African Republic">Central African Republic</option>
                                            <option value="Chad">Chad</option>
                                            <option value="Channel Islands">Channel Islands</option>
                                            <option value="Chile">Chile</option>
                                            <option value="China">China</option>
                                            <option value="Christmas Island">Christmas Island</option>
                                            <option value="Cocos Island">Cocos Island</option>
                                            <option value="Colombia">Colombia</option>
                                            <option value="Comoros">Comoros</option>
                                            <option value="Congo">Congo</option>
                                            <option value="Cook Islands">Cook Islands</option>
                                            <option value="Costa Rica">Costa Rica</option>
                                            <option value="Cote DIvoire">Cote DIvoire</option>
                                            <option value="Croatia">Croatia</option>
                                            <option value="Cuba">Cuba</option>
                                            <option value="Curaco">Curacao</option>
                                            <option value="Cyprus">Cyprus</option>
                                            <option value="Czech Republic">Czech Republic</option>
                                            <option value="Denmark">Denmark</option>
                                            <option value="Djibouti">Djibouti</option>
                                            <option value="Dominica">Dominica</option>
                                            <option value="Dominican Republic">Dominican Republic</option>
                                            <option value="East Timor">East Timor</option>
                                            <option value="Ecuador">Ecuador</option>
                                            <option value="Egypt">Egypt</option>
                                            <option value="El Salvador">El Salvador</option>
                                            <option value="Equatorial Guinea">Equatorial Guinea</option>
                                            <option value="Eritrea">Eritrea</option>
                                            <option value="Estonia">Estonia</option>
                                            <option value="Ethiopia">Ethiopia</option>
                                            <option value="Falkland Islands">Falkland Islands</option>
                                            <option value="Faroe Islands">Faroe Islands</option>
                                            <option value="Fiji">Fiji</option>
                                            <option value="Finland">Finland</option>
                                            <option value="France">France</option>
                                            <option value="French Guiana">French Guiana</option>
                                            <option value="French Polynesia">French Polynesia</option>
                                            <option value="French Southern Ter">French Southern Ter</option>
                                            <option value="Gabon">Gabon</option>
                                            <option value="Gambia">Gambia</option>
                                            <option value="Georgia">Georgia</option>
                                            <option value="Germany">Germany</option>
                                            <option value="Ghana">Ghana</option>
                                            <option value="Gibraltar">Gibraltar</option>
                                            <option value="Great Britain">Great Britain</option>
                                            <option value="Greece">Greece</option>
                                            <option value="Greenland">Greenland</option>
                                            <option value="Grenada">Grenada</option>
                                            <option value="Guadeloupe">Guadeloupe</option>
                                            <option value="Guam">Guam</option>
                                            <option value="Guatemala">Guatemala</option>
                                            <option value="Guinea">Guinea</option>
                                            <option value="Guyana">Guyana</option>
                                            <option value="Haiti">Haiti</option>
                                            <option value="Hawaii">Hawaii</option>
                                            <option value="Honduras">Honduras</option>
                                            <option value="Hong Kong">Hong Kong</option>
                                            <option value="Hungary">Hungary</option>
                                            <option value="Iceland">Iceland</option>
                                            <option value="Indonesia">Indonesia</option>
                                            <option value="India">India</option>
                                            <option value="Iran">Iran</option>
                                            <option value="Iraq">Iraq</option>
                                            <option value="Ireland">Ireland</option>
                                            <option value="Isle of Man">Isle of Man</option>
                                            <option value="Israel">Israel</option>
                                            <option value="Italy">Italy</option>
                                            <option value="Jamaica">Jamaica</option>
                                            <option value="Japan">Japan</option>
                                            <option value="Jordan">Jordan</option>
                                            <option value="Kazakhstan">Kazakhstan</option>
                                            <option value="Kenya">Kenya</option>
                                            <option value="Kiribati">Kiribati</option>
                                            <option value="Korea North">Korea North</option>
                                            <option value="Korea Sout">Korea South</option>
                                            <option value="Kuwait">Kuwait</option>
                                            <option value="Kyrgyzstan">Kyrgyzstan</option>
                                            <option value="Laos">Laos</option>
                                            <option value="Latvia">Latvia</option>
                                            <option value="Lebanon">Lebanon</option>
                                            <option value="Lesotho">Lesotho</option>
                                            <option value="Liberia">Liberia</option>
                                            <option value="Libya">Libya</option>
                                            <option value="Liechtenstein">Liechtenstein</option>
                                            <option value="Lithuania">Lithuania</option>
                                            <option value="Luxembourg">Luxembourg</option>
                                            <option value="Macau">Macau</option>
                                            <option value="Macedonia">Macedonia</option>
                                            <option value="Madagascar">Madagascar</option>
                                            <option value="Malaysia">Malaysia</option>
                                            <option value="Malawi">Malawi</option>
                                            <option value="Maldives">Maldives</option>
                                            <option value="Mali">Mali</option>
                                            <option value="Malta">Malta</option>
                                            <option value="Marshall Islands">Marshall Islands</option>
                                            <option value="Martinique">Martinique</option>
                                            <option value="Mauritania">Mauritania</option>
                                            <option value="Mauritius">Mauritius</option>
                                            <option value="Mayotte">Mayotte</option>
                                            <option value="Mexico">Mexico</option>
                                            <option value="Midway Islands">Midway Islands</option>
                                            <option value="Moldova">Moldova</option>
                                            <option value="Monaco">Monaco</option>
                                            <option value="Mongolia">Mongolia</option>
                                            <option value="Montserrat">Montserrat</option>
                                            <option value="Morocco">Morocco</option>
                                            <option value="Mozambique">Mozambique</option>
                                            <option value="Myanmar">Myanmar</option>
                                            <option value="Nambia">Nambia</option>
                                            <option value="Nauru">Nauru</option>
                                            <option value="Nepal">Nepal</option>
                                            <option value="Netherland Antilles">Netherland Antilles</option>
                                            <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                            <option value="Nevis">Nevis</option>
                                            <option value="New Caledonia">New Caledonia</option>
                                            <option value="New Zealand">New Zealand</option>
                                            <option value="Nicaragua">Nicaragua</option>
                                            <option value="Niger">Niger</option>
                                            <option value="Nigeria">Nigeria</option>
                                            <option value="Niue">Niue</option>
                                            <option value="Norfolk Island">Norfolk Island</option>
                                            <option value="Norway">Norway</option>
                                            <option value="Oman">Oman</option>
                                            <option value="Pakistan">Pakistan</option>
                                            <option value="Palau Island">Palau Island</option>
                                            <option value="Palestine">Palestine</option>
                                            <option value="Panama">Panama</option>
                                            <option value="Papua New Guinea">Papua New Guinea</option>
                                            <option value="Paraguay">Paraguay</option>
                                            <option value="Peru">Peru</option>
                                            <option value="Phillipines">Philippines</option>
                                            <option value="Pitcairn Island">Pitcairn Island</option>
                                            <option value="Poland">Poland</option>
                                            <option value="Portugal">Portugal</option>
                                            <option value="Puerto Rico">Puerto Rico</option>
                                            <option value="Qatar">Qatar</option>
                                            <option value="Republic of Montenegro">Republic of Montenegro</option>
                                            <option value="Republic of Serbia">Republic of Serbia</option>
                                            <option value="Reunion">Reunion</option>
                                            <option value="Romania">Romania</option>
                                            <option value="Russia">Russia</option>
                                            <option value="Rwanda">Rwanda</option>
                                            <option value="St Barthelemy">St Barthelemy</option>
                                            <option value="St Eustatius">St Eustatius</option>
                                            <option value="St Helena">St Helena</option>
                                            <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                            <option value="St Lucia">St Lucia</option>
                                            <option value="St Maarten">St Maarten</option>
                                            <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                            <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                            <option value="Saipan">Saipan</option>
                                            <option value="Samoa">Samoa</option>
                                            <option value="Samoa American">Samoa American</option>
                                            <option value="San Marino">San Marino</option>
                                            <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                            <option value="Saudi Arabia">Saudi Arabia</option>
                                            <option value="Senegal">Senegal</option>
                                            <option value="Seychelles">Seychelles</option>
                                            <option value="Sierra Leone">Sierra Leone</option>
                                            <option value="Singapore">Singapore</option>
                                            <option value="Slovakia">Slovakia</option>
                                            <option value="Slovenia">Slovenia</option>
                                            <option value="Solomon Islands">Solomon Islands</option>
                                            <option value="Somalia">Somalia</option>
                                            <option value="South Africa">South Africa</option>
                                            <option value="Spain">Spain</option>
                                            <option value="Sri Lanka">Sri Lanka</option>
                                            <option value="Sudan">Sudan</option>
                                            <option value="Suriname">Suriname</option>
                                            <option value="Swaziland">Swaziland</option>
                                            <option value="Sweden">Sweden</option>
                                            <option value="Switzerland">Switzerland</option>
                                            <option value="Syria">Syria</option>
                                            <option value="Tahiti">Tahiti</option>
                                            <option value="Taiwan">Taiwan</option>
                                            <option value="Tajikistan">Tajikistan</option>
                                            <option value="Tanzania">Tanzania</option>
                                            <option value="Thailand">Thailand</option>
                                            <option value="Togo">Togo</option>
                                            <option value="Tokelau">Tokelau</option>
                                            <option value="Tonga">Tonga</option>
                                            <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                            <option value="Tunisia">Tunisia</option>
                                            <option value="Turkey">Turkey</option>
                                            <option value="Turkmenistan">Turkmenistan</option>
                                            <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                            <option value="Tuvalu">Tuvalu</option>
                                            <option value="Uganda">Uganda</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="Ukraine">Ukraine</option>
                                            <option value="United Arab Erimates">United Arab Emirates</option>
                                            <option value="United States of America">United States of America</option>
                                            <option value="Uraguay">Uruguay</option>
                                            <option value="Uzbekistan">Uzbekistan</option>
                                            <option value="Vanuatu">Vanuatu</option>
                                            <option value="Vatican City State">Vatican City State</option>
                                            <option value="Venezuela">Venezuela</option>
                                            <option value="Vietnam">Vietnam</option>
                                            <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                            <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                            <option value="Wake Island">Wake Island</option>
                                            <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                            <option value="Yemen">Yemen</option>
                                            <option value="Zaire">Zaire</option>
                                            <option value="Zambia">Zambia</option>
                                            <option value="Zimbabwe">Zimbabwe</option>
                                        </select>
                                    </div>

                                </div>




                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="position: relative;">
                                        <input type="password" class="form-control wizard-required" id="pwd" name="acct_password">
                                        <label for="pwd" class="wizard-form-text-label">Password*</label>
                                        <div class="wizard-form-error"></div>

                                        <!-- Toggle Eye Icon -->
                                        <span class="toggle-password" data-target="pwd" style="position: absolute; top: 50%; right: 10px; cursor: pointer;  transform: translateY(-50%);">
                                            <!-- Eye icon (visible initially) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon-eye" width="24" height="24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                viewBox="0 0 24 24">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>

                                            <!-- Eye-off icon (hidden by default) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon-eye-off hidden" width="24" height="24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                viewBox="0 0 24 24">
                                                <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.14 21.14 0 0 1 5.06-6.06" />
                                                <path d="M1 1l22 22" />
                                            </svg>

                                        </span>
                                    </div>


                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="password" class="form-control wizard-required" id="confirmPassword"
                                            name="confirmPassword">
                                        <label for="confirmPassword" class="wizard-form-text-label">Confirm
                                            Password*</label>
                                        <div class="wizard-form-error"></div>
                                        <!-- Toggle Eye Icon -->
                                        <span class="toggle-password" data-target="confirmPassword" style="position: absolute; top: 50%; right: 10px; cursor: pointer;  transform: translateY(-50%);">
                                            <!-- Eye icon (visible initially) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon-eye" width="24" height="24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                viewBox="0 0 24 24">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>

                                            <!-- Eye-off icon (hidden by default) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon-eye-off hidden" width="24" height="24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                viewBox="0 0 24 24">
                                                <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.14 21.14 0 0 1 5.06-6.06" />
                                                <path d="M1 1l22 22" />
                                            </svg>

                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group clearfix" style="">
                                <a href="index.php" style="margin-left:10px;background:red;"
                                    class="form-wizard-next-btn">Back To Login</a>
                                <a href="javascript:;" class="form-wizard-next-btn float-right">Next</a>
                            </div>
                        </fieldset>
                        <fieldset class="wizard-fieldset">
                            <h5>Create your login</h5>
                            <h5>Account Details</h5>
                            <div class="form-group">
                                <select class="form-control" name="currency" id="currency-select" required>
                                    <option selected disabled>Select Currency</option>

                                    <option value="$">USD</option>
                                    <option value="€">EUR</option>
                                    <option value="¥">JPY</option>
                                    <option value="£">GBP</option>
                                    <option value="A$">AUD</option>
                                    <option value="C$">CAD</option>
                                    <option value="Fr">CHF</option>
                                    <option value="¥">CNY</option>
                                    <option value="₹">INR</option>
                                    <option value="R$">BRL</option>
                                    <option value="₽">RUB</option>
                                    <option value="₩">KRW</option>
                                    <option value="₪">ILS</option>
                                    <option value="₺">TRY</option>
                                    <option value="₦">NGN</option>
                                    <option value="₫">VND</option>
                                    <option value="฿">THB</option>
                                    <option value="₱">PHP</option>
                                    <option value="₡">CRC </option>
                                    <option value="₲">PYG </option>
                                    <option value="zł">PLN</option>
                                    <option value="R">ZAR</option>
                                    <option value="฿">MMK </option>
                                    <option value="₴">UAH</option>
                                    <option value="₺">AZN </option>
                                    <option value="₸">KZT </option>
                                    <option value="﷼">IRR </option>
                                    <option value="₨">PKR </option>
                                    <option value="₭">LAK</option>
                                    <option value="₡">CRC</option>

                                </select>
                            </div>

                            <div class="form-group">
                                <select class="form-control" name="account-type" id="currency-select" required>
                                    <option selected disabled>Account Type</option>
                                    <option value="Savings" data-select2-id="13">Savings Account</option>
                                    <option value="Current" data-select2-id="14">Current Account</option>
                                    <option value="Checking" data-select2-id="15">Checking Account</option>
                                    <option value="Fixed Deposit" data-select2-id="16">Fixed Deposit</option>
                                    <option value="Non Resident" data-select2-id="17">Non Resident Account</option>
                                    <option value="Online Banking" data-select2-id="18">Online Banking</option>
                                    <option value="Domicilary Account" data-select2-id="19">Domicilary Account</option>
                                    <option value="Joint Account" data-select2-id="20">Joint Account</option>


                                </select>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control wizard-required" id="username"
                                    name="acct_pin">
                                <label for="city" class="wizard-form-text-label">Account Pin*</label>
                                <div class="wizard-form-error"></div>
                            </div>



                            <div class="form-group clearfix">
                                <a href="javascript:;" class="form-wizard-previous-btn float-left">Previous</a>

                                <a href="javascript:;" class="form-wizard-next-btn float-right">Next</a>
                            </div>
                        </fieldset>
                        <fieldset class="wizard-fieldset">
                                                          

                                <div class="form-group">

                                    <label for="dob" class="">Date of Birth*</label>
                                    <input type="date" class="form-control wizard-required" id="dob" name="dob">
                                    <div class="wizard-form-error"></div>
                                    <span class="wizard-password-eye"><i class="far fa-eye"></i></span>
                                </div>

                                <div class="form-group">
                                    <label for="dob" class="">Profile Image*</label>
                                    <input type="file" class="form-control wizard-required" id="dob" name="profile">
                                    <div class="wizard-form-error"></div>
                                    <span class="wizard-password-eye"><i class="far fa-eye"></i></span>
                                </div>


                            

                            <div class="form-group clearfix">
                                <a href="javascript:;" class="form-wizard-previous-btn float-left">Previous</a>
                                <button class="form-wizard-submit float-right btn btn-primary" type="submit"
                                    name="regSubmit">Submit</button>
                            </div>
                        </fieldset>


                        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
                        <script>
                            document.getElementById('url').value = window.location.href;
                            toastr.options = {

                                "closeButton": true,
                                "progressBar": true,
                                "positionClass": "toast-bottom-right", // other options: toast-bottom-left, toast-bottom-right, toast-top-left, toast-top-full-width, toast-bottom-full-width, toast-top-center, toast-bottom-center
                                "timeOut": "2000" // milliseconds before toast disappears
                            }




                            let form = document.getElementById('regForm');
                            form.addEventListener('submit', (event) => {
                                event.preventDefault();

                                console.log('Form submitted!');

                                let formData = new FormData(form);

                                // Debug: log form values
                                for (let [key, value] of formData.entries()) {
                                    console.log(`${key}: ${value}`);
                                }

                                $.ajax({
                                    url: '<?php echo $domain; ?>server/api/client/register.php',
                                    type: 'POST',
                                    data: formData,
                                    processData: false, // 👈 Required for FormData
                                    contentType: false, // 👈 Required for FormData
                                    success: function(data) {
                                        console.log(data);

                                        // If needed, parse JSON string
                                        // if (typeof data === 'string') data = JSON.parse(data);

                                        if (data.status === 'success') {
                                            toastr.success(data.message);
                                            setTimeout(() => {
                                                location.href = '<?php echo $domain; ?>auth/index.php';
                                            }, 3000);
                                        } else {
                                            toastr.error(data.message);
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Error:', error);
                                        toastr.error('An error occurred while processing your request.');
                                    }
                                });
                            });
                        </script>

                    </form>
                </div>
            </div>
        </div>
    </section>






    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="./bootstrap/js/popper.min.js"></script>
    <script src="./bootstrap/js/bootstrap.min.js"></script>
    <script src="./plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="./asset/js/app.js"></script>

    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="./asset/js/authentication/form-2.js"></script>
    <script src="./plugins/highlight/highlight.pack.js"></script>
    <script src="./asset/js/custom.js"></script>
    <!-- END GLOBAL MANDATORY STYLES -->
    <script src="./plugins/notification/snackbar/snackbar.min.js"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <script src="./asset/js/components/notification/custom-snackbar.js"></script>
    <!--  END CUSTOM SCRIPTS FILE  -->

    <!-- BEGIN THEME GLOBAL STYLE -->
    <script src="./asset/js/scrollspyNav.js"></script>
    <script src="./plugins/sweetalerts/sweetalert2.min.js"></script>
    <script src="./plugins/sweetalerts/custom-sweetalert.js"></script>
    <script src="./extra/formjs.js"></script>
    <!-- END THEME GLOBAL STYLE -->
    <script>
        $(document).ready(function() {
            $(".numpad").hide();
            $('.input').click(function() {
                $('.numpad').fadeToggle('fast');
            });

            $('.del').click(function() {
                $('.input').val($('.input').val().substring(0, $('.input').val().length - 1));
            });
            $('.faq').click(function() {
                alert("Enter Your OTP Sent to you ");
            })
            $('.shuffle').click(function() {
                $('.input').val($('.input').val() + $(this).text());
                $('.shuffle').shuffle();
            });
            (function($) {

                $.fn.shuffle = function() {

                    var allElems = this.get(),
                        getRandom = function(max) {
                            return Math.floor(Math.random() * max);
                        },
                        shuffled = $.map(allElems, function() {
                            var random = getRandom(allElems.length),
                                randEl = $(allElems[random]).clone(true)[0];
                            allElems.splice(random, 1);
                            return randEl;
                        });

                    this.each(function(i) {
                        $(this).replaceWith($(shuffled[i]));
                    });

                    return $(shuffled);

                };

            })(jQuery);

        });
    </script>
    <script>
        $(function() {
            $('#datepicker').keypress(function(event) {
                event.preventDefault();
                return false;
            });
        });


        function switchVisible() {
            if (document.getElementById('Div1')) {

                if (document.getElementById('Div1').style.display === 'none') {
                    document.getElementById('Div1').style.display = 'block';
                    document.getElementById('Div2').style.display = 'none';
                    document.getElementById('nextShow').style.display = 'none';
                } else {
                    document.getElementById('Div1').style.display = 'none';
                    document.getElementById('Div2').style.display = 'block';
                    document.getElementById('nextShow').style.display = 'block';
                    document.getElementById('Button1').style.display = 'none';
                }
            }
        }
    </script>

    <script>
        document.querySelectorAll('.toggle-password').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const eye = this.querySelector('.icon-eye');
                const eyeOff = this.querySelector('.icon-eye-off');

                if (input.type === 'password') {
                    input.type = 'text';
                    eye.classList.add('hidden');
                    eyeOff.classList.remove('hidden');
                } else {
                    input.type = 'password';
                    eye.classList.remove('hidden');
                    eyeOff.classList.add('hidden');
                }

            });
        });
    </script>


</body>


</html>