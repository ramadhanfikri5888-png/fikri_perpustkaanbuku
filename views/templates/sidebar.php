<?php
/**
 * VIEW: sidebar.php
 * Komponen Panel Menu Samping kiri layar berwarna gelap (Dasbor Admin).
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
// Sidebar Admin
$menu_aktif = $controller ?? '';
?>
<div class="sidebar">
    <div class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <h4>📚 Perpustakaan</h4>
    </div>
    <hr style="border-color: #495057;">
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="<?= base_url('dashboard') ?>" class="<?= $menu_aktif == 'dashboard' ? 'active' : '' ?>">🏠 Dashboard Admin</a>
        </li>
        <li>
            <a href="<?= base_url('buku') ?>" class="<?= $menu_aktif == 'buku' ? 'active' : '' ?>">📕 Data Buku</a>
        </li>
        <li>
            <a href="<?= base_url('kategori') ?>" class="<?= $menu_aktif == 'kategori' ? 'active' : '' ?>">🏷️ Kategori</a>
        </li>
        <li>
            <a href="<?= base_url('user') ?>" class="<?= $menu_aktif == 'user' ? 'active' : '' ?>">👥 Data User</a>
        </li>
        <li>
            <a href="<?= base_url('peminjaman/persetujuan') ?>" class="<?= ($menu_aktif == 'peminjaman' && $action == 'persetujuan') ? 'active' : '' ?>">
                🔔 Persetujuan Pinjam
            </a>
        </li>
        <li>
            <a href="<?= base_url('peminjaman') ?>" class="<?= ($menu_aktif == 'peminjaman' && $action == 'index') ? 'active' : '' ?>">📋 Peminjaman Aktif</a>
        </li>
        <li>
            <a href="<?= base_url('laporan') ?>" class="<?= $menu_aktif == 'laporan' ? 'active' : '' ?>">📊 Laporan</a>
        </li>
    </ul>
    
    <hr style="border-color: #495057;">
    <div style="padding: 10px 20px; color: #adb5bd; font-size: 13px;">
        👤 <?= e($_SESSION['nama']) ?> (Admin)
        <br><br>
        <a href="<?= base_url('auth/logout') ?>" class="btn btn-outline-danger btn-sm w-100 mt-2 text-white">🚪 Logout</a>
    </div>
</div>
