<?php
include('../../server/connection.php');
include('../../server/auth/client.php');


$userId = $id;
$message = "";

/* ---------------------------
   HELPER: API KEY GENERATOR
---------------------------- */
function generateApiKey($type = 'live')
{
    return 'sk_' . $type . '_' . bin2hex(random_bytes(16));
}

/* ---------------------------
   HANDLE FORM SUBMISSIONS
---------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* CHANGE PASSWORD */
    if (isset($_POST['change_password'])) {

        $current = $_POST['current_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];

        if ($new !== $confirm) {
            $message = "Passwords do not match";
        } else {
            $stmt = $connection->prepare("SELECT password FROM users WHERE id=?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->bind_result($hashed);
            $stmt->fetch();
            $stmt->close();

            if (!password_verify($current, $hashed)) {
                $message = "Current password is incorrect";
            } else {
                $newHash = password_hash($new, PASSWORD_DEFAULT);
                $up = $connection->prepare("UPDATE users SET password=? WHERE id=?");
                $up->bind_param("si", $newHash, $userId);
                $up->execute();
                $message = "Password updated successfully";
            }
        }
    }

    /* NOTIFICATION SETTINGS */
    if (isset($_POST['save_notifications'])) {
        $email = isset($_POST['email_notifications']) ? 1 : 0;
        $push  = isset($_POST['push_notifications']) ? 1 : 0;

        $stmt = $connection->prepare("
            UPDATE users 
            SET email_notifications=?, push_notifications=? 
            WHERE id=?
        ");
        $stmt->bind_param("iii", $email, $push, $userId);
        $stmt->execute();
        $message = "Notification settings saved";
    }

    /* API KEY GENERATION */
    if (isset($_POST['generate_live'])) {
        $key = generateApiKey('live');
        $stmt = $connection->prepare("UPDATE users SET api_key_live=? WHERE id=?");
        $stmt->bind_param("si", $key, $userId);
        $stmt->execute();
        $message = "Live API key generated";
    }

    if (isset($_POST['generate_test'])) {
        $key = generateApiKey('test');
        $stmt = $connection->prepare("UPDATE users SET api_key_test=? WHERE id=?");
        $stmt->bind_param("si", $key, $userId);
        $stmt->execute();
        $message = "Test API key generated";
    }
}

/* USER PREFERENCES */
if (isset($_POST['save_preferences'])) {

    $country  = $_POST['country'];
    $currency = $_POST['currency'];

    $stmt = $connection->prepare("
        UPDATE users 
        SET country=?, currency=? 
        WHERE id=?
    ");
    $stmt->bind_param("ssi", $country, $currency, $userId);
    $stmt->execute();

    $message = "Preferences updated successfully";
}


/* FETCH USER DATA */
$user = mysqli_fetch_assoc(mysqli_query($connection, "SELECT 
    email_notifications, 
    push_notifications, 
    api_key_live, 
    api_key_test,
    country,
    currency
FROM users 
WHERE id='$userId'
"));



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

                <?php if ($message): ?>
                    <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Change Password Card -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4">Change Password</h3>
                        <form method="POST" class="space-y-4">
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
                            <button type="submit" name="change_password"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Update Password</button>
                        </form>
                    </div>

                    <!-- Notification Settings Card -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="font-semibold mb-4">Notifications</h3>
                        <form method="POST" class="space-y-4">
                            <label class="flex justify-between items-center">
                                <span>Email Notifications</span>
                                <input type="checkbox" name="email_notifications" <?php if ($user['email_notifications']) echo 'checked'; ?>>
                            </label>
                            <label class="flex justify-between items-center">
                                <span>Push Notifications</span>
                                <input type="checkbox" name="push_notifications" <?php if ($user['push_notifications']) echo 'checked'; ?>>
                            </label>
                            <button name="save_notifications" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                                Save
                            </button>
                        </form>
                    </div>

                    <!-- Preferences Card -->
                    <!-- <div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-semibold mb-4">Preferences</h3>

    <form method="POST" class="space-y-4">

      
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Country
            </label>
            <select name="country" required
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Select country</option>
                <option value="NG" <?= ($user['country'] ?? '') === 'NG' ? 'selected' : '' ?>>Nigeria</option>
                <option value="GH" <?= ($user['country'] ?? '') === 'GH' ? 'selected' : '' ?>>Ghana</option>
                <option value="US" <?= ($user['country'] ?? '') === 'US' ? 'selected' : '' ?>>United States</option>
            </select>
        </div>

       
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Currency
            </label>
            <select name="currency" required
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Select currency</option>
                <option value="NGN" <?= ($user['currency'] ?? '') === 'NGN' ? 'selected' : '' ?>>₦ NGN</option>
                <option value="GHS" <?= ($user['currency'] ?? '') === 'GHS' ? 'selected' : '' ?>>₵ GHS</option>
                <option value="USD" <?= ($user['currency'] ?? '') === 'USD' ? 'selected' : '' ?>>$ USD</option>
            </select>
        </div>

        <button name="save_preferences"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            Save Preferences
        </button>
    </form>
</div> -->



                    <div class="bg-white rounded-xl shadow p-6 lg:col-span-2">
                        <h3 class="font-semibold mb-4">API Keys</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="text-sm">Live API Key</label>
                                <div class="flex gap-2">
                                    <input readonly value="<?php echo $user['api_key_live'] ?? 'Not generated'; ?>" class="flex-1 px-3 py-2 border rounded-lg bg-gray-100 text-sm">
                                    <button name="generate_live" formmethod="POST" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                                        Generate
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="text-sm">Test API Key</label>
                                <div class="flex gap-2">
                                    <input readonly value="<?php echo $user['api_key_test'] ?? 'Not generated'; ?>" class="flex-1 px-3 py-2 border rounded-lg bg-gray-100 text-sm">
                                    <button name="generate_test" formmethod="POST" class="bg-gray-800 text-white px-4 py-2 rounded-lg">
                                        Generate
                                    </button>
                                </div>
                            </div>

                            <a href="/docs/api" class="text-blue-600 font-medium inline-flex items-center gap-2">
                                <i class="bi bi-book"></i> API Documentation
                            </a>
                        </div>
                    </div>

                </div>
            </section>
        </main>
    </div>

    <?php include('../components/bottomnav.php') ?>

</body>

</html>