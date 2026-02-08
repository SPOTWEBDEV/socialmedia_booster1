<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<?php
$currentPath = $_SERVER['REQUEST_URI'];
?>

<aside class="w-64 bg-white border-r hidden md:flex flex-col shadow-lg">
    <!-- Logo / Brand -->
    <div class="p-6 flex items-center gap-2 border-b">
        <i class="bi bi-lightning-charge-fill text-3xl bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent"></i>
        <span class="text-xl font-bold text-blue-600">BoostPanel</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2">
        <?php
        // Define menu items with URL paths
        $menuItems = [
            ['title' => 'Dashboard', 'icon' => 'bi-speedometer2', 'link' => 'user/dashboard/', 'gradient' => 'from-blue-500 to-purple-600', 'hover' => 'from-purple-500 to-pink-500'],
            ['title' => 'Deposit', 'icon' => 'bi-wallet2', 'link' => 'user/deposit/', 'gradient' => 'from-green-400 to-teal-500', 'hover' => 'from-teal-400 to-blue-500'],
            ['title' => 'Orders', 'icon' => 'bi-basket', 'link' => 'user/order/', 'gradient' => 'from-yellow-400 to-orange-500', 'hover' => 'from-orange-400 to-red-500'],
            ['title' => 'Referral', 'icon' => 'bi-person-plus-fill', 'link' => 'user/referral/', 'gradient' => 'from-pink-500 to-purple-600', 'hover' => 'from-purple-500 to-pink-500'],
            ['title' => 'Settings', 'icon' => 'bi-gear-fill', 'link' => 'user/setting/', 'gradient' => 'from-gray-500 to-gray-700', 'hover' => 'from-gray-700 to-black'],
        ];


        foreach ($menuItems as $item) {
            // Check if current URL contains menu link path
            $isActive = strpos($currentPath, $item['link']) !== false;
        ?>
            <a href="<?php echo $domain .  $item['link']; ?>"
                class="flex items-center gap-3 px-4 py-2 rounded-lg transition relative group
               <?php echo $isActive ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600'; ?>">
                <?php if ($isActive): ?>
                    <span class="absolute left-0 top-0 bottom-0 w-1 bg-blue-600 rounded-r-lg"></span>
                <?php endif; ?>
                <i class="bi <?php echo $item['icon']; ?> text-xl bg-gradient-to-r <?php echo $item['gradient']; ?> bg-clip-text text-transparent group-hover:<?php echo $item['hover']; ?> transition-all"></i>
                <span class="group-hover:text-blue-600 transition-colors"><?php echo $item['title']; ?></span>
            </a>
        <?php } ?>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t">
        <a href="/user/logout/"
            class="flex items-center gap-3 px-4 py-2 rounded-lg text-red-600 hover:bg-red-50 transition relative group">
            <i class="bi bi-box-arrow-right text-xl bg-gradient-to-r from-red-400 to-pink-500 bg-clip-text text-transparent group-hover:from-pink-400 group-hover:to-red-500 transition-all"></i>
            <span class="group-hover:text-pink-600 transition-colors">Logout</span>
        </a>
    </div>
</aside>