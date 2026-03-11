<?php
include('../../server/connection.php');
include('../../server/auth/client.php');

header('Content-Type: application/json');

$body = json_decode(file_get_contents("php://input"), true);

$amount  = floatval($body['amount'] ?? 0);
$user_id = intval($body['user_id'] ?? 0);

if ($amount <= 0) {
    echo json_encode(["error" => "Invalid amount"]);
    exit;
}

if ($user_id <= 0) {
    echo json_encode(["error" => "Invalid user"]);
    exit;
}

/* -------------------------
FETCH USER
------------------------- */

$stmt = $connection->prepare("SELECT email, phone, full_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

$user = $result->fetch_assoc();

/* -------------------------
CRYPTOMUS CONFIG
------------------------- */





/* -------------------------
CREATE ORDER
------------------------- */

$order_id = uniqid("CRY_");

$network = $body['network'] ?? '';
$currency = $body['currency'] ?? '';

if (!$network || !$currency) {
    echo json_encode([
        "error" => "Network or currency missing"
    ]);
    exit;
}

$data = [
    "currency" => $currency,
    "network" => $network,
    "order_id" => $order_id,
    "url_callback" => "https://yourdomain.com/cryptomus-callback.php"
];

$jsonData = json_encode($data);

/* -------------------------
SIGNATURE
------------------------- */
$URL = "https://api.cryptomus.com/v1/wallet";
$sign = md5(base64_encode($jsonData) . $API_KEY);



$ch = curl_init($URL);

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
    echo json_encode([
        "error" => curl_error($ch)
    ]);
    exit;
}

$result = json_decode($response, true);

if ($result['state'] == 0) {

    $wallet = $result['result']['address'];
    $url = $result['result']['url'];
    $access_code = $result['result']['uuid'];

    $stmt = $connection->prepare(
        "INSERT INTO deposit (user_id, reference, amount, currency, network, status, response, access_code)
        VALUES (?, ?, ?, ?, ?, 'pending', ?, ?)"
    );

    $stmt->bind_param("isdssss", $user_id, $order_id, $amount, $currency, $network, $response, $access_code);
    $stmt->execute();

    echo json_encode([
        "status" => true,
        "wallet_address" => $wallet,
        "network" => $network,
        "currency" => $currency,
        "amount" => $amount,
        "authorization_url" => $url
    ]);

} else {

    echo json_encode([
        "error" => $result
    ]);

}