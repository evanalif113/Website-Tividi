<?php

$active_page = 'gallery';
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
$action = $_GET['action'] ?? 'list'; // Default action is to show the list
$id = $_GET['id'] ?? null;

// Handle form submissions for ADD and EDIT
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['add_gallery'])) {
        $image_path = ''; // Mulai dengan path kosong
        $message = '';

        // Prioritas 1: Cek apakah ada file yang diupload
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {

            $upload_dir = '../images/gallery/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_info = pathinfo($_FILES['image_file']['name']);
            $file_extension = strtolower($file_info['extension']);
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($file_extension, $allowed_extensions)) {
                $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $upload_path)) {
                    // JIKA UPLOAD BERHASIL, GUNAKAN PATH INI
                    $image_path = 'images/gallery/' . $new_filename;
                } else {
                    $message = 'Error: Gagal memindahkan file yang diupload!';
                }
            } else {
                $message = 'Error: Format file tidak didukung!';
            }
        }
        // Prioritas 2: Jika tidak ada file diupload, baru gunakan path manual
        elseif (!empty($_POST['image_path'])) {
            $image_path = $_POST['image_path'];
        }

        // Lanjutkan untuk menyimpan ke database HANYA jika image_path sudah terisi
        if (!empty($image_path) && empty($message)) {
            $stmt = $db->prepare("INSERT INTO gallery (title, image_path, category, description, is_featured, is_hero) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['title'],
                $image_path, // Variabel ini sekarang berisi path yang benar
                $_POST['category'],
                $_POST['description'],
                isset($_POST['is_featured']) ? 1 : 0,
                isset($_POST['is_hero']) ? 1 : 0
            ]);
            header("Location: manage_gallery.php?message=add_success");
            exit();
        } else {
            // Jika tidak ada gambar sama sekali
            if (empty($message)) {
                $message = "Error: Anda harus upload file atau mengisi path manual.";
            }
        }
    }

    // --- HANDLE EDIT ---
    elseif (isset($_POST['edit_gallery'])) {
        $image_path = $_POST['current_image_path'];
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            // (Logika upload file untuk edit ada di sini)
            // ...
            $upload_dir = '../images/gallery/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            $file_extension = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($file_extension, $allowed_extensions)) {
                $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $upload_path)) {
                    $old_file = '../' . $_POST['current_image_path'];
                    if (file_exists($old_file)) unlink($old_file);
                    $image_path = 'images/gallery/' . $new_filename;
                } else {
                    $message = 'Error: Gagal mengupload file!';
                }
            } else {
                $message = 'Error: Format file tidak didukung!';
            }
        }

        if (empty($message)) {
            $stmt = $db->prepare("UPDATE gallery SET title=?, image_path=?, category=?, description=?, is_featured=?, is_hero=? WHERE id=?");
            $stmt->execute([
                $_POST['title'],
                $image_path,
                $_POST['category'],
                $_POST['description'],
                isset($_POST['is_featured']) ? 1 : 0,
                isset($_POST['is_hero']) ? 1 : 0,
                $_POST['id']
            ]);
            header("Location: manage_gallery.php?message=edit_success");
            exit();
        }
    }
}

// --- HANDLE DELETE ---
if (isset($_GET['delete'])) {
    $stmt = $db->prepare("SELECT image_path FROM gallery WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    if ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $file_path = '../' . $item['image_path'];
        if (file_exists($file_path)) unlink($file_path);

        $stmt = $db->prepare("DELETE FROM gallery WHERE id=?");
        $stmt->execute([$_GET['delete']]);
        header("Location: manage_gallery.php?message=delete_success");
        exit();
    }
}

// Set message from URL redirect
if (isset($_GET['message'])) {
    $messages = [
        'add_success' => 'Foto berhasil ditambahkan!',
        'edit_success' => 'Gallery berhasil diupdate!',
        'delete_success' => 'Foto berhasil dihapus!'
    ];
    $message = $messages[$_GET['message']] ?? '';
}

// --- DATA FETCHING FOR VIEWS ---
if ($action === 'list') {
    $stmt = $db->prepare("SELECT * FROM gallery ORDER BY created_at DESC");
    $stmt->execute();
    $gallery_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
if ($action === 'edit' && $id) {
    $stmt = $db->prepare("SELECT * FROM gallery WHERE id=?");
    $stmt->execute([$id]);
    $gallery_item = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$gallery_item) { // Jika ID tidak ditemukan, kembali ke list
        header('Location: manage_gallery.php');
        exit();
    }
}

