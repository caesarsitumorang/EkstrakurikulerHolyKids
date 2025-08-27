<?php
session_start();
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../../../login.php");
    exit;
}

$id_siswa = $_SESSION['id_ref'];

$query = "
    SELECT e.nama_ekstra, e.hari, e.jam, e.lokasi, p.status, p.tanggal_daftar
    FROM pendaftaran_ekstrakurikuler p
    JOIN ekstrakurikuler e ON p.id_ekstra = e.id_ekstra
    WHERE p.id_siswa = ?
    ORDER BY p.tanggal_daftar DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_siswa);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
  <h2>📋 Status Pendaftaran Ekstrakurikuler</h2>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Nama Ekstrakurikuler</th>
          <th>Hari & Jam</th>
          <th>Lokasi</th>
          <th>Tanggal Daftar</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['nama_ekstra']) ?></td>
            <td><?= htmlspecialchars($row['hari']) ?>, <?= htmlspecialchars($row['jam']) ?></td>
            <td><?= htmlspecialchars($row['lokasi']) ?></td>
            <td><?= date('d M Y', strtotime($row['tanggal_daftar'])) ?></td>
            <td>
              <?php
                $status = $row['status'];
                if ($status === 'pending') {
                    echo "<span class='badge badge-pending'>Menunggu</span>";
                } elseif ($status === 'diterima') {
                    echo "<span class='badge badge-diterima'>Diterima</span>";
                } elseif ($status === 'ditolak') {
                    echo "<span class='badge badge-ditolak'>Ditolak</span>";
                }
              ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="info">Kamu belum pernah mengajukan pendaftaran ekstrakurikuler.</p>
  <?php endif; ?>
</div>

<style>
.container {
  font-family: 'Segoe UI', sans-serif;
  padding: 30px;
}

h2 {
  color: #082465;
  margin-bottom: 20px;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

th, td {
  padding: 12px 16px;
  text-align: left;
  border-bottom: 1px solid #eee;
}

th {
  background-color: #082465;
  color: white;
}

tr:hover {
  background-color: #f8f9ff;
}

.badge {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.85rem;
  color: white;
}

.badge-pending {
  background-color:rgb(142, 112, 25);
}

.badge-diterima {
  background-color: #28a745;
}

.badge-ditolak {
  background-color: #dc3545;
}

.info {
  margin-top: 20px;
  background-color: #f1f4f9;
  padding: 16px;
  border-left: 5px solid #999;
  border-radius: 6px;
  color: #555;
}
</style>
