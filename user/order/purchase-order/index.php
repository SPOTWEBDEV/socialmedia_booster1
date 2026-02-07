<?php
include('../../../server/connection.php');
include('../../../server/auth/client.php');
include_once('../../../server/api/boosting.php');



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
    $siteFee         = ($quantity / 1000) * $site_price;

    $sub_price   = round($thirdPartyPrice, 4);
    $order_price = round($thirdPartyPrice + $siteFee, 4);

    if ($order_price > $balance) {
        echo "<script>alert('Insufficient balance');</script>";
        exit;
    }

    // Send order to API
    $order = $api->order([
        'service'  => $service_id,
        'link'     => $social_url,
        'quantity' => $quantity
    ]);

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
        (user, service_id, order_name, sub_price, order_price,
         order_category, social_url, message, quanity, order_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "iissdsssis",
        $id,
        $service_id,
        $order_name,
        $sub_price,
        $order_price,
        $order_category,
        $social_url,
        $message,
        $quantity,
        $orderId
    );

    if ($stmt->execute()) {

        // Deduct balance
        $deduct = $connection->prepare(
            "UPDATE users SET balance = balance - ? WHERE id = ?"
        );
        $deduct->bind_param("di", $order_price, $id);
        $deduct->execute();

        echo "<script>
            localStorage.removeItem('selectedOrder');
            alert('Order placed successfully!');
            window.location.href = '../my-order/';
        </script>";
    } else {
        echo "<script>alert('Failed to save order');</script>";
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Dashboard</title>

    <!-- Google Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        inter: ["Inter", "sans-serif"],
                    },
                },
            },
        };
    </script>

    <!-- Icons -->
    <!-- Heroicons (inline SVG usage) -->
</head>

<body class="bg-gray-100 font-inter">
    <!-- Layout Wrapper -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include('../../components/sidebar.php') ?>

        <!-- Main Content -->
        <main class="flex-1 ">
            <!-- Topbar -->
            <header
                class="bg-white border-b px-6 py-4 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Welcome back</p>
                    <h1 class="text-xl font-semibold"><?php echo $fullname ?> 👋</h1>
                </div>
                <div class="flex items-center gap-4">
                    <button class="relative">
                        <span
                            class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        🔔
                    </button>
                </div>
            </header>

            <section class="p-6 space-y-6">
                <h2 class="text-xl font-semibold">Boost Services</h2>

                <div id="order-form" class="bg-white rounded-xl p-6 mt-10 max-w-xl">

                    <form method="POST" class="space-y-4">

                        <div>
                            <label class="text-sm font-medium">Category</label>
                            <input type="text" id="orderCategory" name="order_category"
                                class="mt-1 w-full border rounded-lg px-4 py-2 text-sm bg-gray-100" readonly>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Service</label>
                            <input type="text" id="orderName" name="order_name"
                                class="mt-1 w-full border rounded-lg px-4 py-2 text-sm bg-gray-100" readonly>
                        </div>

                        <input type="hidden" id="orderRate" name="orderRate">
                        <input type="hidden" id="orderService" name="service">

                        <div>
                            <label class="text-sm font-medium">Quantity</label>
                            <input type="number" id="quanity" name="quanity"
                                class="mt-1 w-full border rounded-lg px-4 py-2 text-sm">
                        </div>

                        <div>
                            <label class="text-sm font-medium">Target URL</label>
                            <input type="url" name="order_url"
                                class="mt-1 w-full border rounded-lg px-4 py-2 text-sm">
                        </div>

                        <div>
                            <label class="text-sm font-medium">Total Price</label>
                            <input type="text" id="totalPrice"
                                class="mt-1 w-full border rounded-lg px-4 py-2 text-sm bg-gray-100" readonly>

                                <span class="overtotal"></span>
                        </div>
                        

                        <div>
                            <label class="text-sm font-medium">Additional Notes</label>
                            <textarea name="message"
                                class="mt-1 w-full border rounded-lg px-4 py-2 text-sm" rows="3"></textarea>
                        </div>

                        <button type="submit" name="send_message"
                            class="w-full bg-blue-600 text-white py-2 rounded-lg">
                            Submit Order
                        </button>

                    </form>
                </div>
            </section>

        </main>
    </div>

    <?php include('../../components/bottomnav.php') ?>




    <script>
        lucide.createIcons();
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let order = localStorage.getItem("selected_service");

            if (!order) {
                alert("No service selected.");
                window.location.href = "./services";
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

            console.log(thirdParty ,  siteFee , total)

            document.querySelector('.overtotal').innerHTML = `thirdParty price : ${thirdParty.toFixed(4)} Site Price : ${siteFee.toFixed(4)} total Price : ${total.toFixed(4)}`

            document.getElementById("totalPrice").value = total.toFixed(4);
        });
    </script>

</body>

</html>