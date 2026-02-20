<?php
include("../../../server/connection.php");
include('../../../server/auth/client.php');
include_once('../../../server/api/boosting.php');

function truncateDecimal($number, $precision = 4)
{
    $factor = pow(10, $precision);
    return floor($number * $factor) / $factor;
}



$get = mysqli_query($connection, "SELECT  siteprice ,  rateusd  FROM sitedetails  ORDER BY id LIMIT 1");
$data = mysqli_fetch_assoc($get);
$site_price = floatval($data['siteprice'] ?? 0);
$rate = floatval($data['rateusd'] ?? 0);



// ===============================
// Fetch site price
// ===============================
$get = mysqli_query($connection, "SELECT  siteprice  FROM sitedetails  ORDER BY id LIMIT 1");
$data = mysqli_fetch_assoc($get);
$site_price = floatval($data['siteprice'] ?? 0);

// ===============================
// Handle form submission
// ===============================
if (isset($_POST['send_message'])) {

    $service_id     = intval($_POST['service']);
    $order_name     = trim($_POST['order_name']);
    $order_rate     = floatval($_POST['orderRate']);
    $order_category = trim($_POST['order_category']);
    $social_url     = trim($_POST['order_url']);
    $message        = trim($_POST['message']);
    $quantity       = intval($_POST['quanity']);

    if ($service_id <= 0 || $quantity <= 0 || $order_rate <= 0) {
        echo "<script>alert('Invalid order data');</script>";
        exit;
    }

    // Price calculation (SERVER SIDE)
    $thirdPartyPrice = ($quantity / 1000) * $order_rate;
    $siteFee         = truncateDecimal(($quantity / 1000) * $site_price);

    $sub_price   = truncateDecimal($thirdPartyPrice, 4);
    $order_price = truncateDecimal($thirdPartyPrice + $siteFee, 4);
    $naria_price = truncateDecimal($order_price * $rate, 4);

     echo "<script>alert('$siteFee');</script>";





    if ($naria_price > $balance) {
        echo "<script>alert('Insufficient balance');</script>";
        exit;
    }

    // Send order to API
    $order = $api->order([
        'service'  => $service_id,
        'link'     => $social_url,
        'quantity' => $quantity,
        "action" => "add"
    ]);

    print_r($order);

    if (isset($order->error)) {
        echo "<script>alert('API Error: {$order->error}');</script>";
        exit;
    }

    if (!isset($order->order)) {
        echo "<script>alert('Unexpected API response');</script>";
        exit;
    }

    $orderId = $order->order;

    // Save order
    $stmt = $connection->prepare("
    INSERT INTO user_orders
    (user, service_id, order_name, third_party_charge, naria_price, order_price,
     order_category, social_url, message, quanity, order_id , profit)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)
");


    $stmt->bind_param(
        "iisdddsssiss",
        $id,
        $service_id,
        $order_name,
        $sub_price,     // third-party charge
        $naria_price,
        $order_price,
        $order_category,
        $social_url,
        $message,
        $quantity,
        $orderId,
        $siteFee
    );


    if ($stmt->execute()) {

        // Deduct balance
        $deduct = $connection->prepare(
            "UPDATE users SET balance = balance - ? WHERE id = ?"
        );
        $deduct->bind_param("di", $naria_price, $id);
        $deduct->execute();

        echo "<script>
            localStorage.removeItem('selected_service');
            alert('Order placed successfully!');
            window.location.href = '../my-order/';
        </script>";
    } else {
        echo "<script>alert('Failed to save order');</script>";
    }
}



?>


<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | Order </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>client/images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>client/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>client/vendor/toastr/toastr.min.css">
</head>

<body class="dashboard">

    <div id="main-wrapper">
        <!-- nav -->
        <?php include("../../include/header.php") ?>

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
                                        <h2 class="text-2xl font-bold text-gray-800">Boost Services</h2>
                                        <p class="text-gray-500 text-sm">Choose a service and place your order instantly.</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button onclick="window.location.href='../my-order/'"
                                        class="px-5 py-2.5 bg-primary border border-gray-300 rounded-xl text-sm font-medium hover:bg-gray-100 transition">
                                        View Order History
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">


                    <form method="POST">

                        <!-- Category -->
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" id="orderCategory" name="order_category"
                                class="form-control bg-light" readonly>
                        </div>

                        <!-- Service -->
                        <div class="mb-3">
                            <label class="form-label">Service</label>
                            <input type="text" id="orderName" name="order_name"
                                class="form-control bg-light" readonly>
                        </div>

                        <input type="hidden" id="orderRate" name="orderRate">
                        <input type="hidden" id="orderService" name="service">

                        <!-- Quantity -->
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" id="quanity" name="quanity"
                                class="form-control" required>
                        </div>

                        <!-- URL -->
                        <div class="mb-3">
                            <label class="form-label">Target URL</label>
                            <input type="url" name="order_url"
                                class="form-control" required>
                        </div>

                        <!-- Total Price -->
                        <div class="mb-3">
                            <label class="form-label">Total Price</label>
                            <input type="text" id="totalPrice"
                                class="form-control bg-light fw-bold" readonly>
                        </div>

                        <!-- Breakdown -->
                        <div class="alert alert-secondary small" id="priceBreakdown">
                            Price breakdown will appear here.
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label class="form-label">Additional Notes</label>
                            <textarea name="message"
                                class="form-control"
                                rows="3"></textarea>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid">
                            <button type="submit" name="send_message"
                                class="btn btn-primary btn-lg rounded-3">
                                <i class="bi bi-send-fill me-2"></i>
                                Submit Order
                            </button>
                        </div>

                    </form>

                </div>





            </div>
        </div>



    </div>

    <script src="<?php echo $domain ?>client/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>client/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!--  -->
    <!--  -->
    <script src="<?php echo $domain ?>client/js/scripts.js"></script>
   <script>
        function truncateDecimal(num, decimals) {
            const factor = Math.pow(10, decimals);
            return Math.floor(num * factor) / factor;
        }

        document.addEventListener("DOMContentLoaded", () => {
            let order = localStorage.getItem("selected_service");

            if (!order) {
                alert("No service selected.");
                window.location.href = "../";
                return;
            }




            order = JSON.parse(order);

            document.getElementById("orderName").value = order.name;
            document.getElementById("orderCategory").value = order.category;
            document.getElementById("orderRate").value = order.rate;
            document.getElementById("orderService").value = order.service;

            document.getElementById("quanity").placeholder =
                `Min: ${order.min} - Max: ${order.max}`;
        });

        document.getElementById("quanity").addEventListener("input", function() {
            const qty = parseFloat(this.value);
            const rate = parseFloat(document.getElementById("orderRate").value);
            const sitePrice = Number(<?= $site_price ?>);

            if (isNaN(qty) || qty <= 0) {
                document.getElementById("totalPrice").value = "";
                return;
            }

            const thirdParty = (qty / 1000) * rate;
            const siteFee = (qty / 1000) * sitePrice;
            const total = thirdParty + siteFee;

            const thirdPartyT = truncateDecimal(thirdParty, 4);
            const siteFeeT = truncateDecimal(siteFee, 4);
            const totalT = truncateDecimal(total, 4);


            console.log(`Third-party: ${thirdPartyT} | Site fee: ${siteFeeT} | Total: ${totalT}`)

            // document.querySelector('.overtotal').innerHTML =
            //     `Third-party: ${thirdPartyT} | Site fee: ${siteFeeT} | Total: ${totalT}`;

            document.getElementById("totalPrice").value = totalT;

        });
    </script>
</body>

</html>