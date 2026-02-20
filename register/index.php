<?php
include('../server/connection.php');

$success = "";
$error = "";

$referral_code = trim($_GET['referral_code'] ?? '');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username  = trim($_POST['username']);
    $name     = trim($_POST['name']);
    $phone    = trim($_POST['phone']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];


    // Basic validation
    if (empty($name) || empty($phone) || empty($email) || empty($password) || empty($username)) {
        $error = "All fields are required.";
    } else {

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $check = $connection->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email already registered.";
        } else {

            // Find referrer ID if referral code is provided
            $referrer_id = null;
            if (!empty($referral_code)) {
                $stmt_ref = $connection->prepare("SELECT id FROM users WHERE referral_code = ?");
                $stmt_ref->bind_param("s", $referral_code);
                $stmt_ref->execute();
                $result_ref = $stmt_ref->get_result();
                if ($result_ref->num_rows > 0) {
                    $referrer_id = $result_ref->fetch_assoc()['id'];
                }
                $stmt_ref->close();
            }

            // Generate a unique referral code for the new user
            function generateReferralCode($length = 6)
            {
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                $code = '';
                for ($i = 0; $i < $length; $i++) {
                    $code .= $chars[random_int(0, strlen($chars) - 1)];
                }
                return $code;
            }
            $new_referral_code = generateReferralCode();

            // Insert new user
            $country  = 'NG';
            $currency = 'NGN';

            $stmt = $connection->prepare(
                "INSERT INTO users 
    (username , full_name, phone, email, password, referrer_id, referral_code, country, currency) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ? ,?)"
            );

            $stmt->bind_param(
                "sssssisss",
                $username,
                $name,
                $phone,
                $email,
                $hashedPassword,
                $referrer_id,
                $new_referral_code,
                $country,
                $currency
            );




            if ($stmt->execute()) {

                // Credit referral bonus if there is a referrer
                if ($referrer_id) {
                    // Fetch referral bonus from sitedetails table
                    $bonusQuery = $connection->query("SELECT refferalbonus FROM sitedetails LIMIT 1");
                    $bonus = 0;
                    if ($bonusQuery && $bonusQuery->num_rows > 0) {
                        $bonus = $bonusQuery->fetch_assoc()['refferalbonus'];
                    }

                    // Update referrer's referral earnings
                    $update = $connection->prepare("UPDATE users SET referral_earnings = referral_earnings + ? WHERE id = ?");
                    $update->bind_param("di", $bonus, $referrer_id);
                    $update->execute();
                    $update->close();

                    // Notify referrer
                    $refNotify = $connection->prepare("
                        INSERT INTO notifications (type, user_id, message) 
                        VALUES ('system', ?, ?)
                    ");

                    $refMessage = "You earned ₦$bonus referral bonus from a new signup.";
                    $refNotify->bind_param("is", $referrer_id, $refMessage);
                    $refNotify->execute();
                    $refNotify->close();
                }

                $newUserId = $stmt->insert_id;
                // Insert account created notification
                $notify = $connection->prepare("
                    INSERT INTO notifications (type, user_id, message) 
                    VALUES ('system', ?, ?)
                ");

                $message = "Welcome to $sitename! Your account was created successfully.";
                $notify->bind_param("is", $newUserId, $message);
                $notify->execute();
                $notify->close();

                $success = "Account created successfully!";
                echo "<script>
                  setTimeout(()=>{
                    window.location.href = '../login'
                  },2000)
                </script>";
            } else {
                $error = "Something went wrong. Please try again.";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>


<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Meta tags -->
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />

    <!-- Site Title -->
    <title><?php echo $sitename ?></title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" href="../assets/images/fav.svg" />
    <link rel="shortcut icon" type="image/x-icon" href="../assets/images/fav.svg" />

    <!-- Bootstrap css -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <!-- Font css -->
    <link rel="stylesheet" href="../assets/css/font-awesome-pro.css" />
    <!-- Icons css -->
    <link rel="stylesheet" href="../assets/css/flaticon_saasify.css" />
    <!-- Animate css -->
    <link rel="stylesheet" href="../assets/css/animate.css" />
    <!-- Sall css -->
    <link rel="stylesheet" href="../assets/css/sal.css" />
    <!-- Odometer css -->
    <link rel="stylesheet" href="../assets/css/odometer.min.css" />
    <!-- Meanmenu css -->
    <link rel="stylesheet" href="../assets/css/meanmenu.css" />
    <!-- Swiper Slider css -->
    <link rel="stylesheet" href="../assets/css/swiper.min.css" />
    <!-- Magnific-popup css -->
    <link rel="stylesheet" href="../assets/css/magnific-popup.css" />
    <!-- Main css -->
    <link rel="stylesheet" href="../assets/css/main.css" />
    <!-- Responsive css -->
    <link rel="stylesheet" href="../assets/css/responsive.css" />
</head>

<body>
    <div id="tj-overlay-bg2" class="tj-overlay-canvas"></div>




    <?php include('../include/nav.php') ?>

    <!--========== Contact Section Start ==============-->
    <section class="tj-contact-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="tj-contact-content-one">
                        <div class="tj-sec-heading">
                            <span class="sub-title"> Create Account</span>
                            <h2 class="title">Join <?php echo $sitename ?> Today</h2>
                            <p class="desc">
                                Create your free account on <?php echo $sitename ?> and start boosting your social media presence instantly. Reach more people, grow faster, and manage all your promotions from one powerful dashboard.
                            </p>
                        </div>
                        <div class="image-box hover-shape-border">
                            <img class="img-1" src="../assets/images/progress/contact-image.png" alt="Image" />
                            <div class="testimonial-item-shape">
                                <span class="border-shadow shadow-1"></span>
                                <span class="border-shadow shadow-2"></span>
                            </div>
                            <div class="box-shape pulse">
                                <img src="../assets/images/shape/sec-shape5.svg" alt="Shape" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <?php if (!empty($error)): ?>
                        <div style="background:red; margin-bottom:10px;color:white; padding:10px 20px">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div style="background:green; margin-bottom:10px;color:white; padding:10px 20px">
                            <?= $success ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="tj-contact-box">
                        <div class="form-input">
                            <i class="flaticon-user"></i>
                            <input class="input-fill" type="text" id="username" name="username" placeholder="Enter Username" required="" />
                        </div>
                        <div class="form-input">
                            <i class="flaticon-user"></i>
                            <input class="input-fill" type="text" id="name" name="name" placeholder="Enter Full Name" required="" />
                        </div>
                        <div class="form-input">
                            <i class="flaticon-telephone"></i>
                            <input class="input-fill" type="tel" id="phone" name="phone" placeholder="Enter Phone Number" required="" />
                        </div>
                        <div class="form-input">
                            <i class="flaticon-mail"></i>
                            <input class="input-fill" type="email" id="email" name="email" placeholder="Enter Email Address" required="" />
                        </div>
                        <div class="form-input">
                            <i class="flaticon-key"></i>


                            <input class="input-fill" type="password" id="password" name="password" placeholder="Enter Password" required />

                        </div>
                        <div style="margin-top:8px;">
                            <input type="checkbox" id="showPassword">
                            <label for="showPassword"> Show Password</label>
                        </div>

                        <div class="tj-contact-button">
                            <button class="tj-primary-btn contact-btn" type="submit" value="submit">
                                Create Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="contact-bg-shape">
            <img src="../assets/images/shape/sec-shape6.svg" alt="Shape" />
        </div>
        <div class="sec-overly-1">
            <img src="../assets/images/shape/overly-1.svg" alt="Shape" />
        </div>
        <div class="sec-overly-2">
            <img src="../assets/images/shape/overly-2.svg" alt="Shape" />
        </div>
        <div class="tj-circle-box3">
            <span class="circle-1"></span>
            <span class="circle-2"></span>
            <span class="circle-3"></span>
            <span class="circle-4"></span>
        </div>
    </section>

    <!--========== Contact Section End ==============-->



    <!--========== Footer Section Start ==============-->
    <?php include('../include/footer.php') ?>
    <!--========== Footer Section End ==============-->

    <!--========== Start scrollUp ==============-->
    <div class="saasify-scroll-top">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="
                        transition: stroke-dashoffset 10ms linear 0s;
                        stroke-dasharray: 307.919px, 307.919px;
                        stroke-dashoffset: 71.1186px;
                    "></path>
        </svg>
        <div class="saasify-scroll-top-icon">
            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em" viewBox="0 0 24 24" data-icon="mdi:arrow-up" class="iconify iconify--mdi">
                <path fill="currentColor" d="M13 20h-2V8l-5.5 5.5l-1.42-1.42L12 4.16l7.92 7.92l-1.42 1.42L13 8v12Z">
                </path>
            </svg>
        </div>
    </div>
    <!--========== End scrollUp ==============-->


    <script>
        document.getElementById("showPassword").addEventListener("change", function() {
            var password = document.getElementById("password");

            if (this.checked) {
                password.type = "text";
            } else {
                password.type = "password";
            }
        });
    </script>

    <!-- jquery JS -->
    <script src="../assets/js/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="../assets/js/bootstrap-bundle.js"></script>
    <!-- Meanmenu JS -->
    <script src="../assets/js/meanmenu.js"></script>
    <!-- Swiper.min js -->
    <script src="../assets/js/swiper.min.js"></script>
    <!-- Magnific-popup JS -->
    <script src="../assets/js/magnific-popup.js"></script>
    <!-- Appear js -->
    <script src="../assets/js/jquery.appear.min.js"></script>
    <!-- Odometer js -->
    <script src="../assets/js/odometer.min.js"></script>
    <!-- Sal js -->
    <script src="../assets/js/sal.js"></script>
    <!-- Imagesloaded-pkgd js -->
    <script src="../assets/js/imagesloaded-pkgd.js"></script>
    <!-- Main js -->
    <script src="../assets/js/main.js"></script>
</body>

</html>