require_once 'includes/header.php';
?>

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
                                <div class="flex items-center space-x-1">
                                    <?php if ($item['is_featured']): ?>
                                        <span class="text-yellow-500" title="Unggulan"><i class="fas fa-star"></i></span>
                                    <?php endif; ?>
                                    <?php if (!empty($item['is_hero'])): ?>
                                        <span class="text-indigo-600" title="Poster Hero"><i class="fas fa-crown"></i></span>
                                    <?php endif; ?>
                                </div>
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
            <form method="POST" enctype="multipart/form-data" class="space-y-8">
                <?php if ($action === 'edit'): ?>
                    <input type="hidden" name="id" value="<?php echo $gallery_item['id']; ?>">
                    <input type="hidden" name="current_image_path" value="<?php echo htmlspecialchars($gallery_item['image_path']); ?>">
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">Judul Foto</label>
                        <input type="text" name="title" id="title" required
                            value="<?php echo $action === 'edit' ? htmlspecialchars($gallery_item['title']) : ''; ?>"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-turquoise-500 focus:border-turquoise-500 px-3 py-2">
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                        <select name="category" id="category" required
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-turquoise-500 focus:border-turquoise-500 px-3 py-2">
                            <option value="" disabled <?php echo ($action === 'add') ? 'selected' : ''; ?>>Pilih kategori...</option>
                            <option value="interior" <?php echo ($action === 'edit' && $gallery_item['category'] === 'interior') ? 'selected' : ''; ?>>Interior</option>
                            <option value="exterior" <?php echo ($action === 'edit' && $gallery_item['category'] === 'exterior') ? 'selected' : ''; ?>>Exterior</option>
                            <option value="trip" <?php echo ($action === 'edit' && $gallery_item['category'] === 'trip') ? 'selected' : ''; ?>>Trip</option>
                            <option value="hero" <?php echo ($action === 'edit' && $gallery_item['category'] === 'hero') ? 'selected' : ''; ?>>Hero</option>
                        </select>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Upload Gambar</h3>
                    <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                        <?php if ($action === 'edit' && !empty($gallery_item['image_path'])): ?>
                            <div class="mb-4 md:mb-0">
                                <label class="block text-xs font-medium text-gray-700 mb-2">Gambar Saat Ini:</label>
                                <img src="../<?php echo htmlspecialchars($gallery_item['image_path']); ?>"
                                    alt="Current image" class="w-24 h-24 object-cover rounded-lg border">
                                <span class="block text-xs text-gray-500 mt-1"><?php echo htmlspecialchars($gallery_item['image_path']); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="flex-1">
                            <label for="image_file" class="block text-sm font-medium text-gray-700 mb-1">Upload File Baru</label>
                            <input type="file" name="image_file" id="image_file" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-turquoise-50 file:text-turquoise-700 hover:file:bg-turquoise-100">
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF, WebP. Maksimal 5MB.</p>
                        </div>
                    </div>
                    <div class="border-t pt-4 mt-4">
                        <label for="image_path" class="block text-xs font-medium text-gray-700 mb-1">Atau masukkan path manual:</label>
                        <input type="text" name="image_path" id="image_path"
                            value="<?php echo $action === 'edit' ? htmlspecialchars($gallery_item['image_path']) : ''; ?>"
                            class="block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-turquoise-500 focus:border-turquoise-500 px-3 py-2"
                            placeholder="images/gallery/foto-1.jpg">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika menggunakan upload file di atas.</p>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" id="description" rows="3"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-turquoise-500 focus:border-turquoise-500 px-3 py-2"
                        placeholder="Deskripsi foto..."><?php echo $action === 'edit' ? htmlspecialchars($gallery_item['description']) : ''; ?></textarea>
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:space-x-6">
                    <div class="flex items-center mb-2 md:mb-0">
                        <input type="checkbox" name="is_featured" id="is_featured"
                            <?php echo ($action === 'edit' && $gallery_item['is_featured']) ? 'checked' : ''; ?>
                            class="h-4 w-4 text-turquoise-600 focus:ring-turquoise-500 border-gray-300 rounded">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-900">Foto Unggulan</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_hero" id="is_hero"
                            <?php echo ($action === 'edit' && !empty($gallery_item['is_hero'])) ? 'checked' : ''; ?>
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_hero" class="ml-2 block text-sm text-gray-900">Poster Utama (Hero)</label>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" name="<?php echo $action === 'edit' ? 'edit_gallery' : 'add_gallery'; ?>"
                        class="bg-turquoise-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-turquoise-700 shadow">
                        <?php echo $action === 'edit' ? 'Update Gallery' : 'Upload Foto'; ?>
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

</div>
</div>
</body>

</html>