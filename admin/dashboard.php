<?php

$active_page = 'dashboard';
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

/** @var Database $database */
$database = new Database();
$db = $database->getConnection();

$stats = [];
$stmt = $db->prepare("SELECT COUNT(*) as count FROM gallery");
$stmt->execute();
$stats['gallery'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// BAGIAN 2: PANGGIL HEADER
require_once 'includes/header.php';
?>

<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Dashboard</h2>
    <p class="text-gray-600">Selamat datang di panel admin Bus Tividi Pariwisata</p>
</div>

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