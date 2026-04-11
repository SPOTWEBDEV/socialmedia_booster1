<?php
include('./server/connection.php');

// 🔐 YOUR KEYS


// input
$order_id = 'CRY_69d92e906df65';
$uuid = $_GET['uuid'] ?? '';

$response = null;
$error = null;

if (!$order_id && !$uuid) {
    $error = "Please provide order_id or uuid in URL";
} else {

    // -------------------------
    // BUILD REQUEST DATA
    // -------------------------
    $data = [];

    if ($order_id) {
        $data['order_id'] = $order_id;
    } else {
        $data['uuid'] = $uuid;
    }

    $jsonData = json_encode($data);

    // -------------------------
    // SIGNATURE
    // -------------------------
    $sign = md5(base64_encode($jsonData) . $API_KEY);

    // -------------------------
    // CURL REQUEST
    // -------------------------
    $ch = curl_init("https://api.cryptomus.com/v1/payment/info");

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => [
            "merchant: $MERCHANT_UUID",
            "sign: $sign",
            "Content-Type: application/json"
        ]
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
    }

    curl_close($ch);
}

$result = json_decode($response, true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cryptomus API Test</title>
    <style>
        body { font-family: Arial; background:#f4f4f4; padding:20px; }
        .box { background:#fff; padding:20px; border-radius:10px; margin-bottom:20px; }
        pre { background:#000; color:#0f0; padding:15px; overflow:auto; }
        input { padding:10px; width:300px; }
        button { padding:10px 15px; cursor:pointer; }
    </style>
</head>
<body>

<div class="box">
    <h2>Cryptomus Payment Info Test</h2>

    <form method="GET">
        <div>
            <input type="text" name="order_id" placeholder="Enter order_id">
        </div>
        <br>
        <div>
            <input type="text" name="uuid" placeholder="OR enter uuid">
        </div>
        <br>
        <button type="submit">Check Payment</button>
    </form>
</div>

<?php if ($error): ?>
    <div class="box" style="color:red;">
        <b>Error:</b> <?= $error ?>
    </div>
<?php endif; ?>

<?php if ($response): ?>
    <div class="box">
        <h3>Raw Response</h3>
        <pre><?= htmlspecialchars($response) ?></pre>
    </div>

    <div class="box">
        <h3>Decoded Response</h3>
        <pre><?php print_r($result); ?></pre>
    </div>

    <div class="box">
        <h3>Status Summary</h3>
        <?php
        $status = $result['result']['payment_status'] ?? 'unknown';
        $amount = $result['result']['amount'] ?? '0';
        $currency = $result['result']['currency'] ?? '';
        $network = $result['result']['network'] ?? '';

        echo "<p><b>Status:</b> $status</p>";
        echo "<p><b>Amount:</b> $amount $currency ($network)</p>";
        ?>
    </div>
<?php endif; ?>

</body>
</html>