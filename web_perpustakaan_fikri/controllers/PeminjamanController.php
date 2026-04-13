<?php
/**
 * CONTROLLER: Peminjaman
 * File Inti Logika Sirkulasi! Merancang proses Cart (Keranjang) Buku Siswa, Approval dari Admin, 
 * hingga Perhitungan Denda Pengembalian. 
 */
login_check(); // Semua akses wajib Login

// Kumpulkan senjata Models: Butuh mengobrol dengan tabel peminjaman, update stok buku, dan identitas user.
$peminjamanModel = new Peminjaman();
$bukuModel = new Buku();
$userModel = new User();

/**
 * -------------------------------------------------------------
 * SISTEM KERANJANG SEMENTARA (SESSION) - Area Siswa
 * -------------------------------------------------------------
 * Ini memastikan sebelum Siswa fix mengajukan, buku ditampung di memori Session Browser.
 */
// Jika belum ada keranjang, cetak Array kosong
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

/* -------------------------------------------------------------
   MODUL 1: Menambah Buku Ke Dalam Keranjang (Katalog)
------------------------------------------------------------- */
if ($action === 'keranjang_tambah') {
    if ($_SESSION['role'] !== 'siswa') { redirect('dashboard'); }
    
    // Ambil Nomor Seri (ID) Buku dari URL Parameter
    $id_buku = $_GET['id'] ?? null;
    
    if ($id_buku) {
        $buku = $bukuModel->getById($id_buku);
        // Memastikan judul bukunya valid dan Fisik Bukunya MASIH ADA (Stok > 0)
        if ($buku && $buku['stok'] > 0) {
            // Memastikan buku yang sama tidak dicentang dua kali (Anti-Double)
            if (!in_array($id_buku, $_SESSION['keranjang'])) {
                // Masukkan (Push) id Buku ke dompet keranjang Memori!
                $_SESSION['keranjang'][] = $id_buku;
                $_SESSION['sukses'] = "Buku ditambahkan ke ajuan pinjam!";
            } else {
                $_SESSION['error'] = "Buku sudah ada di daftar ajuan!";
            }
        } else {
            $_SESSION['error'] = "Buku tidak ditemukan atau stok habis!";
        }
    }
    // Pantulkan kembali posisi layar siswa agar dia tidak pusing pindah halaman tiap klik "Tambah"
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;

/* -------------------------------------------------------------
   MODUL 2: Buang Buku dari Keranjang (Membatalkan Ajuan Tunggal)
------------------------------------------------------------- */
} elseif ($action === 'keranjang_hapus') {
    $id_buku = $_GET['id'] ?? null;
    // Cari letak sarang ID buku tersebut ada di baris Array keberapa (Array Search)
    if (($key = array_search($id_buku, $_SESSION['keranjang'])) !== false) {
        // Hancurkan kunci array itu
        unset($_SESSION['keranjang'][$key]);
        // Rapatkan kembali urutan angka array (Re-inkedks)
        $_SESSION['keranjang'] = array_values($_SESSION['keranjang']); 
    }
    // Kembali pantau Halaman Keranjang
    redirect('peminjaman/keranjang');

/* -------------------------------------------------------------
   MODUL 3: TAYANGAN LAYAR KERANJANG
------------------------------------------------------------- */
} elseif ($action === 'keranjang') {
    if ($_SESSION['role'] !== 'siswa') { redirect('dashboard'); }
    
    $buku_keranjang = [];
    // Menjabarkan angka-angka ID yang ada dalam memori Session
    foreach ($_SESSION['keranjang'] as $id_buku) {
        // Konversikan angka ID itu menjadi Judul, Cover, dll (Ambil dari DB)
        $b = $bukuModel->getById($id_buku);
        if ($b) $buku_keranjang[] = $b;
    }
    require_once __DIR__ . '/../views/keranjang.php';

/* -------------------------------------------------------------
   MODUL 4: SUBMIT PEMINJAMAN KE ADMIN (CHECK-OUT)
------------------------------------------------------------- */
} elseif ($action === 'ajukan') {
    if ($_SESSION['role'] !== 'siswa') { redirect('dashboard'); }
    
    // Cegah iseng klik tombol Check Out apabila Keranjang masih nihil
    if (empty($_SESSION['keranjang'])) {
        $_SESSION['error'] = "Daftar ajuan kosong!";
        redirect('peminjaman/keranjang');
    }
    
    // Membuat No Resi Kode Transaksi yang unik (Kombinasi Tanggal/Jam + ID Siswanya)
    $kode_transaksi = 'TRX-' . time() . '-' . $_SESSION['user_id'];
    
    // Pecah keranjangnya, dan serahkan ke database satu per satu sebagai data baru bertitle 'menunggu'
    foreach ($_SESSION['keranjang'] as $id_buku) {
        $peminjamanModel->tambahAjuan($kode_transaksi, $_SESSION['user_id'], $id_buku);
    }
    
    // Bersihkan Memori Dompet/Keranjang Siswa karena sudah terkirim!
    $_SESSION['keranjang'] = []; 
    $_SESSION['sukses'] = "Pengajuan peminjaman berhasil dikirim! Menunggu persetujuan admin.";
    
    // Pindah ke jendela Sejarah Transaksi
    redirect('peminjaman/riwayat');

/* =============================================================
   SISI PETUGAS ADMIN / PERPUSTAKAAN
   ============================================================= */

/* -------------------------------------------------------------
   MODUL 5: MEJA PERSETUJUAN (Notifikasi Admin)
------------------------------------------------------------- */
} elseif ($action === 'persetujuan') {
    admin_check();
    // Ambil daftar merah yang menunggu Stempel 'DIPINJAM'
    $pengajuan = $peminjamanModel->getPengajuanMasuk();
    require_once __DIR__ . '/../views/peminjaman_persetujuan.php';

/* -------------------------------------------------------------
   MODUL 6: STEMPEL SETUJU + POTONG STOK OTOMATIS
------------------------------------------------------------- */
} elseif ($action === 'setujui') {
    admin_check();
    $pinjam = $peminjamanModel->getById($param);
    
    // Validasi Keamanan Berlapis (Barangkali disetujui dua kali akibat error humanis)
    if ($pinjam && $pinjam['status'] === 'menunggu') {
        $buku = $bukuModel->getById($pinjam['id_buku']);
        
        // Cek lagi apakah stok detik Ini masih ada? (Siapa tau keburu diambil oleh petugas/siswa lain di cabang)
        if ($buku && $buku['stok'] > 0) {
            
            $tgl_pinjam = date('Y-m-d'); // Catat Jam dan Hari Ini sebagai start awal
            
            // Rumus Masa Pinjam. Standar: 7 HARI kemudian adalah DUE DATE (Batas pengembalian)
            $tgl_hk = date('Y-m-d', strtotime('+7 days'));
            
            // Eksekusi Setujui: Merubah Status Menunggu -> Dipinjam, Pasang Tanggal
            $peminjamanModel->setujui($param, $tgl_pinjam, $tgl_hk);
            
            // Eksekusi Potong Stok Fisis: Buku di laci Rak berkurang (Trigger)
            $bukuModel->kurangiStok($pinjam['id_buku']);
            
            $_SESSION['sukses'] = "Peminjaman disetujui!";
        } else {
            $_SESSION['error'] = "Gagal disetujui: Stok habis!";
        }
    }
    // Langsung pantulkan layar pada file yang sama agar admin cepat memproses resi berikutnya
    redirect('peminjaman/persetujuan');

/* -------------------------------------------------------------
   MODUL 7: TOLAK MENTAH-MENTAH (REJECT)
------------------------------------------------------------- */
} elseif ($action === 'tolak') {
    admin_check();
    // Ini mengganti statusnya ditolak. Siswa akan bisa baca alasannya (Atau murni hanya label 'ditolak')
    $peminjamanModel->tolak($param);
    $_SESSION['sukses'] = "Pengajuan ditolak!";
    redirect('peminjaman/persetujuan');

/* -------------------------------------------------------------
   MODUL 8: FORM HITUNG DENDA & PENGEMBALIAN FISIK (CHECK-IN)
------------------------------------------------------------- */
} elseif ($action === 'kembali') {
    $pinjam = $peminjamanModel->getByIdDipinjam($param);
    if (!$pinjam) { redirect('peminjaman'); }
    
    // Prediksi/Perhitungan Real Time dari Server saat Admin membuka Halaman Tagihan/Kembali
    $tgl_kembali = date('Y-m-d');
    $selisih = (strtotime($tgl_kembali) - strtotime($pinjam['tgl_harus_kembali'])) / 86400; // Selisih Hari Total
    $denda = $peminjamanModel->hitungDenda($pinjam['tgl_harus_kembali']); // Kalkulasi Nominal IDR
    
    // Apabila Admin menekan Tombol Konfirmasi Kembalikan
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ambil Data Tanggal Serah Terima (Admin BISA mengakali Manual Tglnya bila terjadi kelonggaran)
        $tgl_kembali = $_POST['tgl_kembali'] ?? date('Y-m-d');
        
        // Kalkulasi ketat denda Final bersumber POST input dari form tadi
        $denda = $peminjamanModel->hitungDenda($pinjam['tgl_harus_kembali'], $tgl_kembali);
        
        // Eksekusi 1: Pindahkan baris 'dipinjam' menjadi status 'dikembalikan'
        $peminjamanModel->kembalikan($param, $tgl_kembali, $denda);
        
        // Eksekusi 2: Tambah (Restock) jumlah buku ini pada Lemari (Inventory) karena sudah diserahkan!
        $bukuModel->tambahStok($pinjam['id_buku']);
        
        // Menyusun Kalimat Alert (Notifikasi Hijau)
        $msg = "Buku berhasil dikembalikan!";
        if ($denda > 0) $msg .= " Terdapat Denda Sebesar: Rp " . number_format($denda, 0, ',', '.');
        $_SESSION['sukses'] = $msg;
        
        // Admin kembali ke Tabel Data, Siswa kembali ke Riwayat.
        redirect($_SESSION['role'] === 'admin' ? 'peminjaman' : 'peminjaman/riwayat');
    }
    // Cetak Form Detail tagihan Denda
    require_once __DIR__ . '/../views/peminjaman_kembali.php';

