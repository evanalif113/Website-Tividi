<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $company['company_name'] ?? 'Bus Tividi Pariwisata'; ?> - Transportasi Pariwisata Terpercaya</title>
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
                    <a href="#home" class="bg-gradient-to-br from-emerald-400 via-teal-500 to-cyan-400 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-emerald-500 hover:via-teal-600 hover:to-cyan-500 transition-all duration-300 shadow-lg transform hover:scale-105 ring-2 ring-white/20">Beranda</a>
                    <a href="tentang.php" class="text-gray-700 hover:text-emerald-600 px-3 py-2 text-sm font-medium transition-colors hover:bg-emerald-50 rounded-lg">Tentang</a>
                    <a href="fasilitas.php" class="text-gray-700 hover:text-teal-600 px-3 py-2 text-sm font-medium transition-colors hover:bg-teal-50 rounded-lg">Fasilitas</a>
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
                <a href="indx.php" class="block px-3 py-2 bg-turquoise-600 text-white rounded-lg text-sm font-medium mx-3">Beranda</a>
                <a href="tentang.php" class="block px-3 py-2 text-gray-700 hover:text-turquoise-600 text-sm font-medium">Tentang</a>
                <a href="fasilitas.php" class="block px-3 py-2 text-gray-700 hover:text-turquoise-600 text-sm font-medium">Fasilitas</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <?php
    if ($hero_item && !empty($hero_item['image_path'])) {
        $hero_bg = "background-image: url('" . htmlspecialchars($hero_item['image_path']) . "');";
    } else {
        $hero_bg = "background-image: url('images/cover.png');";
    }
    ?>
    <section id="home" class="relative bg-contain bg-center bg-no-repeat text-white pt-16"
        style="<?php echo $hero_bg; ?> height: 600px; background-size: contain; background-color:rgba(240, 249, 255, 0);">
        <!-- Overlay untuk memastikan teks tetap terbaca -->
        <div class="absolute inset-0 bg-black bg-opacity-10"></div>

        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 flex items-center min-h-screen">
            <div class="text-center w-full">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 animate-fade-in drop-shadow-lg">
                    <?php echo $company['company_name'] ?? 'Tividi Bus Pariwisata'; ?>
                </h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-90 drop-shadow-md">
                    <?php echo $company['description'] ?? 'Transportasi Pariwisata Terpercaya untuk Perjalanan Wisata Anda'; ?>
                </p>
            </div>
        </div>
    </section>

    <!-- Company Profile Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Selamat Datang di Tividi Bus Pariwisata</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Tividi hadir untuk memberikan pengalaman perjalanan yang tak terlupakan dengan
                    layanan bus pariwisata terbaik di kelasnya. Armada kami dilengkapi dengan
                    fasilitas modern dan kenyamanan maksimal, mulai dari kursi yang luas dan empuk,
                    pendingin udara yang sejuk, hingga sistem hiburan yang membuat perjalanan
                    Anda semakin menyenangkan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-turquoise-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bus text-turquoise-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Armada Modern</h3>
                    <p class="text-gray-600">Bus dengan fasilitas lengkap dan terawat untuk kenyamanan perjalanan Anda.</p>
                </div>

                <div class="text-center">
                    <div class="bg-turquoise-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-turquoise-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Keamanan Terjamin</h3>
                    <p class="text-gray-600">Sopir berpengalaman dan sistem keamanan terdepan untuk perjalanan yang aman.</p>
                </div>

                <div class="text-center">
                    <div class="bg-turquoise-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-star text-turquoise-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Pelayanan Prima</h3>
                    <p class="text-gray-600">Layanan 24/7 dengan customer service yang responsif dan profesional.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Profile Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Video Profil Perusahaan</h2>
                <p class="text-lg text-gray-600 mb-8">Lihat bagaimana kami memberikan layanan terbaik</p>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="relative bg-black rounded-lg overflow-hidden shadow-2xl">
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe
                            class="w-full h-96 md:h-[500px]"
                            src="https://www.youtube.com/embed/Eec_ozXcj_E"
                            title="Video Profil Bus Tividi Pariwisata"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>

                <!-- Video Description -->
                <div class="mt-8 text-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Tentang Video Ini</h3>
                    <p class="text-gray-600 max-w-2xl mx-auto">
                        Video ini menampilkan profil lengkap Bus Tividi Pariwisata, mulai dari fasilitas interior yang nyaman,
                        eksterior yang elegan, hingga testimoni dari pelanggan yang puas dengan layanan kami.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Lokasi Kantor</h2>
                <p class="text-lg text-gray-600">Kunjungi kantor kami untuk konsultasi langsung</p>
            </div>

            <!-- Grid untuk dua lokasi -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <a href="https://www.google.com/maps/place/Bus+Efisiensi+Cabang+Yogyakarta/@-7.777902,110.0632325,49387m/data=!3m1!1e3!4m10!1m2!2m1!1stividi+trans+jogja!3m6!1s0x2e7ae47a08110a8f:0x69ddcda70cc5fc54!8m2!3d-7.8029718!4d110.3111333!15sChJ0aXZpZGkgdHJhbnMgam9namFaFCISdGl2aWRpIHRyYW5zIGpvZ2phkgEWdHJhbnNwb3J0YXRpb25fc2VydmljZZoBJENoZERTVWhOTUc5blMwVkpRMEZuU1VSQ2MyVnhRUzEzUlJBQqoBVgoNL2cvMTFoZDhoMG1kNhABKgoiBnRpdmlkaSgmMh8QASIbMyDeQoqBt0-mpjDfRuGl9Tr5pfm7oVNsN-uhMhYQAiISdGl2aWRpIHRyYW5zIGpvZ2ph4AEA-gEECAAQIQ!16s%2Fg%2F1pzq_c01j?hl=id&entry=ttu&g_ep=EgoyMDI1MTAwMS4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="block">
                    <div class="bg-gradient-to-br from-turquoise-400 via-turquoise-500 to-orange-400 rounded-xl h-80 flex items-center justify-center shadow-2xl transform hover:scale-[1.02] transition-all duration-300 cursor-pointer group overflow-hidden relative">
                        <!-- Animated background pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-transparent via-white to-transparent transform -skew-x-12 animate-pulse"></div>
                        </div>

                        <div class="text-center text-white relative z-10">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <i class="fas fa-map-marker-alt text-4xl text-white drop-shadow-lg animate-bounce"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-2 drop-shadow-lg">Peta Lokasi</h3>
                            <p class="text-lg mb-1 drop-shadow-md opacity-95">Reservasi Bus Tividi Jogja</p>
                            <p class="text-base mb-2 drop-shadow-md opacity-90">Jl. Raya Wates, Km. 6, Ambarketawang, Yogyakarta</p>
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1 inline-block mt-2 border border-white/30">
                                <p class="text-sm font-medium">
                                    <i class="fas fa-external-link-alt mr-2 animate-pulse"></i>Klik untuk membuka di Google Maps
                                </p>
                            </div>
                        </div>

                        <!-- Decorative elements -->
                        <div class="absolute top-4 right-4 w-8 h-8 bg-white/20 rounded-full animate-ping"></div>
                        <div class="absolute bottom-4 left-4 w-6 h-6 bg-white/15 rounded-full animate-pulse"></div>
                    </div>
                </a>
                <a href="https://www.google.com/maps/place/Agen+Reservasi+Bus+Efisiensi/@-7.6984108,109.6980174,17z/data=!4m10!1m2!2m1!1sAlamat+kantor+cabang+Kebumen+Jl+Wonosari+KM+6,+Kebumen,+Jawa+Tengah!3m6!1s0x2e7ac9f406a2a635:0x4c7fa75fb78799a0!8m2!3d-7.6988705!4d109.700336!15sCkNBbGFtYXQga2FudG9yIGNhYmFuZyBLZWJ1bWVuIEpsIFdvbm9zYXJpIEtNIDYsIEtlYnVtZW4sIEphd2EgVGVuZ2FoIgJIAVo8IjprYW50b3IgY2FiYW5nIGtlYnVtZW4gamwgd29ub3Nhcmkga20gNiBrZWJ1bWVuIGphd2EgdGVuZ2FokgEWdHJhbnNwb3J0YXRpb25fc2VydmljZZoBJENoZERTVWhOTUc5blMwVkpRMEZuU1VOR01tUlFUSGRuUlJBQqoBfxABKhkiFWthbnRvciBjYWJhbmcga2VidW1lbigmMiAQASIcNjK-BVB1B57AiK-0WMp6bXEHcc7cnAJ5H96bpTI-EAIiOmthbnRvciBjYWJhbmcga2VidW1lbiBqbCB3b25vc2FyaSBrbSA2IGtlYnVtZW4gamF3YSB0ZW5nYWjgAQD6AQQIABA6!16s%2Fg%2F11b6d2nsxf?entry=ttu&g_ep=EgoyMDI1MDkwMy4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="block">
                    <div class="bg-gradient-to-br from-turquoise-400 via-turquoise-500 to-orange-400 rounded-xl h-80 flex items-center justify-center shadow-2xl transform hover:scale-[1.02] transition-all duration-300 cursor-pointer group overflow-hidden relative">
                        <!-- Animated background pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-transparent via-white to-transparent transform -skew-x-12 animate-pulse"></div>
                        </div>

                        <div class="text-center text-white relative z-10">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <i class="fas fa-map-marker-alt text-4xl text-white drop-shadow-lg animate-bounce"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-2 drop-shadow-lg">Peta Lokasi</h3>
                            <p class="text-lg mb-1 drop-shadow-md opacity-95">Rest Area Tividi Kebumen</p>
                            <p class="text-base mb-2 drop-shadow-md opacity-90">JL. Raya Kutoarjo Km. 6, Jatisari Kebumen</p>
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1 inline-block mt-2 border border-white/30">
                                <p class="text-sm font-medium">
                                    <i class="fas fa-external-link-alt mr-2 animate-pulse"></i>Klik untuk membuka di Google Maps
                                </p>
                            </div>
                        </div>

                        <!-- Decorative elements -->
                        <div class="absolute top-4 right-4 w-8 h-8 bg-white/20 rounded-full animate-ping"></div>
                        <div class="absolute bottom-4 left-4 w-6 h-6 bg-white/15 rounded-full animate-pulse"></div>
                    </div>
                </a>
                <a href="https://www.google.com/maps/dir/-7.6763173,109.6702356/Pool+Efisiensi+Cilacap,+Jl.+Perintis+Kemerdekaan+No.52,+Rejanegara,+Gumilir,+Kec.+Cilacap+Utara,+Kabupaten+Cilacap,+Jawa+Tengah+53231/@-7.6435033,108.6942412,9z/data=!3m1!4b1!4m9!4m8!1m1!4e1!1m5!1m1!1s0x2e656d2e97f6c849:0x30111e5caafe7c8e!2m2!1d109.0377665!2d-7.6887698?entry=ttu&g_ep=EgoyMDI1MDkwMy4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="block">
                    <div class="bg-gradient-to-br from-turquoise-400 via-turquoise-500 to-orange-400 rounded-xl h-80 flex items-center justify-center shadow-2xl transform hover:scale-[1.02] transition-all duration-300 cursor-pointer group overflow-hidden relative">
                        <!-- Animated background pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-transparent via-white to-transparent transform -skew-x-12 animate-pulse"></div>
                        </div>

                        <div class="text-center text-white relative z-10">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <i class="fas fa-map-marker-alt text-4xl text-white drop-shadow-lg animate-bounce"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-2 drop-shadow-lg">Peta Lokasi</h3>
                            <p class="text-lg mb-1 drop-shadow-md opacity-95">Rest Area Bus Cilacap</p>
                            <p class="text-base mb-2 drop-shadow-md opacity-90">JL. Raya Perintis Kemerdekaan No.52, Rejanegara, Cilacap</p>
                            <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1 inline-block mt-2 border border-white/30">
                                <p class="text-sm font-medium">
                                    <i class="fas fa-external-link-alt mr-2 animate-pulse"></i>Klik untuk membuka di Google Maps
                                </p>
                            </div>
                        </div>

                        <!-- Decorative elements -->
                        <div class="absolute top-4 right-4 w-8 h-8 bg-white/20 rounded-full animate-ping"></div>
                        <div class="absolute bottom-4 left-4 w-6 h-6 bg-white/15 rounded-full animate-pulse"></div>
                    </div>
                </a>
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
                        <a href="http://www.youtube.com/@BUSEFISIENSIOFFICIAL" class="flex items-center space-x-3 text-gray-300 hover:text-emerald-400 transition-colors">
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