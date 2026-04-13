<?php 
/**
 * VIEW: katalog.php
 * Layar Etalase Rak Buku Digital tempat Siswa memilih dan memilah buku pinjaman.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = 'Katalog Buku'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<h3 class="mb-4">📖 Katalog Buku</h3>

<!-- Search -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?= base_url('buku/katalog') ?>" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control" placeholder="Cari judul atau penulis..." value="<?= e($_GET['q'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategori_list as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= ($_GET['kategori'] ?? '') == $k['id'] ? 'selected' : '' ?>><?= e($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">🔍 Cari</button>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($_SESSION['sukses'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?= $_SESSION['error']; unset($_SESSION['error']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<!-- Daftar Buku Card -->
<div class="row">
    <?php if (empty($daftar_buku)): ?>
        <div class="col-12"><p class="text-muted text-center py-5">Tidak ada buku ditemukan.</p></div>
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
                                <span class="badge bg-success mb-2 w-100">Tersedia (<?= $b['stok'] ?>)</span>
                                <?php if (!in_array($b['id'], $_SESSION['keranjang'] ?? [])): ?>
                                    <a href="<?= base_url('peminjaman/keranjang_tambah?id=' . $b['id']) ?>" class="btn btn-sm btn-outline-primary w-100">+ Tambah ke Ajuan</a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-secondary w-100" disabled>Sudah di Keranjang</button>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="badge bg-danger w-100 mb-2">Habis Dipinjam</span>
                                <button class="btn btn-sm btn-outline-secondary w-100" disabled>Stok Habis</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>
