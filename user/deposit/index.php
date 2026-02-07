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
                <section class="p-6 space-y-6">
                    <h2 class="text-xl font-semibold">Deposit Funds</h2>


                    <!-- Deposit Form -->
                    <div class="bg-white p-6 rounded-xl max-w-lg">
                        <h3 class="font-semibold mb-4">Make a Deposit</h3>

                        <form id="depositForm" class="space-y-4">
                            <div>
                                <label class="text-sm font-medium">Amount (NGN)</label>
                                <input type="number" id="depositAmount" placeholder="5000" min="100" required
                                    class="w-full mt-1 border rounded-lg px-4 py-2" />
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-2 rounded-lg">
                                Pay with Etegram
                            </button>
                        </form>
                    </div>


                </section>
            </section>
        </main>
    </div>

    <?php include('../components/bottomnav.php') ?>



    <script>
        let url = "<?php echo $domain ?>" + 'server/api/etegram-init.php'
        document.getElementById("depositForm").addEventListener("submit", async function(e) {
            e.preventDefault();

            const amount = parseFloat(document.getElementById("depositAmount").value);
            if (amount <= 0) {
                alert("Enter a valid amount.");
                return;
            }

            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    amount,
                    user_id :"<?php echo $id ?>"
                })
            });

            console.log(response)

            const data = await response.json();

            console.log(data)

            if (data.status && data.authorization_url) {
                window.location.href = data.authorization_url;
            } else {
                alert("Error initializing payment. Try again.");
            }
        });
    </script>

</body>

</html>