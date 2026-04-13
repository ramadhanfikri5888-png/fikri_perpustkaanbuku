<?php 
/**
 * VIEW: dashboard_siswa.php
 * Tampilan Beranda Siswa yang menyajikan koleksi buku terbaru dan status peminjaman.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = 'Dashboard Siswa'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>🏠 Halo, <?= e($_SESSION['nama']) ?>!</h3>
</div>
<hr>

<?php if (!empty($_SESSION['sukses'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
<?php endif; ?>
<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<!-- Peminjaman Aktif -->
<?php if (!empty($pinjam_aktif)): ?>
    <h5 class="mt-4 mb-3">📋 Peminjaman Berlangsung</h5>
    <div class="row">
        <?php foreach ($pinjam_aktif as $p): ?>
            <div class="col-md-4 mb-3">
                <div class="card border-warning shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold"><?= e($p['judul']) ?></h6>
                        <p class="mb-1 text-muted" style="font-size:13px;">Pinjam: <?= $p['tgl_pinjam'] ?></p>
                        <p class="mb-2" style="font-size:13px;">
                            Jatuh Tempo: 
                            <strong class="<?= strtotime($p['tgl_harus_kembali']) < time() ? 'text-danger' : 'text-primary' ?>">
                                <?= $p['tgl_harus_kembali'] ?>
                                <?= strtotime($p['tgl_harus_kembali']) < time() ? '⚠️ TERLAMBAT' : '' ?>
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Buku Terbaru -->
<div class="d-flex justify-content-between align-items-end mt-5 mb-3">
    <h5>📚 Buku Terbaru</h5>
    <a href="<?= base_url('buku/katalog') ?>" class="btn btn-outline-primary btn-sm">Lihat Semua Buku →</a>
</div>

<div class="row">
    <?php foreach ($buku_terbaru as $b): ?>
        <div class="col-6 col-md-3 mb-4 d-flex align-items-stretch">
            <div class="card book-card w-100 shadow-sm border-0">
                <?php if ($b['cover']): ?>
                    <img src="<?= base_url('public/uploads/' . $b['cover']) ?>" class="card-img-top" style="height:200px;object-fit:cover;">
                <?php else: ?>
                    <div class="book-placeholder" style="height:200px;">📕</div>
                <?php endif; ?>
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title text-truncate" title="<?= e($b['judul']) ?>"><?= e($b['judul']) ?></h6>
                    <p class="card-text text-muted mb-2" style="font-size:12px;">✍️ <?= e($b['penulis'] ?: '-') ?></p>
                    <div class="mt-auto">
                        <?php if ($b['stok'] > 0): ?>
                            <span class="badge bg-success w-100 mb-2">Tersedia</span>
                            <?php if (!in_array($b['id'], $_SESSION['keranjang'] ?? [])): ?>
                                <a href="<?= base_url('peminjaman/keranjang_tambah?id=' . $b['id']) ?>" class="btn btn-sm btn-outline-primary w-100">+ Ke Ajuan</a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-secondary w-100" disabled>Di Ajuan</button>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="badge bg-danger w-100 mb-2">Habis</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>
