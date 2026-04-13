<?php 
/**
 * VIEW: buku_index.php
 * Halaman Tampilan Tabel Daftar Buku yang dikelola eksklusif oleh Admin Perpustakaan.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = 'Data Buku'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>📕 Data Buku</h3>
    <a href="<?= base_url('buku/tambah') ?>" class="btn btn-primary btn-sm">+ Tambah Buku</a>
</div>
<hr>

<?php if (!empty($_SESSION['sukses'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control" placeholder="Cari judul atau penulis..." value="<?= e($_GET['q'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover table-striped mb-0">
            <thead class="table-dark">
                <tr><th>No</th><th>Cover</th><th>Kode</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Stok</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if (empty($daftar_buku)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data buku</td></tr>
                <?php else: ?>
                    <?php foreach ($daftar_buku as $i => $b): ?>
                        <tr>
                            <td><?= $offset + $i + 1 ?></td>
                            <td>
                                <?php if ($b['cover']): ?>
                                    <img src="<?= base_url('public/uploads/' . $b['cover']) ?>" style="width:40px;height:55px;object-fit:cover;border-radius:4px;">
                                <?php else: ?>
                                    <div style="width:40px;height:55px;background:#eee;border-radius:4px;" class="d-flex align-items-center justify-content-center">📕</div>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-secondary"><?= e($b['kode_buku']) ?></span></td>
                            <td><?= e($b['judul']) ?></td>
                            <td><?= e($b['penulis'] ?: '-') ?></td>
                            <td><?= e($b['nama_kategori'] ?? '-') ?></td>
                            <td>
                                <?php if ($b['stok'] > 0): ?>
                                    <span class="badge bg-success"><?= $b['stok'] ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Habis</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('buku/edit/' . $b['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= base_url('buku/hapus/' . $b['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/templates/pagination.php'; ?>
<?php require_once __DIR__ . '/templates/footer.php'; ?>
