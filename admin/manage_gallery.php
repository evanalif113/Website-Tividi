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
    if (isset($_POST['add_gallery'])) {
        $image_path = '';
        
        // Handle file upload
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../images/gallery/';
            
            // Create directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($file_extension, $allowed_extensions)) {
                $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $upload_path)) {
                    $image_path = 'images/gallery/' . $new_filename;
                } else {
                    $message = 'Error: Gagal mengupload file!';
                }
            } else {
                $message = 'Error: Format file tidak didukung! Gunakan JPG, PNG, GIF, atau WebP.';
            }
        } else {
            // Use manual path if no file uploaded
            $image_path = $_POST['image_path'];
        }
        
        if ($image_path && empty($message)) {
            $stmt = $db->prepare("INSERT INTO gallery (title, image_path, category, description, is_featured) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['title'],
                $image_path,
                $_POST['category'],
                $_POST['description'],
                isset($_POST['is_featured']) ? 1 : 0
            ]);
            $message = 'Foto berhasil ditambahkan ke gallery!';
            $action = 'list';
        }
    } elseif (isset($_POST['edit_gallery'])) {
        $image_path = $_POST['current_image_path']; // Keep current image by default
        
        // Handle file upload for edit
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../images/gallery/';
            
            // Create directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($file_extension, $allowed_extensions)) {
                $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $upload_path)) {
                    // Delete old file if it exists
                    $old_file = '../' . $_POST['current_image_path'];
                    if (file_exists($old_file) && strpos($_POST['current_image_path'], 'images/gallery/') === 0) {
                        unlink($old_file);
                    }
                    $image_path = 'images/gallery/' . $new_filename;
                } else {
                    $message = 'Error: Gagal mengupload file!';
                }
            } else {
                $message = 'Error: Format file tidak didukung! Gunakan JPG, PNG, GIF, atau WebP.';
            }
        } elseif (!empty($_POST['image_path']) && $_POST['image_path'] !== $_POST['current_image_path']) {
            // Use manual path if provided and different from current
            $image_path = $_POST['image_path'];
        }
        
        if (empty($message)) {
            $stmt = $db->prepare("UPDATE gallery SET title=?, image_path=?, category=?, description=?, is_featured=? WHERE id=?");
            $stmt->execute([
                $_POST['title'],
                $image_path,
                $_POST['category'],
                $_POST['description'],
                isset($_POST['is_featured']) ? 1 : 0,
                $_POST['id']
            ]);
            $message = 'Gallery berhasil diupdate!';
            $action = 'list';
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    // Get image path before deleting from database
    $stmt = $db->prepare("SELECT image_path FROM gallery WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($item) {
        // Delete file if it exists in gallery folder
        $file_path = '../' . $item['image_path'];
        if (file_exists($file_path) && strpos($item['image_path'], 'images/gallery/') === 0) {
            unlink($file_path);
        }
        
        // Delete from database
        $stmt = $db->prepare("DELETE FROM gallery WHERE id=?");
        $stmt->execute([$_GET['delete']]);
        $message = 'Foto berhasil dihapus dari gallery!';
    }
    $action = 'list';
}

