<?php 
/**
 * VIEW: kategori_index.php
 * Tabel daftar manajemen seluruh Kategori / Rak virtual.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = 'Kategori Buku'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>🏷️ Kategori Buku</h3>
    <a href="<?= base_url('kategori/tambah') ?>" class="btn btn-primary btn-sm">+ Tambah</a>
</div>
<hr>

<?php if (!empty($_SESSION['sukses'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control" placeholder="Cari nama kategori..." value="<?= e($_GET['q'] ?? '') ?>">
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
            <thead class="table-dark"><tr><th>No</th><th>Nama Kategori</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php if (empty($kategori_list)): ?>
                    <tr><td colspan="3" class="text-center text-muted py-4">Belum ada kategori yang sesuai</td></tr>
                <?php else: ?>
                    <?php foreach ($kategori_list as $i => $k): ?>
                        <tr>
                            <td><?= $offset + $i + 1 ?></td>
                            <td><?= e($k['nama_kategori']) ?></td>
                            <td>
                                <a href="<?= base_url('kategori/edit/' . $k['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= base_url('kategori/hapus/' . $k['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
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