/* -------------------------------------------------------------
   MODUL 9: RIWAYAT BUKU-BUKU SISWA (User Only)
------------------------------------------------------------- */
} elseif ($action === 'riwayat') {
    // Membaca Data Historis Terpisah: Menunggu ACC, Aktif diTangan Siswa, Semua Riwayat.
    $menunggu = $peminjamanModel->getMenungguByUser($_SESSION['user_id']);
    $aktif = $peminjamanModel->getAktifByUser($_SESSION['user_id']);
    $riwayat = $peminjamanModel->getRiwayatByUser($_SESSION['user_id']);
    
    // Tampilkan View-nya
    require_once __DIR__ . '/../views/riwayat.php';

/* -------------------------------------------------------------
   MODUL 10: HAPUS RIWAYAT TOTAL (Hard Delete)
------------------------------------------------------------- */
} elseif ($action === 'hapus') {
    admin_check();
    $p = $peminjamanModel->hapus($param);
    
    // CEK AMAN: Apabila Buku yang dihapus riwayatnya "masih dibawa siswa" (Dipinjam), 
    // maka kita harus memintanya (tambah balik) stok ke sistem inventory terlebih dahulu! (Pendeteksi Kehilangan)
    if ($p && $p['status'] === 'dipinjam') {
        $bukuModel->tambahStok($p['id_buku']);
    }
    
    // Barulah dirobek kertas buktinya
    $peminjamanModel->delete($param);
    $_SESSION['sukses'] = "Data peminjaman dihapus!";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;

/* -------------------------------------------------------------
   MODUL UMUM: DATA KESELURUHAN (TABLE ADMIN)
------------------------------------------------------------- */
} else {
    // Ruang Master Peminjaman (Full Paginasi)
    admin_check();
    $page = (int)($_GET['page'] ?? 1);
    if ($page < 1) $page = 1;
    $limit = 20;
    $offset = ($page - 1) * $limit;
    $search = $_GET['q'] ?? '';
    
    $daftar = $peminjamanModel->getAll($limit, $offset, $search);
    $total_data = $peminjamanModel->countAll($search);
    $total_pages = ceil($total_data / $limit) ?: 1;
    
    require_once __DIR__ . '/../views/peminjaman_index.php';
}
