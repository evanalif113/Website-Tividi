<?php
require_once 'config/database.php';

// Fetch data from database
try {
    $gallery_stmt = $pdo->query("SELECT * FROM gallery ORDER BY category, id ASC");
    $gallery_items = $gallery_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $gallery_items = [];
}

// Filter: exclude items with is_hero = 1
$gallery_items = array_filter($gallery_items, function ($item) {
    return empty($item['is_hero']) || $item['is_hero'] == 0;
});

// Validate and clean gallery items
$gallery_items = array_map(function ($item) {
    return [
        'id' => $item['id'] ?? 0,
        'title' => $item['title'] ?? '',
        'image_url' => $item['image_path'] ?? '',
        'category' => $item['category'] ?? '',
        'description' => $item['description'] ?? '',
        'is_featured' => $item['is_featured'] ?? 0,
        'is_hero' => $item['is_hero'] ?? 0,
    ];
}, $gallery_items);

// Separate items by category
$interior_items = array_filter($gallery_items, function ($item) {
    return $item['category'] === 'interior';
});
$exterior_items = array_filter($gallery_items, function ($item) {
    return $item['category'] === 'exterior';
});

// Ambil foto hero (is_hero = 1)
$hero_stmt = $pdo->query("SELECT * FROM gallery WHERE is_hero = 1 ORDER BY id DESC LIMIT 1");
$hero_item = $hero_stmt->fetch(PDO::FETCH_ASSOC);

// Gallery unggulan (hanya is_featured = 1)
$featured_items = array_filter($gallery_items, function ($item) {
    return !empty($item['is_featured']) && $item['is_featured'] == 1;
});
$additional_items = array_filter($gallery_items, function ($item) {
    return empty($item['is_featured']) || $item['is_featured'] == 0;
});
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fasilitas - <?php echo $company['name']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- SwiperJS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
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
    <style>
        /* Perkecil ukuran dan ubah warna panah Swiper */
        .swiper-button-next,
        .swiper-button-prev {
            color: #14b8a6;
            /* turquoise-500 */
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(20, 184, 166, 0.15);
            transition: background 0.2s;
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 20px !important;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: #ccfbf1;
            /* turquoise-100 */
        }
    </style>
</head>

