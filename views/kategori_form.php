<?php 
/**
 * VIEW: kategori_form.php
 * Formulir inputan klasifikasi/Kategori (genre) buku baru.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>


        <h3><?= isset($kategori) ? '✏️ Edit Kategori' : '➕ Tambah Kategori' ?></h3>
        <hr>
        
        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori *</label>
                        <input type="text" name="nama_kategori" class="form-control" required value="<?= e($kategori['nama_kategori'] ?? '') ?>" autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary">💾 Simpan</button>
                    <a href="<?= base_url('kategori') ?>" class="btn btn-secondary">Batal</a>
                </form><?php require_once __DIR__ . '/templates/footer.php'; ?>


