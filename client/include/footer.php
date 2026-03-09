  <!-- <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="copyright">
                            <p>© Copyright
                                <script>
                                    var CurrentYear = new Date().getFullYear()
                                    document.write(CurrentYear)
                                </script>
                                <a href="#"><?= $sitename ?></a> I All Rights Reserved
                            </p>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="footer-social">
                            <ul>
                                <li><a href="settings-api.html#"><i class="fi fi-brands-facebook"></i></a></li>
                                <li><a href="settings-api.html#"><i class="fi fi-brands-twitter"></i></a></li>
                                <li><a href="settings-api.html#"><i class="fi fi-brands-linkedin"></i></a></li>
                                <li><a href="settings-api.html#"><i class="fi fi-brands-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

  <div class="footer">
      <div class="container">
          <div class="row">

              <!-- About Site -->
              <div class="col-md-6">
                  <div class="footer-about">
                      <img src="<?= $domain ?>assets/images/logo/logo.png" alt="<?= $sitename ?>" style="height:100px;margin-bottom:10px;">
                      <p>
                          <?= $sitename ?> is a trusted social media boosting platform helping creators, influencers, and brands grow faster with real engagement.
                      </p>
                  </div>
              </div>

              <!-- User Links -->
              <div class="col-md-3">
                  <div class="footer-links">
                      <h5>User Links</h5>
                      <ul>
                          <style>
                              .footer-links ul li a {
                                  color: #7184ad;

                              }
                          </style>
                          <li><a href="dashboard.php">Dashboard</a></li>
                          <li><a href="deposit.php">Deposit</a></li>
                          <li><a href="services.php">Services</a></li>
                          <li><a href="referral.php">Referral</a></li>
                          <li><a href="orders.php">My Orders</a></li>
                      </ul>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="footer-links">
                      <h5>Popular Services</h5>
                      <ul>
                          <style>
                              .footer-links ul li a {
                                  color: #7184ad;

                              }
                          </style>
                          <li><a href="<?php echo $domain ?>client/orders/?category=facebook">Facebook Boosting</a></li>
                          <li><a href="<?php echo $domain ?>client/orders/?category=instagram">Instagram Boosting</a></li>
                          <li><a href="<?php echo $domain ?>client/orders/?category=youtube">YouTube Boosting</a></li>
                          <li><a href="<?php echo $domain ?>client/orders/?category=tiktok">TikTok Boosting</a></li>
                          <li><a href="<?php echo $domain ?>client/orders/?category=telegram">Telegram Boosting</a></li>
                      </ul>
                  </div>
              </div>

              <!-- Social + Copyright -->
              <div class="col-xl-4 col-lg-4 col-md-12 d-flex-row-reverse align-items-center justify-content-center">
                  <!-- <div class="footer-social">
                    <h5>Follow Us</h5>
                    <ul>
                        <li><a href="#"><i class="fi fi-brands-facebook"></i></a></li>
                        <li><a href="#"><i class="fi fi-brands-twitter"></i></a></li>
                        <li><a href="#"><i class="fi fi-brands-linkedin"></i></a></li>
                        <li><a href="#"><i class="fi fi-brands-youtube"></i></a></li>
                    </ul>
                </div> -->

                  <div class="copyright mt-3">
                      <p>© Copyright
                          <script>
                              var CurrentYear = new Date().getFullYear();
                              document.write(CurrentYear);
                          </script>
                          <a href="#"><?= $sitename ?></a> | All Rights Reserved
                      </p>
                  </div>
              </div>

          </div>
      </div>
  </div>