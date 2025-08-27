<?php
include '../../../../../database/config.php';

$query = "SELECT wk.*, k.nama_kelas 
          FROM wali_kelas wk
          LEFT JOIN kelas k ON wk.id_kelas = k.id_kelas
          ORDER BY wk.id_wali ASC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Wali Kelas</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f3f6fb;
      margin: 0;
      padding: 30px;
      color: #333;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .btn {
      background-color: #082465;
      color: white;
      padding: 10px 18px;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      font-size: 14px;
      cursor: pointer;
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
      padding: 14px 16px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }

    th {
      background-color: #f0f4fb;
      color: #082465;
      text-transform: uppercase;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .action-btn {
      margin-right: 6px;
      padding: 6px 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 13px;
      text-decoration: none;
    }

    .btn-edit {
      background-color: #007bff;
      color: white;
    }

    .btn-edit:hover {
      background-color: #0056b3;
    }

    .btn-delete {
      background-color: #dc3545;
      color: white;
    }

    .btn-delete:hover {
      background-color: #b21f2d;
    }
  </style>
</head>
<body>

<div class="top-bar">
  <a href="../../dashboard_admin.php" class="btn">← Kembali</a>
  <a href="tambah_wali_kelas.php" class="btn">+ Tambah Wali Kelas</a>
</div>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Nip</th>
      <th>Nama Lengkap</th>
      <th>Username</th>
      <th>Jenis Kelamin</th>
      <th>Alamat</th>
      <th>Tanggal Lahir</th>
      <th>NO HP</th>
      <th>Kelas</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($row['nip']) ?></td>
      <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
      <td><?= htmlspecialchars($row['username']) ?></td>
      <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
      <td><?= htmlspecialchars($row['alamat']) ?></td>
      <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
      <td><?= htmlspecialchars($row['no_hp']) ?></td>\
      <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
      <td>
        <a href="../wali_kelas/edit_wali_kelas.php?id=<?= $row['id_wali'] ?>" class="action-btn btn-edit">✏ Edit</a>
        <a href="../wali_kelas/hapus_wali_kelas.php?id=<?= $row['id_wali'] ?>" class="action-btn btn-delete" onclick="return confirm('Yakin ingin menghapus admin ini?')">🗑 Hapus</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

</body>
</html>
