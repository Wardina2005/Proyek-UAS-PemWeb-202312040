<?php 
require_once __DIR__ . '/../auth.php';
require_login(); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HiiStyle - <?= $page_title ?? 'Dashboard' ?></title>
    
    <!-- Meta tags for JavaScript -->
    <meta name="base-url" content="<?= get_base_url() ?>">
    <?php if (isset($_SESSION['nama'])): ?>
    <meta name="<?= is_admin() ? 'admin' : 'user' ?>-name" content="<?= htmlspecialchars($_SESSION['nama']) ?>">
    <?php endif; ?>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= get_base_url() ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= get_base_url() ?>/assets/css/responsive.css">
    <link rel="stylesheet" href="<?= get_base_url() ?>/assets/css/color-theme.css">
    
    <?php if (is_admin()): ?>
    <link rel="stylesheet" href="<?= get_base_url() ?>/assets/css/admin.css">
    <link rel="stylesheet" href="<?= get_base_url() ?>/assets/css/hamburger-admin.css">
    <?php else: ?>
    <link rel="stylesheet" href="<?= get_base_url() ?>/assets/css/hamburger-user.css">
    <?php endif; ?>
    
    <style>
        /* Hide default navbar for hamburger implementation */
        .navbar {
            display: none !important;
        }
        
        /* Modern page styling */
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1C1C28 0%, #181823 100%);
            color: #FFFFFF;
            line-height: 1.6;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        /* Add body class for page identification */
        body.admin-page {
            background: linear-gradient(135deg, #1C1C28 0%, #181823 100%);
        }
        
        body.user-page {
            background: linear-gradient(135deg, #1C1C28 0%, #181823 100%);
        }
        
        /* Modern content wrapper */
        .content-wrapper {
            min-height: 100vh;
            padding-top: 100px;
            padding-left: 20px;
            padding-right: 20px;
            padding-bottom: 40px;
        }
        
        /* Modern card styling */
        .modern-card {
            background: rgba(35, 35, 46, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(244, 196, 48, 0.1);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        
        .modern-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(244, 196, 48, 0.1);
        }
        
        /* Page title styling */
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #F4C430 0%, #FFD95E 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .content-wrapper {
                padding-top: 80px;
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .page-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body class="<?= is_admin() ? 'admin-page' : 'user-page' ?>">

<!-- The hamburger panel will be created by JavaScript -->

<!-- Main Content Wrapper -->
<div class="content-wrapper main-content">
    <div class="container-fluid">
        <?php if (isset($page_title)): ?>
        <h1 class="page-title"><?= htmlspecialchars($page_title) ?></h1>
        <?php endif; ?>
        
        <!-- Content will be inserted here by individual pages -->
