<?php
/**
 * CONTROLLER: Auth
 * Bertugas mengurus pintu gerbang autentikasi.
 * Mencakup proses memeriksa kredensial, memulai sesi pengguna, hingga menghancurkan sesi ketika logout.
 */

// Memanggil class Model User untuk mengambil data dari database
$userModel = new User();

// Rute: /auth/login
if ($action === 'login') {
    // Mengecek apakah form disubmit dengan tombol POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Membersihkan spasi berlebih pada inputan username
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Meminta Model menarik data spesifik user yang username-nya aktif
        $user = $userModel->getByUsername($username);
        
        // Verifikasi: Apakah user ditemukan DAN apakah sandi hash-nya cocok?
        if ($user && password_verify($password, $user['password'])) {
            // Jika cocok, catat Identitas ke dalam Sesi Browser (Login Sukses)
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            
            // Masuk ke Halaman Utama Sistem
            redirect('dashboard');
        } else {
            // Jika gagal, munculkan baris error di tampilan
            $error = "Username atau password salah!";
        }
    }
    // Muat UI Form Login
    require_once __DIR__ . '/../views/login.php';

// Rute: /auth/register
} elseif ($action === 'register') {
    // Mengecek apakah tombol daftar ditekan
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Menyusun array asosiatif data yang diketikkan siswa ke form
        $data = [
            'username' => trim($_POST['username'] ?? ''),
            // Mengacak sandi mentah menjadi hash kuat menggunakan Bcrypt
            'password' => password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT),
            'nama'     => trim($_POST['nama'] ?? ''),
            'nis'      => trim($_POST['nis'] ?? ''),
            'kelas'    => trim($_POST['kelas'] ?? ''),
            'angkatan' => trim($_POST['angkatan'] ?? ''),
            'role'     => 'siswa',
            'status'   => 'aktif', // By default akun langsung aktif
        ];
        
        // Validasi Anti-Ganda: Cek jika username sudah terambil sebelumnya
        if ($userModel->cekUsername($data['username'])) {
            $error = "Username sudah digunakan!";
        } else {
            // Melakukan Insert data ke database
            $userModel->tambah($data);
            
            // Melontarkan Pesan Kilat (Flash Message) bahwa register berhasil
            $_SESSION['sukses'] = "Registrasi berhasil! Silakan login.";
            
            // Lontarkan pengunjunga ke laman Login
            redirect('auth/login');
        }
    }
    // Muat UI Form Registrasi Publik
    require_once __DIR__ . '/../views/register.php';

// Rute: /auth/logout
} elseif ($action === 'logout') {
    // Menghancurkan seluruh data sesi dari komputer lokal, memutuskan status Auth
    session_destroy();
    
    // Alihkan paksa pengguna ke Form Login (Atau Landing)
    header("Location: " . base_url('auth/login'));
    exit;
}
