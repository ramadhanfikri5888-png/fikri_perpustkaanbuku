<?php
/**
 * CONTROLLER: Buku
 * Kendali Pusat Arus File Buku.
 * Membawahi akses Data Induk Admin (Tambah, Edit, Hapus Buku), sekaligus layar Rak Buku Siswa (Katalog).
 */
$bukuModel = new Buku();
$kategoriModel = new Kategori();

/* =============================================================
   Rute Katalog: Menu Rak Buku Peminjam (Siswa Domain)
   ============================================================= */
if ($action === 'katalog') {
    // Siswa atau Admin harus ter-autentikasi (Harus Punya Akun)
    login_check();
    
    // Tangkap interaksi Filter jika Siswa mencari spesifik buku / memilih kategori dari kotak Select Options
    $keyword = $_GET['q'] ?? '';
    $kat = $_GET['kategori'] ?? '';
    
    // Jika Siswa mencari...
    if ($keyword || $kat) {
        $daftar_buku = $bukuModel->cari($keyword, $kat);
    } else {
        // Jika Siswa sekadar melihat etalase reguler tanpa ada filter spesifik
        $daftar_buku = $bukuModel->getAll();
    }
    
    // Ambil data dropdwon Kategori agar form pencarian jenis genre tampil di atas Layar
    $kategori_list = $kategoriModel->getAll();
    
    // Lempar hasil proses data ke tampilan etalase Cards Katalog
    require_once __DIR__ . '/../views/katalog.php';

/* =============================================================
   Rute Tambah Buku Baru (Khusus Admin Domain)
   ============================================================= */
} elseif ($action === 'tambah') {
    admin_check(); // Penjaga Pintu Admin Panel
    
    // Diperlukan agar kotak 'Pilih tipe Kategori Form' tersaji dengan benar
    $kategori_list = $kategoriModel->getAll();
    
    // Merespon unggahan Dokumen & Foto Buku
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cover_name = null;
        
        // Memeriksa dan merekam jika ada Berkas Upload sampul (Gambar) 
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
            $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION); // Mencomot Akhiran File (A.JPG -> JPG)
            
            // Penggantian Nama Gambar agar tak bertabrakan saat ada 2 file berhurup sama (Dinamai dgn satuan Jam Server)
            $cover_name = 'cover_' . time() . '.' . $ext;
            
            // Pangkas dan pindahkan dari memori temporer komputer, ke dalam Storage Lokal Folder Uploads
            move_uploaded_file($_FILES['cover']['tmp_name'], __DIR__ . '/../public/uploads/' . $cover_name);
        }
        
        // Simpan barisan Teks Form Input Data Buku ke Sistem MySQL 
        $bukuModel->tambah([
            'kode_buku' => $_POST['kode_buku'], 'judul' => $_POST['judul'],
            'penulis' => $_POST['penulis'], 'penerbit' => $_POST['penerbit'],
            'id_kategori' => $_POST['id_kategori'], 'stok' => $_POST['stok'],
            'lokasi_rak' => $_POST['lokasi_rak'], 'cover' => $cover_name
        ]);
        
        $_SESSION['sukses'] = "Buku berhasil ditambahkan!";
        redirect('buku');
    }
    // Jika pengunjung tak memposting, berikan mereka formulir layarnya.
    require_once __DIR__ . '/../views/buku_form.php';

/* =============================================================
   Rute Edit Buku (Khusus Admin)
   ============================================================= */
} elseif ($action === 'edit') {
    admin_check();
    
    // Cari buku apa yang hendak di edit berdasar link yang di klik (ID Terdapat pada URL)
    $kategori_list = $kategoriModel->getAll();
    $buku = $bukuModel->getById($param);
    if (!$buku) { redirect('buku'); }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cover_name = $buku['cover'];
        
        // Logika Penggantian Sampul (Jika sang Petugas memilih dokumen gambar baru)
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
            $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);
            $cover_name = 'cover_' . time() . '.' . $ext;
            
            // Simpan yang Baru
            move_uploaded_file($_FILES['cover']['tmp_name'], __DIR__ . '/../public/uploads/' . $cover_name);
            
            // Musnahkan (Unlink) gambar peninggalan lamanya supaya server tidak kepenuhan gambar File Sampah
            if ($buku['cover'] && file_exists(__DIR__ . '/../public/uploads/' . $buku['cover'])) {
                unlink(__DIR__ . '/../public/uploads/' . $buku['cover']);
            }
        }
        
        // Laporkan pembaruan formulir ke Model Update Data
        $bukuModel->update($param, [
            'kode_buku' => $_POST['kode_buku'], 'judul' => $_POST['judul'],
            'penulis' => $_POST['penulis'], 'penerbit' => $_POST['penerbit'],
            'id_kategori' => $_POST['id_kategori'], 'stok' => $_POST['stok'],
            'lokasi_rak' => $_POST['lokasi_rak'], 'cover' => $cover_name
        ]);
        
        $_SESSION['sukses'] = "Buku berhasil diupdate!";
        redirect('buku');
    }
    require_once __DIR__ . '/../views/buku_form.php';

/* =============================================================
   Rute Hapus Buku (Khusus Admin)
   ============================================================= */
} elseif ($action === 'hapus') {
    admin_check();
    $buku = $bukuModel->getById($param);
    
    // Bila file Buku ada foto Covernya, ledakan dan buang foto tersebut dari folder lokal server `public/uploads`!.
    if ($buku && $buku['cover'] && file_exists(__DIR__ . '/../public/uploads/' . $buku['cover'])) {
        unlink(__DIR__ . '/../public/uploads/' . $buku['cover']);
    }
    
    // Perintahkan Model untuk mendelete riwayat DB secara permanen
    $bukuModel->hapus($param);
    $_SESSION['sukses'] = "Buku berhasil dihapus!";
    redirect('buku');

/* =============================================================
   Rute DEFAULT / Index Buku (Khusus Admin Panel)
   ============================================================= */
} else {
    admin_check();
    
    // Hitungan Matematis Paginasi Daftar Admin Panel Terlama Hingga Terbaru.
    $page = (int)($_GET['page'] ?? 1);
    if ($page < 1) $page = 1;
    $limit = 20;
    $offset = ($page - 1) * $limit;
    
    // Menerima input "kata cari" dari kolom search yang diletakan HTML
    $search = $_GET['q'] ?? '';
    
    // Tanam Hasil baris MySQL ke variabel PHP array
    $daftar_buku = $bukuModel->getAll($limit, $offset, $search);
    
    // Kalkulasi Total kepingan lembar Paginasi
    $total_data = $bukuModel->countAll($search);
    $total_pages = ceil($total_data / $limit) ?: 1;
    
    // Lanjutkan Eksekusi pemaparan output File View Indeks Tabel!
    require_once __DIR__ . '/../views/buku_index.php';
}
