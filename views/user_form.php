<?php 
/**
 * VIEW: user_form.php
 * Formulir input biodata/manajemen akses untuk akun Anggota (Siswa/Admin).
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */ 
?>
<?php $judul = isset($user) ? 'Edit User' : 'Tambah User'; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><?= isset($user) ? '✏️ Edit User' : '➕ Tambah User' ?></h3>
</div>
<hr>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form method="POST">
            <!-- Row 1: Akun -->
            <h6 class="text-primary mb-3">Login Info</h6>
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small fw-bold">Username *</label>
                    <input type="text" name="username" class="form-control" required value="<?= e($user['username'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small fw-bold">Password <?= isset($user) ? '(kosongkan jika tidak diubah)' : '*' ?></label>
                    <input type="password" name="password" class="form-control" <?= isset($user) ? '' : 'required' ?>>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small fw-bold">Role Hak Akses</label>
                    <select name="role" class="form-select">
                        <option value="siswa" <?= ($user['role'] ?? '') === 'siswa' ? 'selected' : '' ?>>Siswa / Peminjam</option>
                        <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrator</option>
                    </select>
                </div>
            </div>

            <!-- Row 2: Biodata -->
            <h6 class="text-primary mb-3">Biodata Siswa / User</h6>
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small fw-bold">Nama Lengkap *</label>
                    <input type="text" name="nama" class="form-control" required value="<?= e($user['nama'] ?? '') ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label text-muted small fw-bold">NIS (opsional)</label>
                    <input type="text" name="nis" class="form-control" value="<?= e($user['nis'] ?? '') ?>" placeholder="Hanya untuk siswa">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small fw-bold">Kelas (opsional)</label>
                    <input type="text" name="kelas" class="form-control" value="<?= e($user['kelas'] ?? '') ?>" placeholder="Contoh: XII IPA 1">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label text-muted small fw-bold">Angkatan (opsional)</label>
                    <input type="text" name="angkatan" class="form-control" value="<?= e($user['angkatan'] ?? '') ?>" placeholder="Tahun masuk">
                </div>
            </div>

            <!-- Row 3: Status -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <label class="form-label text-muted small fw-bold">Status Akun</label>
                    <select name="status" class="form-select">
                        <option value="aktif" <?= ($user['status'] ?? 'aktif') === 'aktif' ? 'selected' : '' ?>>🟢 Aktif</option>
                        <option value="nonaktif" <?= ($user['status'] ?? '') === 'nonaktif' ? 'selected' : '' ?>>🔴 Non-Aktif / Diblokir</option>
                    </select>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end">
                <a href="<?= base_url('user') ?>" class="btn btn-outline-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary px-4">💾 Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>
