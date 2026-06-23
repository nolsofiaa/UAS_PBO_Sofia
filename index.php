<?php
/**
 * File: index.php
 * Halaman utama untuk menampilkan daftar karyawan dan slip gaji
 */

// Include semua class yang diperlukan
require_once 'classes/karyawan.php';
require_once 'classes/karyawanTetap.php';
require_once 'classes/karyawanKontrak.php';
require_once 'classes/karyawanMagang.php';

// Koneksi database
require_once 'koneksi/database.php';
$db = new Database();
$conn = $db->getConnection();

// Ambil semua data karyawan dari database
$query = "SELECT * FROM tabel_karyawan ORDER BY id_karyawan";
$result = $db->query($query);

// Kelompokkan data berdasarkan jenis karyawan
$dataTetap = [];
$dataKontrak = [];
$dataMagang = [];
$dataLainnya = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Cek jenis karyawan berdasarkan atribut yang terisi
        if ($row['opsi_saham_id'] !== null || $row['tunjangan_kesehatan'] !== null) {
            $dataTetap[] = $row;
        } elseif ($row['durasi_kontrak_bulan'] !== null || $row['agensi_penyalur'] !== null) {
            $dataKontrak[] = $row;
        } elseif ($row['uang_saku_bulanan'] !== null || $row['sertifikat_kampus_merdeka'] !== null) {
            $dataMagang[] = $row;
        } else {
            $dataLainnya[] = $row;
        }
    }
}

// Include header
include 'views/header.php';
?>

