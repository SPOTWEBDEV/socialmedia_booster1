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
            <header
                class="bg-white border-b px-6 py-4 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Welcome back</p>
                    <h1 class="text-xl font-semibold"><?php echo $fullname ?> 👋</h1>
                </div>
                <div class="flex items-center gap-4">
                    <button class="relative">
                        <span
                            class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        🔔
                    </button>
                </div>
            </header>

            <!-- Dashboard Content -->
            <section class="p-2 sm:p-6 space-y-6 ">
                <!-- Wallet Card -->
                <div
                    class="bg-blue-600 text-white rounded-xl p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="col-span-2">
                        <p class="text-sm opacity-80">Wallet Balance</p>
                        <h2 class="text-3xl font-bold">₦<?php echo number_format($balance, 2) ?></h2>
                        <p class="text-sm opacity-80">$2.18</p>
                    </div>
                    <div
                        class="flex items-center justify-between md:justify-end gap-2 sm:gap-4">
                        <button
                            class="flex items-center gap-2 bg-white/20 px-4 py-2 rounded-lg">
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Deposit
                        </button>
                        <button
                            class="flex items-center gap-2 bg-white/20 px-4 py-2 rounded-lg">
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 7h16M4 17h16M10 3l-4 4 4 4M14 21l4-4-4-4" />
                            </svg>
                            Exchange
                        </button>
                    </div>
                </div>


                <section class="p-6 space-y-6">
                    <h2 class="text-xl font-semibold">Deposit Funds</h2>

                   
                    <!-- Deposit Form -->
                    <div class="bg-white p-6 rounded-xl max-w-lg">
                        <h3 class="font-semibold mb-4">Make a Deposit</h3>
                        <form class="space-y-4">
                            <div>
                                <label class="text-sm font-medium">Amount</label>
                                <input type="number" placeholder="5000" class="w-full mt-1 border rounded-lg px-4 py-2" />
                            </div>
                            <div>
                                <label class="text-sm font-medium">Payment Method</label>
                                <select class="w-full mt-1 border rounded-lg px-4 py-2">
                                    <option>Bank Transfer</option>
                                    <option>Card</option>
                                    <option>Crypto</option>
                                </select>
                            </div>
                            <button class="w-full bg-blue-600 text-white py-2 rounded-lg">Proceed</button>
                        </form>
                    </div>                

                </section>
            </section>
        </main>
    </div>

    <?php include('../components/bottomnav.php') ?>




    <script>
        lucide.createIcons();
    </script>
</body>

</html>