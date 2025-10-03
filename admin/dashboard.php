<?php
session_start();
require_once '../config/database.php';

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

/** @var Database $database */
$database = new Database();
$db = $database->getConnection();

// Get statistics
$stats = [];

// Count gallery items
$stmt = $db->prepare("SELECT COUNT(*) as count FROM gallery");
$stmt->execute();
$stats['gallery'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
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
    <!-- Navigation -->
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
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg min-h-screen">
            <div class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="dashboard.php" class="flex items-center p-2 text-turquoise-600 bg-turquoise-50 rounded-lg">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="manage_gallery.php" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-images mr-3"></i>
                            Kelola Gallery
                        </a>
                    </li>
                    <li>
                        <a href="manage_vision_mission.php" class="flex items-center p-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-eye mr-3"></i>
                            Visi & Misi
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Dashboard</h2>
                <p class="text-gray-600">Selamat datang di panel admin Bus Tividi Pariwisata</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <i class="fas fa-images text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Foto Gallery</p>
                            <p class="text-2xl font-semibold text-gray-900"><?php echo $stats['gallery']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="manage_gallery.php?action=add" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-camera text-turquoise-600 text-2xl mr-4"></i>
                            <div>
                                <h3 class="font-semibold text-gray-800">Upload Foto</h3>
                                <p class="text-gray-600 text-sm">Tambah foto ke gallery</p>
                            </div>
                        </a>
                        <a href="manage_vision_mission.php" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-eye text-turquoise-600 text-2xl mr-4"></i>
                            <div>
                                <h3 class="font-semibold text-gray-800">Edit Visi & Misi</h3>
                                <p class="text-gray-600 text-sm">Kelola visi dan misi perusahaan</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>