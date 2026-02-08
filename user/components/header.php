<header class="bg-white border-b px-4 sm:px-6 py-4 flex flex-wrap items-center justify-between shadow-sm gap-2">
    <!-- Left: Search -->
    <div class="flex flex-1 min-w-0 items-center gap-2 sm:gap-6">
        <!-- Search bar -->
        <div class="relative flex-1 min-w-0">
            <input type="text" placeholder="Search..." 
                class="w-full sm:w-fit pl-10 pr-4 py-2 border rounded-lg bg-gray-100 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
            <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
        </div>
    </div>

    <!-- Right: Notifications + Profile -->
    <div class="flex items-center gap-2 sm:gap-4 mt-2 sm:mt-0">
        <!-- Notifications -->
        <div class="relative flex-shrink-0">
            <button id="notifBtn" class="relative p-2 rounded-full hover:bg-gray-100 transition">
                <i class="bi bi-bell-fill text-gray-600 text-lg"></i>
                <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
            </button>
            <div id="notifDropdown" class="absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-lg overflow-hidden hidden z-50">
                <p class="p-4 text-sm text-gray-600">No new notifications</p>
            </div>
        </div>

        <!-- User Profile -->
        <div class="relative flex-shrink-0">
            <button id="profileBtn" class="flex items-center gap-2 rounded-lg hover:bg-gray-100 px-3 py-2 transition whitespace-nowrap">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($fullname); ?>&background=4D5CDA&color=fff" 
                     alt="Avatar" class="w-8 h-8 rounded-full">
                <span class="text-gray-700 font-medium hidden sm:inline"><?php echo $fullname ?></span>
                <i class="bi bi-chevron-down text-gray-500"></i>
            </button>

            <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg overflow-hidden hidden z-50">
                <a href="<?php echo $domain ?>user/dashboard" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Home</a>
                <a href="<?php echo $domain ?>user/setting/" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Settings</a>
                <a href="<?php echo $domain ?>user/logout/" class="block px-4 py-2 text-red-600 hover:bg-red-50">Logout</a>
            </div>
        </div>
    </div>
</header>

<script>
    const notifBtn = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');
    notifBtn.addEventListener('click', () => notifDropdown.classList.toggle('hidden'));

    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');
    profileBtn.addEventListener('click', () => profileDropdown.classList.toggle('hidden'));

    window.addEventListener('click', function(e) {
        if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
            notifDropdown.classList.add('hidden');
        }
        if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
            profileDropdown.classList.add('hidden');
        }
    });
</script>
