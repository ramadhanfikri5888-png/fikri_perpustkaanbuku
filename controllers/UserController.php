<?php
/**
 * CONTROLLER: User 
 * Pengatur Manajamen Akun. Hanya Admin yang dapat memijakkan kaki di URL ini.
 * Bertanggung jawab mengakselerasi Tambah, Reset Password, Role Edit, dan Pemblokiran Akses Pengguna.
 */

admin_check(); // Penjaga jalur: Usir siapapun yang bukan Admin!
$userModel = new User();

/* -------------------------------------------------------------
   MODUL TAMBAH USER (Mode Admin)
------------------------------------------------------------- */
if ($action === 'tambah') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Lapis pertama: Cek keberadaan identitas ganda
        if ($userModel->cekUsername($_POST['username'])) {
            $error = "Username sudah digunakan!";
        } else {
            // Lapis Kedua: Proses Enkripsi dan peletakan dalam Database
            $userModel->tambah([
                'username' => $_POST['username'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'nama' => $_POST['nama'], 
                'role' => $_POST['role'] ?? 'siswa', // Default: Peminjam/Siswa jika form tak dikirim
                'nis' => $_POST['nis'], 
                'kelas' => $_POST['kelas'],
                'angkatan' => $_POST['angkatan'], 
                'status' => $_POST['status'] ?? 'aktif'
            ]);
            $_SESSION['sukses'] = "User berhasil ditambahkan!";
            redirect('user');
        }
    }
    // Layar Form UI Penambahan
    require_once __DIR__ . '/../views/user_form.php';

/* -------------------------------------------------------------
   MODUL UBAH DATA (EDIT USER)
------------------------------------------------------------- */
} elseif ($action === 'edit') {
    // Siapa yang mau diedit? Cari dari ID ujung URL ($param)
    $user = $userModel->getById($param);
    if (!$user) { redirect('user'); }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Serap input HTML Form
        $data = [
            'username' => $_POST['username'], 'nama' => $_POST['nama'],
            'role' => $_POST['role'], 'nis' => $_POST['nis'],
            'kelas' => $_POST['kelas'], 'angkatan' => $_POST['angkatan'],
            'status' => $_POST['status']
        ];
        
        // Kondisi jika Kolom Password KOSONG, maka jangan Update password-nya (Biarkan yang lama)
        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        
        $userModel->update($param, $data);
        $_SESSION['sukses'] = "User berhasil diupdate!";
        redirect('user');
    }
    require_once __DIR__ . '/../views/user_form.php';

/* -------------------------------------------------------------
   MODUL HAPUS AKUN 
------------------------------------------------------------- */
} elseif ($action === 'hapus') {
    // Mengeksekusi Delete Query, sekaligus meneruskan ID diri Admin sendiri agar MENCEGAH sistem BUNUH DIRI (Admin hapus admin)
    $userModel->hapus($param, $_SESSION['user_id']);
    $_SESSION['sukses'] = "User berhasil dihapus!";
    redirect('user');

/* -------------------------------------------------------------
   BAKU (DEFAULT): HALAMAN TABEL DAFTAR PENGGUNA
------------------------------------------------------------- */
} else {
    // Menyusun mekanisme Paginasi Standar 
    $page = (int)($_GET['page'] ?? 1);
    if ($page < 1) $page = 1;
    $limit = 20; // 20 Siswa/User per lembar HTML
    $offset = ($page - 1) * $limit;
    
    // Identifikasi isian tombol search 
    $search = $_GET['q'] ?? '';
    
    // Persiapan variabel data untuk foreach di Tabel HTML
    $daftar_user = $userModel->getAll($limit, $offset, $search);
    $total_data = $userModel->countAll($search);
    $total_pages = ceil($total_data / $limit) ?: 1;
    
    require_once __DIR__ . '/../views/user_index.php';
}
