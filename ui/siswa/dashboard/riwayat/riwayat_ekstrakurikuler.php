<?php
session_start();
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../../../login.php");
    exit;
}

$id_siswa = $_SESSION['id_ref'];
$tanggal_sekarang = date('Y-m-d');

// Ambil semester yang sudah selesai (riwayat)
$semesterQuery = $conn->prepare("SELECT * FROM semester WHERE tanggal_selesai < ? ORDER BY tanggal_selesai DESC LIMIT 1");
$semesterQuery->bind_param("s", $tanggal_sekarang);
$semesterQuery->execute();
$semesterResult = $semesterQuery->get_result();
$semesterData = $semesterResult->fetch_assoc();
?>

<div class="container">
  <h2>📘 Riwayat Ekstrakurikuler per Semester</h2>
  <a href="riwayat/cetak_riwayat.php?id_ekstra=<?= $row['id_ekstra'] ?>" target="_blank" class="btn-cetak">🖨 Cetak PDF</a>
  <?php if (!$semesterData): ?>
    <div class="alert">⚠️ Belum ada semester yang berakhir, data riwayat belum tersedia.</div>
  <?php else: ?>
    <p>
      <strong>Semester:</strong> 
      <?= htmlspecialchars($semesterData['semester']) ?> 
      (<?= date('d M Y', strtotime($semesterData['tanggal_mulai'])) ?> - 
      <?= date('d M Y', strtotime($semesterData['tanggal_selesai'])) ?>)
    </p>

    <?php
    $semesterStart = $semesterData['tanggal_mulai'];
    $semesterEnd = $semesterData['tanggal_selesai'];

$query = "
  SELECT 
      e.id_ekstra, e.nama_ekstra, e.hari, e.jam, e.lokasi, p.tanggal_daftar,
      g.nama_lengkap AS nama_guru, g.no_hp,
      (
          SELECT COUNT(*) 
          FROM absensi_ekstrakurikuler a 
          WHERE a.id_siswa = ? 
            AND a.id_ekstra = p.id_ekstra 
            AND a.status = 'hadir'
      ) AS jumlah_hadir,
      ns.nilai_akhir
  FROM pendaftaran_ekstrakurikuler p
  JOIN ekstrakurikuler e ON p.id_ekstra = e.id_ekstra
  JOIN guru_ekstrakurikuler_map gm ON e.id_ekstra = gm.id_ekstra
  JOIN guru g ON gm.id_guru = g.id_guru
  LEFT JOIN nilai_siswa ns 
      ON ns.id_siswa = p.id_siswa 
      AND ns.id_ekstra = p.id_ekstra
  WHERE p.id_siswa = ? 
    AND p.status = 'diterima'
  ORDER BY e.nama_ekstra ASC
";

 $stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id_siswa, $id_siswa);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0): ?>
      <div class="ekstra-grid">
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="ekstra-card">
            <div class="card-header">
              <h3><?= htmlspecialchars($row['nama_ekstra']) ?></h3>
              
            </div>
            <p><strong>Hari:</strong> <?= htmlspecialchars($row['hari']) ?></p>
            <p><strong>Jam:</strong> <?= htmlspecialchars($row['jam']) ?></p>
            <p><strong>Lokasi:</strong> <?= htmlspecialchars($row['lokasi']) ?></p>
            <p><strong>Guru Pembina:</strong> <?= htmlspecialchars($row['nama_guru']) ?></p>
            <p><strong>No HP Guru:</strong> <?= htmlspecialchars($row['no_hp']) ?></p>
            <p class="date">Diterima sejak: <?= date('d M Y', strtotime($row['tanggal_daftar'])) ?></p>
            <p class="kehadiran"><strong>Jumlah Hadir:</strong> <?= $row['jumlah_hadir'] ?> dari 24</p>
            <p><strong>Nilai Akhir:</strong> 
              <?= $row['nilai_akhir'] !== null ? min(100, number_format($row['nilai_akhir'], 1)) : '-' ?>
            </p>
            <a href="riwayat/detail_kehadiran.php?id_ekstra=<?= $row['id_ekstra'] ?>" class="btn-detail">Lihat Detail</a>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="alert">🙁 Belum ada ekstrakurikuler yang dijalani di semester ini.</div>
    <?php endif; ?>
  <?php endif; ?>
</div>

<style>
.container {
  font-family: 'Segoe UI', sans-serif;
  padding: 30px;
}

h2 {
  color: #082465;
  margin-bottom: 10px;
}

.alert {
  background-color: #fff3cd;
  color: #856404;
  padding: 16px;
  border-left: 5px solid #ffc107;
  border-radius: 6px;
  margin-top: 20px;
}

.ekstra-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
  margin-top: 20px;
}

.ekstra-card {
  background: #fff;
  border-left: 5px solid #082465;
  padding: 16px 20px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.ekstra-card h3 {
  margin: 0;
  color: #082465;
}

.btn-cetak {
  background: #28a745;
  color: white;
  padding: 6px 12px;
  border-radius: 6px;
  text-decoration: none;
  font-size: 0.85rem;
}

.btn-cetak:hover {
  background: #218838;
}

.date {
  font-size: 0.85rem;
  color: #777;
  margin-top: 10px;
}

.kehadiran {
  font-weight: 500;
  margin-top: 8px;
  color: #0a3d62;
}

.btn-detail {
  display: inline-block;
  margin-top: 10px;
  background: #082465;
  color: #fff;
  padding: 6px 12px;
  border-radius: 6px;
  text-decoration: none;
}

.btn-detail:hover {
  background: #061b47;
}
</style>
  