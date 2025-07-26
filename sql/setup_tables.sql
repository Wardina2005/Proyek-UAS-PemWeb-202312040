-- CREATE DATABASE
CREATE DATABASE IF NOT EXISTS hii_style;
USE hii_style;

-- =============================================
-- HIISTYLE E-COMMERCE DATABASE STRUCTURE
-- Total 10 Tables untuk sistem e-commerce lengkap:
-- 1. user - Data pengguna utama
-- 2. kategori - Kategori produk fashion
-- 3. produk - Data produk fashion
-- 4. keranjang - Shopping cart
-- 5. transaksi - Data transaksi pembelian
-- 6. detail_transaksi - Detail item dalam transaksi
-- 7. ulasan - Review dan rating produk
-- 8. aktivitas_admin - Log aktivitas admin
-- 9. wishlist - Daftar favorit pengguna
-- 10. kupon - Sistem diskon dan promosi
-- =============================================

-- Tabel user
CREATE TABLE IF NOT EXISTS user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    email VARCHAR(100),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert data default untuk tabel user
INSERT INTO user (nama, username, password, email, role) VALUES
('Administrator', 'admin', 'admin', 'admin@hiistyle.com', 'admin'),
('User Demo', 'user', 'user', 'user@hiistyle.com', 'user');

-- Tabel kategori
CREATE TABLE IF NOT EXISTS kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100)
);

-- Tabel produk
CREATE TABLE IF NOT EXISTS produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(100),
    id_kategori INT,
    deskripsi TEXT,
    harga DECIMAL(10,2),
    stok INT,
    gambar VARCHAR(255),
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
);

-- Tabel keranjang
CREATE TABLE IF NOT EXISTS keranjang (
    id_keranjang INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    id_produk INT,
    jumlah INT,
    tanggal_ditambahkan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id_user),
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk)
);

-- Tabel transaksi
CREATE TABLE IF NOT EXISTS transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    total DECIMAL(10,2),
    status ENUM('pending', 'dibayar', 'dikirim', 'selesai') DEFAULT 'pending',
    tanggal_transaksi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id_user)
);

-- Tabel detail_transaksi
CREATE TABLE IF NOT EXISTS detail_transaksi (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT,
    id_produk INT,
    jumlah INT,
    subtotal DECIMAL(10,2),
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi),
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk)
);

-- Tabel ulasan
CREATE TABLE IF NOT EXISTS ulasan (
    id_ulasan INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    id_produk INT,
    isi TEXT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id_user),
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk)
);

-- Tabel aktivitas_admin
CREATE TABLE IF NOT EXISTS aktivitas_admin (
    id_aktivitas INT AUTO_INCREMENT PRIMARY KEY,
    id_admin INT,
    aktivitas TEXT,
    waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_admin) REFERENCES user(id_user)
);

-- Data kategori (sample)
INSERT INTO kategori (nama_kategori) VALUES
('Pakaian Pria'),
('Pakaian Wanita'),
('Aksesoris'),
('Sepatu'),
('Tas');

