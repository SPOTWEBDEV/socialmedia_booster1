  <?php
    // System notifications for current user OR overall notifications
    $notif_stmt = $connection->prepare("
    SELECT * 
    FROM notifications
    WHERE type='overall' OR (type='system' AND user_id = ?)
    ORDER BY created_at DESC
    LIMIT 5
");
    $notif_stmt->bind_param("i", $id);
    $notif_stmt->execute();
    $notifications = $notif_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    ?>



  <div class="header">
      <div class="container">
          <div class="row">
              <div class="col-xxl-12">
                  <div class="header-content">
                      <div class="header-left">
                          <div class="brand-logo"><a class="mini-logo" href="#"><img src="<?php echo $domain ?>/images/logoi.png" alt="" width="40"></a></div>
                          <div class="search">

                              <form action="wallets.html#">
                                  <div class="input-group">
                                      <input type="text" class="form-control" placeholder="Search Here">
                                      <span class="input-group-text"><i class="fi fi-br-search"></i></span>
                                  </div>
                              </form>

                          </div>
                      </div>
                      <div class="header-right">
                          <div class="dark-light-toggle" onclick="themeToggle()">
                              <span class="dark"><i class="fi fi-rr-eclipse-alt"></i></span>
                              <span class="light"><i class="fi fi-rr-eclipse-alt"></i></span>
                          </div>
                          <div class="nav-item dropdown notification">
                              <div data-bs-toggle="dropdown">
                                  <div class="notify-bell icon-menu">
                                      <span><i class="fi fi-rs-bells"></i></span>
                                  </div>
                              </div>

                              <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-end">

                                  <h4>Recent Notification</h4>
                                  <div class="lists">
                                      <?php if (!empty($notifications)): ?>
                                          <?php foreach ($notifications as $notif): ?>
                                              <a href="#">
                                                  <div class="d-flex align-items-center">
                                                      <?php
                                                        // Choose icon class based on type/status
                                                        $icon_class = 'fi fi-bs-check'; // default success
                                                        $icon_color = 'success';
                                                        if (strpos(strtolower($notif['message']), 'failed') !== false) {
                                                            $icon_class = 'fi fi-sr-cross-small';
                                                            $icon_color = 'fail';
                                                        } elseif (strpos(strtolower($notif['message']), 'pending') !== false) {
                                                            $icon_class = 'fi fi-rr-triangle-warning';
                                                            $icon_color = 'pending';
                                                        }
                                                        ?>
                                                      <span class="me-3 icon <?= $icon_color ?>"><i class="<?= $icon_class ?>"></i></span>
                                                      <div>
                                                          <p><?= htmlspecialchars($notif['message']) ?></p>
                                                          <span><?= date("Y-m-d H:i:s", strtotime($notif['created_at'])) ?></span>
                                                      </div>
                                                  </div>
                                              </a>
                                          <?php endforeach; ?>
                                      <?php else: ?>
                                          <p class="text-center text-muted">No notifications found</p>
                                      <?php endif; ?>
                                  </div>


                                  <!-- <div class="more">
                                      <a href="notifications.html">More<i class="fi fi-bs-angle-right"></i></a>
                                  </div> -->
                              </div>
                          </div>

                          <div class="dropdown profile_log dropdown">
                              <div data-bs-toggle="dropdown">
                                  <div class="user icon-menu active"><span><i class="fi fi-rr-user"></i></span></div>
                              </div>
                              <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu dropdown-menu-end">
                                  <div class="user-email">
                                      <div class="user">
                                          <span class="thumb"><img class="rounded-full" src="<?php echo $domain ?>/images/avatar/3.jpg" alt=""></span>
                                          <div class="user-info">
                                              <h5><?php echo $username ?></h5>
                                              <span><?php echo $email ?></span>
                                          </div>
                                      </div>
                                  </div>
                                  <a class="dropdown-item" href="<?php echo $domain ?>client/dashboard/">
                                      <span><i class="fi fi-rr-user"></i></span>
                                      Dashboard
                                  </a>
                                  <a class="dropdown-item" href="<?php echo $domain ?>client/deposits/">
                                      <span><i class="fi fi-rr-wallet"></i></span>
                                      Deposit
                                  </a>
                                  <a class="dropdown-item" href="<?php echo $domain ?>client/setting/">
                                      <span><i class="fi fi-rr-settings"></i></span>
                                      Settings
                                  </a>
                                  <a class="dropdown-item logout" href="<?php echo $domain ?>client/logout">
                                      <span><i class="fi fi-bs-sign-out-alt"></i></span>
                                      Logout
                                  </a>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>


  <?php include('../../include/support.php') ?>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>