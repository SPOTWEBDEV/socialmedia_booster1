<?php

include('./server/connection.php');


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
    <link rel="apple-touch-icon" href="assets/images/logo/logo.png" />
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/logo/logo.png" />

    <!-- Bootstrap css -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <!-- Font css -->
    <link rel="stylesheet" href="assets/css/font-awesome-pro.css" />
    <!-- Icons css -->
    <link rel="stylesheet" href="assets/css/flaticon_saasify.css" />
    <!-- Animate css -->
    <link rel="stylesheet" href="assets/css/animate.css" />
    <!-- Sall css -->
    <link rel="stylesheet" href="assets/css/sal.css" />
    <!-- Odometer css -->
    <link rel="stylesheet" href="assets/css/odometer.min.css" />
    <!-- Meanmenu css -->
    <link rel="stylesheet" href="assets/css/meanmenu.css" />
    <!-- Swiper Slider css -->
    <link rel="stylesheet" href="assets/css/swiper.min.css" />
    <!-- Magnific-popup css -->
    <link rel="stylesheet" href="assets/css/magnific-popup.css" />
    <!-- Main css -->
    <link rel="stylesheet" href="assets/css/main.css" />
    <!-- Responsive css -->
    <link rel="stylesheet" href="assets/css/responsive.css" />
</head>

