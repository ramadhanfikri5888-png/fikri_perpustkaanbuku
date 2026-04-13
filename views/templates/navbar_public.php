<?php
/**
 * VIEW: navbar_public.php
 * Desain Navigasi Menu Atas untuk pengunjung anonim (Tanpa status).
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
// Navbar Public
$menu_aktif = $controller ?? '';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="<?= base_url('') ?>">📚 PerpusDigital</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPublic">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navPublic">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('') ?>">Beranda</a>
                </li>
            </ul>
            <div class="d-flex">
                <a href="<?= base_url('auth/login') ?>" class="btn btn-outline-primary me-2">Login</a>
                <a href="<?= base_url('auth/register') ?>" class="btn btn-primary">Daftar Akun</a>
            </div>
        </div>
    </div>
</nav>
