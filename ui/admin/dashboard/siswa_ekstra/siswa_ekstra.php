<?php
session_start();
include '../../../../database/config.php';

// Cek akses admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../../login.php");
    exit;
}

// Ambil data siswa dan ekstra yang diikuti, beserta nama kelas
$query = "
    SELECT s.id_siswa, s.nis, s.nama, k.nama_kelas, e.nama_ekstra
    FROM siswa_ekstrakurikuler se
    JOIN siswa s ON se.id_siswa = s.id_siswa
    JOIN ekstrakurikuler e ON se.id_ekstra = e.id_ekstra
    JOIN kelas k ON s.id_kelas = k.id_kelas
    ORDER BY s.nama ASC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Siswa & Ekstrakurikuler</title>
  <style>

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }

    .btn {
      background-color: #082465;
      color: white;
      padding: 10px 16px;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      cursor: pointer;
      font-size: 14px;
    }

    .btn:hover {
      background-color: #0a2d85;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      overflow: hidden;
    }

    th, td {
      padding: 14px 18px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }

    th {
      background-color: #f0f4fb;
      color: #082465;
      font-weight: 600;
    }

    tr:hover {
      background-color: #f9fbff;
    }

    .action-btn {
      margin-right: 8px;
    }

    .btn-delete {
      background-color: #d32f2f;
    }

    .btn-delete:hover {
      background-color: #b71c1c;
    }
  </style>
</head>
<body>

<div class="top-bar">
  <h2>👨‍🎓 Data Siswa & Ekstrakurikuler</h2>
  <a href="siswa_ekstra/tambah_siswa_ekstra.php" class="btn">+ Tambah Siswa</a>
</div>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>NIS</th>
      <th>Nama Siswa</th>
      <th>Ekstrakurikuler</th>
      <th>Kelas</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($row['nis']) ?></td>
      <td><?= htmlspecialchars($row['nama']) ?></td>
      <td><?= htmlspecialchars($row['nama_ekstra']) ?></td>
      <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
      <td>
        <a href="siswa_ekstra/edit_siswa_ekstra.php?id=<?= $row['id_siswa'] ?>" class="btn action-btn">✏️ Edit</a>
        <a href="siswa_ekstra/hapus_siswa_ekstra.php?id=<?= $row['id_siswa'] ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus?')">🗑️ Hapus</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

</body>
</html>