<body>
    <div id="tj-overlay-bg2" class="tj-overlay-canvas"></div>

    

   
    

    <?php include('./include/nav.php') ?>

    <!--========== Header Section End ==============-->

    <!--========== Slider Section Start ==============-->
    <section id="home" class="tj-slider-section" data-bg-image="assets/images/banner/bg-group5.svg">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="slider-content-area">
                        <div class="tj-sec-heading">
                            <h1 class="title">
                                Boost Your Social Media Growth with <span class="active-color"><?php echo $sitename ?></span>
                            </h1>
                            <p class="desc">
                                <?php echo $sitename ?> helps you grow your social media presence faster. Increase followers, likes, views, and engagement across all major platforms using safe and reliable promotion tools.
                            </p>
                            <div class="tj-slider-button">
                                <a href="https://www.youtube.com/@SPOTWEBCOM-nr2gs"><button class="tj-primary-btn" type="submit" value="submit">Watch Vidoes</button></a>
                                <a href="<?php echo $domain ?>services/"><button class="tj-primary-btn" type="submit" value="submit">Our Serivces</button></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="slider-box">
                        <span class="active-text bg-white">
                            <i class="fa-light fa-check"></i> <?php echo $sitename ?> is one of the best in boosting social media account
                        </span>
                        <div class="slider-area">
                            <div class="swiper tj-banner-slider">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <div class="slider-item">
                                            <img src="assets/images/banner/slider-1.png" alt="Image" />
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="slider-item">
                                            <img src="assets/images/banner/slider-1.png" alt="Image" />
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="slider-item">
                                            <img src="assets/images/banner/slider-1.png" alt="Image" />
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                        <div class="slider-shape shake-y">
                            <img src="assets/images/shape/sec-shape14.svg" alt="Shape" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tj-circle-box-one">
            <span class="circle_1"></span>
            <span class="circle_2"></span>
            <span class="circle_3"></span>
            <span class="circle_4"></span>
            <span class="circle_5"></span>
            <span class="circle_6"></span>
            <span class="circle_7"></span>
            <span class="circle_8"></span>
            <span class="circle_9"></span>
        </div>
        <div class="tj-circle-box-one tj-circle-box-one-1">
            <span class="circle_1"></span>
            <span class="circle_2"></span>
            <span class="circle_3"></span>
            <span class="circle_4"></span>
            <span class="circle_5"></span>
            <span class="circle_6"></span>
            <span class="circle_7"></span>
            <span class="circle_8"></span>
            <span class="circle_9"></span>
        </div>
    </section>
    <!--========== Slider Section End ==============-->

    <!--========== Counter Section Start ==============-->
    <section class="tj-counter-section-two">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="counter-content-box-two">
                        <div class="counter-item">
                            <div class="counter-icon">
                                <img src="assets/images/icon/shape-border.png" alt="Border" />
                                <i class="flaticon-task-planning"></i>
                            </div>
                            <div class="counter-number">
                                <div class="tj-count"><span class="odometer" data-count="100">0</span>k</div>
                                <span class="sub-title"> Campaigns Completed</span>
                            </div>
                        </div>
                        <div class="counter-item">
                            <div class="counter-icon">
                                <img src="assets/images/icon/shape-border.png" alt="Border" />
                                <i class="flaticon-checkmark"></i>
                            </div>
                            <div class="counter-number">
                                <div class="tj-count"><span class="odometer" data-count="55">0</span>%</div>
                                <span class="sub-title"> Engagement Increase</span>
                            </div>
                        </div>
                        <div class="counter-item">
                            <div class="counter-icon">
                                <img src="assets/images/icon/shape-border.png" alt="Border" />
                                <i class="flaticon-world"></i>
                            </div>
                            <div class="counter-number">
                                <div class="tj-count"><span class="odometer" data-count="328">0</span>k</div>
                                <span class="sub-title"> Creators & Brands</span>
                            </div>
                        </div>
                        <div class="counter-item">
                            <div class="counter-icon">
                                <img src="assets/images/icon/shape-border.png" alt="Border" />
                                <i class="flaticon-customer-satisfaction"></i>
                            </div>
                            <div class="counter-number">
                                <div class="tj-count"><span class="odometer" data-count="100">0</span>%</div>
                                <span class="sub-title"> Secure & Reliable</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--========== Counter Section End ==============-->


    <!--========== Feature Section Start ==============-->
    <section id="features" class="feature-section-two">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="feature-content-box">
                        <div class="tj-sec-heading">
                            <h2 class="title">
                                Boost all your social accounts in <span class="active-color"> one place</span>
                            </h2>
                            <p class="desc">
                                <?php echo $sitename ?> gives you everything you need to grow faster on social media. Manage campaigns, track engagement, and boost your visibility across multiple platforms from a single dashboard.
                            </p>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="feature-item-two hover-shape-border">
                                    <div class="feature-icon">
                                        <i class="flaticon-objective"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h5 class="title">
                                            <a class="title-link" href="#"> Targeted Growth</a>
                                        </h5>
                                        <p class="desc">
                                            Reach the right audience with targeted boosts designed to increase real engagement on your social media profiles.
                                        </p>
                                    </div>
                                    <div class="feature-item-shape">
                                        <span class="border-shadow shadow-1"></span>
                                        <span class="border-shadow shadow-2"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="feature-item-two margin_top hover-shape-border">
                                    <div class="feature-icon">
                                        <i class="flaticon-time-management"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h5 class="title">
                                            <a class="title-link" href="#"> Fast Delivery</a>
                                        </h5>
                                        <p class="desc">
                                            Start seeing results quickly with automated systems that deliver likes, followers, and views without delays.
                                        </p>
                                    </div>
                                    <div class="feature-item-shape">
                                        <span class="border-shadow shadow-1"></span>
                                        <span class="border-shadow shadow-2"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="feature-item-two hover-shape-border">
                                    <div class="feature-icon">
                                        <i class="flaticon-skills"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h5 class="title"><a class="title-link" href="#"> Real Engagement</a></h5>
                                        <p class="desc">
                                            Boost your credibility with high-quality engagement that helps your content gain more reach and visibility.
                                        </p>
                                    </div>
                                    <div class="feature-item-shape">
                                        <span class="border-shadow shadow-1"></span>
                                        <span class="border-shadow shadow-2"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="feature-item-two margin_top hover-shape-border">
                                    <div class="feature-icon">
                                        <i class="flaticon-cooperation"></i>
                                    </div>
                                    <div class="feature-content">
                                        <h5 class="title">
                                            <a class="title-link" href="#"> Multiple Platforms</a>
                                        </h5>
                                        <p class="desc">
                                            Grow on Instagram, TikTok, YouTube, Facebook, and more — all supported in one powerful boosting platform.
                                        </p>
                                    </div>
                                    <div class="feature-item-shape">
                                        <span class="border-shadow shadow-1"></span>
                                        <span class="border-shadow shadow-2"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <img class="image-1 shake-y" src="assets/images/feature/team1.png" alt="Image" />
                    <!-- <div class="feature-geroup-image">
                        
                        <img class="image-1 shake-y" src="assets/images/feature/team1.png" alt="Image" />
                        
                    </div> -->
                </div>
            </div>
        </div>
        <div class="feature-shape">
            <img src="assets/images/shape/overly-11.svg" alt="Shape" />
        </div>
        <div class="feature-shape1 shake-y">
            <img src="assets/images/shape/sec-shape11.svg" alt="Shape" />
        </div>
    </section>
    <!--========== Feature Section End ==============-->

    <!--========== Solution Section Start ==============-->
    <section id="about" class="tj-solution-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="tj-solution-content-one">
                        <div class="tj-sec-heading">
                            <h2 class="title">One platform, endless <span class="active-color"> Growth</span></h2>
                            <p class="desc">
                                <?php echo $sitename ?> provides powerful tools to boost your social media accounts safely and efficiently. From followers to views, everything is designed to help you grow faster and stand out online.
                            </p>
                            <div class="check-list">
                                <ul class="list-gap">
                                    <li>
                                        <i class="flaticon-checkmark"></i> Boost followers, likes, views, and comments instantly.
                                    </li>
                                    <li>
                                        <i class="flaticon-checkmark"></i> Secure system with real-time campaign tracking.
                                    </li>
                                </ul>
                            </div>
                            <div class="tj-solution-button">
                                <a class="tj-primary-btn" href="<?php  echo $domain ?>register/"> Start Boosting Free</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="solution-group-image">
                        <img class="image-1 shake-y" src="assets/images/feature/solution-1.png" alt="Image" />
                       
                       
                      
                        <img class="image-5 pulse" src="assets/images/shape/sec-shape12.svg" alt="Image" />
                    </div>
                </div>
            </div>
        </div>
        <div class="tj-circle-box-one-2">
            <span class="circle_1"></span>
            <span class="circle_2"></span>
            <span class="circle_3"></span>
            <span class="circle_4"></span>
            <span class="circle_5"></span>
            <span class="circle_6"></span>
            <span class="circle_7"></span>
            <span class="circle_8"></span>
            <span class="circle_9"></span>
        </div>
    </section>
    <!--========== Solution Section End ==============-->


    <!--========== Collaboration Section Start ==============-->
    <section class="tj-collaboration-section">
        <div class="container p-relative">
            <div class="row">
                <div class="col-lg-6"></div>
                <div class="col-lg-6">
                    <div class="tj-collaboration-content">
                        <div class="tj-sec-heading">
                            <h2 class="title">Manage multiple social accounts and track growth easily.</h2>
                            <p class="desc">
                                <?php echo $sitename ?> allows creators, brands, and marketers to manage boosting campaigns efficiently while monitoring performance and engagement in real time.
                            </p>
                            <div class="check-list">
                                <ul class="list-gap">
                                    <li>
                                        <i class="flaticon-checkmark"></i> Simple dashboard to manage all boost campaigns.
                                    </li>
                                    <li>
                                        <i class="flaticon-checkmark"></i> Monitor followers, likes, and views instantly.
                                    </li>
                                    <li>
                                        <i class="flaticon-checkmark"></i> Launch and control boosts across multiple platforms with ease.
                                    </li>
                                </ul>
                            </div>
                            <div class="tj-collaboration-button">
                                <a class="tj-primary-btn" href="<?php  echo $domain ?>register/"> Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="collaboration-shape pulse">
                <img src="assets/images/shape/sec-shape13.svg" alt="shape" />
            </div>
        </div>
        <div class="collaboration-bg-image">
            <img src="assets/images/feature/boostkore.png" alt="Image" />
        </div>
        <div class="tj-circle-box-one-2">
            <span class="circle_1"></span>
            <span class="circle_2"></span>
            <span class="circle_3"></span>
            <span class="circle_4"></span>
            <span class="circle_5"></span>
            <span class="circle_6"></span>
            <span class="circle_7"></span>
            <span class="circle_8"></span>
            <span class="circle_9"></span>
        </div>
    </section>
    <!--========== Collaboration Section End ==============-->

    <!--========== Testimonial Section Start ==============-->
    <section class="tj-testimonial-slider-section" data-bg-image="assets/images/shape/group-overly4.svg">
        <div class="container">
            <div class="row">
                <div class="tj-sec-heading text-center">
                    <span class="sub-title"> Testimonials</span>
                    <h2 class="title">What our users say about <?php echo $sitename ?></h2>
                    <p class="desc">
                        Trusted by content creators, influencers, and businesses worldwide to boost social media growth safely and effectively.
                    </p>
                </div>
            </div>
        </div>
        <div class="swiper-container roll-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="testimonial-slider-item">
                        <div class="testimonial-content-box">
                            <div class="testimonial-top-header">
                               
                                <div class="testimonial-auother">
                                    <h6 class="title">Peter Buckland</h6>
                                    <span> Digital Marketer</span>
                                    <div class="testimonial-rating">
                                        <ul class="list-gap">
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial-arrow">
                                <img src="assets/images/icon/arrow-2.png" alt="Icon" />
                            </div>
                        </div>
                        <p class="desc">
                            I saw real engagement growth within days. The boosting process was smooth and very easy to manage.
                        </p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="testimonial-slider-item">
                        <div class="testimonial-content-box">
                            <div class="testimonial-top-header">
                                
                                <div class="testimonial-auother">
                                    <h6 class="title">Joseph Manning</h6>
                                    <span> Content Creator</span>
                                    <div class="testimonial-rating">
                                        <ul class="list-gap">
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial-arrow">
                                <img src="assets/images/icon/arrow-2.png" alt="Icon" />
                            </div>
                        </div>
                        <p class="desc">
                            My followers and views increased steadily. I like how transparent and reliable the platform is.
                        </p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="testimonial-slider-item">
                        <div class="testimonial-content-box">
                            <div class="testimonial-top-header">
                                
                                <div class="testimonial-auother">
                                    <h6 class="title">Dylan Hodges</h6>
                                    <span> Brand Owner</span>
                                    <div class="testimonial-rating">
                                        <ul class="list-gap">
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star"></i></li>
                                            <li><i class="fa-solid fa-star-half-stroke"></i></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="testimonial-arrow">
                                <img src="assets/images/icon/arrow-2.png" alt="Icon" />
                            </div>
                        </div>
                        <p class="desc">
                            This platform helped our brand reach more people and gain strong engagement across social media.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--========== Testimonial Section End ==============-->


    <!--========== Communication Section Start ==============-->
    <section class="tj-communication-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="tj-communication-content">
                        <div class="tj-sec-heading">
                            <h2 class="title">
                                Manage all your boost campaigns from one place.
                                <span class="active-color"> Grow Faster</span>
                            </h2>
                            <p class="desc">
                                <?php echo $sitename ?> gives you full control over your social media growth. Monitor campaign status, track engagement, and manage multiple platforms seamlessly from a single dashboard.
                            </p>
                            <div class="tj-communication-button">
                                <a class="tj-black-btn" href="<?php  echo $domain ?>register/"> Start Boosting Free</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="communication-image shake-y">
                        <img src="assets/images/feature/boostkore1.png" alt="Image" />
                    </div>
                </div>
            </div>
        </div>
        <div class="communication-shape pulse">
            <img src="assets/images/shape/sec-shape13.svg" alt="Image" />
        </div>
    </section>
    <!--========== Communication Section End ==============-->

    <!--========== Footer Section Start ==============-->
    <?php  include('./include/footer.php') ?>
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

    <!-- jquery JS -->
    <script src="assets/js/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap-bundle.js"></script>
    <!-- Meanmenu JS -->
    <script src="assets/js/meanmenu.js"></script>
    <!-- Swiper.min js -->
    <script src="assets/js/swiper.min.js"></script>
    <!-- Magnific-popup JS -->
    <script src="assets/js/magnific-popup.js"></script>
    <!-- Appear js -->
    <script src="assets/js/jquery.appear.min.js"></script>
    <!-- Odometer js -->
    <script src="assets/js/odometer.min.js"></script>
    <!-- Sal js -->
    <script src="assets/js/sal.js"></script>
    <!-- Imagesloaded-pkgd js -->
    <script src="assets/js/imagesloaded-pkgd.js"></script>
    <!-- Main js -->
    <script src="assets/js/main.js"></script>
</body>

</html>