<?php
/**
 * File: classes/karyawanKontrak.php
 * Update method hitungGajiBersih()
 */

require_once 'karyawan.php';

class KaryawanKontrak extends Karyawan {
    private $durasiKontrakBulan;
    private $agensiPenyalur;

    // Constructor
    public function __construct(
        $id_karyawan = null,
        $nama_karyawan = null,
        $departemen = null,
        $hariKerjaMasuk = null,
        $gajiDasarPerHari = null,
        $durasiKontrakBulan = 0,
        $agensiPenyalur = null
    ) {
        parent::__construct(
            $id_karyawan,
            $nama_karyawan,
            $departemen,
            $hariKerjaMasuk,
            $gajiDasarPerHari
        );
        
        $this->durasiKontrakBulan = $durasiKontrakBulan;
        $this->agensiPenyalur = $agensiPenyalur;
    }

    // Getter dan Setter
    public function getDurasiKontrakBulan() {
        return $this->durasiKontrakBulan;
    }

    public function getAgensiPenyalur() {
        return $this->agensiPenyalur;
    }

    public function setDurasiKontrakBulan($durasiKontrakBulan) {
        $this->durasiKontrakBulan = $durasiKontrakBulan;
    }

    public function setAgensiPenyalur($agensiPenyalur) {
        $this->agensiPenyalur = $agensiPenyalur;
    }

    /**
     * METHOD OVERRIDING - hitungGajiBersih()
     * Logika: Gaji murni berdasarkan jumlah hari kehadiran
     * Formula: hariKerjaMasuk * gajiDasarPerHari
     * Tidak ada potongan pajak, tidak ada tunjangan
     */
    public function hitungGajiBersih() {
        // Hitung jumlah hari kerja dari tanggal masuk sampai sekarang
        $hariKerja = $this->hitungHariKerja();
        
        // Gaji bersih = jumlah hari kerja * gaji dasar per hari
        // MURNI berdasarkan kehadiran, tanpa potongan apapun
        $gajiBersih = $hariKerja * $this->getGajiDasarPerHari();
        
        return $gajiBersih;
    }

    // Implementasi abstract method tampilkanProfilKaryawan
    public function tampilkanProfilKaryawan() {
        // Hitung sisa kontrak
        $tanggalMasuk = new DateTime($this->getHariKerjaMasuk());
        $tanggalSekarang = new DateTime('now');
        $selisihBulan = $tanggalMasuk->diff($tanggalSekarang);
        $bulanBerjalan = ($selisihBulan->y * 12) + $selisihBulan->m;
        $sisaKontrak = max(0, $this->durasiKontrakBulan - $bulanBerjalan);
        
        ?>
        <div class="profile-card profile-kontrak">
            <div class="profile-header">
                <h3>📄 Karyawan Kontrak</h3>
                <span class="badge badge-kontrak">Status: Kontrak</span>
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
                        <td><strong>Durasi Kontrak</strong></td>
                        <td>: <?php echo $this->durasiKontrakBulan; ?> bulan</td>
                    </tr>
                    <tr>
                        <td><strong>Sisa Kontrak</strong></td>
                        <td>: <?php echo $sisaKontrak; ?> bulan</td>
                    </tr>
                    <tr>
                        <td><strong>Agensi Penyalur</strong></td>
                        <td>: <?php echo htmlspecialchars($this->agensiPenyalur); ?></td>
                    </tr>
                    <tr>
                        <td style="background: #e3f2fd; font-weight: bold;">Gaji Bersih (MURNI)</td>
                        <td style="background: #e3f2fd; font-weight: bold; color: #1976d2;">
                            Rp <?php echo number_format($this->hitungGajiBersih(), 0, ',', '.'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 12px; color: #666; text-align: center;">
                            * Sistem pengujian murni berdasarkan jumlah hari kehadiran
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <style>
            .profile-kontrak { border-left: 5px solid #FF9800; }
            .badge-kontrak { background: #FF9800; color: white; }
            .profile-table td { padding: 8px 12px; border-bottom: 1px solid #eee; }
        </style>
        <?php
    }

    // Method spesifik
    public function cekStatusKontrak() {
        $tanggalMasuk = new DateTime($this->getHariKerjaMasuk());
        $tanggalSekarang = new DateTime('now');
        $selisihBulan = $tanggalMasuk->diff($tanggalSekarang);
        $bulanBerjalan = ($selisihBulan->y * 12) + $selisihBulan->m;
        
        $sisaKontrak = $this->durasiKontrakBulan - $bulanBerjalan;
        
        if ($sisaKontrak <= 0) {
            return "Kontrak telah berakhir";
        } elseif ($sisaKontrak <= 1) {
            return "Kontrak akan berakhir dalam 1 bulan - Segera perpanjang!";
        } elseif ($sisaKontrak <= 3) {
            return "Kontrak akan berakhir dalam {$sisaKontrak} bulan";
        } else {
            return "Kontrak masih aktif ({$sisaKontrak} bulan tersisa)";
        }
    }

    public function perpanjangKontrak($bulanTambahan) {
        $this->durasiKontrakBulan += $bulanTambahan;
        return "Kontrak diperpanjang {$bulanTambahan} bulan. Total durasi: {$this->durasiKontrakBulan} bulan";
    }

    // Override method simpanKeDatabase
    public function simpanKeDatabase() {
        $nama = $this->db->escapeString($this->getNamaKaryawan());
        $departemen = $this->db->escapeString($this->getDepartemen());
        $agensi = $this->db->escapeString($this->agensiPenyalur);
        
        $sql = "INSERT INTO tabel_karyawan 
                (nama_karyawan, departemen, hari_kerja_masuk, gaji_dasar_per_hari, 
                 durasi_kontrak_bulan, agensi_penyalur) 
                VALUES 
                ('$nama', '$departemen', '{$this->getHariKerjaMasuk()}', '{$this->getGajiDasarPerHari()}', 
                 '{$this->durasiKontrakBulan}', '$agensi')";
        
        return $this->db->execute($sql);
    }
}

?>