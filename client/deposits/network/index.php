<?php
include("../../../server/connection.php");
include('../../../server/auth/client.php');



$user_id = $id;




$payment_methods = [];




$URL = "https://api.cryptomus.com/v1/payment/services";
$ch = curl_init($URL);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "merchant: $MERCHANT_UUID",
    "sign: $sign",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "Curl Error: " . curl_error($ch);
}

$payment_methods = json_decode($response, true);

curl_close($ch);



?>


<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | Deposit </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" href="<?php echo $domain ?>assets/images/logo/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>client/css/style.css">
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    <!-- jQuery (required by Toastr) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="">
        toastr.options = {

            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-bottom-right", // other options: toast-bottom-left, toast-bottom-right, toast-top-left, toast-top-full-width, toast-bottom-full-width, toast-top-center, toast-bottom-center
            "timeOut": "900000" // milliseconds before toast disappears
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* ================= CUSTOM SELECT ================= */
        .custom-select-box {
            border: 1px solid #ddd;
            padding: 12px 15px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            background: #fff;
            transition: 0.2s ease;
        }

        .custom-select-box:hover {
            border-color: #0d6efd;
        }

        /* ================= MODAL ================= */
        .custom-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .custom-modal.active {
            display: flex;
        }

        .custom-modal-content {
            background: #fff;
            width: 95%;
            max-width: 450px;
            border-radius: 16px;
            padding: 20px;
            animation: fadeIn 0.25s ease;
        }

        @keyframes fadeIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .close-btn {
            border: none;
            background: none;
            font-size: 18px;
            cursor: pointer;
        }

        .modal-body .method-item {
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
            margin-bottom: 10px;
            border: 1px solid #eee;
            transition: 0.2s ease;
        }

        .modal-body .method-item:hover {
            background: #f8f9fa;
            border-color: #0d6efd;
        }

        .modal-content {
            background: transparent;
            border: none;
            box-shadow: none;
        }

        .custom-payment-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            width: 100%;
            max-height: 85vh;
            /* prevent overflow outside screen */
            display: flex;
            flex-direction: column;
        }

        /* Scroll area when receipt section appears */
        #receiptSection {
            overflow-y: auto;
            max-height: 35vh;
        }

        /* Smooth scrollbar (optional) */
        .custom-modal-content::-webkit-scrollbar,
        #modalContent::-webkit-scrollbar {
            width: 5px;
        }

        .custom-modal-content::-webkit-scrollbar-thumb,
        #modalContent::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }
    </style>
</head>

