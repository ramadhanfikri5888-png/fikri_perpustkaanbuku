<?php 
/**
 * VIEW: peminjaman_kembali.php
 * Layar Kalkulator Denda & Form Pengembalian Buku di sisi meja Admin.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = 'Pengembalian Buku'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>


        <h3>📗 Pengembalian Buku</h3>
        <hr>
        
        <div class="card">
            <div class="card-body">
                <h5>Detail Peminjaman</h5>
                <table class="table table-bordered">
                    <tr><th width="200">Peminjam</th><td><?= e($pinjam['nama']) ?></td></tr>
                    <tr><th>Buku</th><td><?= e($pinjam['judul']) ?></td></tr>
                    <tr><th>Tanggal Pinjam</th><td><?= $pinjam['tgl_pinjam'] ?></td></tr>
                    <tr><th>Jatuh Tempo</th><td><?= $pinjam['tgl_harus_kembali'] ?></td></tr>
                    <tr>
                        <th>Status Keterlambatan</th>
                        <td>
                            <?php if ($denda > 0): ?>
                                <span class="text-danger">
                                    ⚠️ Terlambat <?= (int)$selisih ?> hari - Denda: Rp <?= number_format($denda, 0, ',', '.') ?>
                                </span>
                            <?php else: ?>
                                <span class="text-success">✅ Tepat waktu, tidak ada denda</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pengembalian</label>
                        <input type="date" name="tgl_kembali" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success" onclick="return confirm('Konfirmasi pengembalian buku?')">
                        ✅ Konfirmasi Pengembalian
                    </button>
                    <a href="<?= base_url($_SESSION['role'] === 'admin' ? 'peminjaman' : 'peminjaman/riwayat') ?>" class="btn btn-secondary">Batal</a>
                </form><?php require_once __DIR__ . '/templates/footer.php'; ?>