// Get gallery items for listing
if ($action === 'list') {
    $stmt = $db->prepare("SELECT * FROM gallery ORDER BY created_at DESC");
    $stmt->execute();
    $gallery_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get single gallery item for editing
if ($action === 'edit' && $id) {
    $stmt = $db->prepare("SELECT * FROM gallery WHERE id=?");
    $stmt->execute([$id]);
    $gallery_item = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Gallery - Admin Bus Tividi</title>
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
            <h1 class="text-xl font-bold">Admin Panel - Kelola Fasilitas</h1>
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
                        if ($action === 'add') echo 'Upload Foto Baru';
                        elseif ($action === 'edit') echo 'Edit Gallery';
                        else echo 'Kelola Gallery';
                        ?>
                    </h3>
                    <?php if ($action === 'list'): ?>
                    <a href="?action=add" class="bg-turquoise-600 text-white px-4 py-2 rounded-lg hover:bg-turquoise-700">
                        <i class="fas fa-plus"></i> Upload Foto
                    </a>
                    <?php else: ?>
                    <a href="?action=list" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <?php endif; ?>
                </div>

                <?php if ($action === 'list'): ?>
                <!-- Gallery Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php foreach ($gallery_items as $item): ?>
                    <div class="bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="h-48 overflow-hidden">
                            <?php if (!empty($item['image_path']) && file_exists('../' . $item['image_path'])): ?>
                                <img src="../<?php echo htmlspecialchars($item['image_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['title']); ?>" 
                                     class="w-full h-full object-cover hover:scale-105 transition duration-300">
                            <?php else: ?>
                                <div class="h-full bg-gradient-to-r from-turquoise-400 to-turquoise-600 flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <i class="fas fa-image text-4xl mb-2"></i>
                                        <p class="text-sm">No Image</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    <?php
                                    if ($item['category'] === 'interior') echo 'bg-blue-100 text-blue-800';
                                    elseif ($item['category'] === 'exterior') echo 'bg-green-100 text-green-800';
                                    else echo 'bg-purple-100 text-purple-800';
                                    ?>">
                                    <?php echo ucfirst($item['category']); ?>
                                </span>
                                <?php if ($item['is_featured']): ?>
                                <span class="text-yellow-500"><i class="fas fa-star"></i></span>
                                <?php endif; ?>
                            </div>
                            <h4 class="font-medium text-gray-900 mb-1"><?php echo htmlspecialchars($item['title']); ?></h4>
                            <p class="text-sm text-gray-600 mb-3"><?php echo htmlspecialchars(substr($item['description'], 0, 50)) . '...'; ?></p>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500"><?php echo date('d M Y', strtotime($item['created_at'])); ?></span>
                                <div class="flex space-x-2">
                                    <a href="?action=edit&id=<?php echo $item['id']; ?>" class="text-turquoise-600 hover:text-turquoise-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $item['id']; ?>" class="text-red-600 hover:text-red-900"
                                       onclick="return confirm('Yakin ingin menghapus foto ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php elseif ($action === 'add' || $action === 'edit'): ?>
                <!-- Add/Edit Form -->
                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <?php if ($action === 'edit'): ?>
                    <input type="hidden" name="id" value="<?php echo $gallery_item['id']; ?>">
                    <input type="hidden" name="current_image_path" value="<?php echo htmlspecialchars($gallery_item['image_path']); ?>">
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Foto</label>
                            <input type="text" name="title" id="title" required
                                   value="<?php echo $action === 'edit' ? htmlspecialchars($gallery_item['title']) : ''; ?>"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-turquoise-500 focus:border-turquoise-500">
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category" id="category" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-turquoise-500 focus:border-turquoise-500">
                                <option value="interior" <?php echo ($action === 'edit' && $gallery_item['category'] === 'interior') ? 'selected' : ''; ?>>Interior</option>
                                <option value="exterior" <?php echo ($action === 'edit' && $gallery_item['category'] === 'exterior') ? 'selected' : ''; ?>>Exterior</option>
                                <option value="trip" <?php echo ($action === 'edit' && $gallery_item['category'] === 'trip') ? 'selected' : ''; ?>>Trip</option>
                            </select>
                        </div>
                    </div>

                    <!-- File Upload Section -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Gambar</h3>
                        
                        <?php if ($action === 'edit' && !empty($gallery_item['image_path'])): ?>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini:</label>
                            <div class="flex items-center space-x-4">
                                <img src="../<?php echo htmlspecialchars($gallery_item['image_path']); ?>" 
                                     alt="Current image" class="w-20 h-20 object-cover rounded-lg border">
                                <span class="text-sm text-gray-600"><?php echo htmlspecialchars($gallery_item['image_path']); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <label for="image_file" class="block text-sm font-medium text-gray-700">Upload File Baru</label>
                            <input type="file" name="image_file" id="image_file" accept="image/*"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-turquoise-50 file:text-turquoise-700 hover:file:bg-turquoise-100">
                            <p class="mt-1 text-sm text-gray-500">Format yang didukung: JPG, PNG, GIF, WebP (Max: 5MB)</p>
                        </div>
                        
                        <div class="border-t pt-4">
                            <p class="text-sm text-gray-600 mb-2">Atau masukkan path manual:</p>
                            <input type="text" name="image_path" id="image_path"
                                   value="<?php echo $action === 'edit' ? htmlspecialchars($gallery_item['image_path']) : ''; ?>"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-turquoise-500 focus:border-turquoise-500"
                                   placeholder="images/gallery/foto-1.jpg">
                            <p class="mt-1 text-sm text-gray-500">Kosongkan jika menggunakan upload file di atas</p>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-turquoise-500 focus:border-turquoise-500"
                                  placeholder="Deskripsi foto..."><?php echo $action === 'edit' ? htmlspecialchars($gallery_item['description']) : ''; ?></textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured"
                               <?php echo ($action === 'edit' && $gallery_item['is_featured']) ? 'checked' : ''; ?>
                               class="h-4 w-4 text-turquoise-600 focus:ring-turquoise-500 border-gray-300 rounded">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-900">Tampilkan sebagai foto unggulan</label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" name="<?php echo $action === 'edit' ? 'edit_gallery' : 'add_gallery'; ?>"
                                class="bg-turquoise-600 text-white px-4 py-2 rounded-lg hover:bg-turquoise-700">
                            <?php echo $action === 'edit' ? 'Update Gallery' : 'Upload Foto'; ?>
                        </button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>