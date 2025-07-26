<?php
/**
 * Core Functions untuk HiiStyle
 * Berisi fungsi-fungsi utility yang digunakan di seluruh aplikasi
 */

/**
 * Sanitize input untuk mencegah XSS
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Format currency ke Rupiah
 */
function format_rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

/**
 * Format tanggal Indonesia
 */
function format_tanggal($tanggal) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    
    $split = explode('-', date('Y-m-d', strtotime($tanggal)));
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

/**
 * Generate random string untuk token
 */
function generate_token($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Validasi email
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Compress dan resize gambar
 */
function compress_image($source, $destination, $quality = 80) {
    $info = getimagesize($source);
    
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    } else {
        return false;
    }
    
    imagejpeg($image, $destination, $quality);
    imagedestroy($image);
    return true;
}

/**
 * Log aktivitas user
 */
function log_activity($conn, $id_user, $aktivitas) {
    $aktivitas = mysqli_real_escape_string($conn, $aktivitas);
    $query = "INSERT INTO aktivitas_admin (id_admin, aktivitas, waktu) VALUES ('$id_user', '$aktivitas', NOW())";
    return mysqli_query($conn, $query);
}

/**
 * Pagination helper
 */
function create_pagination($total_records, $records_per_page, $current_page, $base_url) {
    $total_pages = ceil($total_records / $records_per_page);
    $pagination = '';
    
    if ($total_pages > 1) {
        $pagination .= '<nav><ul class="pagination justify-content-center">';
        
        // Previous button
        if ($current_page > 1) {
            $prev_page = $current_page - 1;
            $pagination .= '<li class="page-item"><a class="page-link" href="' . $base_url . '?page=' . $prev_page . '">Previous</a></li>';
        }
        
        // Page numbers
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $current_page) ? 'active' : '';
            $pagination .= '<li class="page-item ' . $active . '"><a class="page-link" href="' . $base_url . '?page=' . $i . '">' . $i . '</a></li>';
        }
        
        // Next button
        if ($current_page < $total_pages) {
            $next_page = $current_page + 1;
            $pagination .= '<li class="page-item"><a class="page-link" href="' . $base_url . '?page=' . $next_page . '">Next</a></li>';
        }
        
        $pagination .= '</ul></nav>';
    }
    
    return $pagination;
}
?>