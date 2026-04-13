<?php 
/**
 * VIEW: home.php
 * Halaman Publik (Landing Page) yang tampil paling awal bagi pengunjung luar sebelum login.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = 'Perpustakaan Digital'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<!-- Hero -->
<div class="bg-primary text-white text-center py-5 rounded-4 shadow-sm mb-4 mt-2">
    <div class="container py-4">
        <h1 class="fw-bold">📚 Perpustakaan Pintar</h1>
        <p class="lead mb-0">Temukan dan pinjam bukumu dengan mudah</p>
    </div>
</div>

<!-- Search -->
<div class="card shadow-sm mb-4 border-0">
    <div class="card-body">
        <form method="GET" action="<?= base_url('') ?>" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control" placeholder="Cari judul atau penulis..." value="<?= e($keyword) ?>">
            </div>
            <div class="col-md-4">
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategori_list as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= $kat == $k['id'] ? 'selected' : '' ?>><?= e($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">🔍 Cari</button>
            </div>
        </form>
    </div>
</div>

<!-- Daftar Buku -->
<h4 class="mb-3">📖 Katalog Buku Terbaru</h4>
<div class="row">
    <?php if (empty($daftar_buku)): ?>
        <div class="col-12"><p class="text-muted text-center py-5">Belum ada buku tersedia.</p></div>
    <?php else: ?>
        <?php foreach ($daftar_buku as $b): ?>
            <div class="col-6 col-md-3 mb-4 d-flex align-items-stretch">
                <div class="card book-card w-100 shadow-sm border-0">
                    <?php if ($b['cover']): ?>
                        <img src="<?= base_url('public/uploads/' . $b['cover']) ?>" class="card-img-top">
                    <?php else: ?>
                        <div class="book-placeholder">📕</div>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title text-truncate" title="<?= e($b['judul']) ?>"><?= e($b['judul']) ?></h6>
                        <p class="card-text text-muted mb-2" style="font-size:12px;">
                            ✍️ <?= e($b['penulis'] ?: '-') ?><br>
                            🏷️ <?= e($b['nama_kategori'] ?? '-') ?><br>
                        </p>
                        <div class="mt-auto">
                            <?php if ($b['stok'] > 0): ?>
                                <span class="badge bg-success w-100 mb-2">Tersedia (<?= $b['stok'] ?>)</span>
                                <a href="<?= base_url('auth/login') ?>" class="btn btn-sm btn-outline-primary w-100" onclick="alert('Silakan login terlebih dahulu untuk meminjam buku.')">🔒 Pinjam</a>
                            <?php else: ?>
                                <span class="badge bg-danger w-100 mb-2">Habis</span>
                                <button class="btn btn-sm btn-outline-secondary w-100" disabled>Kosong</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<footer class="text-center py-4 mt-5 bg-light rounded-top-4">
    <small class="text-muted text-uppercase fw-bold">&copy; <?= date('Y') ?> Perpustakaan Digital</small>
</footer>

<?php require_once __DIR__ . '/templates/footer.php'; ?>
