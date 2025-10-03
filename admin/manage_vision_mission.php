<?php
session_start();
require_once '../config/database.php';

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();

$message = '';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle form submissions
if ($_POST) {
    if (isset($_POST['add_vision_mission'])) {
        $stmt = $db->prepare("INSERT INTO vision_mission (type, content) VALUES (?, ?)");
        $stmt->execute([
            $_POST['type'],
            $_POST['content']
        ]);
        $message = 'Visi/Misi berhasil ditambahkan!';
        $action = 'list';
    } elseif (isset($_POST['edit_vision_mission'])) {
        $stmt = $db->prepare("UPDATE vision_mission SET type=?, content=? WHERE id=?");
        $stmt->execute([
            $_POST['type'],
            $_POST['content'],
            $_POST['id']
        ]);
        $message = 'Visi/Misi berhasil diupdate!';
        $action = 'list';
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $stmt = $db->prepare("DELETE FROM vision_mission WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    $message = 'Visi/Misi berhasil dihapus!';
    $action = 'list';
}

// Get vision mission for listing
if ($action === 'list') {
    $stmt = $db->prepare("SELECT * FROM vision_mission ORDER BY type, created_at DESC");
    $stmt->execute();
    $vision_missions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get single vision mission for editing
if ($action === 'edit' && $id) {
    $stmt = $db->prepare("SELECT * FROM vision_mission WHERE id=?");
    $stmt->execute([$id]);
    $vision_mission = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Visi & Misi - Admin Bus Tividi</title>
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
    <nav class="bg-turquoise-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Admin Panel - Kelola Visi & Misi</h1>
            <div class="space-x-4">
                <a href="dashboard.php" class="hover:text-turquoise-200">Dashboard</a>
                <a href="logout.php" class="hover:text-turquoise-200">Logout</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <?php if ($message): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <?php
                        if ($action === 'add') echo 'Tambah Visi/Misi Baru';
                        elseif ($action === 'edit') echo 'Edit Visi/Misi';
                        else echo 'Kelola Visi & Misi';
                        ?>
                    </h3>
                    <?php if ($action === 'list'): ?>
                    <a href="?action=add" class="bg-turquoise-600 text-white px-4 py-2 rounded-lg hover:bg-turquoise-700">
                        <i class="fas fa-plus"></i> Tambah Visi/Misi
                    </a>
                    <?php else: ?>
                    <a href="?action=list" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <?php endif; ?>
                </div>

                <?php if ($action === 'list'): ?>
                <!-- Vision Mission List -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php 
                    $visions = array_filter($vision_missions, function($vm) { return $vm['type'] === 'vision'; });
                    $missions = array_filter($vision_missions, function($vm) { return $vm['type'] === 'mission'; });
                    ?>
                    
                    <!-- Visi Section -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-blue-800 mb-4">
                            <i class="fas fa-eye mr-2"></i>Visi Perusahaan
                        </h4>
                        <?php foreach ($visions as $vision): ?>
                        <div class="bg-white rounded-lg p-4 mb-3 relative">
                            <p class="text-gray-700 mb-3"><?php echo nl2br(htmlspecialchars($vision['content'])); ?></p>
                            <div class="flex justify-end space-x-2">
                                <a href="?action=edit&id=<?php echo $vision['id']; ?>" class="text-turquoise-600 hover:text-turquoise-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?delete=<?php echo $vision['id']; ?>" class="text-red-600 hover:text-red-900"
                                   onclick="return confirm('Yakin ingin menghapus visi ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($visions)): ?>
                        <p class="text-gray-500 italic">Belum ada visi yang ditambahkan.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Misi Section -->
                    <div class="bg-green-50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-green-800 mb-4">
                            <i class="fas fa-target mr-2"></i>Misi Perusahaan
                        </h4>
                        <?php foreach ($missions as $mission): ?>
                        <div class="bg-white rounded-lg p-4 mb-3 relative">
                            <p class="text-gray-700 mb-3"><?php echo nl2br(htmlspecialchars($mission['content'])); ?></p>
                            <div class="flex justify-end space-x-2">
                                <a href="?action=edit&id=<?php echo $mission['id']; ?>" class="text-turquoise-600 hover:text-turquoise-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="?delete=<?php echo $mission['id']; ?>" class="text-red-600 hover:text-red-900"
                                   onclick="return confirm('Yakin ingin menghapus misi ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($missions)): ?>
                        <p class="text-gray-500 italic">Belum ada misi yang ditambahkan.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php elseif ($action === 'add' || $action === 'edit'): ?>
                <!-- Add/Edit Form -->
                <form method="POST" class="space-y-6">
                    <?php if ($action === 'edit'): ?>
                    <input type="hidden" name="id" value="<?php echo $vision_mission['id']; ?>">
                    <?php endif; ?>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Tipe</label>
                        <select name="type" id="type" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-turquoise-500 focus:border-turquoise-500">
                            <option value="vision" <?php echo ($action === 'edit' && $vision_mission['type'] === 'vision') ? 'selected' : ''; ?>>Visi</option>
                            <option value="mission" <?php echo ($action === 'edit' && $vision_mission['type'] === 'mission') ? 'selected' : ''; ?>>Misi</option>
                        </select>
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">Konten</label>
                        <textarea name="content" id="content" rows="6" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-turquoise-500 focus:border-turquoise-500"
                                  placeholder="Masukkan visi atau misi perusahaan..."><?php echo $action === 'edit' ? htmlspecialchars($vision_mission['content']) : ''; ?></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" name="<?php echo $action === 'edit' ? 'edit_vision_mission' : 'add_vision_mission'; ?>"
                                class="bg-turquoise-600 text-white px-4 py-2 rounded-lg hover:bg-turquoise-700">
                            <?php echo $action === 'edit' ? 'Update Visi/Misi' : 'Tambah Visi/Misi'; ?>
                        </button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>