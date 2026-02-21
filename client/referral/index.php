<?php
include('../../server/connection.php');
include('../../server/auth/client.php');

$userId = $id;



/* USER REFERRAL INFO */
$userReferral = mysqli_fetch_assoc(mysqli_query($connection, "
    SELECT referral_code, referral_earnings 
    FROM users 
    WHERE id = '$userId'
"));

/* SUMMARY */
$referralSummary = mysqli_fetch_assoc(mysqli_query($connection, "
    SELECT COUNT(*) AS total_referrals,
           COALESCE(SUM(referral_earnings),0) AS total_earned
    FROM users
    WHERE referrer_id = '$userId'
"));

/* REFERRED USERS */
$referredUsers = mysqli_query($connection, "
    SELECT full_name, email, referral_earnings, created_at
    FROM users
    WHERE referrer_id = '$userId'
    ORDER BY created_at DESC
");




$refCode = urlencode($userReferral['referral_code']);

$inviteMessage = urlencode(
    "🚀 Join $sitename today and start boosting your social media account!\n\n" .
        "Use my referral code: {$userReferral['referral_code']}\n\n" .
        "Sign up here: {$domain}register?referral_code={$userReferral['referral_code']}"
);

$whatsappLink = "https://wa.me/?text={$inviteMessage}";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $sitename ?> | Referral</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= $domain ?>/images/favicon.png">
    <link rel="stylesheet" href="<?= $domain ?>client/css/style.css">
    <link rel="stylesheet" href="<?= $domain ?>client/vendor/toastr/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="dashboard">

    <div id="main-wrapper">

        <!-- Header -->
         <?php include("../include/header.php") ?>


        <!-- Sidebar -->
        <?php include("../include/sidenav.php") ?>

        <div class="content-body">
            <div class="container">

                <!-- Page Title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <h3>Referral Dashboard</h3>
                            <p>Invite friends & earn rewards</p>
                        </div>
                    </div>
                </div>

                <!-- Referral Code -->
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card bg-primary text-white">
                            <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <small>Your Referral Code</small>
                                    <h3 class="text-white" id="referralCode"><?= htmlspecialchars($userReferral['referral_code']) ?></h3>
                                    <small>Share this code with friends</small>
                                </div>

                                <div class="mt-2">
                                    <button onclick="copyReferral()" class="btn btn-light text-black me-2">
                                        <i class="bi bi-clipboard"></i> Copy
                                    </button>

                                    <a target="_blank"
                                        href="<?= $whatsappLink ?>"
                                        class="btn btn-success">
                                        <i class="bi bi-whatsapp"></i> Share on WhatsApp
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="row">
                    <div class="col-xxl-6 col-xl-6">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    <p>Total Referrals</p>
                                    <h3><?= $referralSummary['total_referrals'] ?></h3>
                                </div>
                                <i class="bi bi-people-fill fs-1 text-primary"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-6 col-xl-6">
                        <div class="card">
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    <p>Total Earnings</p>
                                    <h3>₦<?= number_format($referralSummary['total_earned'], 0) ?></h3>
                                </div>
                                <i class="bi bi-cash-stack fs-1 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Referral Earnings Analysis</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="referralChart" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Referred Users</h4>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Earnings</th>
                                            <th>Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($referredUsers)): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['full_name']) ?></td>
                                                <td><?= htmlspecialchars($row['email']) ?></td>
                                                <td class="text-success">
                                                    ₦<?= number_format($row['referral_earnings'], 0) ?>
                                                </td>
                                                <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="<?php echo $domain ?>client/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>client/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $domain ?>client/js/scripts.js"></script>
    
    <script>
        function copyReferral() {
            const referralCode = document.getElementById("referralCode").textContent;
            navigator.clipboard.writeText(referralCode).then(() => {
                alert("Referral code copied to clipboard!");
            }).catch(err => {
                alert("Failed to copy referral code. Please try manually.");
            });
        }
    </script>
    <script>
        const ctx = document.getElementById('referralChart').getContext('2d');
        const referralChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [<?php
                    $earningsData = [];
                    $dateLabels = [];
                    $result = mysqli_query($connection, "
                        SELECT DATE(created_at) AS date, SUM(referral_earnings) AS earnings
                        FROM users
                        WHERE referrer_id = '$userId'
                        GROUP BY DATE(created_at)
                        ORDER BY DATE(created_at) ASC
                    ");
                    while ($row = mysqli_fetch_assoc($result)) {
                        $dateLabels[] = "'" . date("d M", strtotime($row['date'])) . "'";
                        $earningsData[] = $row['earnings'];
                    }
                    echo implode(",", $dateLabels);
                ?>],
                datasets: [{
                    label: 'Earnings (₦)',
                    data: [<?php echo implode(",", $earningsData); ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    </script>
</body>
