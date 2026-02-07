<?php
include('../../../server/connection.php');

/* -------------------------
   GET & CLEAN ACCESS CODE
-------------------------- */
$access = $_GET['access-code'];
$rawAccessCode = $_GET['access-code'] ?? $_GET['code'] ?? '';
$accessCode = explode('?', $rawAccessCode)[0];

if (!$accessCode) {
    die("Invalid access code");
}

/* -------------------------
   FETCH TRANSACTION BY ACCESS CODE
-------------------------- */
$stmt = $connection->prepare(
    "SELECT id, reference, access_code , status, amount FROM deposit WHERE access_code = ?"
);
$stmt->bind_param("s", $accessCode);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Transaction not found");
}

$deposit = $result->fetch_assoc();
$reference = $deposit['reference'];

/* -------------------------
   DEFAULT STATUS
-------------------------- */
$paymentStatus = $deposit['status'];


echo $paymentStatus;

/* -------------------------
   VERIFY ONLY IF PENDING
-------------------------- */
if ($paymentStatus === 'pending') {

    $projectID = '69875132aebfc2d5a7e9a1cc';
    $publicKey = 'pk_live-64e7b375350e4b519092237a4de9db2d';

    $url = "https://api-checkout.etegram.com/api/transaction/verify/{$projectID}/{$accessCode}";

    

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => "PATCH",   // ✅ REQUIRED
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $publicKey"
        ]
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    print_r($response);

    $res = json_decode($response, true);

    print_r($res);

    // if (!empty($res['data']['status']) && $res['data']['status'] === 'success') {
    //     $paymentStatus = 'approved';
    // } else {
    //     $paymentStatus = 'declined';
    // }

    /* -------------------------
       UPDATE DB
    -------------------------- */
    // $stmt = $connection->prepare(
    //     "UPDATE deposit SET status = ? WHERE id = ?"
    // );
    // $stmt->bind_param("si", $paymentStatus, $deposit['id']);
    // $stmt->execute();
}

/* -------------------------
   UI VARIABLES
-------------------------- */
$isSuccess = $paymentStatus === 'approved';

$title = $isSuccess ? "Payment Successful" : "Payment Failed";
$message = $isSuccess
    ? "Your deposit has been successfully processed and credited."
    : "Your payment could not be verified. Please contact support.";

$textColor = $isSuccess ? "text-green-600" : "text-red-600";
$iconBg   = $isSuccess ? "bg-green-100" : "bg-red-100";
$btnColor = $isSuccess ? "bg-green-600 hover:bg-green-700" : "bg-red-600 hover:bg-red-700";
?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans">

    <div class="max-w-md w-full">
        <div class="bg-white shadow-xl rounded-2xl p-8 text-center">

            <!-- ICON -->
            <div class="mx-auto mb-6 w-24 h-24 rounded-full flex items-center justify-center <?= $iconBg ?>">
                <?php if ($isSuccess): ?>
                    <!-- SUCCESS ICON -->
                    <svg class="w-14 h-14 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5 13l4 4L19 7" />
                    </svg>
                <?php else: ?>
                    <!-- FAILED ICON -->
                    <svg class="w-14 h-14 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                <?php endif; ?>
            </div>

            <!-- TITLE -->
            <h1 class="text-2xl font-bold <?= $textColor ?>">
                <?= $title ?>
            </h1>

            <!-- MESSAGE -->
            <p class="text-gray-600 mt-3">
                <?= $message ?>
            </p>

            <!-- DETAILS -->
            <div class="mt-6 bg-gray-50 rounded-xl p-4 text-sm text-left">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-500">Reference</span>
                    <span class="font-medium"><?= htmlspecialchars($accessCode) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="font-semibold <?= $textColor ?>">
                        <?= ucfirst($paymentStatus) ?>
                    </span>
                </div>
            </div>

            <!-- ACTION -->
            <a href="/dashboard"
               class="mt-8 inline-block w-full text-white py-3 rounded-xl font-semibold transition <?= $btnColor ?>">
                Go to Dashboard
            </a>

        </div>
    </div>

</body>
</html>
