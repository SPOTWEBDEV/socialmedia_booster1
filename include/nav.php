
<div class="tj-offcanvas-area">
        <div class="tj-offcanvas-header d-flex align-items-center justify-content-between">
            <div class="logo-area text-center">
                <a href="index.html"><img src="<?php  echo $domain ?>assets/images/logo/logo.png" alt="Logo" /></a>
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

<header class="tj-header-area" id="tj-header-sticky">
    <div class="container">
        <div class="row">
            <div class="header-content-area">
                <div class="logo-area">
                    <a href="#home">
                        <img src="<?php  echo $domain ?>assets/images/logo/logo.png" alt="Logo" />
                    </a>
                </div>

                <!-- Mainmenu Start -->
                <div class="tj-main-menu d-lg-block d-none text-center" id="main-menu">
                    <ul class="main-menu">
                        <li>
                            <a class="active" href="<?php  echo $domain ?>#home"> Home</a>
                        </li>
                        <li>
                            <a href="<?php  echo $domain ?>#about"> About Us</a>
                        </li>
                        <li>
                            <a href="<?php  echo $domain ?>#features"> Features</a>
                        </li>
                        <li>
                            <a href="<?php  echo $domain ?>services/"> Services</a>
                        </li>


                        <div class="d-md-none">
                            <a style="color: white; border:2px solid" class="tj-black-btn" href="<?php  echo $domain ?>register/"> Start Boosting Free</a>
                            <a style="color: white; border:2px solid" class="tj-black-btn" href="<?php  echo $domain ?>login/"> Login</a>
                        </div>
                      
                        
                        
                    </ul>
                </div>
                <!-- Mainmenu Item End -->

                <div class="header-button-box d-lg-block d-none">
                    <div class="tj-login-button header-button">
                        <a class="tj-secondary-btn" href="<?php echo $domain ?>login"> Login </a>
                    </div>
                    <div class="tj-singup-button header-button">
                        <a class="tj-primary-btn" href="<?php echo $domain ?>register"> Register</a>
                    </div>
                </div>

                <div class="tj-canva-icon d-lg-none">
                    <a class="canva_expander nav-menu-link menu-button" href="#">
                        <span class="dot1"></span>
                        <span class="dot2"></span>
                        <span class="dot3"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>