<?php
/**
 * File: classes/karyawanMagang.php
 * Class KaryawanMagang - turunan dari abstract class Karyawan
 * Mewakili karyawan dengan status magang
 */

require_once 'karyawan.php';

class KaryawanMagang extends Karyawan {
    // Properti tambahan spesifik untuk karyawan magang
    private $uangSakuBulanan;
    private $sertifikatKampusMerdeka;

    // Constructor
    public function __construct(
        $id_karyawan = null,
        $nama_karyawan = null,
        $departemen = null,
        $hariKerjaMasuk = null,
        $gajiDasarPerHari = null,
        $uangSakuBulanan = 0,
        $sertifikatKampusMerdeka = null
    ) {
        // Panggil constructor parent
        parent::__construct(
            $id_karyawan,
            $nama_karyawan,
            $departemen,
            $hariKerjaMasuk,
            $gajiDasarPerHari
        );
        
        // Inisialisasi properti spesifik
        $this->uangSakuBulanan = $uangSakuBulanan;
        $this->sertifikatKampusMerdeka = $sertifikatKampusMerdeka;
    }

    // Getter untuk properti tambahan
    public function getUangSakuBulanan() {
        return $this->uangSakuBulanan;
    }

    public function getSertifikatKampusMerdeka() {
        return $this->sertifikatKampusMerdeka;
    }

    // Setter untuk properti tambahan
    public function setUangSakuBulanan($uangSakuBulanan) {
        $this->uangSakuBulanan = $uangSakuBulanan;
    }

    public function setSertifikatKampusMerdeka($sertifikatKampusMerdeka) {
        $this->sertifikatKampusMerdeka = $sertifikatKampusMerdeka;
    }

    // Implementasi abstract method hitungGajiBersih
    public function hitungGajiBersih() {
        // Untuk magang, gaji = uang saku bulanan + (hari kerja * gaji dasar)
        $hariKerja = $this->hitungHariKerja();
        $gajiDasar = $hariKerja * $this->getGajiDasarPerHari();
        
        // Uang saku bulanan
        $uangSaku = $this->uangSakuBulanan;
        
        // Tidak ada pajak untuk magang (UMK dibawah PTKP)
        $gajiBersih = $gajiDasar + $uangSaku;
        
        return $gajiBersih;
    }

    // Implementasi abstract method tampilkanProfilKaryawan
    public function tampilkanProfilKaryawan() {
        ?>
        <div class="profile-card profile-magang">
            <div class="profile-header">
                <h3>🎓 Karyawan Magang</h3>
                <span class="badge badge-magang">Status: Magang</span>
            </div>
            <div class="profile-body">
                <table class="profile-table">
                    <tr>
                        <td><strong>ID Karyawan</strong></td>
                        <td>: <?php echo htmlspecialchars($this->getIdKaryawan()); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>: <?php echo htmlspecialchars($this->getNamaKaryawan()); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Departemen</strong></td>
                        <td>: <?php echo htmlspecialchars($this->getDepartemen()); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Masuk</strong></td>
                        <td>: <?php echo htmlspecialchars($this->getHariKerjaMasuk()); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Masa Magang</strong></td>
                        <td>: <?php echo $this->hitungMasaKerja(); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Gaji Dasar/Hari</strong></td>
                        <td>: Rp <?php echo number_format($this->getGajiDasarPerHari(), 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Uang Saku Bulanan</strong></td>
                        <td>: Rp <?php echo number_format($this->uangSakuBulanan, 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Sertifikat Kampus Merdeka</strong></td>
                        <td>: <?php echo htmlspecialchars($this->sertifikatKampusMerdeka ?? 'Belum ada'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Gaji Kotor</strong></td>
                        <td>: Rp <?php echo number_format($this->hitungGajiKotor(), 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Gaji Bersih</strong></td>
                        <td>: <strong>Rp <?php echo number_format($this->hitungGajiBersih(), 0, ',', '.'); ?></strong></td>
                    </tr>
                </table>
            </div>
        </div>
        <style>
            .profile-magang { border-left: 5px solid #2196F3; }
            .badge-magang { background: #2196F3; color: white; }
        </style>
        <?php
    }

    // Method khusus untuk karyawan magang
    public function getSertifikatUrl() {
        if ($this->sertifikatKampusMerdeka) {
            return "sertifikat/" . $this->sertifikatKampusMerdeka . ".pdf";
        }
        return null;
    }

    public function isSertifikatValid() {
        if ($this->sertifikatKampusMerdeka) {
            // Cek apakah sertifikat sudah divalidasi (contoh: cek pola MSIB-XXX)
            return preg_match('/^MSIB-\d{3}$/', $this->sertifikatKampusMerdeka);
        }
        return false;
    }

    public function konversiStatus() {
        $masaKerja = $this->hitungMasaKerja();
        $tahun = (int)explode(' ', $masaKerja)[0];
        
        if ($tahun >= 1) {
            return "Berhak diangkat menjadi karyawan tetap";
        } elseif ($tahun >= 6) {
            return "Berhak diperpanjang masa magang";
        } else {
            return "Dalam masa evaluasi magang";
        }
    }

    // Override method simpanKeDatabase untuk menyertakan properti tambahan
    public function simpanKeDatabase() {
        $nama = $this->db->escapeString($this->getNamaKaryawan());
        $departemen = $this->db->escapeString($this->getDepartemen());
        $sertifikat = $this->db->escapeString($this->sertifikatKampusMerdeka);
        
        $sql = "INSERT INTO tabel_karyawan 
                (nama_karyawan, departemen, hari_kerja_masuk, gaji_dasar_per_hari, 
                 uang_saku_bulanan, sertifikat_kampus_merdeka) 
                VALUES 
                ('$nama', '$departemen', '{$this->getHariKerjaMasuk()}', '{$this->getGajiDasarPerHari()}', 
                 '{$this->uangSakuBulanan}', '$sertifikat')";
        
        return $this->db->execute($sql);
    }
}

?>