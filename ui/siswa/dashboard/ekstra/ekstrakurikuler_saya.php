<?php
session_start();
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../../../login.php");
    exit;
}

$id_siswa = $_SESSION['id_ref'];

$query = "
    SELECT 
        e.id_ekstra, e.nama_ekstra, e.hari, e.jam, e.lokasi, p.tanggal_daftar,
        g.nama_lengkap AS nama_guru, g.no_hp,
        (
            SELECT COUNT(*) 
            FROM absensi_ekstrakurikuler a 
            WHERE a.id_siswa = ? AND a.id_ekstra = p.id_ekstra AND a.status = 'hadir'
        ) AS jumlah_hadir
    FROM pendaftaran_ekstrakurikuler p
    JOIN ekstrakurikuler e ON p.id_ekstra = e.id_ekstra
    JOIN guru_ekstrakurikuler_map gm ON e.id_ekstra = gm.id_ekstra
    JOIN guru g ON gm.id_guru = g.id_guru
    WHERE p.id_siswa = ? AND p.status = 'diterima'
    ORDER BY e.nama_ekstra ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id_siswa, $id_siswa);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
  <h2>🎓 Ekstrakurikuler Saya</h2>
  <p>Berikut adalah daftar ekstrakurikuler yang sudah kamu ikuti. Hadirlah secara rutin untuk menambah pengalamanmu.</p>

  <?php if ($result->num_rows > 0): ?>
    <div class="ekstra-grid">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="ekstra-card">
          <h3><?= htmlspecialchars($row['nama_ekstra']) ?></h3>
          <p><strong>Hari:</strong> <?= htmlspecialchars($row['hari']) ?></p>
          <p><strong>Jam:</strong> <?= htmlspecialchars($row['jam']) ?></p>
          <p><strong>Lokasi:</strong> <?= htmlspecialchars($row['lokasi']) ?></p>
          <p><strong>Guru Pembina:</strong> <?= htmlspecialchars($row['nama_guru']) ?></p>
          <p><strong>No HP Guru Pembina:</strong> <?= htmlspecialchars($row['no_hp']) ?></p>
          <p class="date">Diterima sejak: <?= date('d M Y', strtotime($row['tanggal_daftar'])) ?></p>
          <p class="kehadiran"><strong>Jumlah Hadir:</strong> <?= $row['jumlah_hadir'] ?> dari 24 pertemuan</p>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <div class="alert">🙁 Kamu belum mengikuti ekstrakurikuler mana pun.</div>
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
  background-color: #ffe5e5;
  color: #a80000;
  padding: 16px;
  border-left: 5px solid #ff0000;
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

.ekstra-card h3 {
  margin-top: 0;
  color: #082465;
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
</style>
