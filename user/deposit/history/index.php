<?php
include('../../../server/connection.php');
include('../../../server/auth/client.php');

// Handle search and status filter
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';

// Build the query dynamically
$query = "SELECT `id`, `user_id`, `reference`, `access_code`, `amount`, `status`, `created_at` FROM `deposit` WHERE 1";

if ($statusFilter !== '' && $statusFilter !== 'all') {
    $query .= " AND `status` = '" . mysqli_real_escape_string($connection, $statusFilter) . "'";
}

if ($search !== '') {
    $search = mysqli_real_escape_string($connection, $search);
    $query .= " AND (`reference` LIKE '%$search%' OR `user_id` LIKE '%$search%')";
}

$query .= " ORDER BY `created_at` DESC";

$result = mysqli_query($connection, $query);
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
            <header class="bg-white border-b px-6 py-4 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Welcome back</p>
                    <h1 class="text-xl font-semibold"><?php echo $fullname ?> 👋</h1>
                </div>
                <div class="flex items-center gap-4">
                    <button class="relative">
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        🔔
                    </button>
                </div>
            </header>

            <section class="p-6 space-y-6">
                <h2 class="text-xl font-semibold">Deposit History</h2>

                <!-- Filters -->
                <div class="bg-white p-4 rounded-xl flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
                    <form method="GET" class="flex gap-3">
                        <select id="status" class="border rounded-lg px-3 py-2 text-sm">
                            <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All Status</option>
                            <option value="approved" <?= $statusFilter === 'Approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="Processing" <?= $statusFilter === 'Processing' ? 'selected' : '' ?>>Processing</option>
                            <option value="Pending" <?= $statusFilter === 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Failed" <?= $statusFilter === 'Failed' ? 'selected' : '' ?>>Failed</option>
                        </select>
                        <input
                            id="searchInput"
                            type="search"
                            name="search"
                            value="<?= htmlspecialchars($search) ?>"
                            placeholder="Search by reference or user ID"
                            class="border rounded-lg px-4 py-2 text-sm w-full md:w-64" />



                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Filter</button>
                    </form>
                </div>

                <div class="bg-white p-4 rounded-xl ">

                 <!-- Desktop Table -->
                <div class="hidden md:block">
                    <table class="w-full text-sm">
                        <thead class="text-left text-gray-500">
                            <tr>
                                <th class="py-2">Reference</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3"><?= htmlspecialchars($row['reference']) ?></td>

                                        <td class="<?= $row['status'] === 'approved' ? 'text-green-600' : ($row['status'] === 'Processing' ? 'text-yellow-600' : 'text-red-600') ?>">
                                            <?= $row['status'] ?>
                                        </td>
                                        <td>₦<?= number_format($row['amount']) ?></td>
                                        <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>

                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-400">No deposits found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Vertical Orders -->
                <div class="space-y-4 md:hidden">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php mysqli_data_seek($result, 0); // Reset result pointer for mobile 
                        ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium"><?= htmlspecialchars($row['reference']) ?></span>
                                    <span class="<?= $row['status'] === 'Completed' ? 'text-green-600' : ($row['status'] === 'Processing' ? 'text-yellow-600' : 'text-red-600') ?> text-sm">
                                        <?= $row['status'] ?>
                                    </span>
                                </div>

                                <div class="mt-1 text-sm text-gray-500">Amount: ₦<?= number_format($row['amount']) ?></div>
                                <div class="text-sm text-gray-500">Date: <?= date('d M Y', strtotime($row['created_at'])) ?></div>

                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center text-gray-400 py-4">No deposits found</div>
                    <?php endif; ?>
                </div>
                </div>

               








            </section>


        </main>
    </div>

    <?php include('../../components/bottomnav.php') ?>

</body>

</html>