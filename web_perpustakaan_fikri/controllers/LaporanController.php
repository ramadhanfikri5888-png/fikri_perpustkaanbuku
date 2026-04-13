<?php
/**
 * CONTROLLER: Laporan 
 * Pengatur Filter Bukti Transaksi berdasarkan Parameter Tanggal.
 * Data di sini berguna untuk pemantauan pembukuan dan kas denda.
 */
admin_check(); // Tersegel khusus Admin

$peminjamanModel = new Peminjaman();

// Mengambil variabel request kalender input 'dari' tanggal berapa?
// Default: Jika tidak diisi, ambil tanggal 1 di bulan yang berjalan saat ini
$dari = $_GET['dari'] ?? date('Y-m-01');

// Mengambil request 'sampai' tanggal berapa? (Cutoff-date)
// Default: Hari ini (Today)
$sampai = $_GET['sampai'] ?? date('Y-m-d');

// Model akan meramu data hanya yang berada antara rentang Waktu (BETWEEN dateA AND dateB)
$daftar = $peminjamanModel->getByPeriode($dari, $sampai);

// Perulangan Matematika Akuntansi ringan, menghitung total Rupiah Denda di rentang waktu tsb.
$total_denda = 0;
foreach ($daftar as $d) { 
    $total_denda += $d['denda']; 
}

// Terbitkan laporannya ke layar!
require_once __DIR__ . '/../views/laporan.php';
