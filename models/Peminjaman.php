<?php
/**
 * MODEL: Peminjaman
 * Sebuah model yang paling rumit dan memuat urat nadi dari fungsi perpustakaan.
 * Bertanggung jawab mulai dari proses "Pengajuan Ke Keranjang -> Persetujuan -> Peminjaman Aktif -> Pengembalian & Perhitungan Denda".
 */
class Peminjaman {
    private $db;
    
    // Auto-koneksi setiap pemanggilan
    public function __construct() {
        $this->db = koneksi();
    }
    
    /**
     * READ: Menarik daftar lengkap semua transaksi peminjaman (Digabung (JOIN) ke nama Siswa dan daftar Judul Buku).
     * Digunakan Administrator pada halaman Data Peminjaman.
     * Mengandung mekanisme Limit (Paginasi 20) dan kolom kustomisasi pencarian berdasarkan ID/Nama/Buku.
     */
    public function getAll($limit = null, $offset = null, $search = null) {
        $sql = "SELECT peminjaman.*, user.nama, user.nis, buku.judul 
                FROM peminjaman 
                JOIN user ON peminjaman.id_user = user.id 
                JOIN buku ON peminjaman.id_buku = buku.id 
                WHERE 1=1";
        $params = [];
        
        // Memeriksa Jika ada permohonan pencarian teks
        if ($search) {
            $sql .= " AND (user.nama LIKE ? OR buku.judul LIKE ? OR peminjaman.kode_transaksi LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        // Urutan ASC, transaksi tertua di atas.
        $sql .= " ORDER BY peminjaman.id ASC";
        
        // Aturan Paginasi
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
            if ($offset !== null) {
                $sql .= " OFFSET " . (int)$offset;
            }
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mesin komputasi total kalkulasi baris transaksi. (Digunakan Rumus Pagination)
     */
    public function countAll($search = null) {
        $sql = "SELECT COUNT(*) 
                FROM peminjaman 
                JOIN user ON peminjaman.id_user = user.id 
                JOIN buku ON peminjaman.id_buku = buku.id 
                WHERE 1=1";
        $params = [];
        if ($search) {
            $sql .= " AND (user.nama LIKE ? OR buku.judul LIKE ? OR peminjaman.kode_transaksi LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /**
     * Modul notifikasi khusus Admin panel.
     * Tarik baris data yang status perlakuannya masih "menunggu" dikonfirmasi.
     */
    public function getPengajuanMasuk() {
        return $this->db->query("SELECT peminjaman.*, user.nama, user.nis, buku.judul 
                                  FROM peminjaman 
                                  JOIN user ON peminjaman.id_user = user.id 
                                  JOIN buku ON peminjaman.id_buku = buku.id 
                                  WHERE peminjaman.status = 'menunggu'
                                  ORDER BY peminjaman.tgl_pengajuan ASC")->fetchAll();
    }
    
    /**
     * Tarik data detil dari sebuah ID keranjang/transaksi tunggal
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT peminjaman.*, buku.judul, user.nama 
                                     FROM peminjaman 
                                     JOIN buku ON peminjaman.id_buku = buku.id 
                                     JOIN user ON peminjaman.id_user = user.id 
                                     WHERE peminjaman.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Memastikan ID transaksi yang diminta itu adalah milik buku yang sedang dalam ranah dibawa jinjingan siswa ("dipinjam").
     */
    public function getByIdDipinjam($id) {
        $stmt = $this->db->prepare("SELECT peminjaman.*, buku.judul, user.nama 
                                     FROM peminjaman 
                                     JOIN buku ON peminjaman.id_buku = buku.id 
                                     JOIN user ON peminjaman.id_user = user.id 
                                     WHERE peminjaman.id = ? AND peminjaman.status = 'dipinjam'");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Koleksi Data Halaman Siswa: Menampilkan rentetan buku yang belum ia serahkan kembali (dipinjam).
     */
    public function getAktifByUser($id_user) {
        $stmt = $this->db->prepare("SELECT peminjaman.*, buku.judul, buku.cover 
                                     FROM peminjaman 
                                     JOIN buku ON peminjaman.id_buku = buku.id 
                                     WHERE peminjaman.id_user = ? AND peminjaman.status = 'dipinjam'
                                     ORDER BY peminjaman.tgl_pinjam DESC");
        $stmt->execute([$id_user]);
        return $stmt->fetchAll();
    }

    /**
     * Koleksi Data Isi Keranjang Siswa. (Buku yang baru di klik tapi menunggu keputusan persetujuan petugas admin)
     */
    public function getMenungguByUser($id_user) {
        $stmt = $this->db->prepare("SELECT peminjaman.*, buku.judul, buku.cover 
                                     FROM peminjaman 
                                     JOIN buku ON peminjaman.id_buku = buku.id 
                                     WHERE peminjaman.id_user = ? AND peminjaman.status = 'menunggu'
                                     ORDER BY peminjaman.tgl_pengajuan DESC");
        $stmt->execute([$id_user]);
        return $stmt->fetchAll();
    }
    
    /**
     * Keseluruhan catatan jejak rekam aktifitas seorang Siswa dalam perpustakaan. (All History Log)
     */
    public function getRiwayatByUser($id_user) {
        $stmt = $this->db->prepare("SELECT peminjaman.*, buku.judul, buku.cover 
                                     FROM peminjaman 
                                     JOIN buku ON peminjaman.id_buku = buku.id 
                                     WHERE peminjaman.id_user = ? 
                                     ORDER BY peminjaman.id DESC");
        $stmt->execute([$id_user]);
        return $stmt->fetchAll();
    }
    
    /**
     * Highlight 5 tabel rekaman pinjaman teratas pada halaman Muka (Dashboard Admin Panel).
     */
    public function getTerbaru($limit = 5) {
        return $this->db->query("SELECT peminjaman.*, user.nama, user.nis, buku.judul 
                                  FROM peminjaman 
                                  JOIN user ON peminjaman.id_user = user.id 
                                  JOIN buku ON peminjaman.id_buku = buku.id 
                                  ORDER BY peminjaman.id DESC LIMIT $limit")->fetchAll();
    }
    
    /**
     * Menghitung kotak ringkas total buku yang sedang terbawa di luar oleh para peminjamnya. (Dashboard)
     */
    public function totalDipinjam() {
        return $this->db->query("SELECT COUNT(*) FROM peminjaman WHERE status='dipinjam'")->fetchColumn();
    }
    
    /**
     * Menghitung Kotak statistik pengunjung aktif dan peminjaman dalam kurun waktu 1 hari kalender (HARI INI).
     */
    public function transaksiHariIni() {
        return $this->db->query("SELECT COUNT(*) FROM peminjaman WHERE DATE(tgl_pengajuan) = CURDATE() OR tgl_pinjam = CURDATE()")->fetchColumn();
    }
    
    /**
     * Pemasukan keuangan perpustakaan dari kas telat kembali buku-buku.
     */
    public function totalDenda() {
        return $this->db->query("SELECT COALESCE(SUM(denda),0) FROM peminjaman")->fetchColumn();
    }
    
    /**
     * Reportase Custom (Laporan). Penarikan bukti rekap harian berdasarkan tanggal start "Dari" dan cut-off "Sampai".
     */
    public function getByPeriode($dari, $sampai) {
        $stmt = $this->db->prepare("SELECT peminjaman.*, user.nama, user.nis, buku.judul 
                                     FROM peminjaman 
                                     JOIN user ON peminjaman.id_user = user.id 
                                     JOIN buku ON peminjaman.id_buku = buku.id 
                                     WHERE peminjaman.tgl_pinjam BETWEEN ? AND ? 
                                     ORDER BY peminjaman.tgl_pinjam DESC");
        $stmt->execute([$dari, $sampai]);
        return $stmt->fetchAll();
    }
    
    /**
     * WORKFLOW: STEP 1 (SISWA)
     * Tambah perantara tabel keranjang (Aplikasi merekam pengajuan ke admin, status awal='menunggu')
     */
    public function tambahAjuan($kode_transaksi, $id_user, $id_buku) {
        $stmt = $this->db->prepare("INSERT INTO peminjaman (kode_transaksi, id_user, id_buku, status) VALUES (?,?,?,'menunggu')");
        return $stmt->execute([$kode_transaksi, $id_user, $id_buku]);
    }

    /**
     * WORKFLOW: STEP 2A (ADMIN MENGIZINKAN)
     * Mengaktifkan lampu hijau dengan mengubah status. Waktu pinjam dan batas hari tenggat dicatatkan ke database!
     */
    public function setujui($id, $tgl_pinjam, $tgl_harus_kembali) {
        $stmt = $this->db->prepare("UPDATE peminjaman SET status='dipinjam', tgl_pinjam=?, tgl_harus_kembali=? WHERE id=?");
        return $stmt->execute([$tgl_pinjam, $tgl_harus_kembali, $id]);
    }

    /**
     * WORKFLOW: STEP 2B (ADMIN MENOLAK)
     * Merubah status barisan rekam menjadi 'ditolak' tanpa menggores catatan penanggalan batas waktu.
     */
    public function tolak($id) {
        $stmt = $this->db->prepare("UPDATE peminjaman SET status='ditolak' WHERE id=?");
        return $stmt->execute([$id]);
    }
    
    /**
     * WORKFLOW: STEP 3 (PENGEMBALIAN & PENUTUPAN SIKLUS)
     * Siswa melaporkan fisik bukunya, mengubah setatus jadi 'dikembalikan'. Denda final ditagihkan disaksikan Admin.
     */
    public function kembalikan($id, $tgl_kembali, $denda) {
        return $this->db->prepare("UPDATE peminjaman SET status='dikembalikan', tgl_kembali=?, denda=? WHERE id=?")
                        ->execute([$tgl_kembali, $denda, $id]);
    }
    
    /**
     * Meraih atribut data sementara semata demi perlindungan integritas jika tombol hapus ditekan.
     */
    public function hapus($id) {
        $stmt = $this->db->prepare("SELECT id_buku, status FROM peminjaman WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Penghapusan permanen sejarah transaksi baris buku yang sudah lampau (dari database).
     */
    public function delete($id) {
        return $this->db->prepare("DELETE FROM peminjaman WHERE id = ?")->execute([$id]);
    }
    
    /**
     * RUMUS MATEMATIKA KALKULATOR DENDA
     * Menghitung telisik hari pinjam dan hari aktual mengembalikan. 
     * Setiap sisa 1 hari ditagih ongkos pelanggaran sejumlah Rp.1000,- (Bisa di rubah di sini).
     */
    public function hitungDenda($tgl_harus_kembali, $tgl_kembali = null) {
        if (!$tgl_harus_kembali) return 0;
        
        // Asumsi hari ini jika parameter dikembalikan tidak memiliki waktu (alias hari saat tombol dipencet)
        $tgl_kembali = $tgl_kembali ?? date('Y-m-d');
        
        // Hitung selisih detik timestamp, kemudian bagi dengan (24 jam x 60 menit x 60 detik) = 86400 (perhitungan detik dalam satu hari)
        $selisih = (strtotime($tgl_kembali) - strtotime($tgl_harus_kembali)) / 86400;
        
        // Rumus Multiplier: Selisih Keterlambatan dikali Rp 1.000,-
        return ($selisih > 0) ? $selisih * 1000 : 0;
    }
}