<div class="container">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <h1>🏢 Sistem Informasi Kepegawaian</h1>
        <p class="subtitle">Kelola dan lihat informasi karyawan serta slip gaji secara dinamis</p>
        <div class="stats">
            <div class="stat-card">
                <span class="stat-number"><?php echo count($dataTetap); ?></span>
                <span class="stat-label">Karyawan Tetap</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo count($dataKontrak); ?></span>
                <span class="stat-label">Karyawan Kontrak</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo count($dataMagang); ?></span>
                <span class="stat-label">Karyawan Magang</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo count($dataTetap) + count($dataKontrak) + count($dataMagang); ?></span>
                <span class="stat-label">Total Karyawan</span>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="tabs">
        <button class="tab-btn active" onclick="showTab('semua')">📋 Semua Karyawan</button>
        <button class="tab-btn" onclick="showTab('tetap')">👔 Karyawan Tetap</button>
        <button class="tab-btn" onclick="showTab('kontrak')">📄 Karyawan Kontrak</button>
        <button class="tab-btn" onclick="showTab('magang')">🎓 Karyawan Magang</button>
    </div>

    <!-- All Employees -->
    <div id="tab-semua" class="tab-content active">
        <h2>📋 Semua Karyawan</h2>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>Tanggal Masuk</th>
                        <th>Gaji/Hari</th>
                        <th>Jenis</th>
                        <th>Gaji Bersih</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Tampilkan semua data
                    $allData = array_merge($dataTetap, $dataKontrak, $dataMagang, $dataLainnya);
                    foreach ($allData as $data):
                        $karyawan = null;
                        $jenis = '';
                        
                        if (isset($data['opsi_saham_id']) && $data['opsi_saham_id'] !== null) {
                            $karyawan = new KaryawanTetap(
                                $data['id_karyawan'],
                                $data['nama_karyawan'],
                                $data['departemen'],
                                $data['hari_kerja_masuk'],
                                $data['gaji_dasar_per_hari'],
                                $data['tunjangan_kesehatan'] ?? 0,
                                $data['opsi_saham_id']
                            );
                            $jenis = '<span class="badge badge-tetap">Tetap</span>';
                        } elseif (isset($data['durasi_kontrak_bulan']) && $data['durasi_kontrak_bulan'] !== null) {
                            $karyawan = new KaryawanKontrak(
                                $data['id_karyawan'],
                                $data['nama_karyawan'],
                                $data['departemen'],
                                $data['hari_kerja_masuk'],
                                $data['gaji_dasar_per_hari'],
                                $data['durasi_kontrak_bulan'],
                                $data['agensi_penyalur']
                            );
                            $jenis = '<span class="badge badge-kontrak">Kontrak</span>';
                        } elseif (isset($data['uang_saku_bulanan']) && $data['uang_saku_bulanan'] !== null) {
                            $karyawan = new KaryawanMagang(
                                $data['id_karyawan'],
                                $data['nama_karyawan'],
                                $data['departemen'],
                                $data['hari_kerja_masuk'],
                                $data['gaji_dasar_per_hari'],
                                $data['uang_saku_bulanan'],
                                $data['sertifikat_kampus_merdeka'] ?? null
                            );
                            $jenis = '<span class="badge badge-magang">Magang</span>';
                        }
                        
                        if ($karyawan):
                    ?>
                    <tr>
                        <td><?php echo $data['id_karyawan']; ?></td>
                        <td><?php echo htmlspecialchars($data['nama_karyawan']); ?></td>
                        <td><?php echo htmlspecialchars($data['departemen']); ?></td>
                        <td><?php echo date('d-m-Y', strtotime($data['hari_kerja_masuk'])); ?></td>
                        <td>Rp <?php echo number_format($data['gaji_dasar_per_hari'], 0, ',', '.'); ?></td>
                        <td><?php echo $jenis; ?></td>
                        <td><strong>Rp <?php echo number_format($karyawan->hitungGajiBersih(), 0, ',', '.'); ?></strong></td>
                        <td>
                            <a href="views/slipGaji.php?id=<?php echo $data['id_karyawan']; ?>" class="btn-sm btn-primary">Slip</a>
                        </td>
                    </tr>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab Karyawan Tetap -->
    <div id="tab-tetap" class="tab-content">
        <h2>👔 Karyawan Tetap</h2>
        <div class="card-grid">
            <?php foreach ($dataTetap as $data): 
                $karyawan = new KaryawanTetap(
                    $data['id_karyawan'],
                    $data['nama_karyawan'],
                    $data['departemen'],
                    $data['hari_kerja_masuk'],
                    $data['gaji_dasar_per_hari'],
                    $data['tunjangan_kesehatan'] ?? 0,
                    $data['opsi_saham_id'] ?? null
                );
            ?>
            <div class="card card-tetap">
                <div class="card-header">
                    <h3><?php echo htmlspecialchars($data['nama_karyawan']); ?></h3>
                    <span class="badge badge-tetap">Tetap</span>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> <?php echo $data['id_karyawan']; ?></p>
                    <p><strong>Departemen:</strong> <?php echo htmlspecialchars($data['departemen']); ?></p>
                    <p><strong>Tanggal Masuk:</strong> <?php echo date('d-m-Y', strtotime($data['hari_kerja_masuk'])); ?></p>
                    <p><strong>Gaji/Hari:</strong> Rp <?php echo number_format($data['gaji_dasar_per_hari'], 0, ',', '.'); ?></p>
                    <p><strong>Tunjangan Kesehatan:</strong> Rp <?php echo number_format($data['tunjangan_kesehatan'] ?? 0, 0, ',', '.'); ?></p>
                    <p><strong>Opsi Saham:</strong> <?php echo $data['opsi_saham_id'] ?? 'Tidak ada'; ?></p>
                    <p><strong>Gaji Bersih:</strong> <span class="salary">Rp <?php echo number_format($karyawan->hitungGajiBersih(), 0, ',', '.'); ?></span></p>
                </div>
                <div class="card-footer">
                    <a href="views/slipGaji.php?id=<?php echo $data['id_karyawan']; ?>" class="btn-sm btn-primary">Lihat Slip</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Tab Karyawan Kontrak -->
    <div id="tab-kontrak" class="tab-content">
        <h2>📄 Karyawan Kontrak</h2>
        <div class="card-grid">
            <?php foreach ($dataKontrak as $data): 
                $karyawan = new KaryawanKontrak(
                    $data['id_karyawan'],
                    $data['nama_karyawan'],
                    $data['departemen'],
                    $data['hari_kerja_masuk'],
                    $data['gaji_dasar_per_hari'],
                    $data['durasi_kontrak_bulan'] ?? 0,
                    $data['agensi_penyalur'] ?? null
                );
            ?>
            <div class="card card-kontrak">
                <div class="card-header">
                    <h3><?php echo htmlspecialchars($data['nama_karyawan']); ?></h3>
                    <span class="badge badge-kontrak">Kontrak</span>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> <?php echo $data['id_karyawan']; ?></p>
                    <p><strong>Departemen:</strong> <?php echo htmlspecialchars($data['departemen']); ?></p>
                    <p><strong>Tanggal Masuk:</strong> <?php echo date('d-m-Y', strtotime($data['hari_kerja_masuk'])); ?></p>
                    <p><strong>Gaji/Hari:</strong> Rp <?php echo number_format($data['gaji_dasar_per_hari'], 0, ',', '.'); ?></p>
                    <p><strong>Durasi Kontrak:</strong> <?php echo $data['durasi_kontrak_bulan']; ?> bulan</p>
                    <p><strong>Agensi Penyalur:</strong> <?php echo htmlspecialchars($data['agensi_penyalur']); ?></p>
                    <p><strong>Gaji Bersih:</strong> <span class="salary">Rp <?php echo number_format($karyawan->hitungGajiBersih(), 0, ',', '.'); ?></span></p>
                </div>
                <div class="card-footer">
                    <a href="views/slipGaji.php?id=<?php echo $data['id_karyawan']; ?>" class="btn-sm btn-primary">Lihat Slip</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Tab Karyawan Magang -->
    <div id="tab-magang" class="tab-content">
        <h2>🎓 Karyawan Magang</h2>
        <div class="card-grid">
            <?php foreach ($dataMagang as $data): 
                $karyawan = new KaryawanMagang(
                    $data['id_karyawan'],
                    $data['nama_karyawan'],
                    $data['departemen'],
                    $data['hari_kerja_masuk'],
                    $data['gaji_dasar_per_hari'],
                    $data['uang_saku_bulanan'] ?? 0,
                    $data['sertifikat_kampus_merdeka'] ?? null
                );
            ?>
            <div class="card card-magang">
                <div class="card-header">
                    <h3><?php echo htmlspecialchars($data['nama_karyawan']); ?></h3>
                    <span class="badge badge-magang">Magang</span>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> <?php echo $data['id_karyawan']; ?></p>
                    <p><strong>Departemen:</strong> <?php echo htmlspecialchars($data['departemen']); ?></p>
                    <p><strong>Tanggal Masuk:</strong> <?php echo date('d-m-Y', strtotime($data['hari_kerja_masuk'])); ?></p>
                    <p><strong>Gaji/Hari:</strong> Rp <?php echo number_format($data['gaji_dasar_per_hari'], 0, ',', '.'); ?></p>
                    <p><strong>Uang Saku Bulanan:</strong> Rp <?php echo number_format($data['uang_saku_bulanan'] ?? 0, 0, ',', '.'); ?></p>
                    <p><strong>Sertifikat:</strong> <?php echo $data['sertifikat_kampus_merdeka'] ?? 'Belum ada'; ?></p>
                    <p><strong>Gaji Bersih:</strong> <span class="salary">Rp <?php echo number_format($karyawan->hitungGajiBersih(), 0, ',', '.'); ?></span></p>
                </div>
                <div class="card-footer">
                    <a href="views/slipGaji.php?id=<?php echo $data['id_karyawan']; ?>" class="btn-sm btn-primary">Lihat Slip</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById('tab-' + tabName).classList.add('active');
    document.querySelector(`.tab-btn[onclick="showTab('${tabName}')"]`).classList.add('active');
}
</script>

<?php
include 'views/footer.php';
$db->closeConnection();
?>