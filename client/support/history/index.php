<?php
include("../../../server/connection.php");
include('../../../server/auth/client.php');

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $sitename ?> | Support History</title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/favicon.png">
    <link rel="stylesheet" href="<?php echo $domain ?>client/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>client/vendor/toastr/toastr.min.css">
</head>

<body class="dashboard">

    <div id="main-wrapper">

        <?php include("../../include/header.php") ?>
        <?php include("../../include/sidenav.php") ?>

        <div class="content-body">
            <div class="container">

                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-4">
                                    <div class="page-title-content">
                                        <h3>Support History</h3>
                                        <p class="mb-2">View all your support messages</p>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <a href="../">
                                        <button class="btn btn-primary">New Message</button>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <?php

                    $limit = 10;
                    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;

                    /* COUNT TOTAL RECORDS */
                    $count_sql = "SELECT COUNT(*) AS total FROM support_messages WHERE user_id = ?";
                    $count_stmt = mysqli_prepare($connection, $count_sql);
                    mysqli_stmt_bind_param($count_stmt, "i", $user_id);
                    mysqli_stmt_execute($count_stmt);
                    $count_result = mysqli_stmt_get_result($count_stmt);
                    $total_row = mysqli_fetch_assoc($count_result);
                    $total_records = $total_row['total'];
                    $total_pages = ceil($total_records / $limit);
                    mysqli_stmt_close($count_stmt);


                    /* FETCH SUPPORT MESSAGES */
                    $sql = "
    SELECT id, type, message, status, created_at
    FROM support_messages
    WHERE user_id = ?
    ORDER BY id DESC
    LIMIT ? OFFSET ?
";

                    $stmt = mysqli_prepare($connection, $sql);
                    mysqli_stmt_bind_param($stmt, "iii", $user_id, $limit, $offset);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    ?>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Your Messages</h4>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>TYPE</th>
                                                <th>MESSAGE</th>
                                                <th>DATE</th>
                                                <th>STATUS</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php if (mysqli_num_rows($result) > 0): $sn = $offset + 1; ?>
                                                <?php while ($row = mysqli_fetch_assoc($result)): ?>

                                                    <tr>
                                                        <td><?= $sn++ ?></td>

                                                        <td><?= ucfirst(htmlspecialchars($row['type'])) ?></td>

                                                        <td style="max-width:250px;">
                                                            <?= htmlspecialchars(substr($row['message'], 0, 80)) ?>...
                                                        </td>

                                                        <td><?= date("Y-m-d", strtotime($row['created_at'])) ?></td>

                                                        <td>
                                                            <span class="badge
<?php
                                                    if ($row['status'] === 'resolved') echo 'bg-success';
                                                    elseif ($row['status'] === 'pending') echo 'bg-warning';
                                                    else echo 'bg-danger';
?>">
                                                                <?= ucfirst($row['status']) ?>
                                                            </span>
                                                        </td>

                                                    </tr>

                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">No support messages found</td>
                                                </tr>
                                            <?php endif; ?>

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
        <script src="<?php echo $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo $domain ?>client/js/scripts.js"></script>

</body>

</html>