<?php 
/**
 * VIEW: laporan.php
 * Layar Laporan Keuangan/Sirkulasi (Denda & Periode) khusus Admin.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = 'Laporan'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>📊 Laporan Peminjaman</h3>
</div>
<hr>

<!-- Filter Periode -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <form method="GET" action="<?= base_url('laporan') ?>" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label text-muted small fw-bold">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control" value="<?= e($dari) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small fw-bold">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control" value="<?= e($sampai) ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">🔍 Filter Laporan</button>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center bg-light">
            <div class="card-body py-4">
                <h3 class="mb-1 fw-bold text-primary"><?= count($daftar) ?></h3>
                <p class="mb-0 text-muted small text-uppercase fw-bold">Total Transaksi</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center bg-light">
            <div class="card-body py-4">
                <h3 class="mb-1 fw-bold text-danger">Rp <?= number_format($total_denda, 0, ',', '.') ?></h3>
                <p class="mb-0 text-muted small text-uppercase fw-bold">Total Denda</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center bg-light">
            <div class="card-body py-4">
                <h5 class="mb-1 fw-bold text-dark mt-2"><?= $dari ?> s/d <?= $sampai ?></h5>
                <p class="mb-0 text-muted small text-uppercase fw-bold">Periode Laporan</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabel -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table mb-0 table-hover">
            <thead class="table-dark">
                <tr><th>No</th><th>Kode Trx</th><th>Nama</th><th>Buku</th><th>Pinjam</th><th>Tempo</th><th>Kembali</th><th>Status</th><th>Denda</th></tr>
            </thead>
            <tbody>
                <?php if (empty($daftar)): ?>
                    <tr><td colspan="9" class="text-center text-muted py-5">Tidak ada data pada periode ini</td></tr>
                <?php else: ?>
                    <?php foreach ($daftar as $i => $d): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><span class="badge bg-secondary"><?= e($d['kode_transaksi']) ?></span></td>
                            <td><?= e($d['nama']) ?></td>
                            <td><?= e($d['judul']) ?></td>
                            <td><?= $d['tgl_pinjam'] ?></td>
                            <td><?= $d['tgl_harus_kembali'] ?></td>
                            <td><?= $d['tgl_kembali'] ?: '-' ?></td>
                            <td>
                                <?php if ($d['status'] === 'dipinjam'): ?>
                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                <?php elseif ($d['status'] === 'dikembalikan'): ?>
                                    <span class="badge bg-success">Dikembalikan</span>
                                <?php elseif ($d['status'] === 'ditolak'): ?>
                                    <span class="badge bg-danger">Ditolak</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Menunggu</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $d['denda'] > 0 ? 'Rp ' . number_format($d['denda'], 0, ',', '.') : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>
