<?php
include("../../server/connection.php");
include('../../server/auth/client.php');
include_once('../../server/api/boosting.php');
$services = $api->services();



$get = mysqli_query($connection, "SELECT  siteprice  FROM sitedetails  ORDER BY id LIMIT 1");
$data = mysqli_fetch_assoc($get);
$site_price = floatval($data['siteprice'] ?? 0);





?>


<!DOCTYPE html>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $sitename ?> | Order </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" href="<?php echo $domain ?>assets/images/logo/favicon.png">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $domain ?>client/css/style.css">
    <link rel="stylesheet" href="<?php echo $domain ?>client/vendor/toastr/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
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
                                        <h2 class="text-2xl font-bold text-gray-800">Boost Services</h2>
                                        <p class="text-gray-500 text-sm">Choose a service and place your order instantly.</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button onclick="window.location.href='./my-order/'"
                                        class="px-5 py-2.5 bg-primary border border-gray-300 rounded-xl text-sm font-medium hover:bg-gray-100 transition">
                                        View Order History
                                    </button>

                                    <button data-bs-toggle="dropdown"
                                        class="px-5 py-2.5 bg-primary border border-gray-300 rounded-xl text-sm font-medium hover:bg-gray-100 transition">
                                        How To Order?
                                    </button>
                                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-end col-6 px-4 py-3">

                                        <h4>Tutorial Video</h4>
                                        <div class="lists">
                                            <?php

                                            $tutorial_stmt = $connection->prepare("
                                                    SELECT * 
                                                    FROM tutorial
                                                    
                                                ");
                                            $tutorial_stmt->execute();
                                            $tutorial = $tutorial_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

                                            if (!empty($tutorial)): $count = 0 ?>
                                                <?php foreach ($tutorial as $tuto): $count++ ?>

                                                    <div class="d-flex align-items-center">

                                                        <div class="shrink-0">
                                                            <div class="d-flex gap-2 g-2">
                                                                <?= $count ?> :

                                                                <div class="d-flex gap-1 align-items-center">
                                                                    <i class="<?= $tuto['icon'] ?>"></i>
                                                                    <?= htmlspecialchars($tuto['title']) ?>
                                                                </div>

                                                                <a href="<?= $tuto['link'] ?>"><span class="text-danger">[Watch Now]</span></a>
                                                        
                                                            </div>

                                                        </div>
                                                    </div>

                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p class="text-center text-muted">No tutorial found</p>
                                            <?php endif; ?>
                                        </div>



                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xxl-12 col-xl-12">

                        <section class="p-8 space-y-8">
                            <!-- Filters Card -->


                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-body">
                                    <div class="row g-3 align-items-center">

                                        <div class="col-md-4">
                                            <select id="categoryFilter" class="form-select">

                                                <option value="all">All Categories</option>
                                                <option value="youtube">YouTube</option>
                                                <option value="instagram">Instagram</option>
                                                <option value="tiktok">TikTok</option>
                                                <option value="facebook">Facebook</option>
                                                <option value="telegram">Telegram</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <input
                                                id="searchInput"
                                                type="text"
                                                class="form-control"
                                                placeholder="Search service...">
                                        </div>

                                    </div>
                                </div>
                            </div>



                            <!-- Services Grid -->
                            <div class="row g-4 mt-4" id="servicesGrid">

                                <?php foreach ($services as $service):
                                    $categoryLower = strtolower($service->category);
                                    $status = ($service->cancel == 1) ? 'available' : 'unavailable';

                                    $thirdParty = (1000 / 1000) * $service->rate;
                                    $siteFee = (1000 / 1000) * $site_price;
                                    $total = $thirdParty + $siteFee;
                                ?>

                                    <div class="col-xl-3 col-lg-4 col-md-6">
                                        <div class="card shadow-sm border-0 h-100 service-card p-4"
                                            data-name="<?= strtolower($service->name) ?>"
                                            data-category="<?= $categoryLower ?>"
                                            data-status="<?= $status ?>"
                                            data-service='<?= json_encode($service, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>

                                            <h6 class="fw-bold mb-1">
                                                <?= htmlspecialchars($service->name) ?>
                                            </h6>

                                            <small class="text-muted d-block mb-2">
                                                <?= htmlspecialchars($service->category) ?>
                                            </small>

                                            <small class="text-muted d-block mb-3">
                                                Min: <?= $service->min ?> |
                                                Max: <?= number_format($service->max) ?>
                                            </small>

                                            <div class="mb-3">
                                                <span class="fs-4 fw-bold">
                                                    $<?= number_format($total, 2) ?>
                                                </span>
                                                <small class="text-muted">/ 1,000</small>
                                            </div>

                                            <button
                                                class="btn btn-primary w-100 order-btn">
                                                Order Now
                                            </button>

                                        </div>
                                    </div>

                                <?php endforeach; ?>

                            </div>


                        </section>


                    </div>
                </div>





            </div>
        </div>



    </div>

    <script src="<?php echo $domain ?>client/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo $domain ?>client/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!--  -->
    <!--  -->
    <script src="<?php echo $domain ?>client/js/scripts.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');

            function filterServices() {
                const search = searchInput.value.toLowerCase().trim();
                const category = categoryFilter.value.toLowerCase();

                document.querySelectorAll('.service-card').forEach(card => {

                    const col = card.closest('[class*="col-"]'); // hide column instead of card
                    const name = card.dataset.name.toLowerCase();
                    const cardCategory = card.dataset.category.toLowerCase();

                    const matchesSearch = name.includes(search);

                    const matchesCategory =
                        category === 'all' ||
                        cardCategory.includes(category);

                    if (matchesSearch && matchesCategory) {
                        col.style.display = "";
                    } else {
                        col.style.display = "none";
                    }
                });
            }

            searchInput.addEventListener('keyup', filterServices);
            categoryFilter.addEventListener('change', filterServices);

        });
        // 🛒 Order Button → localStorage + redirect
        document.querySelectorAll('.order-btn').forEach(button => {
            button.addEventListener('click', function() {
                const card = this.closest('.service-card');
                const serviceData = JSON.parse(card.dataset.service);

                localStorage.setItem('selected_service', JSON.stringify(serviceData));

                window.location.href = './purchase-order';
            });
        });
    </script>

</body>

</html>