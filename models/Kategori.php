<?php
/**
 * MODEL: Kategori
 * Bertanggung jawab menangani semua transaksi database terkait tabel `kategori`.
 * Seperti penambahan genre buku, pengubahan, dan pengelompokan buku.
 */
class Kategori {
    private $db;
    
    // Konstruktor: Otomatis menyambungkan ke database setiap objek dipanggil
    public function __construct() {
        $this->db = koneksi();
    }
    
    /**
     * READ: Mengambil seluruh relasi data tabel Kategori. 
     * Sudah difasilitasi dengan limit paginasi (20 per halaman) dan fitur search.
     */
    public function getAll($limit = null, $offset = null, $search = null) {
        $sql = "SELECT * FROM kategori WHERE 1=1";
        $params = [];
        
        // Cek jika admin sedang mengetik di kolom pencarian
        if ($search) {
            $sql .= " AND nama_kategori LIKE ?";
            $params[] = "%$search%";
        }
        
        // Mengurutkan data secara ASC (Mulai dari ID yang paling lama ke yang terbaru)
        $sql .= " ORDER BY id ASC";
        
        // Membatasi hasil yang dikembalikan Query (LIMIT dan OFFSET untuk Paginasi)
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
            if ($offset !== null) {
                $sql .= " OFFSET " . (int)$offset;
            }
        }
        
        // Lakukan Binding Parameter demi keamanan dari celah SQL Injection
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Hitung total kategori buku untuk membuat pembagian tombol halaman (Paginator).
     */
    public function countAll($search = null) {
        $sql = "SELECT COUNT(*) FROM kategori WHERE 1=1";
        $params = [];
        if ($search) {
            $sql .= " AND nama_kategori LIKE ?";
            $params[] = "%$search%";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    /**
     * Ambil 1 baris detail kategori secara spesifik berdasarkan Nomor ID.
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM kategori WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * CREATE: Menyimpan isian form Kategori Baru ke dalam sistem database.
     */
    public function tambah($nama_kategori) {
        $stmt = $this->db->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
        return $stmt->execute([$nama_kategori]);
    }
    
    /**
     * UPDATE: Merevisi nama kategori yang sudah tersimpan sebelumnya.
     */
    public function update($id, $nama_kategori) {
        $stmt = $this->db->prepare("UPDATE kategori SET nama_kategori = ? WHERE id = ?");
        return $stmt->execute([$nama_kategori, $id]);
    }
    
    /**
     * DELETE: Menghapus sebuah kategori dari data base
     * Fungsi opsional tambahan jika dirasa perlu di masa depan.
     */
    public function hapus($id) {
        return $this->db->prepare("DELETE FROM kategori WHERE id = ?")->execute([$id]);
    }
}
