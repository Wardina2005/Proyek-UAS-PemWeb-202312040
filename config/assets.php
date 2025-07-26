<?php
/**
 * Assets Configuration for HiiStyle
 * Centralized asset management for better performance
 */

/**
 * Get optimized CSS includes
 */
function get_css_includes($additional_css = []) {
    $base_url = get_base_url();
    $css_files = [
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css',
        $base_url . '/assets/css/main.css', // Main consolidated CSS file
    ];
    
    // Add additional CSS files if provided
    foreach ($additional_css as $css) {
        $css_files[] = $base_url . '/assets/css/' . $css;
    }
    
    $output = '';
    foreach ($css_files as $css_file) {
        $output .= '<link rel="stylesheet" href="' . $css_file . '">' . "\n    ";
    }
    
    return $output;
}

/**
 * Get optimized JS includes
 */
function get_js_includes($additional_js = []) {
    $base_url = get_base_url();
    $js_files = [
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
        $base_url . '/assets/js/main.js', // Main consolidated JS file
    ];
    
    // Add additional JS files if provided
    foreach ($additional_js as $js) {
        $js_files[] = $base_url . '/assets/js/' . $js;
    }
    
    $output = '';
    foreach ($js_files as $js_file) {
        $output .= '<script src="' . $js_file . '"></script>' . "\n    ";
    }
    
    return $output;
}

/**
 * Get meta tags for better SEO and functionality
 */
function get_page_meta_tags($page_title = 'HiiStyle', $description = 'Elegance in Every Detail') {
    $base_url = get_base_url();
    
    return '
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="' . $description . '">
    <meta name="keywords" content="fashion, style, clothing, ecommerce, hiistyle">
    <meta name="author" content="HiiStyle Team">
    <meta name="base-url" content="' . $base_url . '">
    <title>' . $page_title . '</title>
    ';
}

/**
 * Generate page header with optimized assets
 */
function generate_page_header($page_title = 'HiiStyle', $description = 'Elegance in Every Detail', $additional_css = [], $body_class = '') {
    return '<!DOCTYPE html>
<html lang="id">
<head>
    ' . get_page_meta_tags($page_title, $description) . '
    ' . get_css_includes($additional_css) . '
</head>
<body class="' . $body_class . '">';
}

/**
 * Generate page footer with optimized scripts
 */
function generate_page_footer($additional_js = []) {
    return '
    ' . get_js_includes($additional_js) . '
</body>
</html>';
}

/**
 * Add user/admin specific meta tags
 */
function add_user_meta($user_data) {
    if (isset($user_data['nama'])) {
        echo '<meta name="user-name" content="' . htmlspecialchars($user_data['nama']) . '">' . "\n";
    }
    if (isset($user_data['role']) && $user_data['role'] === 'admin') {
        echo '<meta name="admin-name" content="' . htmlspecialchars($user_data['nama']) . '">' . "\n";
    }
}
?>