<body class="dashboard">

    <div id="main-wrapper">
        <?php include("../../include/header.php") ?>
        <!-- nav -->


        <!-- side nav -->
        <?php include("../../include/sidenav.php") ?>

        <div class="content-body">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-4">
                                    <div class="page-title-content">
                                        <h3>Deposit</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="./history/"><button class="btn btn-primary mr-2">View Deposit History</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12 col-xl-12">

                        <div class="row">
                            <div class="row g-4">

                                <form id="depositForm">
                                    <!-- SUBMIT AMOUNT -->
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Select Network</h4>
                                            </div>
                                            <div class="card-body">
                                                <!-- Payment Methods -->
                                                <div class="mb-3">
                                                    <label class="form-label">Select Payment Method</label>

                                                    <div class="custom-select-box" id="openMethodModal">
                                                        <span id="selectedMethodText">Click to select method</span>
                                                        <i class="bi bi-chevron-down"></i>
                                                    </div>

                                                    <input type="hidden" id="paymentMethodInput">
                                                </div>

                                                <!-- Amount -->
                                                <div class="mb-3">
                                                    <label class="form-label">Enter Amount</label>
                                                    <input name="amount" id="depositAmount" type="number" class="form-control" placeholder="Enter amount">
                                                </div>



                                                <button type="submit" class="btn btn-primary">Proceed</button>

                                            </div>
                                        </div>
                                    </div>

                                </form>

                                <!-- ================= METHOD MODAL ================= -->
                                <div class="custom-modal" id="methodModal">
                                    <div class="custom-modal-content">

                                        <div class="modal-header">
                                            <h5>Select Payment Method</h5>
                                            <button class="close-btn" id="closeMethodModal">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>

                                        <div class="modal-body" id="methodList"></div>

                                    </div>
                                </div>




                                <script>
                                    // const method = JSON.parse(<?= json_encode($payment_methods) ?>);
                                    const method = <?= json_encode($payment_methods) ?>;

                                    console.log(method)

                                    // const allowedNetworks = [
                                    //     "bitcoin",
                                    //     "ethereum",
                                    //     "tron",
                                    //     "bsc",
                                    //     "solana",
                                    //     "polygon",
                                    //     "litecoin",
                                    //     "doge",
                                    //     "ton",
                                    //     "xrp"
                                    // ];

                                    const allowedNetworks = [
                                        "BTC",
                                        "ETH",
                                        "TRX",
                                        "SOL",
                                        "LTC",
                                        "DOGE",
                                        "TON",
                                        "XRP"
                                    ];

                                    const methods = method.result.filter(m =>
                                        allowedNetworks.includes(m.network)
                                    );



                                    console.log(methods)


                                    let selectedMethod = null;
                                    let selectedAmount = null;

                                    /* ================= METHOD MODAL ================= */

                                    const methodModal = document.getElementById("methodModal");
                                    const openMethodBtn = document.getElementById("openMethodModal");
                                    const closeMethodBtn = document.getElementById("closeMethodModal");
                                    const methodList = document.getElementById("methodList");
                                    const selectedText = document.getElementById("selectedMethodText");

                                    openMethodBtn.onclick = () => methodModal.classList.add("active");
                                    closeMethodBtn.onclick = () => methodModal.classList.remove("active");

                                    methodModal.addEventListener("click", (e) => {
                                        if (e.target === methodModal) {
                                            methodModal.classList.remove("active");
                                        }
                                    });

                                    /* Populate payment methods */
                                    methods.forEach(method => {

                                        const div = document.createElement("div");
                                        div.className = "method-item";


                                        const {
                                            network,
                                            currency,
                                            is_available,
                                            commission,
                                            limit

                                        } = method

                                        let label = currency + " (" + network.toUpperCase() + ")";


                                        const {
                                            min_amount,
                                            max_amount
                                        } = limit


                                        div.innerText = label;

                                        div.onclick = () => {
                                            selectedMethod = method;
                                            selectedText.innerText = label;
                                            methodModal.classList.remove("active");
                                        };

                                        methodList.appendChild(div);
                                    });


                                    /* ================= FORM SUBMIT ================= */

                                    document.getElementById("depositForm").addEventListener("submit", async function(e) {

                                        e.preventDefault();

                                        const amount = parseFloat(document.getElementById("depositAmount").value);

                                        if (!amount || amount <= 0) {
                                            toastr.error("Enter valid amount");
                                            return;
                                        }

                                        if (!selectedMethod) {
                                            toastr.error("Select payment method");
                                            return;
                                        }

                                        amount;

                                        const response = await fetch("<?= $domain ?>server/api/cryptomus-init.php", {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/json"
                                            },
                                            body: JSON.stringify({
                                                amount,
                                                user_id: "<?= $id ?>",
                                                network: selectedMethod.network,
                                                currency: selectedMethod.currency
                                            })
                                        });

                                        const text = await response.text();
                                        console.log(text);

                                        const data = JSON.parse(text);

                                     

                                        console.log(data)

                                        if (data.status && data.authorization_url) {
                                            window.location.href = data.authorization_url;
                                        } else {
                                            alert("cryptomus error");
                                        }





                                    });
                                </script>





                            </div>
                        </div>

                    </div>
                </div>





            </div>
            <?php include("../../include/footer.php") ?>
        </div>



    </div>
    <script src="<?php echo $domain ?>client/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>client/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="<?php echo $domain ?>client/js/scripts.js"></script>
</body>

</html>