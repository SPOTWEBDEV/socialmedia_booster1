<?php
include('../../server/connection.php');
include('../../server/auth/client.php');

header('Content-Type: application/json');

$body = json_decode(file_get_contents("php://input"), true);

$user_id = intval($body['user_id'] ?? 0);
$amount = $body['amount']; // default
$currency = $body['currency'];

if ($user_id <= 0) {
    echo json_encode(["error" => "Invalid user"]);
    exit;
}

/* -------------------------
FETCH USER
------------------------- */
$stmt = $connection->prepare("SELECT email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["error" => "User not found"]);
    exit;
}

/* -------------------------
CREATE ORDER
------------------------- */
$order_id = uniqid("CRY_");

/* -------------------------
REQUEST DATA
------------------------- */
$data = [
    "amount" => (string)$amount,
    "currency" => $currency,
    "order_id" => $order_id,

    // 🔥 redirects
    "url_success" => $domain . "client/deposits/status/cry/?action=success&order_id=" . $order_id,
    "url_return"  => $domain . "client/deposits/status/cry/?action=cancel&order_id=" . $order_id,

    // 🔥 webhook
    "url_callback" => $domain . "server/api/cryptomus-callback.php"
];

$jsonData = json_encode($data);

/* -------------------------
SIGNATURE
------------------------- */
$URL = "https://api.cryptomus.com/v1/payment";
$sign = md5(base64_encode($jsonData) . $API_KEY);

/* -------------------------
CURL REQUEST
------------------------- */
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
    echo json_encode(["error" => curl_error($ch)]);
    exit;
}

$result = json_decode($response, true);

/* -------------------------
SUCCESS
------------------------- */
if ($result['state'] == 0) {

    $payment_url = $result['result']['url'];
    $uuid = $result['result']['uuid'];

    $response_json = json_encode($result);

    $stmt = $connection->prepare(
        "INSERT INTO deposit (user_id, reference, currency, network, status, response, access_code , amount)
         VALUES (?, ?, ?, ?, 'pending', ?, ?, ?)"
    );

    // network not needed → reuse currency or set NULL
    $network = $currency;

    $stmt->bind_param("issssss", $user_id, $order_id, $currency, $network, $response_json, $uuid, $amount);
    $stmt->execute();

    echo json_encode([
        "status" => true,
        "payment_url" => $payment_url,
        "reference" => $order_id,
        "response"=>$result['result']
    ]);
} else {
    echo json_encode([
        "error" => $result
    ]);
}
