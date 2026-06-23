<?php
/**
 * File koneksi.php
 * Digunakan untuk koneksi ke database MySQL
 */

class Database {
    private $host = 'localhost';
    private $username = 'root';      // Sesuaikan dengan username MySQL Anda
    private $password = '';          // Sesuaikan dengan password MySQL Anda
    private $database = 'db_uas_pbo_trpl1b_sofia';
    private $connection;

    // Constructor - membuat koneksi saat objek dibuat
    public function __construct() {
        $this->connect();
    }

    // Method untuk koneksi ke database
    private function connect() {
        try {
            $this->connection = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->database
            );

            // Cek koneksi
            if ($this->connection->connect_error) {
                throw new Exception("Koneksi gagal: " . $this->connection->connect_error);
            }

            // Set charset ke utf8mb4 untuk mendukung karakter khusus
            $this->connection->set_charset("utf8mb4");

            // echo "Koneksi berhasil!"; // Untuk testing, bisa di-uncomment

        } catch (Exception $e) {
            die("Error koneksi database: " . $e->getMessage());
        }
    }

    // Method untuk mendapatkan koneksi
    public function getConnection() {
        return $this->connection;
    }

    // Method untuk menutup koneksi
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    // Method untuk menjalankan query SELECT
    public function query($sql) {
        try {
            $result = $this->connection->query($sql);
            if ($result === false) {
                throw new Exception("Error query: " . $this->connection->error);
            }
            return $result;
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Method untuk menjalankan query INSERT, UPDATE, DELETE
    public function execute($sql) {
        try {
            if ($this->connection->query($sql) === true) {
                return $this->connection->insert_id; // Return ID terakhir yang di-insert
            } else {
                throw new Exception("Error executing query: " . $this->connection->error);
            }
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Method untuk escape string (mencegah SQL injection)
    public function escapeString($string) {
        return $this->connection->real_escape_string($string);
    }

    // Destructor - menutup koneksi saat objek dihapus
    public function __destruct() {
        $this->closeConnection();
    }
}

// Membuat objek koneksi database (opsional, bisa langsung di-include di file lain)
// $db = new Database();
// $conn = $db->getConnection();

?>