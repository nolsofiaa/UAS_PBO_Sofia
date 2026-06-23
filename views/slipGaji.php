<?php
/**
 * File: views/slipGaji.php
 * Halaman untuk menampilkan slip gaji per karyawan
 */

require_once '../classes/karyawan.php';
require_once '../classes/karyawanTetap.php';
require_once '../classes/karyawanKontrak.php';
require_once '../classes/karyawanMagang.php';
require_once '../koneksi/database.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: ../index.php');
    exit;
}

// Koneksi database
$db = new Database();
$conn = $db->getConnection();

// Ambil data karyawan
$query = "SELECT * FROM tabel_karyawan WHERE id_karyawan = $id";
$result = $db->query($query);

if ($result->num_rows == 0) {
    header('Location: ../index.php');
    exit;
}

$data = $result->fetch_assoc();

// Buat objek karyawan sesuai jenisnya
$karyawan = null;
$jenis = '';
$spesifikasi = [];

if ($data['opsi_saham_id'] !== null || $data['tunjangan_kesehatan'] !== null) {
    $karyawan = new KaryawanTetap(
        $data['id_karyawan'],
        $data['nama_karyawan'],
        $data['departemen'],
        $data['hari_kerja_masuk'],
        $data['gaji_dasar_per_hari'],
        $data['tunjangan_kesehatan'] ?? 0,
        $data['opsi_saham_id'] ?? null
    );
    $jenis = 'Tetap';
    $spesifikasi = [
        'Tunjangan Kesehatan' => 'Rp ' . number_format($data['tunjangan_kesehatan'] ?? 0, 0, ',', '.'),
        'Opsi Saham ID' => $data['opsi_saham_id'] ?? 'Tidak ada'
    ];
} elseif ($data['durasi_kontrak_bulan'] !== null || $data['agensi_penyalur'] !== null) {
    $karyawan = new KaryawanKontrak(
        $data['id_karyawan'],
        $data['nama_karyawan'],
        $data['departemen'],
        $data['hari_kerja_masuk'],
        $data['gaji_dasar_per_hari'],
        $data['durasi_kontrak_bulan'] ?? 0,
        $data['agensi_penyalur'] ?? null
    );
    $jenis = 'Kontrak';
    $spesifikasi = [
        'Durasi Kontrak' => $data['durasi_kontrak_bulan'] . ' bulan',
        'Agensi Penyalur' => $data['agensi_penyalur'] ?? '-'
    ];
} elseif ($data['uang_saku_bulanan'] !== null || $data['sertifikat_kampus_merdeka'] !== null) {
    $karyawan = new KaryawanMagang(
        $data['id_karyawan'],
        $data['nama_karyawan'],
        $data['departemen'],
        $data['hari_kerja_masuk'],
        $data['gaji_dasar_per_hari'],
        $data['uang_saku_bulanan'] ?? 0,
        $data['sertifikat_kampus_merdeka'] ?? null
    );
    $jenis = 'Magang';
    $spesifikasi = [
        'Uang Saku Bulanan' => 'Rp ' . number_format($data['uang_saku_bulanan'] ?? 0, 0, ',', '.'),
        'Sertifikat Kampus Merdeka' => $data['sertifikat_kampus_merdeka'] ?? 'Belum ada'
    ];
}

include 'header.php';
?>

