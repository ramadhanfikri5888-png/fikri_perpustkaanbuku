<?php
/**
 * VIEW: register.php
 * Halaman Pendaftaran Identitas Diri (Akun Baru) bagi Siswa Perpustakaan.
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */
?>
<?php $judul = 'Daftar Anggota'; ?>
<?php $hide_navbar = true; ?>
<?php require_once __DIR__ . '/templates/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header text-center">
                    <h4>📝 Daftar Anggota Baru</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">NIS</label>
                            <input type="text" name="nis" class="form-control" required
                                value="<?= e($_POST['nis'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required
                                value="<?= e($_POST['nama'] ?? '') ?>">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas" class="form-control" required
                                    value="<?= e($_POST['kelas'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Angkatan</label>
                                <input type="text" name="angkatan" class="form-control" required
                                    value="<?= e($_POST['angkatan'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required
                                value="<?= e($_POST['username'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Daftar</button>
                    </form>

                    <hr>
                    <p class="text-center mb-0">
                        Sudah punya akun? <a href="<?= base_url('auth/login') ?>">Login di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>