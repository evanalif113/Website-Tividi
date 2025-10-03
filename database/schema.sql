-- Database schema for Bus Tividi Pariwisata
CREATE DATABASE IF NOT EXISTS bustividipariwisata;
USE bustividipariwisata;



-- Gallery Table
CREATE TABLE gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    image_path VARCHAR(255) NOT NULL,
    category ENUM('interior', 'exterior', 'trip') DEFAULT 'interior',
    description TEXT,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);



-- Vision Mission Table
CREATE TABLE vision_mission (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type ENUM('vision', 'mission') NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);



-- Insert initial data


INSERT INTO gallery (title, image_path, category, description, is_featured) VALUES
('Interior Bus Premium', 'images/gallery/interior-1.jpg', 'interior', 'Kursi empuk dengan AC dan entertainment system', TRUE),
('Eksterior Bus Modern', 'images/gallery/exterior-1.jpg', 'exterior', 'Desain modern dengan cat yang menarik', TRUE),
('Perjalanan ke Bromo', 'images/gallery/trip-1.jpg', 'trip', 'Dokumentasi perjalanan wisata ke Gunung Bromo', FALSE);



INSERT INTO vision_mission (type, content) VALUES
('vision', 'Menjadi perusahaan transportasi pariwisata terdepan di Indonesia yang memberikan pelayanan terbaik dan pengalaman perjalanan yang tak terlupakan.'),
('mission', 'Memberikan layanan transportasi pariwisata yang aman, nyaman, dan terpercaya dengan harga yang kompetitif serta mengutamakan kepuasan pelanggan.');






