<?php
include("../../server/connection.php");
include('../../server/auth/client.php');

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $type = $_POST['type'];
    $msg  = trim($_POST['message']);

    if (empty($msg)) {
        $message = "Message cannot be empty";
    } else {

        $stmt = mysqli_prepare(
            $connection,
            "INSERT INTO support_messages (user_id, type, message) VALUES (?, ?, ?)"
        );

        mysqli_stmt_bind_param($stmt, "iss", $user_id, $type, $msg);
        mysqli_stmt_execute($stmt);

        $message = "Support message sent successfully";
    }
}

?>


<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | setting</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>client/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>client/vendor/toastr/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body class="dashboard">

    <div id="main-wrapper">
        <!-- nav -->


        <!-- sidnav -->
        <?php include("../include/sidenav.php") ?>

        <div class="content-body">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title">
                            <div class="row align-items-center justify-content-between">
                                <div class="col-xl-4">
                                    <div class="page-title-content">
                                        <h3>Support </h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Support Page</p>
                                    </div>
                                </div>
                                 <div class="col-auto">
                                    <a href="./history/">
                                        <button class="btn btn-primary">View History</button>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        
                      
                            <?php if (!empty($message)): ?>
                                <div style="background:green; margin-bottom:10px;color:white; padding:10px 20px">
                                    <?= $message ?>
                                </div>
                            <?php endif; ?>


                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Send Message</h4>
                                    </div>

                                    <div class="card-body">

                                        <?php if ($message): ?>
                                            <div class="alert alert-info"><?= $message ?></div>
                                        <?php endif; ?>

                                        <form method="post">

                                            <div class="mb-3">
                                                <label class="form-label">Message Type</label>
                                                <select name="type" class="form-control" required>
                                                    <option value="">Select Type</option>
                                                    <option value="tips">Tips</option>
                                                    <option value="promo">Promo</option>
                                                    <option value="update">Update</option>
                                                    <option value="complain">Complain</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Your Message</label>
                                                <textarea name="message" class="form-control" rows="5" required></textarea>
                                            </div>

                                            <button class="btn btn-primary">Send Message</button>

                                        </form>
                                    </div>
                                </div>
                            




                        

                    </div>
                </div>
            </div>
        </div>


    </div>
    <script src="<?php echo $domain ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!--  -->
    <!--  -->
    <script src="<?php echo $domain ?>client/js/scripts.js"></script>
</body>

</html>