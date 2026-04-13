<?php 
/**
 * VIEW: buku_form.php
 * Halaman antarmuka formulir (form) untuk input Penambahan atau Pengubahan Data Buku.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = isset($buku) ? 'Edit Buku' : 'Tambah Buku'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>


        <h3><?= isset($buku) ? '✏️ Edit Buku' : '➕ Tambah Buku' ?></h3>
        <hr>
        
        <div class="card">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Buku *</label>
                            <input type="text" name="kode_buku" class="form-control" required value="<?= e($buku['kode_buku'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Judul Buku *</label>
                            <input type="text" name="judul" class="form-control" required value="<?= e($buku['judul'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Penulis</label>
                            <input type="text" name="penulis" class="form-control" value="<?= e($buku['penulis'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control" value="<?= e($buku['penerbit'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="id_kategori" class="form-select">
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($kategori_list as $k): ?>
                                    <option value="<?= $k['id'] ?>" <?= ($buku['id_kategori'] ?? '') == $k['id'] ? 'selected' : '' ?>>
                                        <?= e($k['nama_kategori']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stok" class="form-control" min="0" value="<?= $buku['stok'] ?? 0 ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Lokasi Rak</label>
                            <input type="text" name="lokasi_rak" class="form-control" value="<?= e($buku['lokasi_rak'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Cover Buku</label>
                        <?php if (!empty($buku['cover'])): ?>
                            <div class="mb-2">
                                <img src="<?= base_url('public/uploads/' . $buku['cover']) ?>" style="width:100px;height:130px;object-fit:cover;">
                                <br><small class="text-muted">Cover saat ini</small>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="cover" class="form-control" accept="image/*">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">💾 Simpan</button>
                    <a href="<?= base_url('buku') ?>" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>
