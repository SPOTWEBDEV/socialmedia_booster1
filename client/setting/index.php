<?php
include("../../server/connection.php");
include('../../server/auth/client.php');


$user_id = $_SESSION['user_id'];
$message = "";

/* =========================
   API KEY GENERATOR
========================= */
function generateApiKey($type = 'live')
{
    return 'sk_' . $type . '_' . bin2hex(random_bytes(16));
}

/* =========================
   HANDLE FORMS
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* CHANGE PASSWORD */
    if (isset($_POST['change_password'])) {

        $old = $_POST['old_password'];
        $new = $_POST['new_password'];

        $stmt = mysqli_prepare($connection, "SELECT password FROM users WHERE id=?");
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($res);

        if (!$row || !password_verify($old, $row['password'])) {
            $message = "Old password incorrect";
        } else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $up = mysqli_prepare($connection, "UPDATE users SET password=? WHERE id=?");
            mysqli_stmt_bind_param($up, "si", $hash, $user_id);
            mysqli_stmt_execute($up);
            $message = "Password updated successfully";
        }
    }

    /* API KEYS */
    if (isset($_POST['generate_live'])) {
        $key = generateApiKey('live');
        $stmt = mysqli_prepare($connection, "UPDATE users SET api_key_live=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "si", $key, $user_id);
        mysqli_stmt_execute($stmt);
        $message = "Live API key generated";
    }

    if (isset($_POST['generate_test'])) {
        $key = generateApiKey('test');
        $stmt = mysqli_prepare($connection, "UPDATE users SET api_key_test=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "si", $key, $user_id);
        mysqli_stmt_execute($stmt);
        $message = "Test API key generated";
    }

    /* NOTIFICATIONS */
    if (isset($_POST['save_notifications'])) {
        $email = isset($_POST['email_notifications']) ? 1 : 0;
        $push  = isset($_POST['push_notifications']) ? 1 : 0;

        $stmt = mysqli_prepare($connection, "
            UPDATE users SET email_notifications=?, push_notifications=? WHERE id=?
        ");
        mysqli_stmt_bind_param($stmt, "iii", $email, $push, $user_id);
        mysqli_stmt_execute($stmt);
        $message = "Notification settings saved";
    }

    /* PREFERENCES */
    if (isset($_POST['save_preferences'])) {
        $country  = $_POST['country'];
        $currency = $_POST['currency'];

        $stmt = mysqli_prepare($connection, "
            UPDATE users SET country=?, currency=? WHERE id=?
        ");
        mysqli_stmt_bind_param($stmt, "ssi", $country, $currency, $user_id);
        mysqli_stmt_execute($stmt);
        $message = "Preferences updated";
    }

    /* CHANGE EMAIL */
    if (isset($_POST['change_email'])) {

        $new_email = trim($_POST['new_email']);

        if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email address";
        } else {

            // Check if email already exists
            $check = mysqli_prepare($connection, "SELECT id FROM users WHERE email=? AND id!=?");
            mysqli_stmt_bind_param($check, "si", $new_email, $user_id);
            mysqli_stmt_execute($check);
            $result = mysqli_stmt_get_result($check);

            if (mysqli_num_rows($result) > 0) {
                $message = "Email already in use";
            } else {

                $stmt = mysqli_prepare($connection, "UPDATE users SET email=? WHERE id=?");
                mysqli_stmt_bind_param($stmt, "si", $new_email, $user_id);
                mysqli_stmt_execute($stmt);

                $message = "Email updated successfully";
            }
        }
    }
    /* SAVE TIMEZONE */
    if (isset($_POST['save_timezone'])) {

        $timezone = $_POST['timezone'];

        // Optional: Validate timezone
        if (!in_array($timezone, timezone_identifiers_list())) {
            $message = "Invalid timezone selected";
        } else {

            $stmt = mysqli_prepare(
                $connection,
                "UPDATE users SET timezone=? WHERE id=?"
            );

            mysqli_stmt_bind_param($stmt, "si", $timezone, $user_id);
            mysqli_stmt_execute($stmt);

            $message = "Timezone updated successfully";
        }
    }
}

/* FETCH USER */
$user = mysqli_fetch_assoc(mysqli_query($connection, "
    SELECT 
        api_key_live,
        api_key_test,
        email_notifications,
        push_notifications,
        country,
        currency
    FROM users WHERE id='$user_id'
"));
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
         <?php include("../include/header.php") ?>


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
                                        <h3>Settings</h3>
                                        <p class="mb-2">Welcome <?= $sitename ?> Finance Management</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12 col-xl-12">
                        <div class="settings-menu">
                            <a href="settings.html">Account</a>

                        </div>
                        <div class="row">



                            <div class="row">
                                <?php if (!empty($message)): ?>
                                    <div style="background:green; margin-bottom:10px;color:white; padding:10px 20px">
                                        <?= $message ?>
                                    </div>
                                <?php endif; ?>

                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Change Email</h4>
                                        </div>
                                        <div class="card-body">
                                            <form method="post">
                                                <input type="hidden" name="change_email">

                                                <div class="mb-3">
                                                    <label class="form-label">Current Email</label>
                                                    <input type="email" class="form-control"
                                                        value="<?= $email ?>" readonly>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">New Email</label>
                                                    <input type="email" name="new_email"
                                                        class="form-control" required>
                                                </div>

                                                <button class="btn btn-primary">Update Email</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Password Setting</h4>
                                        </div>
                                        <div class="card-body">
                                            <form method="post">
                                                <div class="row g-3">

                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Old Password</label>
                                                        <input type="password" name="old_password" class="form-control" required>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">New Password</label>
                                                        <input type="password" name="new_password" class="form-control" required>
                                                    </div>

                                                    <div class="col-12 mb-3">
                                                        <button class="btn btn-primary">Change</button>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Timezone Settings</h4>
                                        </div>
                                        <div class="card-body">

                                            <form method="post">
                                                <input type="hidden" name="save_timezone">

                                                <div class="mb-3">
                                                    <label class="form-label">Select Timezone</label>

                                                    <select name="timezone" class="form-control" required>
                                                        <?php
                                                        $timezones = timezone_identifiers_list();
                                                        foreach ($timezones as $tz):
                                                        ?>
                                                            <option value="<?= $tz ?>"
                                                                <?= ($timezone == $tz) ? 'selected' : '' ?>>
                                                                <?= $tz ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <button class="btn btn-primary">Save Timezone</button>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">API Keys</h4>
                                        </div>
                                        <div class="card-body">

                                            <form method="post">
                                                <label>Live API Key</label>
                                                <div class="d-flex mb-3">
                                                    <input class="form-control me-2" readonly
                                                        value="<?= $user['api_key_live'] ?? 'Not generated' ?>">
                                                    <button name="generate_live" class="btn btn-primary">Generate</button>
                                                </div>

                                                <label>Test API Key</label>
                                                <div class="d-flex">
                                                    <input class="form-control me-2" readonly
                                                        value="<?= $user['api_key_test'] ?? 'Not generated' ?>">
                                                    <button name="generate_test" class="btn btn-dark">Generate</button>
                                                </div>
                                            </form>

                                            <p class="mt-3">View Documentation <a href="<?= $domain ?>/docs">here</a></p>

                                        </div>
                                    </div>
                                </div>


                                <div class="col-xxl-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Notifications</h4>
                                        </div>
                                        <div class="card-body">
                                            <form method="post">
                                                <input type="hidden" name="save_notifications">

                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="email_notifications"
                                                        <?= $user['email_notifications'] ? 'checked' : '' ?>>
                                                    <label>Email Notifications</label>
                                                </div>

                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="push_notifications"
                                                        <?= $user['push_notifications'] ? 'checked' : '' ?>>
                                                    <label>Push Notifications</label>
                                                </div>

                                                <button class="btn btn-primary">Save</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>


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