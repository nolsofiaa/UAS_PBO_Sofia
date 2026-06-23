<?php
/**
 * File: classes/karyawanTetap.php
 * Class KaryawanTetap - turunan dari abstract class Karyawan
 * Mewakili karyawan dengan status tetap
 */

require_once 'karyawan.php';

class KaryawanTetap extends Karyawan {
    // Properti tambahan spesifik untuk karyawan tetap
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
        // Panggil constructor parent
        parent::__construct(
            $id_karyawan,
            $nama_karyawan,
            $departemen,
            $hariKerjaMasuk,
            $gajiDasarPerHari
        );
        
        // Inisialisasi properti spesifik
        $this->tunjanganKesehatan = $tunjanganKesehatan;
        $this->opsiSahamId = $opsiSahamId;
    }

    // Getter untuk properti tambahan
    public function getTunjanganKesehatan() {
        return $this->tunjanganKesehatan;
    }

    public function getOpsiSahamId() {
        return $this->opsiSahamId;
    }

    // Setter untuk properti tambahan
    public function setTunjanganKesehatan($tunjanganKesehatan) {
        $this->tunjanganKesehatan = $tunjanganKesehatan;
    }

    public function setOpsiSahamId($opsiSahamId) {
        $this->opsiSahamId = $opsiSahamId;
    }

    // Implementasi abstract method hitungGajiBersih
    public function hitungGajiBersih() {
        // Gaji kotor dari parent
        $gajiKotor = $this->hitungGajiKotor();
        
        // Perhitungan pajak (5% untuk karyawan tetap)
        $pajak = $gajiKotor * 0.05;
        
        // Tunjangan kesehatan
        $tunjangan = $this->tunjanganKesehatan;
        
        // Bonus dari opsi saham (jika ada)
        $bonusSaham = 0;
        if ($this->opsiSahamId !== null && $this->opsiSahamId !== '') {
            $bonusSaham = $gajiKotor * 0.02; // Bonus 2% dari gaji kotor
        }
        
        // Gaji bersih = gaji kotor - pajak + tunjangan + bonus saham
        $gajiBersih = $gajiKotor - $pajak + $tunjangan + $bonusSaham;
        
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
                        <td><strong>Masa Kerja</strong></td>
                        <td>: <?php echo $this->hitungMasaKerja(); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Gaji Dasar/Hari</strong></td>
                        <td>: Rp <?php echo number_format($this->getGajiDasarPerHari(), 0, ',', '.'); ?></td>
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
            .profile-card { border: 1px solid #ddd; border-radius: 10px; margin: 15px 0; overflow: hidden; }
            .profile-tetap { border-left: 5px solid #4CAF50; }
            .profile-header { background: #f8f9fa; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
            .profile-header h3 { margin: 0; color: #333; }
            .badge { padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; }
            .badge-tetap { background: #4CAF50; color: white; }
            .profile-body { padding: 20px; }
            .profile-table { width: 100%; border-collapse: collapse; }
            .profile-table td { padding: 8px 12px; border-bottom: 1px solid #eee; }
            .profile-table tr:last-child td { border-bottom: none; }
        </style>
        <?php
    }

    // Method khusus untuk karyawan tetap
    public function hitungBonusTahunan() {
        $gajiKotor = $this->hitungGajiKotor();
        $masaKerjaTahun = (int)explode(' ', $this->hitungMasaKerja())[0];
        
        // Bonus tahunan: 1 bulan gaji + tambahan per tahun kerja
        $bonus = $gajiKotor;
        if ($masaKerjaTahun >= 5) {
            $bonus += $gajiKotor * 0.5; // Bonus 50% untuk masa kerja >5 tahun
        } elseif ($masaKerjaTahun >= 3) {
            $bonus += $gajiKotor * 0.3; // Bonus 30% untuk masa kerja >3 tahun
        }
        
        return $bonus;
    }

    // Override method simpanKeDatabase untuk menyertakan properti tambahan
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