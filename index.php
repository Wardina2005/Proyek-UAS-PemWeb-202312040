<?php
require_once 'config/koneksi.php';
require_once 'config/assets.php';
require_once 'auth.php';

// Redirect jika sudah login
if (is_logged_in()) {
    if (is_admin()) {
        header('Location: ' . get_base_url() . '/admin/dashboard.php');
    } else {
        header('Location: ' . get_base_url() . '/user/dashboard.php');
    }
    exit;
}

$page_title = 'HiiStyle - Elegance in Every Detail';
$description = 'Platform e-commerce fashion terpercaya dengan koleksi eksklusif dan kualitas premium';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?= get_page_meta_tags($page_title, $description) ?>
    <?= get_css_includes() ?>
    <style>
        .hero-section {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.9), rgba(0, 0, 0, 0.7)), 
                        url('assets/img/wallpaper.png') no-repeat center center;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
            position: relative;
        }
        
        .hero-content {
            text-align: center;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            color: #d4af37;
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        
        .btn-hero {
            background: linear-gradient(45deg, #d4af37, #f4d03f);
            border: none;
            color: #000;
            font-weight: 600;
            padding: 15px 40px;
            font-size: 1.2rem;
            border-radius: 50px;
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(212, 175, 55, 0.4);
            color: #000;
        }
        
        .features-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }
        
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: #d4af37;
            margin-bottom: 20px;
        }
        
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: #d4af37 !important;
        }
        
        .btn-outline-gold {
            border: 2px solid #d4af37;
            color: #d4af37;
            font-weight: 600;
        }
        
        .btn-outline-gold:hover {
            background: #d4af37;
            color: #000;
        }
        
        .btn-gold {
            background: #d4af37;
            border: none;
            color: #000;
            font-weight: 600;
        }
        
        .btn-gold:hover {
            background: #b8962e;
            color: #fff;
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="<?= get_base_url() ?>">
                <i class="bi bi-gem me-2"></i>HiiStyle
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a href="#features" class="nav-link me-3">Fitur</a>
                    <a href="#about" class="nav-link me-3">Tentang</a>
                    <a href="login.php" class="btn btn-outline-gold me-2">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                    <a href="register.php" class="btn btn-gold">
                        <i class="bi bi-person-plus me-1"></i>Register
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <h1 class="hero-title">HiiStyle</h1>
                        <p class="hero-subtitle">Temukan Koleksi Fashion Terbaik dengan Gaya yang Tak Terbatas</p>
                        <p class="lead mb-4">Platform e-commerce fashion terpercaya dengan koleksi eksklusif dan kualitas premium</p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="login.php" class="btn btn-hero">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Mulai Berbelanja
                            </a>
                            <a href="register.php" class="btn btn-outline-light btn-lg">
                                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="display-4 fw-bold mb-3">Mengapa Memilih HiiStyle?</h2>
                    <p class="lead text-muted">Pengalaman berbelanja fashion yang tak terlupakan</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Kualitas Terjamin</h4>
                        <p class="text-muted">Semua produk telah melalui quality control ketat untuk memastikan kualitas terbaik</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Pengiriman Cepat</h4>
                        <p class="text-muted">Sistem pengiriman yang efisien dengan tracking real-time untuk kepuasan pelanggan</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Customer Service 24/7</h4>
                        <p class="text-muted">Tim support yang siap membantu Anda kapan saja dengan respon yang cepat</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Pembayaran Aman</h4>
                        <p class="text-muted">Berbagai metode pembayaran yang aman dan terpercaya untuk kemudahan transaksi</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Easy Return</h4>
                        <p class="text-muted">Kebijakan pengembalian yang mudah dan fleksibel untuk kepuasan berbelanja</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-star"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Koleksi Eksklusif</h4>
                        <p class="text-muted">Produk fashion terbaru dan eksklusif dari brand-brand ternama dunia</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 bg-dark text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-4">Tentang HiiStyle</h2>
                    <p class="lead mb-4">HiiStyle adalah platform e-commerce fashion yang menghadirkan koleksi terbaik dengan standar kualitas internasional.</p>
                    <p class="mb-4">Kami berkomitmen untuk memberikan pengalaman berbelanja yang luar biasa dengan produk-produk pilihan, layanan terbaik, dan kepuasan pelanggan sebagai prioritas utama.</p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-warning fw-bold">1000+</h3>
                                <p class="mb-0">Produk Tersedia</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-warning fw-bold">5000+</h3>
                                <p class="mb-0">Pelanggan Puas</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="bi bi-bag-heart display-1 text-warning"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-warning fw-bold">
                        <i class="bi bi-gem me-2"></i>HiiStyle
                    </h5>
                    <p class="mb-0">Platform fashion terpercaya untuk gaya hidup modern</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; <?= date('Y') ?> HiiStyle. All rights reserved.</p>
                    <p class="mb-0">
                        <small class="text-muted">
                            Powered by <span class="text-warning">HiiStyle Team</span>
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Smooth Scrolling -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

<?php echo generate_page_footer(); ?>
