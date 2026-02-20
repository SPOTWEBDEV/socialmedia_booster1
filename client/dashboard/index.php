<?php
include("../../server/connection.php");
include('../../server/auth/client.php');



/* ------------------------------------
   DASHBOARD STATISTICS
------------------------------------- */

// Total Orders + Total Spent
$order_stmt = $connection->prepare("
    SELECT COUNT(*) as total_orders,
           SUM(naria_price) as total_spent
    FROM user_orders
    WHERE user = ?
");
$order_stmt->bind_param("i", $id);
$order_stmt->execute();
$order_data = $order_stmt->get_result()->fetch_assoc();

$total_orders = $order_data['total_orders'] ?? 0;
$total_spent = $order_data['total_spent'] ?? 0;


// Total Deposits
$deposit_stmt = $connection->prepare("
    SELECT COUNT(*) as total_deposits,
           SUM(amount) as total_deposit_amount
    FROM deposit
    WHERE user_id = ?
");
$deposit_stmt->bind_param("i", $id);
$deposit_stmt->execute();
$deposit_data = $deposit_stmt->get_result()->fetch_assoc();

$total_deposits = $deposit_data['total_deposits'] ?? 0;
$total_deposit_amount = $deposit_data['total_deposit_amount'] ?? 0;


/* ------------------------------------
   RECENT ACTIVITY (Deposit + Orders)
------------------------------------- */

$sql = "
    SELECT id,
           'Deposit' AS type,
           amount AS amount,
           created_at AS date,
           status
    FROM deposit
    WHERE user_id = ?

    UNION ALL

    SELECT id,
           'Order' AS type,
           -naria_price AS amount,
           created_at AS date,
           status
    FROM user_orders
    WHERE user = ?

    ORDER BY date DESC
    LIMIT 10
";

$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "ii", $id, $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);


/* ------------------------------------
   HELPER FUNCTION
------------------------------------- */
function money($amount)
{
    return number_format((float)$amount, 4);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $sitename ?> | dashboard</title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/favicon.png">
    <link rel="stylesheet" href="<?php echo $domain ?>client/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>client/vendor/toastr/toastr.min.css">
</head>

<body class="dashboard">

    <div id="main-wrapper">

        <?php include("../include/header.php") ?>
        <!-- side nav -->
        <?php include("../include/sidenav.php") ?>

        <div class="content-body">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-4">
                                    <div class="page-title-content">
                                        <h3>Dashboard</h3>
                                        <p class="mb-2">Welcome <?= $username ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="breadcrumbs">
                                        <a href="<?php echo  $domain ?>/dashboard/ ">Dashboard</a>
                                        <span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wallet-tab">
                    <div class="row g-0">
                        <div class="col-12">
                            <div class="nav d-flex flex-wrap justify-content-between gap-1">

                                <div class="col-12 col-md-4">
                                    <div class="wallet-nav">
                                        <div class="wallet-nav-icon">
                                            <span><i class="fi fi-rr-bank"></i></span>
                                        </div>
                                        <div class="wallet-nav-text">
                                            <h3>Balance</h3>
                                            <p>₦<?= money($balance) ?></p>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="wallet-nav">
                                        <div class="wallet-nav-icon">
                                            <span><i class="fi fi-rr-bank"></i></span>
                                        </div>
                                        <div class="wallet-nav-text">
                                            <h3>Total Deposit</h3>
                                            <p><?= $total_deposits ?></p>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3">
                                    <div class="wallet-nav">
                                        <div class="wallet-nav-icon">
                                            <span><i class="fi fi-rr-bank"></i></span>
                                        </div>
                                        <div class="wallet-nav-text">
                                            <h3>Total Place Order</h3>
                                            <p><?= $total_orders ?></p>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="wallet-nav">
                                        <div class="wallet-nav-icon">
                                            <span><i class="fi fi-rr-calendar"></i></span>
                                        </div>
                                        <div class="wallet-nav-text">
                                            <h3>Streak Days</h3>
                                            <p><?= $current_streak ?> Day<?= $current_streak > 1 ? 's' : '' ?></p>
                                            <small>High: <?= $highest_streak ?> Day<?= $highest_streak > 1 ? 's' : '' ?></small>
                                        </div>
                                    </div>
                                </div>



                            </div>


                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Transaction History</h4>
                            </div>
                            <div class="card-body">
                                <div class="transaction-table">
                                    <div class="table-responsive">
                                        <?php
                                        $sql = "
    SELECT id,
           'Deposit' AS type,
           amount AS amount,
           created_at AS date,
           status
    FROM deposit
    WHERE user_id = ?

    UNION ALL

    SELECT id,
           'Order' AS type,
           -naria_price AS amount,
           created_at AS date,
           status
    FROM user_orders
    WHERE user = ?

    ORDER BY date DESC
    LIMIT 10
";

                                        $stmt = mysqli_prepare($connection, $sql);
                                        mysqli_stmt_bind_param($stmt, "ii", $id, $id);
                                        mysqli_stmt_execute($stmt);
                                        $result = mysqli_stmt_get_result($stmt);

                                        ?>

                                        <table class="table mb-0 table-responsive-sm">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Transaction Type</th>
                                                    <th>Transaction Amount</th>
                                                    <th>Transaction Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (mysqli_num_rows($result) > 0) {
                                                    $count = 0;
                                                    while ($transaction = mysqli_fetch_assoc($result)) {
                                                        $count++;
                                                        // Format amount with ₦ sign
                                                        $amount = number_format($transaction['amount'], 2);
                                                        if ($transaction['amount'] < 0) {
                                                            $amount = "-₦" . number_format(abs($transaction['amount']), 2);
                                                        } else {
                                                            $amount = "₦" . $amount;
                                                        }
                                                ?>
                                                        <tr>
                                                            <td><?= $count ?></td>
                                                            <td><?= htmlspecialchars($transaction['type']) ?></td>
                                                            <td><?= $amount ?></td>
                                                            <td><?= date("Y-m-d", strtotime($transaction['date'])) ?></td>
                                                            <td>
                                                                <span class="badge text-white 
                                                                    <?= $transaction['status'] == 'pending' ? 'bg-warning' : ($transaction['status'] == 'completed' || $transaction['status'] == 'approved' ? 'bg-success' : 'bg-danger') ?>">
                                                                    <?= ucfirst($transaction['status']) ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo '<tr><td colspan="5" class="text-center text-danger">No transaction history found.</td></tr>';
                                                }
                                                ?>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <?php include('../../include/support.php') ?>


    <script src="https://cdn.jsdelivr.net/npm/jquery@4.0.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap.min.js@3.3.5/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $domain ?>client/vendor/jquery/jquery.min.js"></script>

    <script src="<?php echo $domain ?>client/js/scripts.js"></script>
</body>

</html>