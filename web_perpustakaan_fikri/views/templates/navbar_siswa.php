<?php
/**
 * VIEW: navbar_siswa.php
 * Desain Navigasi Khusus Siswa (Memuat Indikator Jumlah Keranjang).
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
// Navbar Siswa
$menu_aktif = $controller ?? '';
$jml_keranjang = count($_SESSION['keranjang'] ?? []);
?>
<nav class="navbar navbar-expand-lg navbar-light siswa-navbar fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="<?= base_url('dashboard') ?>">📚 PerpusFikri</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navSiswa">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navSiswa">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $menu_aktif == 'dashboard' ? 'active fw-bold' : '' ?>" href="<?= base_url('dashboard') ?>">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $menu_aktif == 'buku' ? 'active fw-bold' : '' ?>" href="<?= base_url('buku/katalog') ?>">Katalog Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($menu_aktif == 'peminjaman' && $action == 'riwayat') ? 'active fw-bold' : '' ?>" href="<?= base_url('peminjaman/riwayat') ?>">Riwayat</a>
                </li>
            </ul>
            
            <div class="d-flex align-items-center">
                <a href="<?= base_url('peminjaman/keranjang') ?>" class="btn btn-light position-relative me-3">
                    🛒 Ajuan Pinjam
                    <?php if ($jml_keranjang > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $jml_keranjang ?>
                        </span>
                    <?php endif; ?>
                </a>
                
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        👤 <?= e($_SESSION['nama']) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
