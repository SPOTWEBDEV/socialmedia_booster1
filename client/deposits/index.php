<?php
include("../../server/connection.php");
include('../../server/auth/client.php');



$user_id = $id;
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deposit'])) {

    $type   = trim($_POST['type'] ?? "");
    $amount = trim($_POST['amount'] ?? "");



    if (empty($type)) {
        $errors[] = "Deposit type is required.";
    } elseif (empty($amount)) {
        $errors[] = "amount is required.";
    }

    if (empty($errors)) {

        $sql = "INSERT INTO deposits (user_id,  type_id, amount)
                VALUES (?, ?,  ?)";

        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "isd", $user_id, $type, $amount);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Deposit submitted successfully. Awaiting confirmation.";
        } else {
            $errors[] = "Deposit failed. Please try again.";
        }

        mysqli_stmt_close($stmt);
    }
}






?>


<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | Deposit </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $domain ?>/images/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>client/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>client/vendor/toastr/toastr.min.css">
</head>

<body class="dashboard">

    <div id="main-wrapper">
        <?php include("../include/header.php") ?>
        <!-- nav -->
        

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
                                        <h3>Deposit</h3>
                                        <p class="mb-2">Welcome To <?= $sitename ?> Management</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="./history/"><button class="btn btn-primary mr-2">View Deposit History</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12 col-xl-12">

                        <div class="row">
                            <div class="row g-4">

                                <form id="depositForm">
                                <!-- SUBMIT AMOUNT -->
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Deposit Form</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Enter Amount </label>
                                                <input name="amount" id="depositAmount" type="number" class="form-control" placeholder="Enter amount">
                                            </div>
                                            <button type="submit" name="deposit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>

                                </form>


                                <script>
                                    let url = "<?php echo $domain ?>" + 'server/api/etegram-init.php'
                                    document.getElementById("depositForm").addEventListener("submit", async function(e) {
                                        e.preventDefault();

                                        const amount = parseFloat(document.getElementById("depositAmount").value);
                                        if (amount <= 0) {
                                            alert("Enter a valid amount.");
                                            return;
                                        }

                                        const response = await fetch(url, {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/json"
                                            },
                                            body: JSON.stringify({
                                                amount,
                                                user_id: "<?php echo $id ?>"
                                            })
                                        });

                                        console.log(response)

                                        const data = await response.json();

                                        console.log(data)

                                        if (data.status && data.authorization_url) {
                                            window.location.href = data.authorization_url;
                                        } else {
                                            alert("Error initializing payment. Try again.");
                                        }
                                    });
                                </script>





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
    <script src="<?php echo $domain ?>/js/scripts.js"></script>
</body>

</html>