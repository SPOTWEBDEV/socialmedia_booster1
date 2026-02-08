<?php
include('../../server/connection.php');
include('../../server/auth/client.php');
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Settings - BoostPanel</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

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

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="bg-gray-100 font-inter">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include('../components/sidebar.php') ?>

        <!-- Main Content -->
        <main class="flex-1">
            <!-- Topbar -->
            <?php include('../components/header.php') ?>

            <!-- Settings Content -->
            <section class="p-6 space-y-6">

                <!-- Page Title -->
                <div class="mb-4">
                    <h2 class="text-2xl font-semibold text-gray-800">Account Settings</h2>
                    <p class="text-gray-500">Manage your account, change password and notifications.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Change Password Card -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Change Password</h3>
                        <form action="update_password.php" method="POST" class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Current Password</label>
                                <input type="password" name="current_password" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">New Password</label>
                                <input type="password" name="new_password" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Confirm New Password</label>
                                <input type="password" name="confirm_password" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Update Password</button>
                        </form>
                    </div>

                    <!-- Notification Settings Card -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Notification Settings</h3>
                        <form action="update_notifications.php" method="POST" class="space-y-4">

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-700 font-medium">Email Notifications</p>
                                    <p class="text-gray-400 text-sm">Receive emails for account activity</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_notifications" class="sr-only peer" checked>
                                    <div
                                        class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 transition-all"></div>
                                    <span
                                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transform transition peer-checked:translate-x-5"></span>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-700 font-medium">Push Notifications</p>
                                    <p class="text-gray-400 text-sm">Receive push notifications on your device</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="push_notifications" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 transition-all"></div>
                                    <span
                                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transform transition peer-checked:translate-x-5"></span>
                                </label>
                            </div>

                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Save Settings</button>
                        </form>
                    </div>

                    <!-- Other Settings Card (Optional) -->
                    <div class="bg-white shadow rounded-lg p-6 md:col-span-2">
                        <h3 class="text-lg font-semibold mb-4">Other Preferences</h3>
                        <form action="update_preferences.php" method="POST" class="space-y-4">

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-700 font-medium">Dark Mode</p>
                                    <p class="text-gray-400 text-sm">Enable dark theme for the dashboard</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="dark_mode" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 transition-all"></div>
                                    <span
                                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transform transition peer-checked:translate-x-5"></span>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-700 font-medium">Marketing Emails</p>
                                    <p class="text-gray-400 text-sm">Receive promotional emails</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="marketing_emails" class="sr-only peer" checked>
                                    <div
                                        class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 transition-all"></div>
                                    <span
                                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transform transition peer-checked:translate-x-5"></span>
                                </label>
                            </div>

                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Save Preferences</button>
                        </form>
                    </div>

                </div>
            </section>
        </main>
    </div>

    <?php include('../components/bottomnav.php') ?>

</body>

</html>
