<?php

include('../server/connection.php');
include_once('../server/api/boosting.php');

$get = mysqli_query($connection, "SELECT  siteprice  FROM sitedetails  ORDER BY id LIMIT 1");
$data = mysqli_fetch_assoc($get);
$site_price = floatval($data['siteprice'] ?? 0);


?>

<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Meta tags -->
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Boostkore helps you grow your social media presence with fast, secure, and affordable boosting services for Instagram, Facebook, TikTok, YouTube, and more.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Site Title -->
    <title><?php echo $sitename ?></title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" href="<?php echo $domain ?>assets/images/logo/logo.png" />
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $domain ?>assets/images/logo/logo.png" />

    <!-- Bootstrap css -->
    <link rel="stylesheet" href="<?php echo $domain ?>assets/css/bootstrap.min.css" />
    <!-- Font css -->
    <link rel="stylesheet" href="<?php echo $domain ?>assets/css/font-awesome-pro.css" />
    <!-- Icons css -->
    <link rel="stylesheet" href="<?php echo $domain ?>assets/css/flaticon_saasify.css" />
    <!-- Animate css -->
    <link rel="stylesheet" href="<?php echo $domain ?>assets/css/animate.css" />
    <!-- Sall css -->
    <link rel="stylesheet" href="<?php echo $domain ?>assets/css/sal.css" />
    <!-- Odometer css -->
    <link rel="stylesheet" href="<?php echo $domain ?>assets/css/odometer.min.css" />
    <!-- Meanmenu css -->
    <link rel="stylesheet" href="<?php echo $domain ?>assets/css/meanmenu.css" />
    <!-- Swiper Slider css -->
    <link rel="stylesheet" href="<?php echo $domain ?>assets/css/swiper.min.css" />
    <!-- Magnific-popup css -->
    <link rel="stylesheet" href="<?php echo $domain ?>assets/css/magnific-popup.css" />
    <!-- Main css -->
    <link rel="stylesheet" href="<?php echo $domain ?>assets/css/main.css" />
    <!-- Responsive css -->
    <link rel="stylesheet" href="<?php echo $domain ?>assets/css/responsive.css" />
</head>

<body cz-shortcut-listen="true">
    <div id="tj-overlay-bg2" class="tj-overlay-canvas"></div>




    <!--========== Footer Section Start ==============-->
    <?php include('../include/nav.php') ?>
    <!--========== Footer Section End ==============-->



    <!--========== Price Section Start ==============-->
    <section class="tj-price-section" style="padding-top: 100px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tj-sec-heading text-center">
                        <span class="sub-title"> Boost Services</span>
                        <h2 class="title">Choose What You Want to Boost</h2>
                        <p class="desc">
                            Below is a list of all available services you can boost. Select a category,
                            review the pricing and limits, and place your order to start boosting instantly.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">

                <?php
                include_once('../server/api/boosting.php');
                $services = $api->services();
                ?>

                <div class="row justify-content-center" id="plansGrid">

                    <?php foreach ($services as $service):

                        $categoryLower = strtolower($service->category);
                        $status = ($service->cancel == 1) ? 'available' : 'unavailable';

                    ?>

                        <div
                            class="col-lg-4 col-md-6 plan-card"
                            data-name="<?= strtolower($service->name) ?>"
                            data-category="<?= $categoryLower ?>"
                            data-status="<?= $status ?>"
                            data-service='<?= json_encode($service) ?>'>

                            <div class="tj-price-item <?= $status === 'available' ? 'price-active' : '' ?>">

                                <div class="price-top-header">
                                    <div class="price-content">
                                        <h6 class="title"><?= htmlspecialchars($service->name) ?></h6>

                                        <div class="price">
                                            <?php

                                                    $thirdParty = (1000 / 1000) * $service->rate;
                                                    $siteFee = (1000 / 1000) * $site_price;
                                                    $total = $thirdParty + $siteFee;


                                                    echo  '$' . $total;

                                                    ?>
                                            <span class="month">Rate for 1,000</span>
                                        </div>
                                    </div>


                                </div>

                                <div class="price-list">
                                    <ul class="list-gap">
                                        <li><i class="flaticon-checkmark"></i> Category: <?= htmlspecialchars($service->category) ?></li>
                                        <li><i class="flaticon-checkmark"></i> Min Order: <?= $service->min ?></li>
                                        <li><i class="flaticon-checkmark"></i> Max Order: <?= number_format($service->max) ?></li>
                                        <li><i class="flaticon-checkmark"></i> Status: <?= ucfirst($status) ?></li>
                                    </ul>
                                </div>

                                <div class="tj-price-button text-center">
                                    <a href="<?php echo $domain ?>register/">
                                        <button
                                            class="tj-transparent-btn order-btn"
                                            <?= $status === 'unavailable' ? 'disabled' : '' ?>>
                                            <?= $status === 'available' ? 'Order Service' : 'Unavailable' ?>
                                        </button>
                                    </a>
                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>





            </div>
            <div class="price-shape pulse">
                <img src="<?php echo $domain ?>assets/images/shape/sec-shape2.svg" alt="Shape">
            </div>
        </div>
        <div class="price-overly">
            <img src="<?php echo $domain ?>assets/images/shape/overly-5.svg" alt="Shape">
        </div>
        <div class="price-shape1">
            <img src="<?php echo $domain ?>assets/images/shape/sec-shape1.svg" alt="Shape">
        </div>
        <div class="price-shape2">
            <img src="<?php echo $domain ?>assets/images/shape/sec-shape7.svg" alt="Shape">
        </div>
    </section>
    <!--========== Price Section End ==============-->



    <!--========== Footer Section Start ==============-->
    <?php include('../include/footer.php') ?>
    <!--========== Footer Section End ==============-->

    <!--========== Start scrollUp ==============-->
    <div class="saasify-scroll-top">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;"></path>
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
    <script src="<?php echo $domain ?>assets/js/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="<?php echo $domain ?>assets/js/bootstrap-bundle.js"></script>
    <!-- Meanmenu JS -->
    <script src="<?php echo $domain ?>assets/js/meanmenu.js"></script>
    <!-- Swiper.min js -->
    <script src="<?php echo $domain ?>assets/js/swiper.min.js"></script>
    <!-- Magnific-popup JS -->
    <script src="<?php echo $domain ?>assets/js/magnific-popup.js"></script>
    <!-- Appear js -->
    <script src="<?php echo $domain ?>assets/js/jquery.appear.min.js"></script>
    <!-- Odometer js -->
    <script src="<?php echo $domain ?>assets/js/odometer.min.js"></script>
    <!-- Sal js -->
    <script src="<?php echo $domain ?>assets/js/sal.js"></script>
    <!-- Imagesloaded-pkgd js -->
    <script src="<?php echo $domain ?>assets/js/imagesloaded-pkgd.js"></script>
    <!-- Main js -->
    <script src="<?php echo $domain ?>assets/js/main.js"></script>


    <div state="voice" class="placeholder-icon" id="tts-placeholder-icon" title="Click to show TTS button" style="background-image: url(&quot;chrome-extension://cpnomhnclohkhnikegipapofcjihldck/data/content_script/icons/voice.png&quot;);"><canvas width="36" height="36" class="loading-circle" id="text-to-speech-loader" style="display: none;"></canvas></div>
</body>



</html>