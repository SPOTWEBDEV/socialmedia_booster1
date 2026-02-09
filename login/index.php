<?php include('../server/connection.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $login    = trim($_POST['login']);   // username or email
    $password = $_POST['password'];

    if (empty($login) || empty($password)) {
        $error = "All fields are required.";
    } else {

        // Fetch user by email OR username
        $stmt = $connection->prepare(
            "SELECT id,  email, password 
             FROM users 
             WHERE email = ? 
             LIMIT 1"
        );

        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {

                // Login success → set session
                $_SESSION['user_id']   = $user['id'];

                echo "<script>
                  setTimeout(()=>{
                    window.location.href = '../user/dashboard/'
                  },1000)
                </script>";
            } else {
                $error = "Invalid login details.";
            }
        } else {
            $error = "Invalid login details.";
        }

        $stmt->close();
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
    <title>Saasify - Startup & SaaS Landing Page HTML Template</title>
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



    <!--========== Mobile Menu Start ==============-->
    <div class="tj-offcanvas-area">
        <div class="tj-offcanvas-header d-flex align-items-center justify-content-between">
            <div class="logo-area text-center">
                <a href="index.html"><img src="../assets/images/logo/mobile-logo.png" alt="Logo" /></a>
            </div>
            <div class="offcanvas-icon">
                <a id="canva_close" href="#">
                    <i class="fa-light fa-xmark"></i>
                </a>
            </div>
        </div>
        <!-- Canvas Mobile Menu start -->
        <nav class="right_menu_togle mobile-navbar-menu d-lg-none" id="mobile-navbar-menu"></nav>
        <!-- Canvas Menu end -->
    </div>
    <!--========== Mobile Menu End ==============-->

    <?php include('../include/nav.php') ?>

    <!--========== Contact Section Start ==============-->
    <section class="tj-contact-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="tj-contact-content-one">
                        <div class="tj-sec-heading">
                            <span class="sub-title"> Welcome Back</span>
                            <h2 class="title">Login to <?php echo $sitename ?></h2>
                            <p class="desc">
                                Access your <?php echo $sitename ?> account to manage your social media boosts, track your orders, and grow your audience faster across all platforms.
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
                            <input class="input-fill" type="text" name="login" placeholder="Username or Email" required />
                        </div>

                        <div class="form-input">
                            <i class="flaticon-objective"></i>
                            <input class="input-fill" type="text" name="password" placeholder="Password" required />
                        </div>

                        <div class="tj-contact-button">
                            <button class="tj-primary-btn contact-btn" type="submit">
                                Login to Account
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