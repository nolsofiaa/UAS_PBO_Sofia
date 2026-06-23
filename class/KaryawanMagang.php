<?php
/**
 * File: classes/karyawanMagang.php
 * Update method hitungGajiBersih()
 */

require_once 'karyawan.php';

class KaryawanMagang extends Karyawan {
    private $uangSakuBulanan;
    private $sertifikatKampusMerdeka;
    private $plafonHarian = 150000; // Plafon harian untuk biaya program

    // Constructor
    public function __construct(
        $id_karyawan = null,
        $nama_karyawan = null,
        $departemen = null,
        $hariKerjaMasuk = null,
        $gajiDasarPerHari = null,
        $uangSakuBulanan = 0,
        $sertifikatKampusMerdeka = null,
        $plafonHarian = 150000
    ) {
        parent::__construct(
            $id_karyawan,
            $nama_karyawan,
            $departemen,
            $hariKerjaMasuk,
            $gajiDasarPerHari
        );
        
        $this->uangSakuBulanan = $uangSakuBulanan;
        $this->sertifikatKampusMerdeka = $sertifikatKampusMerdeka;
        $this->plafonHarian = $plafonHarian;
    }

    // Getter dan Setter
    public function getUangSakuBulanan() {
        return $this->uangSakuBulanan;
    }

    public function getSertifikatKampusMerdeka() {
        return $this->sertifikatKampusMerdeka;
    }

    public function getPlafonHarian() {
        return $this->plafonHarian;
    }

    public function setUangSakuBulanan($uangSakuBulanan) {
        $this->uangSakuBulanan = $uangSakuBulanan;
    }

    public function setSertifikatKampusMerdeka($sertifikatKampusMerdeka) {
        $this->sertifikatKampusMerdeka = $sertifikatKampusMerdeka;
    }

    public function setPlafonHarian($plafonHarian) {
        $this->plafonHarian = $plafonHarian;
    }

    /**
     * METHOD OVERRIDING - hitungGajiBersih()
     * Logika: Gaji = (hariKerja * plafonHarian) + uangSakuBulanan
     * Plafon harian untuk biaya program orientasi, pelatihan, atau asuransi kerja intern
     */
    public function hitungGajiBersih() {
        // Hitung jumlah hari kerja dari tanggal masuk sampai sekarang
        $hariKerja = $this->hitungHariKerja();
        
        // Biaya program berdasarkan plafon harian
        $biayaProgram = $hariKerja * $this->plafonHarian;
        
        // Gaji bersih = biaya program + uang saku bulanan
        $gajiBersih = $biayaProgram + $this->uangSakuBulanan;
        
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
                        <td><strong>Hari Kerja</strong></td>
                        <td>: <?php echo number_format($this->hitungHariKerja()); ?> hari</td>
                    </tr>
                    <tr>
                        <td><strong>Plafon Harian</strong></td>
                        <td>: Rp <?php echo number_format($this->plafonHarian, 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Biaya Program (Hari × Plafon)</strong></td>
                        <td>: Rp <?php echo number_format($this->hitungHariKerja() * $this->plafonHarian, 0, ',', '.'); ?></td>
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
                        <td style="background: #e3f2fd; font-weight: bold;">Gaji Bersih (Program)</td>
                        <td style="background: #e3f2fd; font-weight: bold; color: #1976d2;">
                            Rp <?php echo number_format($this->hitungGajiBersih(), 0, ',', '.'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 12px; color: #666; text-align: center;">
                            * Gaji = (hari kerja × plafon harian) + uang saku bulanan
                        </td>
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

    // Method spesifik
    public function getSertifikatUrl() {
        if ($this->sertifikatKampusMerdeka) {
            return "sertifikat/" . $this->sertifikatKampusMerdeka . ".pdf";
        }
        return null;
    }

    public function isSertifikatValid() {
        if ($this->sertifikatKampusMerdeka) {
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

    // Override method simpanKeDatabase
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