<?php
include('../../server/connection.php');

header('Content-Type: application/json');

// get raw input
$input = file_get_contents("php://input");
$data = json_decode($input, true);

$order_id = $data['order_id'] ?? '';
$status   = $data['payment_status'] ?? '';

if (!$order_id) {
    exit("No order_id");
}

// only process successful payments
if ($status === "paid" || $status === "paid_over") {

    // 🔍 Get deposit
    $stmt = $connection->prepare("SELECT user_id, amount FROM deposit WHERE reference = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $deposit = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($deposit) {

        // ✅ ATOMIC UPDATE (prevents double credit)
        $stmt = $connection->prepare("
            UPDATE deposit 
            SET status = 'approved' 
            WHERE reference = ? AND status = 'pending'
        ");
        $stmt->bind_param("s", $order_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {

            // ✅ ONLY credit if status changed
            $stmt2 = $connection->prepare("
                UPDATE users 
                SET balance = balance + ? 
                WHERE id = ?
            ");
            $stmt2->bind_param("di", $deposit['amount'], $deposit['user_id']);
            $stmt2->execute();
            $stmt2->close();
        }

        $stmt->close();
    }
}

echo json_encode(["success" => true]);