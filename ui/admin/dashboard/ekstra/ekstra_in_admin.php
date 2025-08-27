<?php
session_start();
include '../../../../database/config.php';

// Cek login dan role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../../login.php");
    exit;
}

// Ambil data ekstrakurikuler
$result = mysqli_query($conn, "SELECT * FROM ekstrakurikuler ORDER BY nama_ekstra ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Ekstrakurikuler</title>
  <style>

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    h2 {
      color: #082465;
      margin: 0;
    }

    .btn {
      background-color: #082465;
      color: white;
      padding: 10px 16px;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      font-size: 14px;
      display: inline-block;
      margin-left: 8px;
      transition: background-color 0.2s ease-in-out;
    }

    .btn:hover {
      background-color: #0a2d85;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 6px 16px rgba(0,0,0,0.08);
    }

    th, td {
      padding: 14px 18px;
      border-bottom: 1px solid #f0f0f0;
      text-align: left;
    }

    th {
      background-color: #eaf0fa;
      color: #082465;
    }

    td:last-child {
      white-space: nowrap;
    }

    .btn-delete {
      background-color: #e74c3c;
    }

    .btn-delete:hover {
      background-color: #c0392b;
    }
  </style>
</head>
<body>

<div class="top-bar">
  <h2>📋 Daftar Ekstrakurikuler</h2>
  <a href="ekstra/tambah_ekstra.php" class="btn">+ Tambah Ekstrakurikuler</a>
</div>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Nama Ekstrakurikuler</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($row['nama_ekstra']) ?></td>
      <td>
        <a href="ekstra/edit_ekstra.php?id=<?= $row['id_ekstra'] ?>" class="btn">✏️ Edit</a>
        <a href="ekstra/hapus_ekstra.php?id=<?= $row['id_ekstra'] ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus ekstrakurikuler ini?')">🗑️ Hapus</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

</body>
</html>
