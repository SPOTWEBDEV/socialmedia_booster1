<?php
include('../../../server/connection.php');
include('../../../server/auth/client.php');
include('../../../server/api/boosting.php'); // $api instance

$user_id = $id;



/* ------------------------------------
   FETCH USER ORDERS
------------------------------------- */
$query = "
    SELECT id, order_name, order_category, naria_price,
           social_url, quanity, order_id, status, created_at
    FROM user_orders
    WHERE user = ?
    ORDER BY created_at DESC
";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | My Order </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>client/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>client/vendor/toastr/toastr.min.css">
</head>

<body class="dashboard">

    <div id="main-wrapper">
        <!-- nav -->
        <?php include("../../include/header.php") ?>

        <!-- side nav -->
        <?php include("../../include/sidenav.php") ?>

        <div class="content-body">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-4">
                                    <div class="page-title-content">
                                        <h3>My Order History</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="../"><button class="btn btn-primary mr-2">Placed An Order</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">




                    <div class="hidden md:block bg-white">
                        <table class="table  ">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ORDER NAME</th>
                                    <th>CATEGORY</th>
                                    <th>AMOUNT</th>
                                    <th>DATE</th>
                                    <th>StART COUNT</th>
                                    <th>REMAINS</th>
                                    <th>API STATUS</th>
                                    
                                </tr>
                            </thead>
                            <tbody>

                                <?php if (mysqli_num_rows($result) > 0): $sn = 1 ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?= $sn++ ?></td>
                                            <td><?= htmlspecialchars($row['order_name']) ?></td>
                                            <td><?= htmlspecialchars($row['order_category']) ?></td>
                                            <td>₦<?= number_format($row['naria_price'], 2) ?></td>
                                            <td><?= date("Y-m-d", strtotime($row['created_at'])) ?></td>
                                            <?php

                                              $order_id = $row['order_id'];

                                              $services = $api->status($order_id);



                                            ?>

                                            <td><?php   echo ($services->start_count != '' || $services->start_count != null)? $services->start_count : '0' ?></td>
                                            <td><?php   echo $services->remains ?? '-' ?></td>



                                            <td>
                                                <span class="badge
                                                    <?php
                                                    if ($services->status === 'Completed') echo 'bg-success';
                                                    elseif ($services->status === 'Processing' || $services->status === 'Pending') echo 'bg-danger';
                                                    else echo 'bg-warning';
                                                    ?>">
                                                    <?= ucfirst($services->status) ?>
                                                </span>
                                            </td>
                                            
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No Order history found</td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>







                </div>





            </div>
        </div>



    </div>
    <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!--  -->
    <!--  -->
    <script src="<?php echo $domain ?>/js/scripts.js"></script>
</body>

</html>