<?php
/**
 * File: classes/karyawanTetap.php
 * Update method hitungGajiBersih()
 */

require_once 'karyawan.php';

class KaryawanTetap extends Karyawan {
    private $tunjanganKesehatan;
    private $opsiSahamId;

    // Constructor
    public function __construct(
        $id_karyawan = null,
        $nama_karyawan = null,
        $departemen = null,
        $hariKerjaMasuk = null,
        $gajiDasarPerHari = null,
        $tunjanganKesehatan = 0,
        $opsiSahamId = null
    ) {
        parent::__construct(
            $id_karyawan,
            $nama_karyawan,
            $departemen,
            $hariKerjaMasuk,
            $gajiDasarPerHari
        );
        
        $this->tunjanganKesehatan = $tunjanganKesehatan;
        $this->opsiSahamId = $opsiSahamId;
    }

    // Getter dan Setter
    public function getTunjanganKesehatan() {
        return $this->tunjanganKesehatan;
    }

    public function getOpsiSahamId() {
        return $this->opsiSahamId;
    }

    public function setTunjanganKesehatan($tunjanganKesehatan) {
        $this->tunjanganKesehatan = $tunjanganKesehatan;
    }

    public function setOpsiSahamId($opsiSahamId) {
        $this->opsiSahamId = $opsiSahamId;
    }

    /**
     * METHOD OVERRIDING - hitungGajiBersih()
     * Logika: Gaji = (hariKerja * gajiDasarPerHari) + tunjanganKesehatan
     * Mendapatkan tambahan tunjangan kesehatan
     */
    public function hitungGajiBersih() {
        // Hitung jumlah hari kerja dari tanggal masuk sampai sekarang
        $hariKerja = $this->hitungHariKerja();
        
        // Hitung gaji dasar dari jumlah hari kerja
        $gajiDasar = $hariKerja * $this->getGajiDasarPerHari();
        
        // Gaji bersih = gaji dasar + tunjangan kesehatan
        // Mendapatkan tambahan tunjangan kesehatan
        $gajiBersih = $gajiDasar + $this->tunjanganKesehatan;
        
        return $gajiBersih;
    }

    // Implementasi abstract method tampilkanProfilKaryawan
    public function tampilkanProfilKaryawan() {
        ?>
        <div class="profile-card profile-tetap">
            <div class="profile-header">
                <h3>👔 Karyawan Tetap</h3>
                <span class="badge badge-tetap">Status: Tetap</span>
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
                        <td><strong>Gaji Dasar/Hari</strong></td>
                        <td>: Rp <?php echo number_format($this->getGajiDasarPerHari(), 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Gaji Dasar (Hari Kerja)</strong></td>
                        <td>: Rp <?php echo number_format($this->hitungHariKerja() * $this->getGajiDasarPerHari(), 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tunjangan Kesehatan</strong></td>
                        <td>: Rp <?php echo number_format($this->tunjanganKesehatan, 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Opsi Saham ID</strong></td>
                        <td>: <?php echo htmlspecialchars($this->opsiSahamId ?? 'Tidak ada'); ?></td>
                    </tr>
                    <tr>
                        <td style="background: #e8f5e9; font-weight: bold;">Gaji Bersih (+ Tunjangan)</td>
                        <td style="background: #e8f5e9; font-weight: bold; color: #2e7d32;">
                            Rp <?php echo number_format($this->hitungGajiBersih(), 0, ',', '.'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 12px; color: #666; text-align: center;">
                            * Gaji = (hari kerja × gaji dasar/hari) + tunjangan kesehatan
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <style>
            .profile-tetap { border-left: 5px solid #4CAF50; }
            .badge-tetap { background: #4CAF50; color: white; }
        </style>
        <?php
    }

    // Method spesifik
    public function hitungBonusTahunan() {
        $gajiDasar = $this->hitungHariKerja() * $this->getGajiDasarPerHari();
        $masaKerjaTahun = (int)explode(' ', $this->hitungMasaKerja())[0];
        
        $bonus = $gajiDasar;
        if ($masaKerjaTahun >= 5) {
            $bonus += $gajiDasar * 0.5;
        } elseif ($masaKerjaTahun >= 3) {
            $bonus += $gajiDasar * 0.3;
        }
        
        return $bonus;
    }

    // Override method simpanKeDatabase
    public function simpanKeDatabase() {
        $nama = $this->db->escapeString($this->getNamaKaryawan());
        $departemen = $this->db->escapeString($this->getDepartemen());
        
        $sql = "INSERT INTO tabel_karyawan 
                (nama_karyawan, departemen, hari_kerja_masuk, gaji_dasar_per_hari, 
                 tunjangan_kesehatan, opsi_saham_id) 
                VALUES 
                ('$nama', '$departemen', '{$this->getHariKerjaMasuk()}', '{$this->getGajiDasarPerHari()}', 
                 '{$this->tunjanganKesehatan}', '{$this->opsiSahamId}')";
        
        return $this->db->execute($sql);
    }
}

?>