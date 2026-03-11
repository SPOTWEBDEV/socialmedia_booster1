<?php

include('../../server/connection.php');
include('../../server/auth/client.php');



/* -------------------------
GET RAW POST DATA
------------------------- */

$payload = file_get_contents("php://input");
$data = json_decode($payload, true);

/* -------------------------
VERIFY SIGNATURE
------------------------- */

$headers = getallheaders();
$received_sign = $headers['sign'] ?? '';

$expected_sign = md5(base64_encode($payload) . $API_KEY);

if ($received_sign !== $expected_sign) {
    http_response_code(403);
    exit("Invalid signature");
}

/* -------------------------
PAYMENT DATA
------------------------- */

$order_id = $data['order_id'] ?? null;
$status   = $data['status'] ?? null;
$amount   = $data['amount'] ?? 0;
$tx_hash  = $data['tx_hash'] ?? null;

/* -------------------------
FIND DEPOSIT
------------------------- */

$stmt = $connection->prepare("SELECT * FROM deposit WHERE reference=?");
$stmt->bind_param("s", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    exit("Deposit not found");
}

$deposit = $result->fetch_assoc();

/* -------------------------
IF PAYMENT SUCCESS
------------------------- */

if ($status == "paid") {

    $user_id = $deposit['user_id'];

    // update deposit
    $stmt = $connection->prepare(
        "UPDATE deposit SET status='completed', response=? WHERE reference=?"
    );
    $stmt->bind_param("ss", $tx_hash, $order_id);
    $stmt->execute();

    // credit user balance
    $stmt = $connection->prepare(
        "UPDATE users SET balance = balance + ? WHERE id=?"
    );
    $stmt->bind_param("di", $amount, $user_id);
    $stmt->execute();
}

echo "OK";