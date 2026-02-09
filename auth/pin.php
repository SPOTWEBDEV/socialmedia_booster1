<?php

include('../server/connection.php');
include('../server/auth/user/index.php');

if ($step_verification == 0) {
    $redirect_url = $domain . 'app/dashboard/';
    echo "<script>window.open('$redirect_url', '_self');</script>";
    exit();
} else { ?>


    <html lang="en">

    <!DOCTYPE html>




    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title><?php echo  $sitename ?> - Login </title>
        <link rel="icon" type="image/x-icon" href="./asset/img/favicon.ico">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&amp;display=swap" rel="stylesheet">
        <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="./asset/css/plugins.css" rel="stylesheet" type="text/css">
        <link href="./asset/css/authentication/form-2.css" rel="stylesheet" type="text/css">
        <!-- END GLOBAL MANDATORY STYLES -->
        <link rel="stylesheet" type="text/css" href="./asset/css/forms/theme-checkbox-radio.css">
        <link rel="stylesheet" type="text/css" href="./asset/css/forms/switches.css">
        <link href="./asset/css/pages/error/style-400.css" rel="stylesheet" type="text/css">


        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="./asset/css/scrollspyNav.css" rel="stylesheet" type="text/css">
        <link href="./plugins/animate/animate.css" rel="stylesheet" type="text/css">
        <link href="./plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="./asset/css/elements/alert.css">
        <script src="./plugins/sweetalerts/promise-polyfill.js"></script>
        <link href="./plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css">
        <link href="./plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css">
        <link href="./asset/css/components/custom-sweetalert.css" rel="stylesheet" type="text/css">
        <script src="./asset/js/libs/jquery-3.1.1.min.js"></script>

        <!-- Toastr CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

        <!-- jQuery (required by Toastr) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- Toastr JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


        <script>
            toastr.options = {

                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-bottom-right", // other options: toast-bottom-left, toast-bottom-right, toast-top-left, toast-top-full-width, toast-bottom-full-width, toast-top-center, toast-bottom-center
                "timeOut": "2000" // milliseconds before toast disappears
            }
        </script>

        <!-- END THEME GLOBAL STYLES -->
        <title>Pin</title>
        <style>
            button {
                margin: 3px;
            }

            button {
                display: inline-block;
                border: 1px solid #0a3bff;
                color: #0022ff;
                border-radius: 30px;
                -webkit-border-radius: 30px;
                -moz-border-radius: 30px;
                font-family: Verdana;
                width: auto;
                height: auto;
                font-size: 16px;
                padding: 10px 17px;
                background-color: #FCFAF9;
            }

            button:hover,
            button:active {
                border: 1px solid #FFFFFF;
                color: #FFFDFC;
                background-color: #FC0000;
            }

            input[type=text],
            textarea {
                -webkit-transition: all 0.30s ease-in-out;
                -moz-transition: all 0.30s ease-in-out;
                -ms-transition: all 0.30s ease-in-out;
                -o-transition: all 0.30s ease-in-out;
                outline: none;
                padding: 3px 0px 3px 3px;
                margin: 5px 1px 3px 0px;
                border: 1px solid #DDDDDD;
            }

            input[type=text]:focus,
            textarea:focus {
                box-shadow: 0 0 5px rgba(250, 0, 0, 1);
                padding: 3px 0px 3px 3px;
                margin: 5px 1px 3px 0px;
                border: 1px solid rgba(250, 0, 0, 1);
            }
        </style>
    </head>

    <body>
        <div class="form-container outer">
            <div class="form-form">
                <div class="form-form-wrap">
                    <div class="form-container">
                        <div class="form-content">

                            <div class="d-flex user-meta">
                                <img src="<?php echo $profile_pic  ?>" class="usr-profile"
                                    alt="avatar">
                                <div class="">
                                    <p class=""><?php echo $username   ?></p>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-center">Welcome</h3>
                                    <p class="text-info">Enter PIN </p>

                                </div>
                            </div>

                            <form class="text-left" method="post">
                                <div class="form">
                                    <div \="" class="field-wrapper input mb-2">

                                        <div class="d-flex justify-content-between">

                                            <label for="password">PINCODE</label>

                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-lock">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                        <input id="datepicker" name="pin" type="number" class="form-control input" placeholder="PINCODE" autocomplete="off">



                                    </div>

                                </div>
                                <div class=" text-center">
                                    <div id="container">
                                        <div>
                                            <button class="shuffle">1</button>
                                            <button class="shuffle">2</button>
                                            <button class="shuffle">3</button>
                                        </div>
                                        <div>
                                            <button class="shuffle">4</button>
                                            <button class="shuffle">5</button>
                                            <button class="shuffle">6</button>
                                        </div>
                                        <div>
                                            <button class="shuffle">7</button>
                                            <button class="shuffle">8</button>
                                            <button class="shuffle">9</button>
                                        </div>
                                        <div>
                                            <button class="del">X</button>
                                            <button class="shuffle">0</button>
                                            <button class="faq">?</button>
                                        </div>
                                        <div class="text-center">
                                            <input class="btn btn-primary mt-2" type="submit" value="Submit"
                                                name="pin_submit">

                                        </div>
                                    </div>
                                </div>
                            </form>

                            <?php

                            if (isset($_POST['pin_submit'])) {
                                $inputed_pin = $_POST['pin'];

                                if (empty($inputed_pin)) {
                                    echo "<script>toastr.error('Please enter your PIN.');</script>";
                                } else {
                                    if ($inputed_pin == $pin) {
                                        echo "<script>toastr.success('PIN is correct. Redirecting...');</script>";
                                        echo "<script>
                                                            setTimeout(function() {
                                                                window.location.href = '" . $domain . "app/dashboard/';
                                                            }, 2000);
                                                        </script>";
                                    } else {
                                        echo "<script>toastr.error('Incorrect PIN. Please try again.');</script>";
                                    }
                                }
                            }


                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>


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
        </script>

    </body>

    </html>

<?php }



?>