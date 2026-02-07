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
    $paymentStatus = 'declined';
} else {
    $paymentStatus = 'pending';
}

// -------------------------
// UPDATE DATABASE SAFELY
// -------------------------
$stmt = $connection->prepare(
    "UPDATE deposit SET status = ? WHERE access_code = ? AND status = 'pending'"
);
$stmt->bind_param("ss", $paymentStatus, $accessCode);
$stmt->execute();

// -------------------------
// FETCH DEPOSIT DETAILS
// -------------------------
$stmt = $connection->prepare(
    "SELECT d.user_id, d.amount, d.status, d.reference, u.full_name, u.email, u.phone
     FROM deposit d
     JOIN users u ON d.user_id = u.id
     WHERE d.access_code = ?"
);
$stmt->bind_param("s", $accessCode);
$stmt->execute();
$deposit = $stmt->get_result()->fetch_assoc();

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

            <div class="bg-white shadow-xl w-[90%] sm:w-[400px] rounded-2xl p-8 text-center">

                <!-- ICON -->
                <div class="mx-auto mb-6 w-24 h-24 rounded-full flex items-center justify-center <?= $iconBg ?>">
                    <?php if ($isSuccess): ?>
                        <svg class="w-14 h-14 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    <?php elseif ($paymentStatus === 'declined'): ?>
                        <svg class="w-14 h-14 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    <?php else: ?>
                        <svg class="w-14 h-14 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3" />
                        </svg>
                    <?php endif; ?>
                </div>

                <!-- TITLE -->
                <h1 class="text-2xl font-bold <?= $textColor ?>">
                    <?= $title ?>
                </h1>

                <!-- MESSAGE -->
                <p class="text-gray-600 mt-3">
                    <?= $isSuccess ? "Your deposit has been successfully processed and credited." : ($paymentStatus === 'declined' ? "Your payment could not be completed. Please try again." : "Your payment is pending.") ?>
                </p>

                <!-- DETAILS CARD -->
                <div class="mt-6 bg-gray-50 rounded-xl p-4 text-sm text-left space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Reference</span>
                        <span class="font-medium"><?= htmlspecialchars($deposit['reference']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="font-semibold <?= $textColor ?>"><?= ucfirst($deposit['status']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Amount</span>
                        <span class="font-medium">₦<?= number_format($deposit['amount'], 2) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Name</span>
                        <span class="font-medium"><?= htmlspecialchars($deposit['full_name']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email</span>
                        <span class="font-medium"><?= htmlspecialchars($deposit['email']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Phone</span>
                        <span class="font-medium"><?= htmlspecialchars($deposit['phone']) ?></span>
                    </div>
                </div>

                <!-- BUTTON -->
                <a href="/dashboard"
                    class="mt-8 inline-block w-full text-white py-3 rounded-xl font-semibold transition <?= $btnColor ?>">
                    Go to Dashboard
                </a>

            </div>
        </main>
    </div>

    <?php include('../../components/bottomnav.php') ?>







</body>

</html>