<?php 
/**
 * VIEW: keranjang.php
 * Keranjang Sementara (Draft Ajuan) tempat Siswa mereview buku sebelum check-out.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = 'Keranjang Ajuan Pinjam'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h3 class="mb-4">🛒 Ajuan Peminjaman Buku</h3>
        
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Buku</th>
                            <th>Penulis</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($buku_keranjang)): ?>
                            <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada buku yang dipilih. <a href="<?= base_url('buku/katalog') ?>">Cari buku</a></td></tr>
                        <?php else: ?>
                            <?php foreach ($buku_keranjang as $b): ?>
                                <tr>
                                    <td class="ps-4 d-flex align-items-center">
                                        <?php if ($b['cover']): ?>
                                            <img src="<?= base_url('public/uploads/' . $b['cover']) ?>" style="width:40px;height:55px;object-fit:cover;border-radius:4px;" class="me-3">
                                        <?php else: ?>
                                            <div style="width:40px;height:55px;background:#eee;border-radius:4px;" class="me-3 d-flex align-items-center justify-content-center">📕</div>
                                        <?php endif; ?>
                                        <?= e($b['judul']) ?>
                                    </td>
                                    <td class="align-middle"><?= e($b['penulis']) ?></td>
                                    <td class="align-middle">
                                        <a href="<?= base_url('peminjaman/keranjang_hapus?id=' . $b['id']) ?>" class="btn btn-sm btn-outline-danger">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php if (!empty($buku_keranjang)): ?>
            <div class="text-end mt-4">
                <a href="<?= base_url('buku/katalog') ?>" class="btn btn-outline-primary me-2">Tambah Buku Lain</a>
                <a href="<?= base_url('peminjaman/ajukan') ?>" class="btn btn-primary px-4" onclick="return confirm('Ajukan peminjaman untuk <?= count($buku_keranjang) ?> buku ini?')">Kirim Ajuan Peminjaman</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>
