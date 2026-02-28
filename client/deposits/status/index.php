<?php
include("../../../server/connection.php");
include('../../../server/auth/client.php');




$projectID = '69875132aebfc2d5a7e9a1cc';
$publicKey = 'pk_live-64e7b375350e4b519092237a4de9db2d';

$rawAccessCode = $_GET['access-code'] ?? $_GET['code'] ?? '';
$accessCode = explode('?', $rawAccessCode)[0];

if (!$accessCode) {
    die("Invalid access code");
}

if (!isset($_SESSION['verify_retry'])) {
    $_SESSION['verify_retry'] = 0;
}

// -------------------------
// VERIFY PAYMENT
// -------------------------
$url = "https://api-checkout.etegram.com/api/transaction/verify-payment/{$projectID}/{$accessCode}";

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST => "PATCH",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $publicKey",
        "Content-Type: application/json"
    ],
]);

$response = curl_exec($ch);

if ($response === false) {
    die("cURL Error: " . curl_error($ch));
}

curl_close($ch);

$responseText = trim($response);



// -------------------------
// MAP RESPONSE → STATUS
// -------------------------
if (stripos($responseText, 'already been processed') !== false) {
    $paymentStatus = 'approved';
} elseif (stripos($responseText, "hasn't been recieved") !== false) {
    $paymentStatus = 'pending';
} else {
    $paymentStatus = 'declined';
}

// -------------------------
// FETCH DEPOSIT DETAILS
// -------------------------
$stmt = $connection->prepare(
    "SELECT d.user_id, d.amount, d.status, d.reference, u.full_name, u.email, u.phone, u.balance
     FROM deposit d
     JOIN users u ON d.user_id = u.id
     WHERE d.access_code = ?"
);
$stmt->bind_param("s", $accessCode);
$stmt->execute();
$deposit = $stmt->get_result()->fetch_assoc();
$stmt->close();

// -------------------------
// UPDATE DEPOSIT STATUS
// -------------------------
if ($deposit && $deposit['status'] === 'pending') {

    // If approved → update immediately
    if ($paymentStatus === 'approved') {

        $stmt = $connection->prepare(
            "UPDATE deposit SET status = 'approved' WHERE access_code = ?"
        );
        $stmt->bind_param("s", $accessCode);
        $stmt->execute();
        $stmt->close();

        $newBalance = $deposit['balance'] + $deposit['amount'];
        $stmt = $connection->prepare("UPDATE users SET balance = ? WHERE id = ?");
        $stmt->bind_param("di", $newBalance, $deposit['user_id']);
        $stmt->execute();
        $stmt->close();

        $_SESSION['verify_retry'] = 0; // reset counter
    }

    // If still pending
    elseif ($paymentStatus === 'pending') {

        $_SESSION['verify_retry']++;

        if ($_SESSION['verify_retry'] >= 6) {

            // Keep status as pending in DB (optional update)
            $stmt = $connection->prepare(
                "UPDATE deposit SET status = 'declined' WHERE access_code = ?"
            );
            $stmt->bind_param("s", $accessCode);
            $stmt->execute();
            $stmt->close();

            $_SESSION['verify_retry'] = 0;

            header("Location: {$domain}client/deposits/history");
            exit;
        }
    }

    // If declined
    elseif ($paymentStatus === 'declined') {

        $stmt = $connection->prepare(
            "UPDATE deposit SET status = 'declined' WHERE access_code = ?"
        );
        $stmt->bind_param("s", $accessCode);
        $stmt->execute();
        $stmt->close();

        $_SESSION['verify_retry'] = 0;
    }
}



// UI VARIABLES
$isSuccess = $paymentStatus === 'approved';
$title = $isSuccess ? "Payment Successful" : ($paymentStatus === 'declined' ? "Payment Failed" : "Pending Payment");
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
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | Deposit Status </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" href="<?php echo $domain ?>assets/images/logo/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>client/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>client/vendor/toastr/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="dashboard">

    <div id="main-wrapper">
        <!-- nav -->
        <?php include("../../include/header.php") ?>

        <!-- sidenav -->
        <?php include("../../include/sidenav.php") ?>
        <div class="content-body">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-4">
                                    <div class="page-title-content">
                                        <h3>Deposit History</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="../"><button class="btn btn-primary mr-2">Made Deposit</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">

                    <div class="col-lg-6 col-md-8">

                        <div class="card border-0 shadow-sm rounded-4">

                            <!-- Card Header -->
                            <div class="card-body text-center p-4">

                                <!-- Status Icon -->
                                <?php if ($isSuccess): ?>
                                    <div class="mb-3">
                                        <i class="bi bi-check-circle-fill text-success" style="font-size: 60px;"></i>
                                    </div>
                                <?php elseif ($paymentStatus === 'declined'): ?>
                                    <div class="mb-3">
                                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 60px;"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="mb-3">
                                        <i class="bi bi-clock-history text-warning" style="font-size: 60px;"></i>
                                    </div>
                                <?php endif; ?>

                                <!-- Title -->
                                <h4 class="fw-bold mb-2"><?= $title ?></h4>

                                <!-- Badge -->
                                <?php if ($isSuccess): ?>
                                    <span class="badge bg-success-subtle text-success px-3 py-2">
                                        Approved
                                    </span>
                                <?php elseif ($paymentStatus === 'declined'): ?>
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                        Declined
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning-subtle text-warning px-3 py-2">
                                        Pending
                                    </span>
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
                                        <div class="col-6 text-end fw-semibold">
                                            <?= htmlspecialchars($deposit['reference'] ?? '') ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-6 text-muted">Amount</div>
                                        <div class="col-6 text-end fw-bold">
                                            ₦<?= number_format($deposit['amount'] ?? 0, 2) ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-6 text-muted">Full Name</div>
                                        <div class="col-6 text-end">
                                            <?= htmlspecialchars($deposit['full_name'] ?? '') ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-6 text-muted">Email</div>
                                        <div class="col-6 text-end text-truncate">
                                            <?= htmlspecialchars($deposit['email'] ?? '') ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-6 text-muted">Phone</div>
                                        <div class="col-6 text-end">
                                            <?= htmlspecialchars($deposit['phone'] ?? '') ?>
                                        </div>
                                    </div>

                                </div>

                                <!-- Button -->
                                <?php

                                if ($paymentStatus === 'pending' && $_SESSION['verify_retry'] < 6) {
                                    echo '<div class="alert alert-warning mt-4" role="alert">
                                                Verifying payment... Please wait.
                                            </div>';
                                } elseif ($paymentStatus === 'declined') {
                                    echo '<div class="alert alert-danger mt-4" role="alert">
                                                Payment declined. Please try again or contact support.
                                            </div>';
                                } else {
                                    echo '<div class="d-grid mt-4">
                                    <a href="<?= $domain ?>client/deposits/history"
                                        class="btn btn-primary btn-lg rounded-3">
                                        View Deposit History
                                    </a>
                                </div>';
                                }

                                ?>


                            </div>
                        </div>

                    </div>


                </div>

                <?php if ($paymentStatus === 'pending' && $_SESSION['verify_retry'] < 6): ?>
                    <script>
                        setTimeout(function() {
                            window.location.reload();
                        }, 4000); // 20 seconds
                    </script>
                <?php endif; ?>






                <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
                <script src="<?php echo $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
                <!--  -->
                <!--  -->
                <script src="<?php echo $domain ?>client/js/scripts.js"></script>
</body>

</html>