<body class="bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <img src="images/logo.png" alt="TIVIDI Bus Pariwisata" class="h-14 w-auto">
                    </div>
                </div>


                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="index.php" class="text-gray-700 hover:text-emerald-600 px-3 py-2 text-sm font-medium transition-colors hover:bg-emerald-50 rounded-lg">Beranda</a>
                    <a href="tentang.php" class="text-gray-700 hover:text-emerald-600 px-3 py-2 text-sm font-medium transition-colors hover:bg-emerald-50 rounded-lg">Tentang</a>
                    <a href="fasilitas.php" class="bg-gradient-to-br from-emerald-400 via-teal-500 to-cyan-400 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-emerald-500 hover:via-teal-600 hover:to-cyan-500 transition-all duration-300 shadow-lg transform hover:scale-105 ring-2 ring-white/20">Fasilitas</a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-turquoise-600 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="index.php" class="block px-3 py-2 text-gray-700 hover:text-turquoise-600 text-sm font-medium">Beranda</a>
                <a href="tentang.php" class="block px-3 py-2 text-gray-700 hover:text-turquoise-600 text-sm font-medium">Tentang</a>
                <a href="fasilitas.php" class="block px-3 py-2 bg-turquoise-600 text-white rounded-lg text-sm font-medium mx-3">Fasilitas</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <?php
    if ($hero_item && !empty($hero_item['image_path'])) {
        $hero_bg = "background-image: url('" . htmlspecialchars($hero_item['image_path']) . "');";
    } else {
        $hero_bg = "background: #fde047;";
    }
    ?>
    <section class="relative bg-contain bg-center bg-no-repeat text-white pt-16"
        style="<?php echo $hero_bg; ?> height: 600px; background-size: contain; background-color:rgba(240,249,255,0);">
        <!-- Overlay agar teks tetap terbaca -->
        <div class="absolute inset-0 bg-black bg-opacity-10"></div>

        <!-- Content -->
        <!-- <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 flex items-center min-h-[400px] min-h-screen">
            <div class="text-center w-full"></div>
                <h1 class="text-4xl md:text-6xl font-bold mb-6">Fasilitas Bus</h1>
                <p class="text-xl md:text-2xl mb-8">Kenyamanan dan kemewahan dalam setiap perjalanan</p>
            </div>
        </div> -->
    </section>

    <!-- Welcome Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-4">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">Fasilitas Bus</h1>
                <p class="text-xl md:text-2xl mb-2">Kenyamanan dan kemewahan dalam setiap perjalanan</p>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Bus Pariwisata Tividibukan hanya sekadar transportasi,
                    tapi juga partner perjalanan yang membuat setiap momen wisata
                    Anda menjadi lebih menyenangkan dan tak terlupakan.
                    Kami menawarkan berbagai fasilitas untuk menunjang keamanan dan
                    kenyamanan pada perjalanan Anda,seperti ;
                </p>
            </div>

        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Gallery Bus</h2>
                <p class="text-lg text-gray-600">Lihat koleksi foto interior dan eksterior bus kami</p>
            </div>

            <!-- Gallery Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="gallery-grid">
                <?php foreach ($featured_items as $item): ?>
                    <div class="gallery-item bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-2" data-category="<?php echo $item['category']; ?>">
                        <div class="h-64 overflow-hidden cursor-pointer" onclick="openModal(<?php echo htmlspecialchars(json_encode($item['image_url']), ENT_QUOTES); ?>, <?php echo htmlspecialchars(json_encode($item['title']), ENT_QUOTES); ?>, <?php echo htmlspecialchars(json_encode($item['description']), ENT_QUOTES); ?>)">
                            <?php if (!empty($item['image_url']) && file_exists($item['image_url'])): ?>
                                <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['title']; ?>" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                            <?php else: ?>
                                <div class="h-full bg-gradient-to-br from-turquoise-100 to-turquoise-600 flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <i class="fas fa-image text-6xl mb-4"></i>
                                        <p class="text-lg font-semibold"><?php echo $item['title']; ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2"><?php echo $item['title']; ?></h3>
                            <p class="text-gray-600 mb-4"><?php echo $item['description']; ?></p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-turquoise-600 font-semibold capitalize"><?php echo $item['category']; ?></span>
                                <button class="text-turquoise-600 hover:text-turquoise-700" onclick="openModal(<?php echo htmlspecialchars(json_encode($item['image_url']), ENT_QUOTES); ?>, <?php echo htmlspecialchars(json_encode($item['title']), ENT_QUOTES); ?>, <?php echo htmlspecialchars(json_encode($item['description']), ENT_QUOTES); ?>)">
                                    <i class="fas fa-expand-alt"></i> Lihat
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Interior Features Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Interior Bus Premium</h2>
                <p class="text-lg text-gray-600">Desain interior yang elegan dan fungsional</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Kenyamanan Maksimal</h3>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="bg-turquoise-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">Kursi Ergonomis</h4>
                                <p class="text-gray-600">Kursi dengan desain ergonomis yang dapat direbahkan hingga 160 derajat untuk kenyamanan maksimal selama perjalanan panjang.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-turquoise-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">Ruang Kaki Luas</h4>
                                <p class="text-gray-600">Jarak antar kursi yang luas memberikan ruang gerak yang nyaman untuk kaki dan memungkinkan Anda untuk rileks.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-turquoise-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-4 mt-1">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">Pencahayaan LED</h4>
                                <p class="text-gray-600">Sistem pencahayaan LED yang dapat diatur memberikan suasana yang nyaman dan hemat energi.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative rounded-lg overflow-hidden shadow-lg">
                    <!-- Swiper -->
                    <div class="swiper interior-swiper h-96">
                        <div class="swiper-wrapper">
                            <?php foreach ($interior_items as $item): ?>
                                <?php if (!empty($item['image_url']) && file_exists($item['image_url'])): ?>
                                    <div class="swiper-slide">
                                        <img src="<?php echo $item['image_url']; ?>" alt="Interior Bus" class="w-full h-96 object-cover" />
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <!-- Navigasi panah -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <!-- Pagination bulat -->
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fasilitas Lainnya Section -->
    <section class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4 flex items-center justify-center">
                    <span class="mr-2 text-turquoise-300 text-3xl"></span> Fasilitas lainnya
                </h2>
                <p class="text-lg text-gray-600">
                    Selain fasilitas utama, kami juga menyediakan berbagai fasilitas tambahan untuk menunjang kenyamanan dan kebutuhan Anda selama perjalanan. Setiap detail dirancang agar perjalanan Anda semakin menyenangkan dan praktis.
                </p>
            </div>
            <div class="flex flex-wrap justify-center gap-6">
                <?php foreach ($additional_items as $item): ?>
                    <?php if (!empty($item['image_url']) && file_exists($item['image_url'])): ?>
                        <div class="rounded-xl overflow-hidden shadow-md bg-white" style="width:180px; height:180px;">
                            <img src="<?php echo $item['image_url']; ?>" alt="Fasilitas Tambahan" class="w-full h-full object-cover" />
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Safety Features Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Fitur Keamanan</h2>
                <p class="text-lg text-gray-600">Keselamatan adalah prioritas utama kami</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-red-500 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-fire-extinguisher text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">APAR</h3>
                    <p class="text-gray-600 text-sm">Alat Pemadam Api Ringan tersedia di setiap bus</p>
                </div>
                <div class="text-center">
                    <div class="bg-yellow-500 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hammer text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Emergency Hammer</h3>
                    <p class="text-gray-600 text-sm">Palu darurat untuk memecah kaca dalam situasi emergency</p>
                </div>
                <div class="text-center">
                    <div class="bg-green-500 text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-first-aid text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">P3K</h3>
                    <p class="text-gray-600 text-sm">Kotak P3K lengkap untuk pertolongan pertama</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
        <div class="max-w-4xl mx-auto p-4">
            <div class="bg-white rounded-lg overflow-hidden">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 id="modalTitle" class="text-xl font-semibold"></h3>
                    <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                <div class="p-4">
                    <div id="modalImage" class="mb-4">
                        <!-- Gambar akan diisi oleh JavaScript -->
                    </div>
                    <p id="modalDescription" class="text-gray-600"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-20">
                <!-- Company Info -->
                <div>
                    <img src="images/logo.png" alt="TIVIDI Bus Pariwisata" class="h-16 w-auto mb-4">
                    <p class="text-gray-300 mb-4"><?php echo $company['description'] ?? 'Transportasi pariwisata terpercaya dengan layanan berkualitas tinggi'; ?></p>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-emerald-400">Informasi Kontak</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-emerald-400 mr-3"></i>
                            <span>+62 812-3456-7890</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fab fa-whatsapp text-emerald-400 mr-3"></i>
                            <span>+62 812-3456-7890</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-emerald-400 mr-3"></i>
                            <span>info@bustividipariwisata.com</span>
                        </div>
                    </div>
                </div>



                <!-- Social Media -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-emerald-400">Media Sosial Kami</h4>
                    <div class="space-y-3">
                        <a href="#" class="flex items-center space-x-3 text-gray-300 hover:text-emerald-400 transition-colors">
                            <i class="fab fa-facebook-f text-emerald-400"></i>
                            <span>Facebook</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 text-gray-300 hover:text-emerald-400 transition-colors">
                            <i class="fab fa-instagram text-emerald-400"></i>
                            <span>Instagram</span>
                        </a>
                        <a href="https://www.youtube.com/@BUSEFISIENSIOFFICIAL" class="flex items-center space-x-3 text-gray-300 hover:text-emerald-400 transition-colors">
                            <i class="fab fa-youtube text-emerald-400"></i>
                            <span>Youtube</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-300">&copy; <?php echo date('Y'); ?> <?php echo $company['company_name'] ?? 'Bus Tividi Pariwisata'; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        // Inisialisasi Swiper untuk interior
        var interiorSwiper = new Swiper('.interior-swiper', {
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            slidesPerView: 1,
            spaceBetween: 0,
        });

        // Gallery filtering functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const galleryItems = document.querySelectorAll('.gallery-item');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');

                    // Update active button
                    filterButtons.forEach(btn => {
                        btn.classList.remove('active', 'bg-turquoise-600', 'text-white');
                        btn.classList.add('bg-white', 'text-turquoise-600', 'border-2', 'border-turquoise-600');
                    });

                    this.classList.add('active', 'bg-turquoise-600', 'text-white');
                    this.classList.remove('bg-white', 'text-turquoise-600', 'border-2', 'border-turquoise-600');

                    // Filter gallery items
                    galleryItems.forEach(item => {
                        if (filter === 'all' || item.getAttribute('data-category') === filter) {
                            item.style.display = 'block';
                            item.classList.add('animate-fade-in');
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });

        // Modal functionality
        function openModal(imageUrl, title, description) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalDescription').textContent = description;

            const modalImage = document.getElementById('modalImage');
            if (imageUrl && imageUrl !== 'placeholder.jpg') {
                modalImage.innerHTML = `<img src="${imageUrl}" alt="${title}" class="w-full h-96 object-cover rounded">`;
            } else {
                modalImage.innerHTML = `
                    <div class="h-96 bg-gradient-to-br from-turquoise-100 to-turquoise-600 flex items-center justify-center rounded">
                        <div class="text-center text-white">
                            <i class="fas fa-image text-8xl mb-4"></i>
                            <p class="text-xl font-semibold">Preview Image</p>
                        </div>
                    </div>
                `;
            }

            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Add transition styles
        document.addEventListener('DOMContentLoaded', function() {
            const items = document.querySelectorAll('.gallery-item');
            items.forEach(item => {
                item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                item.style.opacity = '1';
                item.style.transform = 'scale(1)';
            });
        });
    </script>
</body>

</html>