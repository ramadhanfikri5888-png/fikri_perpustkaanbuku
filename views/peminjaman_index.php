<?php 
/**
 * VIEW: peminjaman_index.php
 * Pusat Log Data/Tabel Tabel Riwayat Seluruh Transaksi Peminjaman Perpustakaan.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = 'Data Peminjaman'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>📋 Peminjaman Aktif & Riwayat</h3>
</div>
<hr>

<?php if (!empty($_SESSION['sukses'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control" placeholder="Cari kode trx, nama siswa, atau buku..." value="<?= e($_GET['q'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover table-striped mb-0" style="font-size: 14px;">
            <thead class="table-dark">
                <tr><th>No</th><th>Kode Trx</th><th>Nama</th><th>Buku</th><th>Tgl Pinjam</th><th>Tempo</th><th>Status</th><th>Denda</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php if (empty($daftar)): ?>
                    <tr><td colspan="9" class="text-center text-muted py-4">Data tidak ditemukan</td></tr>
                <?php else: ?>
                    <?php foreach ($daftar as $i => $p): ?>
                        <tr>
                            <td><?= $offset + $i + 1 ?></td>
                            <td><span class="badge bg-secondary"><?= e($p['kode_transaksi']) ?></span></td>
                            <td class="fw-bold"><?= e($p['nama']) ?><br><small class="text-muted fw-normal"><?= e($p['nis'] ?: '-') ?></small></td>
                            <td><?= e($p['judul']) ?></td>
                            <td><?= $p['tgl_pinjam'] ?></td>
                            <td>
                                <?= $p['tgl_harus_kembali'] ?>
                                <?php if ($p['status'] === 'dipinjam' && strtotime($p['tgl_harus_kembali']) < time()): ?>
                                    <br><small class="text-danger fw-bold">⚠️ Menunggak</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['status'] === 'dipinjam'): ?>
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                <?php elseif ($p['status'] === 'dikembalikan'): ?>
                                    <span class="badge bg-success">Selesai</span>
                                <?php elseif ($p['status'] === 'ditolak'): ?>
                                    <span class="badge bg-danger">Ditolak</span>
                                <?php else: ?>
                                    <span class="badge bg-info text-dark">Menunggu</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $p['denda'] > 0 ? 'Rp ' . number_format($p['denda'], 0, ',', '.') : '-' ?></td>
                            <td>
                                <?php if ($p['status'] === 'dipinjam'): ?>
                                    <a href="<?= base_url('peminjaman/kembali/' . $p['id']) ?>" class="btn btn-success btn-sm mb-1 w-100">Kembalikan</a>
                                <?php endif; ?>
                                <a href="<?= base_url('peminjaman/hapus/' . $p['id']) ?>" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Yakin hapus data historis ini?')">Hapus</a>
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
