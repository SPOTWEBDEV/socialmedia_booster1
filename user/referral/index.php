<?php
include('../../server/connection.php');
include('../../server/auth/client.php');

$userId = $id; // from auth

$userReferral = mysqli_fetch_assoc(mysqli_query($connection, "
    SELECT referral_code, referral_earnings 
    FROM users 
    WHERE id = '$userId'
"));

$referralSummary = mysqli_fetch_assoc(mysqli_query($connection, "
    SELECT COUNT(*) AS total_referrals, 
           COALESCE(SUM(referral_earnings),0) AS total_earned 
    FROM users 
    WHERE referrer_id = '$userId'
"));

$referredUsers = mysqli_query($connection, "
    SELECT full_name, email, referral_earnings, created_at 
    FROM users 
    WHERE referrer_id = '$userId'
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral - BoostPanel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .icon-gradient {
            background: linear-gradient(135deg, #4D5CDA, #7F9CF5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="bg-gray-100 font-inter">

    <div class="flex flex-col md:flex-row min-h-screen">

        <!-- Sidebar -->
        <?php include('../components/sidebar.php') ?>

        <!-- Main Content -->
        <main class="flex-1 ">

            <!-- Header -->
            <?php include('../components/header.php') ?>

            <section class="p-2 sm:p-6 space-y-6 ">
                <section class="p-6 space-y-6">

                    <!-- Page Title -->
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Referral Dashboard</h1>
                            <p class="text-gray-500 mt-1 text-sm sm:text-base">Invite friends & earn rewards</p>
                        </div>
                    </div>

                    <!-- Referral Code Card -->
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl shadow-lg p-4 md:p-6 flex flex-col md:flex-row md:justify-between items-start md:items-center gap-4 w-full">
                        <div class="flex-1">
                            <p class="text-sm font-medium opacity-90">Your Referral Code</p>
                            <h3 class="text-2xl sm:text-3xl font-bold tracking-wide" id="referralCode"><?php echo htmlspecialchars($userReferral['referral_code']); ?></h3>
                            <p class="mt-1 text-white/80 text-sm">Share this code with friends to earn rewards</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="copyReferral()" class="bg-white text-blue-600 px-3 sm:px-4 py-2 rounded-lg hover:bg-gray-100 font-semibold flex items-center gap-2 transition text-sm sm:text-base">
                                <i class="bi bi-clipboard-fill"></i> Copy
                            </button>
                            <a href="https://wa.me/?text=Join%20Boostkore%20using%20my%20referral%20code%20<?php echo $userReferral['referral_code']; ?>" target="_blank" class="bg-green-500 px-3 sm:px-4 py-2 rounded-lg hover:bg-green-600 transition flex items-center gap-2 font-semibold text-sm sm:text-base">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <div class="bg-white shadow-md rounded-xl p-4 sm:p-6 flex items-center justify-between hover:shadow-lg transition w-full">
                            <div>
                                <p class="text-gray-500 font-medium text-sm sm:text-base">Total Referrals</p>
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900"><?php echo $referralSummary['total_referrals']; ?></h3>
                            </div>
                            <i class="bi bi-people-fill text-3xl sm:text-4xl icon-gradient"></i>
                        </div>

                        <div class="bg-white shadow-md rounded-xl p-4 sm:p-6 flex items-center justify-between hover:shadow-lg transition w-full">
                            <div>
                                <p class="text-gray-500 font-medium text-sm sm:text-base">Total Earnings</p>
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900">₦<?php echo number_format($referralSummary['total_earned'], 0); ?></h3>
                            </div>
                            <i class="bi bi-cash-stack text-3xl sm:text-4xl icon-gradient"></i>
                        </div>
                    </div>

                    <!-- Chart Section -->
                    <div class="bg-white shadow-md rounded-xl p-4 sm:p-6 w-full">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Referral Earnings Analysis</h3>
                        <canvas id="referralChart" class="w-full h-64 sm:h-72"></canvas>
                    </div>

                    <!-- Referred Users Table -->
                    <div class="bg-white shadow-md rounded-xl p-4 sm:p-6 overflow-x-auto w-full">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Your Referred Users</h3>
                        <table class="w-full text-sm sm:text-base table-auto border-collapse min-w-[500px]">
                            <thead class="bg-gray-50 text-gray-500 text-left">
                                <tr>
                                    <th class="px-4 py-2">Full Name</th>
                                    <th class="px-4 py-2">Email</th>
                                    <th class="px-4 py-2">Referral Earnings</th>
                                    <th class="px-4 py-2">Joined</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                <?php while ($row = mysqli_fetch_assoc($referredUsers)): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-2 font-medium text-gray-900"><?php echo htmlspecialchars($row['full_name']); ?></td>
                                        <td class="px-4 py-2 text-gray-600"><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td class="px-4 py-2 text-green-600 font-semibold">₦<?php echo number_format($row['referral_earnings'], 0); ?></td>
                                        <td class="px-4 py-2 text-gray-500"><?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                </section>
            </section>



        </main>
    </div>

    <!-- Copy Referral JS -->
    <script>
        function copyReferral() {
            const code = "<?php echo htmlspecialchars($userReferral['referral_code']); ?>";
            navigator.clipboard.writeText(code).then(() => {
                alert("Referral code copied: " + code);
            });
        }
    </script>

    <!-- Chart.js Script -->
    <script>
        const ctx = document.getElementById('referralChart').getContext('2d');
        const referralChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    <?php
                    $chartData = mysqli_query($connection, "
                SELECT DATE(created_at) AS ref_date, SUM(referral_earnings) AS earned 
                FROM users 
                WHERE referrer_id = '$userId'
                GROUP BY DATE(created_at)
                ORDER BY DATE(created_at) ASC
            ");
                    $dates = [];
                    $earnings = [];
                    while ($cd = mysqli_fetch_assoc($chartData)) {
                        $dates[] = '"' . $cd['ref_date'] . '"';
                        $earnings[] = $cd['earned'];
                    }
                    echo implode(',', $dates);
                    ?>
                ],
                datasets: [{
                    label: 'Earnings (₦)',
                    data: [<?php echo implode(',', $earnings); ?>],
                    fill: true,
                    backgroundColor: 'rgba(77,92,218,0.15)',
                    borderColor: 'rgba(77,92,218,1)',
                    tension: 0.3,
                    pointBackgroundColor: 'rgba(77,92,218,1)',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>

</html>