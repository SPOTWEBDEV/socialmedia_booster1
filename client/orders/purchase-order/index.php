<?php
include("../../../server/connection.php");
include('../../../server/auth/client.php');
include_once('../../../server/api/boosting.php');

// Function to truncate decimal values
function truncateDecimal($number, $precision = 4) {
    $factor = pow(10, $precision);
    return floor($number * $factor) / $factor;
}

// Fetch site price and USD rate
$get = mysqli_query($connection, "SELECT siteprice, rateusd FROM sitedetails ORDER BY id LIMIT 1");
$data = mysqli_fetch_assoc($get);
$site_price = floatval($data['siteprice'] ?? 0);
$rate = floatval($data['rateusd'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $service_id     = intval($_POST['service']);
    $order_name     = trim($_POST['order_name']);
    $order_rate     = floatval($_POST['orderRate']);
    $order_category = trim($_POST['order_category']);
    $social_url     = trim($_POST['order_url']);
    $message        = trim($_POST['message']);
    $quantity       = intval($_POST['quanity']);
    $account        = trim($_POST['account']);

    // Basic validation
    if ($service_id <= 0 || $quantity <= 0 || $order_rate <= 0) {
        echo "<script>alert('Invalid order data'); window.history.back();</script>";
        return;
    }

    // Calculate pricing
    $thirdPartyPrice = ($quantity / 1000) * $order_rate;
    $siteFee         = truncateDecimal(($quantity / 1000) * $site_price); // USD
    $sub_price       = truncateDecimal($thirdPartyPrice, 4);
    $order_price     = truncateDecimal($thirdPartyPrice + $siteFee, 4); // USD
    $naria_price     = truncateDecimal($order_price * $rate, 4); // Naira

    // ===============================
    // Check user balance based on selected account
    // ===============================
    if ($account === 'main') {
        if ($naria_price > $balance) {
            echo "<script>alert('Insufficient main balance'); window.history.back();</script>";
            return;
        }
    } elseif ($account === 'refferals') {
        if ($naria_price > $referral_earnings) {
            echo "<script>alert('Insufficient referral balance'); window.history.back();</script>";
            return;
        }
    } else {
        echo "<script>alert('Invalid account selected'); window.history.back();</script>";
        return;
    }

    // Send order to API
    $order = $api->order([
        'service'  => $service_id,
        'link'     => $social_url,
        'quantity' => $quantity,
        'action'   => 'add'
    ]);

    if (isset($order->error)) {
        echo "<script>alert('API Error: {$order->error}'); window.history.back();</script>";
        return;
    }

    if (!isset($order->order)) {
        echo "<script>alert('Unexpected API response'); window.history.back();</script>";
        return;
    }

    $orderId = $order->order;

    // ===============================
    // REFERRAL BONUS LOGIC (Naira)
    // ===============================
    $referral_bonus = 0;
    $getRef = $connection->prepare("SELECT referrer_id FROM users WHERE id = ?");
    $getRef->bind_param("i", $id);
    $getRef->execute();
    $res = $getRef->get_result();
    $userData = $res->fetch_assoc();

    if (!empty($userData['referrer_id'])) {
        $referrer_id = $userData['referrer_id'];

        $bonusQuery = $connection->query("SELECT refferalbonus FROM sitedetails LIMIT 1");
        $bonusValue = 0;
        if ($bonusQuery && $bonusQuery->num_rows > 0) {
            $bonusValue = floatval($bonusQuery->fetch_assoc()['refferalbonus']);
        }

        // Referral bonus in USD
        $referral_bonus_usd = ($siteFee * $bonusValue) / 100;

        // Convert to Naira
        $referral_bonus = truncateDecimal($referral_bonus_usd * $rate, 2);

        if ($referral_bonus > 0) {
            // Credit referrer in Naira
            $updateRef = $connection->prepare("
                UPDATE users
                SET referral_earnings = referral_earnings + ?
                WHERE id = ?
            ");
            $updateRef->bind_param("di", $referral_bonus, $referrer_id);
            $updateRef->execute();

            $insertReferral = $connection->prepare("
                INSERT INTO referrals (user_id, from_user, amount)
                VALUES (?, ?, ?)
            ");
            $insertReferral->bind_param("iid", $referrer_id, $id, $referral_bonus);
            $insertReferral->execute();

            $notify = $connection->prepare("
                INSERT INTO notifications (type, user_id, message)
                VALUES ('system', ?, ?)
            ");
            $msg = "You earned ₦$referral_bonus referral bonus from an order.";
            $notify->bind_param("is", $referrer_id, $msg);
            $notify->execute();
        }
    }

    // ===============================
    // SAVE ORDER
    // ===============================
    $realProfit = $siteFee - $referral_bonus_usd; // USD profit
    $stmt = $connection->prepare("
        INSERT INTO user_orders
        (user, service_id, order_name, third_party_charge, naria_price, order_price,
         order_category, social_url, message, quanity, order_id, profit, referral_bonus)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "iisdddsssissd",
        $id,
        $service_id,
        $order_name,
        $sub_price,
        $naria_price,
        $order_price,
        $order_category,
        $social_url,
        $message,
        $quantity,
        $orderId,
        $realProfit,
        $referral_bonus
    );
    $stmt->execute();

    // ===============================
    // DEDUCT USER BALANCE BASED ON SELECTED ACCOUNT
    // ===============================
    if ($account === 'main') {
        $deduct = $connection->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        $deduct->bind_param("di", $naria_price, $id);
        $deduct->execute();
    } elseif ($account === 'refferals') {
        $deduct = $connection->prepare("UPDATE users SET referral_earnings = referral_earnings - ? WHERE id = ?");
        $deduct->bind_param("di", $naria_price, $id);
        $deduct->execute();
    }

    echo "<script>
        localStorage.removeItem('selected_service');
        alert('Order placed successfully!');
        window.location.href = '../my-order/';
    </script>";
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

                        <div class="mb-3">
                            <label class="form-label">Select Account</label>
                            <select name="account" class="form-control bg-light name="" id="">
                                <option value=" main">Main Balance - <?php echo number_format($balance, 4) ?></option>
                                <option value="refferals">Refferals Balance <?php echo number_format($referral_earnings, 4) ?></option>
                            </select>
                        </div>

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
                            <label class="form-label">Total Price (₦)</label>
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
                        <button type="submit" name="send_message"
                            class="btn btn-primary btn-lg rounded-3 loading-btn">
                            <i class="bi bi-send-fill me-2"></i>
                            Submit Order
                        </button>

                    </form>

                </div>





            </div>
        </div>

        <?php include("../../include/footer.php") ?>



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
            const totalTinNaria = truncateDecimal(total * <?= $rate ?>, 4);


            console.log(`Third-party: ${thirdPartyT} | Site fee: ${siteFeeT} | Total: ${totalT} | Total in Naria: ${totalTinNaria}`);

            // document.querySelector('.overtotal').innerHTML =
            //     `Third-party: ${thirdPartyT} | Site fee: ${siteFeeT} | Total: ${totalT}`;

            document.getElementById("totalPrice").value = totalTinNaria;

        });

        document.querySelector("form").addEventListener("submit", function(e) {

            let btn = document.querySelector(".loading-btn");

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';

            return true;

        });
    </script>
</body>

</html>