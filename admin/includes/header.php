<?php
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bus Tividi Pariwisata</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'turquoise': {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-turquoise-600">Admin Panel - Bus Tividi</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Selamat datang, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="../index.php" class="text-turquoise-600 hover:text-turquoise-700" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Lihat Website
                    </a>
                    <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <div class="w-64 bg-white shadow-lg min-h-screen">
            <div class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="dashboard.php"
                            class="flex items-center p-2 rounded-lg
                           <?php echo (isset($active_page) && $active_page === 'dashboard') ? 'text-turquoise-600 bg-turquoise-50 font-semibold' : 'text-gray-700 hover:bg-gray-100'; ?>">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="manage_gallery.php"
                            class="flex items-center p-2 rounded-lg
                           <?php echo (isset($active_page) && $active_page === 'gallery') ? 'text-turquoise-600 bg-turquoise-50 font-semibold' : 'text-gray-700 hover:bg-gray-100'; ?>">
                            <i class="fas fa-images mr-3"></i>
                            Kelola Gallery
                        </a>
                    </li>
                    <li>
                        <a href="manage_vision_mission.php"
                            class="flex items-center p-2 rounded-lg
                           <?php echo (isset($active_page) && $active_page === 'vision') ? 'text-turquoise-600 bg-turquoise-50 font-semibold' : 'text-gray-700 hover:bg-gray-100'; ?>">
                            <i class="fas fa-eye mr-3"></i>
                            Visi & Misi
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex-1 p-8">