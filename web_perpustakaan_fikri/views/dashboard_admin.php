<?php 
/**
 * VIEW: dashboard_admin.php
 * Tampilan Rangkuman (Statistik) Panel Kendali utama bagi Admin paska Login.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = 'Dashboard Admin'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>📈 Dashboard Admin</h3>
</div>
<hr>

<?php if (!empty($_SESSION['sukses'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
<?php endif; ?>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center bg-primary text-white">
            <div class="card-body py-4">
                <h2><?= $total_buku ?></h2>
                <p class="mb-0 text-uppercase fw-bold" style="font-size: 13px;">Total Buku</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center bg-info text-white">
            <div class="card-body py-4">
                <h2><?= $total_anggota ?></h2>
                <p class="mb-0 text-uppercase fw-bold" style="font-size: 13px;">Siswa Aktif</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center bg-warning text-dark">
            <div class="card-body py-4">
                <h2><?= $total_pinjam ?></h2>
                <p class="mb-0 text-uppercase fw-bold" style="font-size: 13px;">Peminjaman Aktif</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center text-white" style="background: #20c997;">
            <div class="card-body py-4">
                <h2><?= $transaksi_hari_ini ?></h2>
                <p class="mb-0 text-uppercase fw-bold" style="font-size: 13px;">Transaksi Hari Ini</p>
            </div>
        </div>
    </div>
</div>

<!-- Laporan Singkat Peminjaman Terbaru -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📋 5 Peminjaman Terbaru</h5>
        <a href="<?= base_url('peminjaman') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0 table-striped">
            <thead class="table-light">
                <tr><th>Kode Trx</th><th>Nama Siswa</th><th>Buku</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php if (empty($pinjam_terbaru)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada peminjaman terbaru.</td></tr>
                <?php else: ?>
                    <?php foreach ($pinjam_terbaru as $p): ?>
                        <tr>
                            <td><span class="badge bg-secondary"><?= e($p['kode_transaksi']) ?></span></td>
                            <td><?= e($p['nama']) ?></td>
                            <td><?= e($p['judul']) ?></td>
                            <td>
                                <?php if ($p['status'] === 'dipinjam'): ?>
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                <?php elseif ($p['status'] === 'dikembalikan'): ?>
                                    <span class="badge bg-success">Dikembalikan</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Menunggu</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>
