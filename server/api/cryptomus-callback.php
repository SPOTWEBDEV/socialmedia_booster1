<?php
include('../../server/connection.php');

header('Content-Type: application/json');

// get raw input
$input = file_get_contents("php://input");
$data = json_decode($input, true);

$order_id = $data['order_id'] ?? '';
$status = $data['payment_status'] ?? '';

if (!$order_id) {
    exit("No order_id");
}

// only process successful payments
if ($status === "paid" || $status === "paid_over") {

    // 🔍 Get deposit
    $stmt = $connection->prepare("SELECT user_id, amount, status FROM deposit WHERE reference = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $deposit = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($deposit) {

        // 🔐 جلوگیری از duplicate credit (VERY IMPORTANT)
        if ($deposit['status'] !== 'approved') {

            // ✅ update deposit
            $stmt = $connection->prepare("UPDATE deposit SET status = 'approved' WHERE reference = ?");
            $stmt->bind_param("s", $order_id);
            $stmt->execute();
            $stmt->close();

            // ✅ credit user balance
            $stmt = $connection->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $stmt->bind_param("di", $deposit['amount'], $deposit['user_id']);
            $stmt->execute();
            $stmt->close();
        }
    }
}

echo json_encode(["success" => true]);