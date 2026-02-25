<?php
include("../../server/connection.php");
include('../../server/auth/client.php');



$user_id = $id;




$payment_methods = [];

$query = "SELECT * FROM payment_methods WHERE status = 1 ORDER BY id DESC";
$result = mysqli_query($connection, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $payment_methods[] = $row;
}






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
        <?php include("../include/header.php") ?>
        <!-- nav -->


        <!-- side nav -->
        <?php include("../include/sidenav.php") ?>

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
                                                <h4 class="card-title">Deposit Form</h4>
                                            </div>
                                            <div class="card-body">

                                                <!-- Amount -->
                                                <div class="mb-3">
                                                    <label class="form-label">Enter Amount</label>
                                                    <input name="amount" id="depositAmount" type="number" class="form-control" placeholder="Enter amount">
                                                </div>

                                                <!-- Payment Methods -->
                                                <div class="mb-3">
                                                    <label class="form-label">Select Payment Method</label>

                                                    <div class="custom-select-box" id="openMethodModal">
                                                        <span id="selectedMethodText">Click to select method</span>
                                                        <i class="bi bi-chevron-down"></i>
                                                    </div>

                                                    <input type="hidden" id="paymentMethodInput">
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

                                <!-- ================= PAYMENT MODAL ================= -->
                                <div class="custom-modal" id="paymentModal">
                                    <div class="custom-modal-content">

                                        <div class="modal-header">
                                            <h5>Payment Details</h5>
                                            <button class="close-btn" id="closePaymentModal">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>

                                        <div id="modalContent"></div>

                                        <div style="margin-top:15px;text-align:center">
                                            <button id="confirmPaymentBtn" class="btn btn-success w-100">
                                                I Have Made Payment
                                            </button>
                                        </div>

                                        <div id="receiptSection" style="display:none;margin-top:15px">
                                            <form id="receiptForm" enctype="multipart/form-data">

                                                <input type="file"
                                                    id="receiptInput"
                                                    class="form-control mb-3"
                                                    accept="image/*"
                                                    required>

                                                <img id="previewImage"
                                                    style="display:none;max-width:100%;border-radius:10px;margin-bottom:15px;height:200px">

                                                <button type="submit"
                                                    class="btn btn-primary w-100">
                                                    Upload Receipt
                                                </button>

                                            </form>
                                        </div>

                                    </div>
                                </div>


                                <script>
                                    const methods = <?= json_encode($payment_methods) ?>;
                                    const url = "<?= $domain ?>server/api/etegram-init.php";

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

                                        let label = method.type;

                                        if (method.type === "bank") {
                                            label += " - " + method.bank_name;
                                        }
                                        if (method.type === "crypto") {
                                            label += " - " + method.wallet_name;
                                        }
                                        if (method.type === "gateway") {
                                            label += " - " + method.gateway_name;
                                        }

                                        div.innerText = label;

                                        div.onclick = () => {
                                            selectedMethod = method;
                                            selectedText.innerText = label;
                                            methodModal.classList.remove("active");
                                        };

                                        methodList.appendChild(div);
                                    });


                                    /* ================= FORM SUBMIT ================= */

                                    document.getElementById("depositForm").addEventListener("submit", function(e) {

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

                                        selectedAmount = amount;

                                        if (selectedMethod.type === "gateway") {

                                            const gateway = selectedMethod.gateway_name.toLowerCase();

                                            if (gateway === "etegram") {
                                                etegram(amount);
                                            } else if (gateway === "paystack") {
                                                paystack(amount);
                                            }

                                            return;
                                        }

                                        openManualPayment(selectedMethod, amount);

                                    });


                                    /* ================= PAYMENT MODAL ================= */

                                    const paymentModal = document.getElementById("paymentModal");
                                    const closePaymentBtn = document.getElementById("closePaymentModal");

                                    closePaymentBtn.onclick = () => paymentModal.classList.remove("active");

                                    paymentModal.addEventListener("click", (e) => {
                                        if (e.target === paymentModal) {
                                            paymentModal.classList.remove("active");
                                        }
                                    });


                                    function openManualPayment(method, amount) {

                                        let content = "";

                                        if (method.type === "bank") {
                                            content = `
            <p><strong>Bank:</strong> ${method.bank_name}</p>
            <p><strong>Account Name:</strong> ${method.account_name}</p>
            <p><strong>Account Number:</strong> ${method.account_number}</p>
            <hr>
            <p>Please transfer ₦${amount} and click below after payment.</p>
        `;
                                        }

                                        if (method.type === "crypto") {
                                            content = `
                                                    <p>
                                                        <strong>Wallet Name:</strong><br>
                                                        ${method.wallet_name}
                                                    </p>
                                                    <p>
                                                        <strong>Wallet Address:</strong><br>
                                                        ${method.wallet_address}
                                                    </p>
                                                    <hr>
                                                    <p>Send exactly ${amount} worth of crypto.</p>
                                                `;

                                            if (method.qr_code) {
                                                content += `
                <div style="text-align:center;margin-top:15px">
                    <img src="<?= $domain ?>uploads/qrcodes/${method.qr_code}"
                         style="max-width:200px;border-radius:10px; height:100px">
                </div>
            `;
                                            }
                                        }

                                        document.getElementById("modalContent").innerHTML = content;

                                        document.getElementById("receiptSection").style.display = "none";
                                        document.getElementById("confirmPaymentBtn").style.display = "block";

                                        paymentModal.classList.add("active");
                                    }


                                    /* ================= SHOW RECEIPT SECTION ================= */

                                    document.getElementById("confirmPaymentBtn").onclick = function() {
                                        this.style.display = "none";
                                        document.getElementById("receiptSection").style.display = "block";
                                    };


                                    /* ================= RECEIPT PREVIEW ================= */

                                    document.getElementById("receiptInput").addEventListener("change", function() {

                                        const file = this.files[0];
                                        if (!file) return;

                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            const preview = document.getElementById("previewImage");
                                            preview.src = e.target.result;
                                            preview.style.display = "block";
                                        };

                                        reader.readAsDataURL(file);

                                    });


                                    /* ================= RECEIPT UPLOAD ================= */

                                    document.getElementById("receiptForm").addEventListener("submit", async function(e) {

                                        e.preventDefault();

                                        const fileInput = document.getElementById("receiptInput");

                                        if (!fileInput.files.length) {
                                            alert("Please upload receipt image");
                                            return;
                                        }

                                        let formData = new FormData();
                                        formData.append("receipt", fileInput.files[0]);
                                        formData.append("method_id", selectedMethod.id);
                                        formData.append("amount", selectedAmount);
                                        formData.append("user_id", "<?= $id ?>");

                                        try {

                                            const response = await fetch("<?= $domain ?>server/api/manual-deposit.php", {
                                                method: "POST",
                                                body: formData
                                            });

                                            console.log(response)

                                            const data = await response.json();

                                            if (data.status) {
                                                alert("Receipt uploaded successfully. Awaiting confirmation.");
                                                window.location.href = "./history/";
                                            } else {
                                                alert("Upload failed. Try again.");
                                            }

                                        } catch (err) {
                                            console.log("Upload error:", err);
                                            alert("Network error. Try again.");
                                        }

                                    });


                                    /* ================= GATEWAY FUNCTIONS ================= */

                                    async function etegram(amount) {

                                        const response = await fetch(url, {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/json"
                                            },
                                            body: JSON.stringify({
                                                amount,
                                                user_id: "<?= $id ?>"
                                            })
                                        });

                                        const data = await response.json();

                                        if (data.status && data.authorization_url) {
                                            window.location.href = data.authorization_url;
                                        } else {
                                            alert("Etegram error");
                                        }
                                    }

                                    function paystack(amount) {
                                        alert("Initialize Paystack here");
                                    }
                                </script>





                            </div>
                        </div>

                    </div>
                </div>





            </div>
            <?php include("../include/footer.php") ?>
        </div>



    </div>
    <script src="<?php echo $domain ?>client/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>client/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="<?php echo $domain ?>client/js/scripts.js"></script>
</body>

</html>