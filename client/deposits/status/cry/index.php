<?php 
include("../../../../server/connection.php");
include('../../../../server/auth/client.php');



$order_id = $_GET['order_id'] ?? '';
$action   = $_GET['action'] ?? '';

if (!$order_id) {
    die("Invalid order");
}

// -------------------------
// FETCH DEPOSIT
// -------------------------
$stmt = $connection->prepare("
    SELECT d.user_id, d.amount, d.status, d.reference,
           u.full_name, u.email, u.phone, u.balance,
           d.currency, d.network
    FROM deposit d
    JOIN users u ON d.user_id = u.id
    WHERE d.reference = ?
");
$stmt->bind_param("s", $order_id);
$stmt->execute();
$deposit = $stmt->get_result()->fetch_assoc();
$stmt->close();

// -------------------------
// CANCEL (SAFE)
// -------------------------
if ($action === 'cancel' && $deposit['status'] === 'pending') {
    $stmt = $connection->prepare("UPDATE deposit SET status = 'declined' WHERE reference = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $stmt->close();

    $deposit['status'] = 'declined';
}

// -------------------------
// VERIFY WITH CRYPTOMUS
// -------------------------
if ($deposit && $deposit['status'] === 'pending') {

    $data = ["order_id" => $order_id];
    $jsonData = json_encode($data);
    $sign = md5(base64_encode($jsonData) . $API_KEY);

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
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['state']) && $result['state'] == 0) {

        $apiStatus = $result['result']['payment_status'];

        // -------------------------
        // SUCCESS
        // -------------------------
        if ($apiStatus === "paid" || $apiStatus === "paid_over") {

            // ✅ ATOMIC UPDATE
            $stmt = $connection->prepare("
                UPDATE deposit 
                SET status = 'approved' 
                WHERE reference = ? AND status = 'pending'
            ");
            $stmt->bind_param("s", $order_id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {

                // ✅ credit user ONCE
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
            $deposit['status'] = 'approved';
        }

        // -------------------------
        // FAILED
        // -------------------------
        elseif ($apiStatus === "cancel" || $apiStatus === "fail") {

            $stmt = $connection->prepare("
                UPDATE deposit 
                SET status = 'declined' 
                WHERE reference = ? AND status = 'pending'
            ");
            $stmt->bind_param("s", $order_id);
            $stmt->execute();
            $stmt->close();

            $deposit['status'] = 'declined';
        }
    }
}

// -------------------------
// UI LOGIC
// -------------------------
$paymentStatus = $deposit['status'] ?? 'pending';
$isSuccess = $paymentStatus === 'approved';

$title = $isSuccess ? "Payment Successful" :
         ($paymentStatus === 'declined' ? "Payment Failed" : "Pending Payment");


$bgColor = $isSuccess ? "bg-green-50" : ($paymentStatus === 'declined' ? "bg-red-50" : "bg-yellow-50");
$iconBg = $isSuccess ? "bg-green-100" : ($paymentStatus === 'declined' ? "bg-red-100" : "bg-yellow-100");
$textColor = $isSuccess ? "text-green-600" : ($paymentStatus === 'declined' ? "text-red-600" : "text-yellow-600");
$btnColor = $isSuccess ? "bg-green-600 hover:bg-green-700" : ($paymentStatus === 'declined' ? "bg-red-600 hover:bg-red-700" : "bg-yellow-600 hover:bg-yellow-700");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $sitename ?> | Deposit Status</title>
    <link rel="icon" type="image/png" href="<?php echo $domain ?>assets/images/logo/favicon.png">
    <link rel="stylesheet" href="<?php echo $domain ?>client/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>client/vendor/toastr/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body class="dashboard">
<div id="main-wrapper">

    <?php include("../../../include/header.php") ?>
    <?php include("../../../include/sidenav.php") ?>

    <div class="content-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page-title">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-xl-4">
                                <div class="page-title-content">
                                    <h3>Deposit Status</h3>
                                    <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="<?php echo $domain ?>client/despoits"><button class="btn btn-primary mr-2">Make Deposit</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body text-center p-4">

                            <!-- Status Icon -->
                            <div class="mb-3">
                                <?php if ($isSuccess): ?>
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 60px;"></i>
                                <?php elseif ($paymentStatus === 'declined'): ?>
                                    <i class="bi bi-x-circle-fill text-danger" style="font-size: 60px;"></i>
                                <?php else: ?>
                                    <i class="bi bi-clock-history text-warning" style="font-size: 60px;"></i>
                                <?php endif; ?>
                            </div>

                            <!-- Title -->
                            <h4 class="fw-bold mb-2"><?= $title ?></h4>

                            <!-- Badge -->
                            <?php if ($isSuccess): ?>
                                <span class="badge bg-success-subtle text-success px-3 py-2">Approved</span>
                            <?php elseif ($paymentStatus === 'declined'): ?>
                                <span class="badge bg-danger-subtle text-danger px-3 py-2">Declined</span>
                            <?php else: ?>
                                <span class="badge bg-warning-subtle text-warning px-3 py-2">Pending</span>
                            <?php endif; ?>

                            <!-- Description -->
                            <p class="text-muted mt-3 mb-4">
                                <?php if ($isSuccess): ?>
                                    Your deposit has been successfully processed and credited to your account.
                                <?php elseif ($paymentStatus === 'declined'): ?>
                                    Your payment was unsuccessful. Please try again or contact support.
                                <?php else: ?>
                                    Your payment is currently being processed. This may take a few minutes.
                                <?php endif; ?>
                            </p>

                            <hr>

                            <!-- Transaction Details -->
                            <div class="text-start">
                                <div class="row mb-3">
                                    <div class="col-6 text-muted">Reference</div>
                                    <div class="col-6 text-end fw-semibold"><?= htmlspecialchars($deposit['reference'] ?? '') ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6 text-muted">Amount</div>
                                    <div class="col-6 text-end fw-bold"><?= number_format($deposit['amount'] ?? 0, 2) ?> <?= htmlspecialchars($deposit['currency'] ?? '') ?> (<?= htmlspecialchars($deposit['network'] ?? '') ?>)</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6 text-muted">Full Name</div>
                                    <div class="col-6 text-end"><?= htmlspecialchars($deposit['full_name'] ?? '') ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6 text-muted">Email</div>
                                    <div class="col-6 text-end text-truncate"><?= htmlspecialchars($deposit['email'] ?? '') ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6 text-muted">Phone</div>
                                    <div class="col-6 text-end"><?= htmlspecialchars($deposit['phone'] ?? '') ?></div>
                                </div>
                            </div>

                            <!-- Button -->
                            <div class="d-grid mt-4">
                                <a href="<?= $domain ?>client/deposits/history"
                                   class="btn btn-primary btn-lg rounded-3">
                                    View Deposit History
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <?php if (!$isSuccess && $paymentStatus !== 'declined' && $action !== 'cancel'): ?>
                <script>
                    setTimeout(function() {
                        window.location.reload();
                    }, 4000);
                </script>
            <?php endif; ?>

        </div>
    </div>

</div>

<script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $domain ?>client/js/scripts.js"></script>
</body>
</html>