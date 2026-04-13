<?php
/**
 * MODEL: Buku
 * Bertanggung jawab menangani semua transaksi database terkait informasi `buku`.
 * Termasuk operasi tambah buku, pengurangan stok, hingga pencarian letak buku.
 */
class Buku {
    private $db;
    
    // Konstruktor: Menyambungkan instansiasi ke Database
    public function __construct() {
        $this->db = koneksi();
    }
    
    /**
     * READ: Menarik seluruh data Buku beserta Nama Kategorinya (Teknik JOIN).
     * Telah disesuaikan untuk fungsi pencarian teks (keyword) dan Paginasi Admin (ASC).
     */
    public function getAll($limit = null, $offset = null, $search = null) {
        // Query dengan relasi tabel buku ke tabel kategori
        $sql = "SELECT buku.*, kategori.nama_kategori FROM buku LEFT JOIN kategori ON buku.id_kategori = kategori.id WHERE 1=1";
        $params = [];
        
        // Jika pencarian terisi (Admin mencari data)
        if ($search) {
            $sql .= " AND (buku.judul LIKE ? OR buku.penulis LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        // Memakai algoritma ASC (Urutan dari yang terlama sampai terbaru)
        $sql .= " ORDER BY buku.id ASC";
        
        // Menyuntik limitasi halaman (Misal: 20 item Data Buku per Halaman)
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
     * Alat hitung jumlah data Buku untuk membantu men-generate tombol paginator Bootstrap.
     */
    public function countAll($search = null) {
        $sql = "SELECT COUNT(*) FROM buku WHERE 1=1";
        $params = [];
        if ($search) {
            $sql .= " AND (judul LIKE ? OR penulis LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    /**
     * Mengambil 1 detail buku berdasarkan ID-nya. 
     * Biasanya dipanggil saat Siswa ingin melihat detail cover dari di form Tambah Keranjang.
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM buku WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Menampilkan semua buku yang stoknya di atas nol (masih ada fisik tersisa).
     */
    public function getTersedia() {
        return $this->db->query("SELECT * FROM buku WHERE stok > 0 ORDER BY judul")->fetchAll();
    }
    
    /**
     * Mengambil koleksi buku terbaru untuk dipajang pada Halaman Beranda (Landing Area).
     * Biasanya dibatasi 6 sampai 8 buku saja. Menggunakan DESC agar yang muncul adalah rilisan paling baru.
     */
    public function getTerbaru($limit = 6) {
        return $this->db->query("SELECT buku.*, kategori.nama_kategori FROM buku LEFT JOIN kategori ON buku.id_kategori = kategori.id ORDER BY buku.id DESC LIMIT $limit")->fetchAll();
    }
    
    /**
     * Fungsi Hitung (Aggregat) guna ditampilkan ke dalam Kotak Laporan Statistik Dashboard.
     */
    public function totalBuku() {
        return $this->db->query("SELECT COUNT(*) FROM buku")->fetchColumn();
    }
    
    /**
     * Query spesifik untuk modul pencarian lanjutan (Katalog Publik Siswa).
     * Mensortir berdasarkan kategori Dropdown atau ketikan kata pencarian.
     */
    public function cari($keyword = '', $kategori = '') {
        $sql = "SELECT buku.*, kategori.nama_kategori FROM buku LEFT JOIN kategori ON buku.id_kategori = kategori.id WHERE 1=1";
        $params = [];
        if ($keyword) {
            $sql .= " AND (judul LIKE ? OR penulis LIKE ?)";
            $params[] = "%$keyword%";
            $params[] = "%$keyword%";
        }
        if ($kategori) {
            $sql .= " AND id_kategori = ?";
            $params[] = $kategori;
        }
        $sql .= " ORDER BY buku.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * CREATE: Method penambahan Buku baru melalui formulir input Admin.
     * Gambar fisik buku disimpan URL Relatif-nya pada kolom 'cover'.
     */
    public function tambah($data) {
        $stmt = $this->db->prepare("INSERT INTO buku (kode_buku, judul, penulis, penerbit, id_kategori, stok, lokasi_rak, cover) VALUES (?,?,?,?,?,?,?,?)");
        return $stmt->execute([
            $data['kode_buku'], $data['judul'], $data['penulis'], $data['penerbit'],
            $data['id_kategori'] ?: null, $data['stok'], $data['lokasi_rak'], $data['cover'] ?? null
        ]);
    }
    
    /**
     * UPDATE: Penyesuaian/Revisi atas biodata buku yang tertuang di master data.
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE buku SET kode_buku=?, judul=?, penulis=?, penerbit=?, id_kategori=?, stok=?, lokasi_rak=?, cover=? WHERE id=?");
        return $stmt->execute([
            $data['kode_buku'], $data['judul'], $data['penulis'], $data['penerbit'],
            $data['id_kategori'] ?: null, $data['stok'], $data['lokasi_rak'], $data['cover'], $id
        ]);
    }
    
    /**
     * DELETE: Menghancurkan pangkalan record sebuah resi buku.
     */
    public function hapus($id) {
        return $this->db->prepare("DELETE FROM buku WHERE id = ?")->execute([$id]);
    }
    
    /**
     * AUTOMATION TRIGER: Mengurangi nilai stok buku (Min 1) secara otomatis 
     * Setiap kali petugas perpustakaan menyetujui ajuan pinjam siswa!
     */
    public function kurangiStok($id) {
        return $this->db->prepare("UPDATE buku SET stok = stok - 1 WHERE id = ?")->execute([$id]);
    }
    
    /**
     * AUTOMATION TRIGER: Mengembalikan Stok asli buku (Plus 1) 
     * Setiap kali siswa sukses memulangkan buku perpustakaan.
     */
    public function tambahStok($id) {
        return $this->db->prepare("UPDATE buku SET stok = stok + 1 WHERE id = ?")->execute([$id]);
    }
}
