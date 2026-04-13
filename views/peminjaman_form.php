<?php 
/**
 * VIEW: peminjaman_form.php
 * File kerangka UI tampilan untuk interaksi halaman peminjaman_form.php
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = 'Pinjam Buku'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<div class="row g-0">
    <?php require_once __DIR__ . '/templates/sidebar.php'; ?>
    
    <div class="col-md-10 content">
        <h3>📝 Form Peminjaman Buku</h3>
        <hr>
        
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?= base_url('peminjaman/pinjam') ?>">
                    
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <div class="mb-3">
                            <label class="form-label">Pilih Anggota *</label>
                            <select name="id_user" class="form-select" required>
                                <option value="">-- Pilih Anggota --</option>
                                <?php foreach ($daftar_siswa as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= e($s['nis'] . ' - ' . $s['nama'] . ' (' . $s['kelas'] . ')') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Pilih Buku *</label>
                        <select name="id_buku" class="form-select" required>
                            <option value="">-- Pilih Buku --</option>
                            <?php foreach ($buku_tersedia as $b): ?>
                                <option value="<?= $b['id'] ?>"><?= e($b['kode_buku'] . ' - ' . $b['judul'] . ' (Stok: ' . $b['stok'] . ')') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Pinjam</label>
                            <input type="text" class="form-control" value="<?= date('Y-m-d') ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jatuh Tempo (7 hari)</label>
                            <input type="text" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" readonly>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">📖 Pinjam Buku</button>
                    <a href="<?= base_url($_SESSION['role'] === 'admin' ? 'peminjaman' : 'dashboard') ?>" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>
