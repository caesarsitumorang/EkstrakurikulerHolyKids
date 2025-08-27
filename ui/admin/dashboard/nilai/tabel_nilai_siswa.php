<?php
include '../../../../database/config.php';

$id_ekstra = isset($_GET['ekstra']) ? $_GET['ekstra'] : '';

if ($id_ekstra === '') {
  echo "<p>Ekstrakurikuler tidak dipilih.</p>";
  exit;
}

$id_ekstra = mysqli_real_escape_string($conn, $id_ekstra);

// Ambil nama ekstra
$get_nama = mysqli_query($conn, "SELECT nama_ekstra FROM ekstrakurikuler WHERE id_ekstra = '$id_ekstra'");
$nama_ekstra = mysqli_fetch_assoc($get_nama)['nama_ekstra'] ?? 'Tidak Diketahui';

// Ambil data nilai siswa
$query = "
  SELECT 
    s.nama AS nama_siswa,
    n.nilai_user,
    n.jumlah_hadir,
    n.nilai_akhir
  FROM nilai_siswa n
  JOIN siswa s ON n.id_siswa = s.id_siswa
  WHERE n.id_ekstra = '$id_ekstra'
  ORDER BY s.nama ASC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Nilai Siswa</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f7fa;
      color: #333;
    }

    .container {
      max-width: 900px;
      margin: 60px auto;
      padding: 40px;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.05);
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: 600;
      font-size: 24px;
      color: #2c3e50;
    }

    .back-link {
      display: inline-block;
      margin-bottom: 30px;
      text-decoration: none;
      color: #3498db;
      font-size: 14px;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      font-size: 15px;
    }

    th, td {
      padding: 14px;
      border: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #ecf0f1;
      color: #2c3e50;
      font-weight: 600;
    }

    tr:nth-child(even) {
      background-color: #fafafa;
    }

    .empty-message {
      text-align: center;
      padding: 30px;
      color: #999;
    }
  </style>
</head>
<body>
  <div class="container">
    <a class="back-link" href="../dashboard_admin.php">← Kembali ke Dashboard</a>
    <h2>📊 Nilai Siswa - <?= htmlspecialchars($nama_ekstra) ?></h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Nilai</th>
            <th>Jumlah Hadir</th>
            <th>Nilai Akhir</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
            <td><?= $row['nilai_user'] ?></td>
            <td><?= $row['jumlah_hadir'] ?></td>
            <td><?= $row['nilai_akhir'] ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="empty-message">Tidak ada data nilai untuk ekstrakurikuler ini.</div>
    <?php endif; ?>
  </div>
</body>
</html>
