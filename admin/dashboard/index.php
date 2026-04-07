<?php

include('../../server/connection.php');
include('../../server/auth/admin/index.php');
include('../../server/api/boosting.php');





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?php echo  $sitename ?>- Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../source/assets/img/favicon.ico" />
    <link href="../source/assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="../source/assets/js/loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="../source/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../source/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../source/assets/css/structure.css" rel="stylesheet" type="text/css" class="structure" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="../source/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="../source/assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css"
        class="dashboard-analytics" />
    <link rel="stylesheet" type="text/css" href="../source/plugins/dropify/dropify.min.css">
    <link href="../source/assets/css/users/account-setting.css" rel="stylesheet" type="text/css" />
    <link href="../source/assets/css/components/custom-modal.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../source/plugins/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../source/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="../source/assets/css/forms/switches.css">
    <link rel="stylesheet" type="text/css" href="../source/plugins/table/datatable/dt-global_style.css">
    <link rel="stylesheet" href="../source/assets/css/card/displayCard.css">

    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="../source/plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="../source/plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="../source/assets/css/components/custom-sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="../source/plugins/sweetalerts/promise-polyfill.js"></script>
    <script src="../source/assets/js/libs/jquery-3.1.1.min.js"></script>

</head>

<body class="dashboard-analytics">

    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <?php include("../includes/navbar.php")  ?>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <?php include("../includes/sidebar.php")  ?>
        <!--  END SIDEBAR  -->





        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="page-header">
                    <div class="page-title">
                        <h3>Admin Analytics</h3>
                    </div>
                </div>



                <div class="row layout-top-spacing">

                    <?php
                    $total_user = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM users"));

                    $total_approved_deposit = mysqli_num_rows(
                        mysqli_query($connection, "SELECT * FROM deposit WHERE status='approved'")
                    );

                    $deposit_sum = mysqli_fetch_assoc(
                        mysqli_query($connection, "SELECT SUM(amount) as amount FROM deposit WHERE status='approved'")
                    );

                    $profit = mysqli_fetch_assoc(
                        mysqli_query($connection, "SELECT SUM(profit) as profit FROM user_orders")
                    );

                    $third_party_charge = $api->balance();
                    ?>

                    <!-- TOTAL USERS -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content">
                                    <div class="w-info">
                                        <h6 class="value"><?php echo number_format($total_user); ?></h6>
                                        <p>Total Users</p>
                                    </div>
                                    <div class="w-icon text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M7 21v-2a4 4 0 0 1 3-3.87"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- APPROVED DEPOSITS -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content">
                                    <div class="w-info">
                                        <h6 class="value"><?php echo number_format($total_approved_deposit); ?></h6>
                                        <p>Approved Deposits</p>
                                    </div>
                                    <div class="w-icon text-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TOTAL DEPOSIT -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content">
                                    <div class="w-info">
                                        <h6 class="value">₦<?php echo number_format($deposit_sum['amount'] ?? 0, 2); ?></h6>
                                        <p>Total Deposit</p>
                                    </div>
                                    <div class="w-icon text-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="1" x2="12" y2="23"></line>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PROFIT -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content">
                                    <div class="w-info">
                                        <h6 class="value">$<?php echo number_format($profit['profit'] ?? 0, 2); ?></h6>
                                        <p>Total Profit</p>
                                    </div>
                                    <div class="w-icon text-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                            <polyline points="17 6 23 6 23 12"></polyline>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- EXPENSE -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content">
                                    <div class="w-info">
                                        <h6 class="value">$<?php echo number_format($expense['total'] ?? 0, 2); ?></h6>
                                        <p>Total Expense</p>
                                    </div>
                                    <div class="w-icon text-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- THIRD PARTY BALANCE -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content">
                                    <div class="w-info">
                                        <h6 class="value">
                                            <?php echo $third_party_charge->balance . ' ' . $third_party_charge->currency; ?>
                                        </h6>
                                        <p>API Balance</p>
                                    </div>
                                    <div class="w-icon text-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M13 2v6"></path>
                                            <path d="M11 2v6"></path>
                                            <path d="M5 8h14"></path>
                                            <path d="M5 12h14"></path>
                                            <path d="M12 12v10"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="footer-wrapper">
                    <div class="footer-section f-section-1">
                        <p class="">Copyright © 2022 <a target="_blank" href="/"><?php echo  $sitename ?></a>, All rights
                            reserved.</p>
                    </div>
                    <div class="footer-section f-section-2">
                        <p class=""><?php echo  $sitename ?> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                </path>
                            </svg></p>
                    </div>
                </div>
            </div>
            <!--  END CONTENT AREA  -->


        </div>
        <!-- END MAIN CONTAINER -->

        <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
        <script src="../source/bootstrap/js/popper.min.js"></script>
        <script src="../source/bootstrap/js/bootstrap.min.js"></script>
        <script src="../source/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
        <script src="../source/assets/js/app.js"></script>
        <script>
            $(document).ready(function() {
                App.init();
            });
        </script>
        <script src="../source/assets/js/custom.js"></script>
        <!-- END GLOBAL MANDATORY SCRIPTS -->

        <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
        <script src="../source/plugins/apex/apexcharts.min.js"></script>
        <script src="../source/assets/js/dashboard/dash_1.js"></script>
        <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

        <script src="../source/plugins/dropify/dropify.min.js"></script>
        <script src="../source/plugins/blockui/jquery.blockUI.min.js"></script>
        <!-- <script src="plugins/tagInput/tags-input.js"></script> -->
        <script src="../source/assets/js/users/account-settings.js"></script>

        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="../source/plugins/highlight/highlight.pack.js"></script>
        <script src="../source/plugins/table/datatable/datatables.js"></script>
        <script src="../source/plugins/select2/select2.min.js"></script>
        <script src="../source/plugins/select2/custom-select2.js"></script>

        <script src="../source/plugins/sweetalerts/sweetalert2.min.js"></script>
        <script src="../source/plugins/sweetalerts/custom-sweetalert.js"></script>
        <script>
            var ss = $(".basic").select2({
                tags: true,
            });
        </script>

        <script>
            $('input').attr('autocomplete', 'off');
        </script>
        <script>
            $('#default-ordering').DataTable({
                "oLanguage": {
                    "oPaginate": {
                        "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },
                    "sInfo": "Showing page _PAGE_ of _PAGES_",
                    "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    "sSearchPlaceholder": "Search...",
                    "sLengthMenu": "Results :  _MENU_",
                },
                // "order": [[ 3, "desc" ]],
                "stripeClasses": [],
                "lengthMenu": [7, 10, 20, 50],
                "pageLength": 7,
                drawCallback: function() {
                    $('.dataTables_paginate > .pagination').addClass(' pagination-style-13 pagination-bordered mb-5');
                }
            });
        </script>

        <script>
            $(".edit-crypto").click(function(e) {
                e.preventDefault();
                $("#crypto_name").val($(this).data('name'));
                $("#wallet_address").val($(this).data('wallet-address'));
                $("#crypto_id").val($(this).data('id'));
                $(".show-modal").click();
            });
        </script>

        <script>
            function toast(msg, type) {
                return swal({
                    type: type,
                    title: type,
                    text: msg,
                    padding: "2em"
                });
            }

            $(".delete-crypto-currency").on('click', function(e) {
                e.preventDefault();
                let crypto_id = $(this).data('id');

                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    padding: '2em'
                }).then(function(result) {
                    if (result.value) {

                        $.ajax({
                            url: 'https://santaaccessfinance.netadmin/crypto-currrency.php',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                'delete_crypto_currency': '',
                                'crypto_currency_id': crypto_id
                            },
                            timeout: 45000,
                            success: function(data) {
                                console.log(data);


                                if (data.error == 1) {
                                    toast(data.msg, 'success');
                                } else {
                                    toast(data.msg, 'error');
                                }

                                setTimeout(function() {
                                    window.location.href = 'https://santaaccessfinance.netadmin/crypto-currrency.php';
                                }, 1000)
                            },
                            error: function(er) {
                                // console.log(er.responseText);
                                toast('error network', 'error');
                            }
                        });

                    }
                })

            });
        </script>


        <!-- END PAGE LEVEL SCRIPTS -->

</body>

</html>