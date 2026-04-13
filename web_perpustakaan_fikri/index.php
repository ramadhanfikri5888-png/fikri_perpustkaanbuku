<?php
/**
 * INDEX.PHP (FRONT CONTROLLER)
 * File ini bertindak sebagai gerbang masuk (Routing) ke seluruh program web.
 * Semua _request_ URL dari pengguna akan mampir ke file ini terlebih dahulu.
 */

// Memulai sesi agar website bisa menyimpan data login pengguna (Session)
session_start();

// Mengimpor koneksi database dari file di folder config
require_once __DIR__ . '/config/database.php';

// Memuat (Include) seluruh file perantara Modul / Tabel dari folder models
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Buku.php';
require_once __DIR__ . '/models/Kategori.php';
require_once __DIR__ . '/models/Peminjaman.php';

/* ========================================================
   FUNGSI BANTUAN (HELPERS) GLOBAL
   ======================================================== */

/**
 * Mencetak URL basis / Absolute Path web perpustakaan
 * Berguna agar penulisan href CSS atau Link Gambar tidak putus
 */
function base_url($path = '') {
    return '/web_perpustakaan_fikri/' . ltrim($path, '/');
}

/**
 * Memindahkan pengguna secara instan dari satu web ke rute URL lain (Header Redirect)
 */
function redirect($path) {
    header("Location: " . base_url($path));
    exit;
}

/**
 * Validasi dan Pemeriksaan Keamanan Akses
 * Jika belum ada data login (Session 'user_id'), paksa user kembali ke halaman Homepage utama (Landing).
 */
function login_check() {
    if (!isset($_SESSION['user_id'])) {
        redirect(''); 
    }
}

/**
 * Validasi Hak Khusus Petugas / Admin.
 * Ini mencegah Siswa mencoba mengakes URL menu Admin. Jika role bukan 'admin', kembalikan ke siswa.
 */
function admin_check() {
    login_check();
    if ($_SESSION['role'] !== 'admin') {
        redirect('dashboard');
    }
}

/**
 * Fungsi sanitasi cepat keamanan tampilan.
 * Mencegah XSS (Cross Site Scripting) / Peretasan via form input. Menetralkan tag HTML berbahaya.
 */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}


/* ========================================================
   LOGIKA ROUTING (PENGATURAN ALAMAT URL & CONTROLLER)
   ======================================================== */

// Mengambil variabel request 'url' yang dilempar dari penulisan .htaccess di server (apache/laragon)
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';

// Mengamankan URL dari input aneh/berbahaya yang diketikkan di address bar
$url = filter_var($url, FILTER_SANITIZE_URL);

// Memecah susunan alamat web (URL) berdasarkan garis miring '/' menjadi bentuk barisan array.
// Contoh url "user/edit/1" akan pecah menjadi: [0] => user, [1] => edit, [2] => 1
$parts = explode('/', $url);

// Menangkap nama modul utama (misalnya "kategori") 
$controller = $parts[0] ?? '';

// Menangkap Perilaku (misalnya "tambah" atau "hapus"). Defaultnya adalah 'index' jika hanya nama modul.
$action = $parts[1] ?? 'index';

// Menangkap Parameter tambahan (Biasanya berisi ID data baris yang ingin dituju/dihapus)
$param = $parts[2] ?? null;


// Struktur Switch (Percabangan) yang menentukan Controller mana yang dipakai sesuai alamat yang dikunjungi
switch ($controller) {
    case 'auth':
        require_once __DIR__ . '/controllers/AuthController.php';
        break;
    case 'buku':
        require_once __DIR__ . '/controllers/BukuController.php';
        break;
    case 'kategori':
        require_once __DIR__ . '/controllers/KategoriController.php';
        break;
    case 'user':
        require_once __DIR__ . '/controllers/UserController.php';
        break;
    case 'peminjaman':
        require_once __DIR__ . '/controllers/PeminjamanController.php';
        break;
    case 'laporan':
        // Controller sementara / dikosongkan karena fitur cetak dihapus
        require_once __DIR__ . '/controllers/LaporanController.php';
        break;
    case 'dashboard':
        // Menuju ruang panel setelah berhasil login (Pusat / Beranda terenkripsi)
        require_once __DIR__ . '/controllers/DashboardController.php';
        break;
    default:
        // Jika web dikunjungi pertama kali (alamat dasar tak ada $controller) atau salah ejaan,
        // Alihkan halaman ke Beranda (Landing page Katalog Publik) milik pengunjung yang belum login.
        require_once __DIR__ . '/controllers/HomeController.php';
        break;
}
