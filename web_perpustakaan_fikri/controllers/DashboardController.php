<?php
/**
 * CONTROLLER: Dashboard
 * Halaman pusat pendaratan setelah user berhasil login.
 * Memuat panel berbeda yang bergantung sepenuhnya dari Role (Siswa vs Admin).
 */

// Lapisan keamanan utama: paksa yang belum login kembali ke beranda!
login_check();

// Inisialisasi Model Data yang sekiranya dibutuhkan untuk menampilkan rangkuman informasi layar
$bukuModel = new Buku();
$userModel = new User();
$peminjamanModel = new Peminjaman();

// Pembelahan Kondisi UI berdasar jabatan hak akses
if ($_SESSION['role'] === 'admin') {
    
    /* =======================================
       DASHBOARD BAGIAN ADMIN
       ======================================= */
    
    // Mengumpulkan Statistik Numerik Total 
    $total_buku = $bukuModel->totalBuku();
    $total_anggota = $userModel->totalSiswaAktif();
    $total_pinjam = $peminjamanModel->totalDipinjam();
    
    // Pergerakan data real-time khusus aktivitas hari ini 
    $transaksi_hari_ini = $peminjamanModel->transaksiHariIni();
    
    // Nominal uang denda yang telah terbayarkan/dikas
    $total_denda = $peminjamanModel->totalDenda();
    
    // Melampirkan tabel data transaksi peminjaman terbaru (Maksimal 5)
    $pinjam_terbaru = $peminjamanModel->getTerbaru(5);
    
    // Menampilkan antarmuka khusus panel kontrol sisi Admin
    require_once __DIR__ . '/../views/dashboard_admin.php';
    
} else {
    
    /* =======================================
       DASHBOARD BAGIAN SISWA
       ======================================= */
       
    // Menarik buku pinjaman yang sedang dirental dan dibawa pulang (Aktif)
    $pinjam_aktif = $peminjamanModel->getAktifByUser($_SESSION['user_id']);
    
    // Rekomendasi/Showcase Buku-buku pendatang terbaru di Perpustakaan
    $buku_terbaru = $bukuModel->getTerbaru(6);
    
    // Menampilkan UI beranda siswa modern (termasuk Navbar spesifik-siswa)
    require_once __DIR__ . '/../views/dashboard_siswa.php';
}
