<?php
/**
 * CONTROLLER: Home (Beranda Utama Publik)
 * Ini adalah muka depan web perpustakaan jika dikunjungi tamu (Belum Login).
 * Menampilkan desain selamat datang dan Etalase Katalog pencarian awal buku.
 */

// Menghubungi database Buku dan Kategori agar Katalog Publik tidak kosong
$bukuModel = new Buku();
$kategoriModel = new Kategori();

// Menangkap variabel filter pencarian buku (Jika tamu menggunakan form cari)
$keyword = $_GET['q'] ?? '';
$kat = $_GET['kategori'] ?? '';

// Periksa apakah tamu mengetikan nama buku atau memilih dropdown genre tipe buku
if ($keyword || $kat) {
    // Alirkan ke Mesin Pencari Khusus
    $daftar_buku = $bukuModel->cari($keyword, $kat);
} else {
    // Tampilkan buku secara general (Kumpulan default)
    $daftar_buku = $bukuModel->getAll();
}

// Bawa juga daftar koleksi Nama-Nama Kategori untuk dirender di opsi combo box pencarian
$kategori_list = $kategoriModel->getAll();

// Setelah seluruh data (variabel) terkumpul, terbitkan layar antar muka utamanya kepada pengunjung
require_once __DIR__ . '/../views/home.php';
