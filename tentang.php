<?php
require_once 'config/database.php';

// Fetch data from database
try {
    $vision_stmt = $pdo->query("SELECT * FROM vision_mission WHERE type = 'vision' LIMIT 1");
    $vision = $vision_stmt->fetch(PDO::FETCH_ASSOC);
    if (!$vision) {
        $vision = ['content' => 'Menjadi perusahaan transportasi pariwisata terdepan di Indonesia yang memberikan pelayanan berkualitas tinggi dan pengalaman perjalanan yang tak terlupakan.'];
    }

    $mission_stmt = $pdo->query("SELECT * FROM vision_mission WHERE type = 'mission' LIMIT 1");
    $mission = $mission_stmt->fetch(PDO::FETCH_ASSOC);
    if (!$mission) {
        $mission = ['content' => 'Memberikan layanan transportasi pariwisata yang aman, nyaman, dan terpercaya dengan armada modern dan tenaga profesional yang berpengalaman.'];
    }


} catch(PDOException $e) {
    // Fallback data if database is not available

    $vision = ['content' => 'Menjadi perusahaan transportasi pariwisata terdepan di Indonesia yang memberikan pelayanan berkualitas tinggi dan pengalaman perjalanan yang tak terlupakan.'];
    $mission = ['content' => 'Memberikan layanan transportasi pariwisata yang aman, nyaman, dan terpercaya dengan armada modern dan tenaga profesional yang berpengalaman.'];

}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - <?php echo $company['name']; ?></title>
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
                    <a href="tentang.php" class="bg-gradient-to-br from-emerald-400 via-teal-500 to-cyan-400 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-emerald-500 hover:via-teal-600 hover:to-cyan-500 transition-all duration-300 shadow-lg transform hover:scale-105 ring-2 ring-white/20">Tentang</a>
                    <a href="fasilitas.php" class="text-gray-700 hover:text-emerald-600 px-3 py-2 text-sm font-medium transition-colors hover:bg-emerald-50 rounded-lg">Fasilitas</a>
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
                <a href="tentang.php" class="block px-3 py-2 bg-turquoise-600 text-white rounded-lg text-sm font-medium mx-3">Tentang</a>
                <a href="fasilitas.php" class="block px-3 py-2 text-gray-700 hover:text-turquoise-600 text-sm font-medium">Fasilitas</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-yellow-500 via-turquoise-500 to-orange-500 text-white pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">Tentang Kami</h1>
                <p class="text-xl md:text-2xl mb-8">Mengenal lebih dekat <?php echo isset($company['name']) ? $company['name'] : 'Bus Tividi Pariwisata'; ?></p>
            </div>
        </div>
    </section>

    <!-- Welcome Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">Selamat Datang di Tividi Bus Pariwisata </h2>
                <p class="text-lg text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    TIVIDI didirikan untuk memenuhi kebutuhan transportasi
yang andal dan nyaman. Kami melihat adanya
permintaan yang tinggi untuk layanan bus
pariwisata, antar-jemput, dan carter, dan kami
ingin menyediakan solusi yang berkualitas bagi
masyarakat. Tujuan utama kami adalah menjadi
penyedia layanan transportasi terkemuka yang
mengutamakan keselamatan dan kenyamanan
penumpang.
                </p>
                <p class="text-lg text-gray-600 max-w-4xl mx-auto leading-relaxed">
                    Sejak awal berdiri, kami terus mengembangkan armada untuk memenuhi
permintaan pasar yang terus meningkat. Perkembangan ini juga diikuti
dengan peningkatan kualitas layanan, yang menjadi salah satu pencapaian
penting bagi kami.
                </p>
            </div>

        </div>
    </section>

    <!-- Vision & Mission Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Visi & Misi</h2>
                <p class="text-lg text-gray-600">Komitmen kami untuk memberikan yang terbaik</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Vision -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-turquoise-600 text-white w-16 h-16 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-eye text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Visi</h3>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        <?php echo $vision['content']; ?>
                    </p>
                </div>

                <!-- Mission -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <div class="flex items-center mb-6">
                        <div class="bg-turquoise-600 text-white w-16 h-16 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-bullseye text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Misi</h3>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        <?php echo $mission['content']; ?>
                    </p>
                </div>
            </div>
        </div>
    </section>



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
</body>
</html>