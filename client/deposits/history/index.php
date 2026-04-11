<?php
include("../../../server/connection.php");
include('../../../server/auth/client.php');



$user_id = $_SESSION['user_id'];
?>


<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | Deposit-History </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" href="<?php echo $domain ?>assets/images/logo/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>client/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>client/vendor/toastr/toastr.min.css">
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
                    <?php

                    // COUNT TOTAL RECORDS (optional - remove if not needed)
                    $count_sql = "SELECT COUNT(*) AS total FROM deposit WHERE user_id = ?";
                    $count_stmt = mysqli_prepare($connection, $count_sql);
                    mysqli_stmt_bind_param($count_stmt, "i", $user_id);
                    mysqli_stmt_execute($count_stmt);
                    $count_result = mysqli_stmt_get_result($count_stmt);
                    $total_row = mysqli_fetch_assoc($count_result);
                    $total_records = $total_row['total'];
                    mysqli_stmt_close($count_stmt);


                    // FETCH DEPOSIT HISTORY (no LIMIT, no OFFSET)
                    $sql = "
    SELECT 
        id,
        user_id,
        reference,
        access_code,
        amount,
        status,
        created_at,
        currency,
        country
    FROM deposit
    WHERE user_id = ?
    ORDER BY id DESC
";

                    $stmt = mysqli_prepare($connection, $sql);
                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>REFERENCE</th>
                                <th>AMOUNT</th>
                                <th>DATE</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if (mysqli_num_rows($result) > 0): $sn = 1; ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $sn++ ?></td>
                                        <td><?= htmlspecialchars($row['reference']) ?></td>
                                        <td><?= $row['amount'] ? number_format($row['amount'], 2) : '0.00' ?> <?= ($row['currency'] != NULL) ? $row['currency'] : 'NAIRA' ?></td>
                                        <td><?= date("Y-m-d H:i:s", strtotime($row['created_at'])) ?></td>
                                        <td>
                                            <span class="badge
                            <?php
                                    if ($row['status'] === 'approved') echo 'bg-success';
                                    elseif ($row['status'] === 'pending') echo 'bg-danger';
                                    else echo 'bg-warning';
                            ?>">
                                                <?= ucfirst($row['status']) ?>
                                            </span>
                                        </td>
                                        <!-- <td>
                                            <?php if ($row['status'] === 'pending' || $row['status'] === 'declined'): ?>
                                                <a href="<?php echo $domain ?>client/deposits/status/?access-code=<?= $row['access_code'] ?>" class="btn btn-sm btn-primary">Verify</a>
                                            <?php endif; ?>
                                               
                                        </td> -->
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No deposit history found</td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>


                    <script src="<?php echo $domain ?>client/vendor/jquery/jquery.min.js"></script>
                    <script src="<?php echo $domain ?>client/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
                    <!--  -->
                    <!--  -->
                    <script src="<?php echo $domain ?>client/js/scripts.js"></script>
</body>

</html>