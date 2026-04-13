<?php
/**
 * VIEW: header.php
 * Komponen Global: Bagian Kepala (Atas) web untuk memuat Library CSS/Head meta tag.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
// Template Header - dipakai semua halaman
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $judul ?? 'Perpustakaan Digital' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        
        /* Layout Admin (Sidebar Fixed) */
        .admin-layout { display: flex; }
        .sidebar { 
            width: 250px; 
            min-height: 100vh; 
            background-color: #343a40; 
            position: fixed; 
            top: 0; left: 0; 
            z-index: 1000;
        }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 12px 20px; display: block; border-left: 3px solid transparent; }
        .sidebar a:hover, .sidebar a.active { color: #fff; background-color: #495057; border-left-color: #0d6efd; }
        .sidebar h4 { color: #fff; padding: 20px; margin-bottom: 0; border-bottom: 1px solid #495057; }
        .admin-content { margin-left: 250px; padding: 30px; width: calc(100% - 250px); }
        
        /* Layout Siswa (Top Navbar Fixed) */
        .siswa-navbar {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .siswa-content { padding: 40px 20px; margin-top: 60px; }
        .cart-badge { position: absolute; top: 0; right: -5px; font-size: 10px; padding: 3px 6px; }
        
        /* Global & Components */
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); transition: transform 0.2s; }
        .book-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
        .book-card img { width: 100%; height: 200px; object-fit: cover; border-radius: 12px 12px 0 0; }
        .book-placeholder { width: 100%; height: 200px; background: #e9ecef; display: flex; align-items: center; justify-content: center; color: #adb5bd; font-size: 48px; border-radius: 12px 12px 0 0; }
        .table { background: white; border-radius: 8px; overflow: hidden; }
        .table thead th { border-bottom: none; }
    </style>
</head>
<body>

<?php
// Handle Layout berdasarkan Session Role
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <div class="admin-layout">
        <?php require_once __DIR__ . '/sidebar.php'; ?>
        <div class="admin-content">
<?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'siswa'): ?>
    <?php require_once __DIR__ . '/navbar_siswa.php'; ?>
    <div class="container siswa-content">
<?php else: ?>
    <?php if (!isset($hide_navbar) || !$hide_navbar): ?>
        <?php require_once __DIR__ . '/navbar_public.php'; ?>
        <div class="container" style="margin-top: 80px;">
    <?php else: ?>
        <div class="container">
    <?php endif; ?>
<?php endif; ?>
