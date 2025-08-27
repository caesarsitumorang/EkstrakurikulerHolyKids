<?php
include '../../../../database/config.php';

if (!isset($_GET['id_ekstra'])) {
    echo "Ekstrakurikuler tidak ditemukan.";
    exit;
}

$id_ekstra = (int)$_GET['id_ekstra'];

// Ambil data siswa dan hitung kehadiran
$query = "
    SELECT s.id_siswa, s.nama, s.nis, k.nama_kelas,
    COUNT(a.status) AS jumlah_hadir
    FROM siswa_ekstrakurikuler se
    JOIN siswa s ON se.id_siswa = s.id_siswa
    JOIN kelas k ON s.id_kelas = k.id_kelas
    LEFT JOIN absensi_ekstrakurikuler a ON a.id_siswa = s.id_siswa 
        AND a.id_ekstra = se.id_ekstra AND a.status = 'hadir'
    WHERE se.id_ekstra = ?
    GROUP BY s.id_siswa
    ORDER BY s.nama ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_ekstra);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="wrapper">
  <div class="header-actions">
    <a href="../dashboard_guru.php" class="btn-back">← Kembali ke Dashboard</a>
    <h2>📋 Data Kehadiran Siswa Ekstrakurikuler</h2>
    <!-- Tombol Cetak PDF -->
    <a href="../data_siswa/cetak_siswa.php?id_ekstra=<?= $id_ekstra ?>" class="btn-cetak">🖨️ Cetak PDF</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>NIS</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Jumlah Hadir</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['nis']) ?></td>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
        <td><?= $row['jumlah_hadir'] ?> dari 24</td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<style>
.wrapper {
  font-family: 'Segoe UI', sans-serif;
  background: #f8f9fb;
  padding: 30px;
}

.header-actions {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  margin-bottom: 20px;
  gap: 10px;
}

.btn-back, .btn-cetak {
  background-color: #082465;
  color: white;
  padding: 10px 16px;
  text-decoration: none;
  border-radius: 6px;
  font-size: 14px;
  transition: background-color 0.3s ease;
}

.btn-back:hover, .btn-cetak:hover {
  background-color: #0a2d85;
}

h2 {
  color: #082465;
  margin: 0;
  font-size: 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

table th, table td {
  padding: 12px 15px;
  text-align: center;
  border-bottom: 1px solid #ddd;
}

table thead {
  background-color: #082465;
  color: white;
}

table tr:nth-child(even) {
  background-color: #f2f4f8;
}
</style>
