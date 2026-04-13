<?php
/**
 * VIEW: login.php
 * Form otentikasi (username/password) bagi Siswa maupun Petugas (Admin).
 * Merupakan kombinasi kode HTML (Tampilan) dan tag PHP (Menerima Data Controller).
 */
?>
<?php $judul = 'Login - Perpustakaan Digital'; ?>
<?php $hide_navbar = true; ?>
<?php require_once __DIR__ . '/templates/header.php' ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">
                    <h4>📚 Login Perpustakaan</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['sukses'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['sukses'];
                        unset($_SESSION['sukses']); ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>

                    <hr>
                    <p class="text-center mb-0">
                        Belum punya akun? <a href="<?= base_url('auth/register') ?>">Daftar di sini</a>
                    </p>
                </div>
            </div>
            <p class="text-center mt-3">
                <a href="<?= base_url('') ?>">← Kembali ke Beranda</a>
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/templates/footer.php'; ?>