<?php
include('../../server/connection.php');
include('../../server/auth/client.php')



?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Dashboard</title>

  <!-- Google Font -->
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
    rel="stylesheet" />

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
  </style>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            inter: ["Inter", "sans-serif"],
          },
        },
      },
    };
  </script>

  <!-- Icons -->
  <!-- Heroicons (inline SVG usage) -->
</head>

<body class="bg-gray-100 font-inter">
  <!-- Layout Wrapper -->
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <?php include('../components/sidebar.php') ?>

    <!-- Main Content -->
    <main class="flex-1 ">
      <!-- Topbar -->
      <?php include('../components/header.php') ?>


      <?php
      include_once('../../server/api/boosting.php');
      $services = $api->services();
 


      ?>

      <!-- Dashboard Content -->
      <section class="p-6 space-y-6">
        <h2 class="text-xl font-semibold">Boost Services</h2>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-xl flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
          <div class="flex gap-3">
            <select id="categoryFilter" class="border rounded-lg px-3 py-2 text-sm">
              <option value="all">All Categories</option>
              <option value="youtube">YouTube</option>
              <option value="instagram">Instagram</option>
              <option value="tiktok">TikTok</option>
              <option value="facebook">Facebook</option>
              <option value="telegram ">Telegram </option>
            </select>

            <select id="statusFilter" class="border rounded-lg px-3 py-2 text-sm">
              <option value="all">All Status</option>
              <option value="available">Available</option>
              <option value="unavailable">Unavailable</option>
            </select>
          </div>

          <input
            id="searchInput"
            type="text"
            placeholder="Search service..."
            class="border rounded-lg px-4 py-2 text-sm w-full md:w-64" />
        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="servicesGrid">

          <?php foreach ($services as $service):
            $categoryLower = strtolower($service->category);
            $status = ($service->cancel == 1) ? 'available' : 'unavailable';
          ?>

            <div
              class="service-card bg-white rounded-xl p-5 border hover:shadow transition"
              data-name="<?= strtolower($service->name) ?>"
              data-category="<?= $categoryLower ?>"
              data-status="<?= $status ?>"
              data-service='<?= json_encode($service, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>

              <div class="flex justify-between items-start">
                <h3 class="font-semibold"><?= htmlspecialchars($service->name) ?></h3>

                <span class="text-xs px-2 py-1 rounded
        <?= $status === 'available'
              ? 'bg-green-100 text-green-600'
              : 'bg-red-100 text-red-600' ?>">
                  <?= ucfirst($status) ?>
                </span>
              </div>

              <p class="text-sm text-gray-500 mt-2">
                Category: <?= htmlspecialchars($service->category) ?>
              </p>

              <p class="text-sm text-gray-500">
                Min: <?= $service->min ?> • Max: <?= number_format($service->max) ?>
              </p>

              <p class="mt-3 text-lg font-bold">
                ₦<?= number_format($service->rate, 2) ?> / 1000
              </p>

              <button
                class="order-btn mt-4 w-full bg-blue-600 text-white py-2 rounded-lg text-sm">
                Order Service
              </button>

            </div>

          <?php endforeach; ?>

        </div>



      </section>
    </main>
  </div>

  <?php include('../components/bottomnav.php') ?>




  <script>
    lucide.createIcons();
  </script>
  <script>
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const cards = document.querySelectorAll('.service-card');

    // 🔍 Search + Filter Logic
    function filterServices() {
      const search = searchInput.value.toLowerCase();
      const category = categoryFilter.value;
      const status = statusFilter.value;

      cards.forEach(card => {
        const name = card.dataset.name;
        const cardCategory = card.dataset.category;
        const cardStatus = card.dataset.status;

        const matchesSearch = name.includes(search);
        const matchesCategory =
          category === 'all' || cardCategory.includes(category);
        const matchesStatus =
          status === 'all' || cardStatus === status;

        card.style.display =
          matchesSearch && matchesCategory && matchesStatus ?
          'block' :
          'none';
      });
    }

    searchInput.addEventListener('input', filterServices);
    categoryFilter.addEventListener('change', filterServices);
    statusFilter.addEventListener('change', filterServices);

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