<?php 
/**
 * VIEW: user_index.php
 * Tabel Master Data keanggotaan pengguna perpustakaan.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = 'Data User'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>👥 Data User</h3>
    <a href="<?= base_url('user/tambah') ?>" class="btn btn-primary btn-sm">+ Tambah User</a>
</div>
<hr>

<?php if (!empty($_SESSION['sukses'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control" placeholder="Cari nama atau username..." value="<?= e($_GET['q'] ?? '') ?>">
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
                <tr><th>No</th><th>Username</th><th>Nama</th><th>Role</th><th>NIS</th><th>Kelas</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if (empty($daftar_user)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Data user tidak ditemukan.</td></tr>
                <?php else: ?>
                    <?php foreach ($daftar_user as $i => $u): ?>
                        <tr>
                            <td><?= $offset + $i + 1 ?></td>
                            <td><?= e($u['username']) ?></td>
                            <td><?= e($u['nama']) ?></td>
                            <td>
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span class="badge bg-primary">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-info">Siswa</span>
                                <?php endif; ?>
                            </td>
                            <td><?= e($u['nis'] ?: '-') ?></td>
                            <td><?= e($u['kelas'] ?: '-') ?></td>
                            <td>
                                <?php if ($u['status'] === 'aktif'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('user/edit/' . $u['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                    <a href="<?= base_url('user/hapus/' . $u['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
                                <?php endif; ?>
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
