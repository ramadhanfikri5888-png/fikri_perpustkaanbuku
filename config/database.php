<?php
/**
 * File Konfigurasi Database (PDO)
 * Bertugas mengatur koneksi antara aplikasi PHP ke server database MySQL.
 */
function koneksi() {
    // Menggunakan variabel static (Singleton Pattern)
    // Fungsinya agar koneksi ($pdo) disimpan di memori dan tidak perlu berulang kali 
    // membuka koneksi jika sebuah query dipanggil lebih dari sekali di satu proses.
    static $pdo = null;
    
    // Jika koneksi belum terbentuk, buat koneksi baru
    if ($pdo === null) {
        try {
            // Melakukan instansiasi objek PDO
            // Parameter: driver database (mysql), lokasi host (localhost), nama db (db_perpus_fikri)
            // Username lokal: 'root', Password standar kosong: ''
            $pdo = new PDO('mysql:host=localhost;dbname=db_perpus_fikri;charset=utf8mb4', 'root', '', [
                // Konfigurasi agar PHP melempar PDOException setiap kali ada script SQL yang error
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // Konfigurasi agar hasil output dari database default-nya berupa Array Associative (kolom => nilai)
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            // Hentikan/matikan laju website dan munculkan teks apabila database mati atau nama tabel salah
            die("Koneksi gagal: " . $e->getMessage());
        }
    }
    
    // Kembalikan status koneksi PDO untuk digunakan oleh file Mode/Query
    return $pdo;
}
