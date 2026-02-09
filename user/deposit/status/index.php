<?php
include('../../../server/connection.php');
include('../../../server/auth/client.php');

$projectID = '69875132aebfc2d5a7e9a1cc';
$publicKey = 'pk_live-64e7b375350e4b519092237a4de9db2d';

$rawAccessCode = $_GET['access-code'] ?? $_GET['code'] ?? '';
$accessCode = explode('?', $rawAccessCode)[0];

if (!$accessCode) {
    die("Invalid access code");
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
    $stmt = $connection->prepare(
        "UPDATE deposit SET status = ? WHERE access_code = ? AND status = 'pending'"
    );
    $stmt->bind_param("ss", $paymentStatus, $accessCode);
    $stmt->execute();
    $stmt->close();

    // -------------------------
    // CREDIT USER BALANCE IF APPROVED
    // -------------------------
    if ($paymentStatus === 'approved') {
        $newBalance = $deposit['balance'] + $deposit['amount'];
        $stmt = $connection->prepare("UPDATE users SET balance = ? WHERE id = ?");
        $stmt->bind_param("di", $newBalance, $deposit['user_id']);
        $stmt->execute();
        $stmt->close();
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
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Deposit Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100 ">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include('../../components/sidebar.php') ?>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
            <!-- Topbar -->
            <?php include('../../components/header.php') ?>

            <!-- Page Content -->
            <section class="flex-1 flex items-center justify-center px-4 py-10">

                <!-- Status Card -->
                <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 text-center">

                    <!-- Status Icon -->
                    <div class="mx-auto mb-6 w-20 h-20 rounded-full flex items-center justify-center <?= $iconBg ?>">
                        <?php if ($isSuccess): ?>
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        <?php elseif ($paymentStatus === 'declined'): ?>
                            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        <?php else: ?>
                            <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                            </svg>
                        <?php endif; ?>
                    </div>

                    <!-- Title -->
                    <h1 class="text-2xl font-bold <?= $textColor ?>">
                        <?= $title ?>
                    </h1>

                    <!-- Description -->
                    <p class="text-gray-500 mt-2 text-sm leading-relaxed">
                        <?php if ($isSuccess): ?>
                            Your deposit has been successfully processed and credited to your account.
                        <?php elseif ($paymentStatus === 'declined'): ?>
                            Your payment was unsuccessful. Please try again or contact support.
                        <?php else: ?>
                            Your payment is currently being processed. This may take a few minutes.
                        <?php endif; ?>
                    </p>

                    <!-- Divider -->
                    <div class="my-6 border-t"></div>

                    <!-- Transaction Details -->
                    <div class="bg-gray-50 rounded-xl p-4 text-sm text-left space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Reference</span>
                            <span class="font-medium text-gray-800">
                                <?= htmlspecialchars($deposit['reference']) ?>
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="font-semibold <?= $textColor ?>">
                                <?= ucfirst($deposit['status']) ?>
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Amount</span>
                            <span class="font-semibold text-gray-800">
                                ₦<?= number_format($deposit['amount'], 2) ?>
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Name</span>
                            <span class="text-gray-800">
                                <?= htmlspecialchars($deposit['full_name']) ?>
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Email</span>
                            <span class="text-gray-800 truncate">
                                <?= htmlspecialchars($deposit['email']) ?>
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Phone</span>
                            <span class="text-gray-800">
                                <?= htmlspecialchars($deposit['phone']) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <a href="<?= $domain ?>user/deposit/history"
                        class="mt-8 inline-flex items-center justify-center w-full py-3 rounded-xl font-semibold text-white transition <?= $btnColor ?>">
                        View Deposit History
                    </a>

                </div>
            </section>
        </main>
    </div>

    <?php include('../../components/bottomnav.php') ?>

</body>


</html>