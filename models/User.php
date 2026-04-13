<?php
/**
 * MODEL: User
 * Bertanggung jawab menangani semua transaksi database (CRUD) pada tabel `user`.
 * Termasuk di dalamnya proses otentikasi login dan pengaturan akses siswa/admin.
 */
class User {
    private $db;
    
    // Konstruktor: Otomatis dipanggil saat objek User dibuat untuk menyambungkan database
    public function __construct() {
        $this->db = koneksi();
    }
    
    /**
     * Mengambil daftar seluruh user lengkap dengan limit paginasi dan filter pencarian.
     * Digunakan pada halaman Daftar User Admin.
     */
    public function getAll($limit = null, $offset = null, $search = null) {
        $sql = "SELECT * FROM user WHERE 1=1";
        $params = [];
        
        // Pencarian dinamis berdasarkan nama lengkap atau username
        if ($search) {
            $sql .= " AND (nama LIKE ? OR username LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        // Urutkan data dari yang paling lama didaftarkan (ID terkecil)
        $sql .= " ORDER BY id ASC";
        
        // Atur pemotongan data untuk sistem Paginasi Paging (Maks 20 per halaman)
        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
            if ($offset !== null) {
                $sql .= " OFFSET " . (int)$offset;
            }
        }
        
        // Siapkan dan tahan injeksi query (Prepared Statement)
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Kembalikan nilai ke controller
    }

    /**
     * Menghitung total banyaknya baris user sesuai pencarian.
     * Dibutuhkan oleh rumus paginasi agar tahu harus dibagi menjadi berapa halaman.
     */
    public function countAll($search = null) {
        $sql = "SELECT COUNT(*) FROM user WHERE 1=1";
        $params = [];
        if ($search) {
            $sql .= " AND (nama LIKE ? OR username LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn(); // Kembalikan 1 angka int (total rows)
    }
    
    /**
     * Mengambil satu baris spesifik data user berdasarkan ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Memeriksa keberadaan username aktif saat Login
     */
    public function getByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE username = ? AND status = 'aktif'");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    
    /**
     * Mencegah adanya Username ganda saat proses Registrasi akun baru
     */
    public function cekUsername($username) {
        $stmt = $this->db->prepare("SELECT id FROM user WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    
    /**
     * Mengambil daftar siswa yang diizinkan untuk meminjam buku.
     */
    public function getSiswaAktif() {
        return $this->db->query("SELECT * FROM user WHERE role='siswa' AND status='aktif' ORDER BY nama")->fetchAll();
    }
    
    /**
     * Menghitung kotak statistik total siswa aktif pada halaman Dashboard Admin
     */
    public function totalSiswaAktif() {
        return $this->db->query("SELECT COUNT(*) FROM user WHERE role='siswa' AND status='aktif'")->fetchColumn();
    }
    
    /**
     * Operasi Create: Menambahkan akun user (Admin/Siswa) baru ke database
     */
    public function tambah($data) {
        $stmt = $this->db->prepare("INSERT INTO user (username, password, nama, role, nis, kelas, angkatan, status) VALUES (?,?,?,?,?,?,?,?)");
        return $stmt->execute([
            $data['username'], $data['password'], $data['nama'],
            $data['role'] ?? 'siswa', $data['nis'] ?? null, $data['kelas'] ?? null,
            $data['angkatan'] ?? null, $data['status'] ?? 'aktif'
        ]);
    }
    
    /**
     * Operasi Update: Memperbarui isi formulir/data seorang user
     */
    public function update($id, $data) {
        $sql = "UPDATE user SET username=?, nama=?, role=?, nis=?, kelas=?, angkatan=?, status=?";
        $params = [$data['username'], $data['nama'], $data['role'], $data['nis'], $data['kelas'], $data['angkatan'], $data['status']];
        
        // Memeriksa Jika password diubah / terisi, tambahkan kondisi Query password baru
        if (!empty($data['password'])) {
            $sql .= ", password=?";
            $params[] = $data['password'];
        }
        $sql .= " WHERE id=?";
        $params[] = $id;
        
        return $this->db->prepare($sql)->execute($params);
    }
    
    /**
     * Operasi Delete: Menghapus data akun user selamanya secara fisik (hard delete).
     * Diberi keamanan exclude_id agar admin tidak secara tidak sengaja menghapus dirinya sendiri.
     */
    public function hapus($id, $exclude_id) {
        return $this->db->prepare("DELETE FROM user WHERE id = ? AND id != ?")->execute([$id, $exclude_id]);
    }
}
