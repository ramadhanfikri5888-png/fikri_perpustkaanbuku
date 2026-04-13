<?php 
/**
 * VIEW: peminjaman_persetujuan.php
 * Inbox / Kotak Masuk Permohonan Pinjam terbaru yang butuh persetujuan segera.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = 'Persetujuan Peminjaman'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>🔔 Persetujuan Peminjaman</h3>
</div>
<hr>

<?php if (!empty($_SESSION['sukses'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
<?php endif; ?>
<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Waktu Ajuan</th>
                    <th>Kode Transaksi</th>
                    <th>Peminjam (NIS)</th>
                    <th>Judul Buku</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pengajuan)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pengajuan baru.</td></tr>
                <?php else: ?>
                    <?php foreach ($pengajuan as $p): ?>
                        <tr>
                            <td class="align-middle"><?= $p['tgl_pengajuan'] ?></td>
                            <td class="align-middle"><span class="badge bg-secondary"><?= e($p['kode_transaksi']) ?></span></td>
                            <td class="align-middle fw-bold"><?= e($p['nama']) ?> <small class="text-muted">(<?= e($p['nis']) ?>)</small></td>
                            <td class="align-middle"><?= e($p['judul']) ?></td>
                            <td class="align-middle">
                                <a href="<?= base_url('peminjaman/setujui/' . $p['id']) ?>" class="btn btn-success btn-sm me-1" onclick="return confirm('Setujui peminjaman buku ini?')">✔️ Setujui</a>
                                <a href="<?= base_url('peminjaman/tolak/' . $p['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tolak peminjaman buku ini?')">❌ Tolak</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>