<div class="container">
    <div class="slip-gaji-container">
        <div class="slip-header">
            <h1><i class="fas fa-file-invoice"></i> Slip Gaji Karyawan</h1>
            <a href="../index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>

        <div class="slip-card">
            <div class="slip-title">
                <h2>SLIP GAJI BULANAN</h2>
                <p>Periode: <?php echo date('F Y'); ?></p>
            </div>

            <div class="slip-body">
                <div class="slip-row">
                    <div class="slip-section">
                        <h3><i class="fas fa-user"></i> Informasi Karyawan</h3>
                        <table class="slip-table">
                            <tr>
                                <td>ID Karyawan</td>
                                <td>:</td>
                                <td><strong><?php echo $data['id_karyawan']; ?></strong></td>
                            </tr>
                            <tr>
                                <td>Nama Karyawan</td>
                                <td>:</td>
                                <td><strong><?php echo htmlspecialchars($data['nama_karyawan']); ?></strong></td>
                            </tr>
                            <tr>
                                <td>Departemen</td>
                                <td>:</td>
                                <td><?php echo htmlspecialchars($data['departemen']); ?></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>
                                    <span class="badge badge-<?php echo strtolower($jenis); ?>">
                                        <?php echo $jenis; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Masuk</td>
                                <td>:</td>
                                <td><?php echo date('d F Y', strtotime($data['hari_kerja_masuk'])); ?></td>
                            </tr>
                            <tr>
                                <td>Masa Kerja</td>
                                <td>:</td>
                                <td><?php echo $karyawan->hitungMasaKerja(); ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="slip-section">
                        <h3><i class="fas fa-info-circle"></i> Spesifikasi <?php echo $jenis; ?></h3>
                        <table class="slip-table">
                            <tr>
                                <td>Gaji Dasar per Hari</td>
                                <td>:</td>
                                <td>Rp <?php echo number_format($data['gaji_dasar_per_hari'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td>Total Hari Kerja</td>
                                <td>:</td>
                                <td><?php echo $karyawan->hitungHariKerja(); ?> hari</td>
                            </tr>
                            <?php foreach ($spesifikasi as $key => $value): ?>
                            <tr>
                                <td><?php echo $key; ?></td>
                                <td>:</td>
                                <td><?php echo $value; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>

                <div class="slip-row">
                    <div class="slip-section full-width">
                        <h3><i class="fas fa-calculator"></i> Perhitungan Gaji</h3>
                        <table class="slip-table calculation">
                            <tr>
                                <td>Rumus Perhitungan</td>
                                <td>:</td>
                                <td>
                                    <?php
                                    $rumus = '';
                                    switch ($jenis) {
                                        case 'Tetap':
                                            $rumus = '(Hari Kerja × Gaji/Hari) + Tunjangan Kesehatan';
                                            break;
                                        case 'Kontrak':
                                            $rumus = 'Hari Kerja × Gaji/Hari (Murni)';
                                            break;
                                        case 'Magang':
                                            $rumus = '(Hari Kerja × Plafon Harian) + Uang Saku';
                                            break;
                                    }
                                    echo $rumus;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Perhitungan Detail</td>
                                <td>:</td>
                                <td>
                                    <?php
                                    $detail = '';
                                    $hariKerja = $karyawan->hitungHariKerja();
                                    $gajiPerHari = $data['gaji_dasar_per_hari'];
                                    
                                    switch ($jenis) {
                                        case 'Tetap':
                                            $tunjangan = $data['tunjangan_kesehatan'] ?? 0;
                                            $detail = "($hariKerja × $gajiPerHari) + " . number_format($tunjangan, 0, ',', '.');
                                            break;
                                        case 'Kontrak':
                                            $detail = "$hariKerja × $gajiPerHari";
                                            break;
                                        case 'Magang':
                                            $plafon = 150000; // Default plafon
                                            $uangSaku = $data['uang_saku_bulanan'] ?? 0;
                                            $detail = "($hariKerja × " . number_format($plafon, 0, ',', '.') . ") + " . number_format($uangSaku, 0, ',', '.');
                                            break;
                                    }
                                    echo $detail;
                                    ?>
                                </td>
                            </tr>
                            <tr class="total-row">
                                <td><strong>TOTAL GAJI BERSIH</strong></td>
                                <td>:</td>
                                <td>
                                    <strong style="font-size: 24px; color: #2e7d32;">
                                        Rp <?php echo number_format($karyawan->hitungGajiBersih(), 0, ',', '.'); ?>
                                    </strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="slip-footer">
                    <div class="signature">
                        <p>Dikeluarkan oleh,</p>
                        <div class="signature-line">
                            <p>_________________________</p>
                            <p><strong>HRD Manager</strong></p>
                        </div>
                    </div>
                    <div class="signature">
                        <p>Diterima oleh,</p>
                        <div class="signature-line">
                            <p>_________________________</p>
                            <p><strong><?php echo htmlspecialchars($data['nama_karyawan']); ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="print-section">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Cetak Slip
            </button>
        </div>
    </div>
</div>

<style>
.slip-gaji-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

.slip-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.slip-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    overflow: hidden;
}

.slip-title {
    background: linear-gradient(135deg, #1a237e, #0d47a1);
    color: white;
    padding: 20px 30px;
    text-align: center;
}

.slip-title h2 {
    margin: 0;
    font-size: 28px;
    letter-spacing: 3px;
}

.slip-title p {
    margin: 5px 0 0;
    opacity: 0.8;
}

.slip-body {
    padding: 30px;
}

.slip-row {
    display: flex;
    gap: 30px;
    margin-bottom: 30px;
}

.slip-section {
    flex: 1;
}

.slip-section.full-width {
    flex: 1 1 100%;
}

.slip-section h3 {
    color: #1a237e;
    border-bottom: 2px solid #e3f2fd;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.slip-table {
    width: 100%;
    border-collapse: collapse;
}

.slip-table td {
    padding: 8px 10px;
    border-bottom: 1px solid #f5f5f5;
}

.slip-table td:first-child {
    font-weight: 600;
    color: #555;
    width: 35%;
}

.slip-table td:nth-child(2) {
    width: 5%;
    text-align: center;
}

.slip-table td:last-child {
    font-weight: 500;
}

.slip-table.calculation td {
    padding: 12px 10px;
}

.slip-table .total-row td {
    background: #e8f5e9;
    border-top: 2px solid #4caf50;
    padding: 15px 10px;
}

.slip-footer {
    display: flex;
    justify-content: space-between;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid #e0e0e0;
}

.signature {
    text-align: center;
    width: 200px;
}

.signature-line {
    margin-top: 40px;
}

.print-section {
    text-align: center;
    margin-top: 30px;
}

.btn {
    display: inline-block;
    padding: 12px 24px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-primary {
    background: #1a237e;
    color: white;
}

.btn-primary:hover {
    background: #0d47a1;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #757575;
    color: white;
}

.btn-secondary:hover {
    background: #616161;
}

@media print {
    .navbar, .footer, .print-section, .slip-header .btn {
        display: none !important;
    }
    .slip-card {
        box-shadow: none !important;
        border: 1px solid #ddd;
    }
    .slip-title {
        background: #1a237e !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .slip-table .total-row td {
        background: #e8f5e9 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}

@media (max-width: 768px) {
    .slip-row {
        flex-direction: column;
        gap: 20px;
    }
    .slip-footer {
        flex-direction: column;
        align-items: center;
        gap: 30px;
    }
}
</style>

<?php
include 'footer.php';
$db->closeConnection();
?>