-- Data produk (sample) dengan URL gambar dari Google
INSERT INTO produk (nama_produk, id_kategori, deskripsi, harga, stok, gambar) VALUES
-- Pakaian Pria
('Kemeja Casual Pria', 1, 'Kemeja casual berkualitas tinggi dengan bahan cotton yang nyaman dipakai sehari-hari', 150000.00, 50, 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=500&h=500&fit=crop'),
('Polo Shirt Premium', 1, 'Polo shirt premium dengan bahan pique cotton yang breathable dan stylish', 120000.00, 45, 'https://images.unsplash.com/photo-1581803118522-7b72a50f7e9f?w=500&h=500&fit=crop'),
-- Pakaian Wanita
('Dress Wanita Elegan', 2, 'Dress elegan untuk acara formal dengan desain yang memukau', 250000.00, 30, 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=500&h=500&fit=crop'),
('Blouse Office Look', 2, 'Blouse formal yang perfect untuk office look yang professional', 165000.00, 25, 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=500&h=500&fit=crop'),
-- Aksesoris
('Jam Tangan Sport', 3, 'Jam tangan sport tahan air dengan fitur chronograph', 200000.00, 25, 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=500&h=500&fit=crop'),
('Kacamata Sunglasses Aviator', 3, 'Kacamata aviator classic dengan UV protection', 125000.00, 40, 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=500&h=500&fit=crop'),
-- Sepatu
('Sneakers Casual', 4, 'Sepatu sneakers nyaman untuk sehari-hari dengan sole yang empuk', 300000.00, 40, 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=500&h=500&fit=crop'),
('High Heels Elegant', 4, 'High heels elegant untuk acara formal dengan tinggi 7cm', 350000.00, 25, 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=500&h=500&fit=crop'),
-- Tas
('Tas Ransel Fashion', 5, 'Tas ransel trendy untuk kuliah dengan compartment laptop', 180000.00, 20, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500&h=500&fit=crop'),
('Handbag Leather Premium', 5, 'Handbag kulit premium dengan desain elegant untuk wanita', 520000.00, 15, 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=500&h=500&fit=crop');

-- Tabel wishlist untuk sistem favorit produk
CREATE TABLE IF NOT EXISTS wishlist (
    id_wishlist INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_produk INT NOT NULL,
    tanggal_ditambahkan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist (id_user, id_produk)
);

-- Tabel kupon untuk sistem diskon dan promosi
CREATE TABLE IF NOT EXISTS kupon (
    id_kupon INT AUTO_INCREMENT PRIMARY KEY,
    kode_kupon VARCHAR(50) UNIQUE NOT NULL,
    nama_kupon VARCHAR(100) NOT NULL,
    tipe_diskon ENUM('persen', 'nominal') DEFAULT 'persen',
    nilai_diskon DECIMAL(10,2) NOT NULL,
    minimum_belanja DECIMAL(10,2) DEFAULT 0,
    maksimal_diskon DECIMAL(10,2) DEFAULT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_berakhir DATE NOT NULL,
    kuota_penggunaan INT DEFAULT NULL,
    jumlah_digunakan INT DEFAULT 0,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Data ulasan (sample)
INSERT INTO ulasan (id_user, id_produk, isi, rating, tanggal) VALUES
(2, 1, 'Kemeja yang sangat nyaman dan berkualitas! Bahannya adem dan tidak mudah kusut.', 5, '2024-01-15 10:30:00'),
(2, 3, 'Dress ini sangat elegan, cocok untuk acara formal. Kualitas jahitannya rapi.', 5, '2024-01-18 14:20:00'),
(2, 5, 'Jam tangannya sporty dan tahan air. Sudah dipakai untuk olahraga dan masih bagus.', 4, '2024-01-20 16:45:00'),
(2, 7, 'Sneakers yang nyaman untuk jalan-jalan. Solnya empuk dan desainnya keren.', 4, '2024-01-22 09:15:00'),
(2, 9, 'Tas ranselnya spacious dan banyak kantong. Cocok untuk kuliah dan kerja.', 5, '2024-01-25 11:30:00'),
(2, 2, 'Polo shirt yang berkualitas premium. Bahannya breathable dan tidak gerah.', 4, '2024-01-28 13:20:00'),
(2, 4, 'Blouse yang perfect untuk office look. Modelnya professional dan elegan.', 5, '2024-02-01 15:10:00'),
(2, 6, 'Kacamata aviator yang classic. UV protectionnya bagus dan nyaman dipakai.', 4, '2024-02-03 08:45:00'),
(2, 8, 'High heels yang elegant tapi tetap nyaman. Tingginya pas dan tidak sakit.', 4, '2024-02-05 17:30:00'),
(2, 10, 'Handbag kulit premium yang berkualitas. Desainnya timeless dan elegan.', 5, '2024-02-08 12:15:00');

-- Data sample wishlist
INSERT INTO wishlist (id_user, id_produk, tanggal_ditambahkan) VALUES
(2, 1, '2024-01-10 08:30:00'),
(2, 3, '2024-01-12 14:20:00'),
(2, 5, '2024-01-15 16:45:00'),
(2, 7, '2024-01-18 09:15:00'),
(2, 9, '2024-01-20 11:30:00');

-- Data sample kupon
INSERT INTO kupon (kode_kupon, nama_kupon, tipe_diskon, nilai_diskon, minimum_belanja, maksimal_diskon, tanggal_mulai, tanggal_berakhir, kuota_penggunaan, status) VALUES
('WELCOME10', 'Diskon Selamat Datang', 'persen', 10.00, 100000.00, 50000.00, '2024-01-01', '2024-12-31', 100, 'aktif'),
('FASHION20', 'Diskon Fashion 20%', 'persen', 20.00, 200000.00, 100000.00, '2024-01-01', '2024-06-30', 50, 'aktif'),
('SAVE50K', 'Hemat 50 Ribu', 'nominal', 50000.00, 300000.00, NULL, '2024-02-01', '2024-04-30', 30, 'aktif'),
('NEWUSER15', 'Diskon User Baru 15%', 'persen', 15.00, 150000.00, 75000.00, '2024-01-01', '2024-12-31', NULL, 'aktif'),
('WEEKEND25', 'Weekend Sale 25%', 'persen', 25.00, 250000.00, 125000.00, '2024-03-01', '2024-03-31', 25, 'nonaktif');
