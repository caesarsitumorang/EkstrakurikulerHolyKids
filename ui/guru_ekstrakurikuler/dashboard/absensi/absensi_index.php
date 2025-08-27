<?php
session_start();
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../../../../login.php");
    exit;
}

$id_guru = $_SESSION['id_ref']; 

$query = "
    SELECT e.id_ekstra, e.nama_ekstra, e.hari, e.jam, e.lokasi
    FROM guru_ekstrakurikuler_map g
    JOIN ekstrakurikuler e ON g.id_ekstra = e.id_ekstra
    WHERE g.id_guru = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_guru);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Absensi Ekstrakurikuler</h2>
<p>Pilih kegiatan yang ingin diabsen:</p>

<div class="ekstra-container">
  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="ekstra-card">
        <h3><?= htmlspecialchars($row['nama_ekstra']) ?></h3>
        <p><strong>Hari:</strong> <?= $row['hari'] ?> | <strong>Jam:</strong> <?= $row['jam'] ?></p>
        <p><strong>Lokasi:</strong> <?= $row['lokasi'] ?></p>
       <a href="absensi/absensi_form.php?id=<?= $row['id_ekstra'] ?>" class="btn-absen">➤ Kelola Absensi</a>
       <a href="absensi/absensi_ditiadakan.php?id=<?= $row['id_ekstra'] ?>" class="btn-absen">➤ Infokan Kegiatan Jika Ditunda</a>

      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>Anda belum membina ekstrakurikuler apa pun.</p>
  <?php endif; ?>
</div>

<style>
.ekstra-container {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.ekstra-card {
  flex: 1 1 300px;
  border: 1px solid #ddd;
  border-left: 5px solid #082465;
  padding: 16px;
  border-radius: 8px;
  background-color: #f9f9f9;
  box-shadow: 1px 1px 6px rgba(0,0,0,0.1);
}

.ekstra-card h3 {
  margin: 0 0 10px;
  color: #082465;
}

.btn-absen {
  display: inline-block;
  margin-top: 10px;
  padding: 8px 12px;
  background-color: #082465;
  color: white;
  text-decoration: none;
  border-radius: 6px;
}

.btn-absen:hover {
  background-color: #0a2d85;
}
</style>
