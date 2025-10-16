<?php
// Set header agar browser tahu bahwa output adalah XML
header('Content-type: application/xml; charset=utf-8');

// Definisikan URL dasar situs Anda
$base_url = 'https://tividitranswisata.web.id/';

// Mulai membuat struktur XML
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// Halaman Beranda
echo '<url>';
echo '<loc>' . $base_url . '</loc>';
echo '<lastmod>' . date('Y-m-d') . '</lastmod>'; // Tanggal hari ini
echo '<changefreq>daily</changefreq>'; // Seberapa sering halaman berubah
echo '<priority>1.0</priority>'; // Prioritas relatif
echo '</url>';

// Halaman Kontak
echo '<url>';
echo '<loc>' . $base_url . '/kontak</loc>';
echo '<lastmod>' . date('Y-m-d') . '</lastmod>';
echo '<changefreq>monthly</changefreq>';
echo '<priority>0.8</priority>';
echo '</url>';

echo '</urlset>';

?>