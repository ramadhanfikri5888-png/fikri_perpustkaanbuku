<?php
/**
 * CONTROLLER: Kategori 
 * Menangani urusan Pengelompokan tipe Buku (Misal: Fiksi, Ilmiah, Ensiklopedia).
 * Seluruh akses kemari wajib berstatus Administrator!
 */

// Memastikan barisan pengaman bahwa hanya Admin yang dapat mengakses rute halaman ini
admin_check();

// Menghidupkan entitas/Model Kategori agar database dapat ditanyakan
$kategoriModel = new Kategori();

/* -------------------------------------------------------------
   KONDISI 1: Tambah Spesies Kategori Baru
   URL: /kategori/tambah
------------------------------------------------------------- */
if ($action === 'tambah') {
    // Mengecek apakah tombol simpan (Kirim Form POST) baru ditekan
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Alirkan kotak isian 'nama_kategori' ke Database.
        $kategoriModel->tambah($_POST['nama_kategori']);
        
        // Ciptakan label sesi "sukses" agar pop-up hijau muncul di tabel depan
        $_SESSION['sukses'] = "Kategori berhasil ditambahkan!";
        
        // Pindahkan petugas kembali melihat tabel beranda index
        redirect('kategori');
    }
    // Jika sekedar membuka URL (Belum di submit) -> Render Form HTML Tambah Kategori
    require_once __DIR__ . '/../views/kategori_form.php';

/* -------------------------------------------------------------
   KONDISI 2: Edit Teks Nama Kategori
   URL: /kategori/edit/123
------------------------------------------------------------- */
} elseif ($action === 'edit') {
    // Coba temukan detail lama Kategori dari Nomor ID yang tersemat di link (Tersimpan di $param)
    $kategori = $kategoriModel->getById($param);
    
    // Keamanan: Misal ID tidak ada/ngarang, maka langsung ditendang balik ke Index.
    if (!$kategori) { redirect('kategori'); }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Melakukan timpa string teks nama lama di Tabel SQL dengan nama baru
        $kategoriModel->update($param, $_POST['nama_kategori']);
        $_SESSION['sukses'] = "Kategori berhasil diupdate!";
        redirect('kategori');
    }
    
    // Kirim data nama lama ('$kategori') ke Formulir supaya kotak isian sudah terisi Value Text
    require_once __DIR__ . '/../views/kategori_form.php';

/* -------------------------------------------------------------
   KONDISI 3: Pemusnahan Data Kategori
   URL: /kategori/hapus/123
------------------------------------------------------------- */
} elseif ($action === 'hapus') {
    // Operasi permanen pembakaran sebaris records dari pangkalan SQL
    $kategoriModel->hapus($param);
    $_SESSION['sukses'] = "Kategori berhasil dihapus!";
    redirect('kategori');

/* -------------------------------------------------------------
   KONDISI TERAKHIR (DEFAULT INDEX): Tabel Utama Data Paginasi
   URL: /kategori
------------------------------------------------------------- */
} else {
    // Menangkap argumen penomeran halaman (Jika web tidak ada ?page= berarti hal 1)
    $page = (int)($_GET['page'] ?? 1);
    if ($page < 1) $page = 1;
    
    // Peraturan maksimal data setiap tampil tabel
    $limit = 20;
    
    // Rumus memotong rentang Offset (Misal page 2: maka baris 0 s.d 20 dilewatkan). 
    $offset = ($page - 1) * $limit;
    
    // Menangkap request pencarian form 'q' (Jika pengguna mencari)
    $search = $_GET['q'] ?? '';
    
    // Susun array rapi berisi barisan data nama Kategori untuk dirender pada perulangan Foreach di HTML
    $kategori_list = $kategoriModel->getAll($limit, $offset, $search);
    
    // Proses pembagian matematis Angka Paginasi (Misal 43 data / 20 = 3 lembar halaman)
    $total_data = $kategoriModel->countAll($search);
    $total_pages = ceil($total_data / $limit) ?: 1;
    
    // Terakhir.. Render tampilannya!
    require_once __DIR__ . '/../views/kategori_index.php';
}
