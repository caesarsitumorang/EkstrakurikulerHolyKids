<?php
include '../../../../../database/config.php';

$query = "SELECT * FROM siswa ORDER BY nama ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Siswa</title>
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
      padding: 6px 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 13px;
      text-decoration: none;
      display: inline-block;
      margin-right: 8px;
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
  <a href="tambah_siswa.php" class="btn">+ Tambah Siswa</a>
</div>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>NIS</th>
      <th>Nama Lengkap</th>
      <th>Username</th>
      <th>Jenis Kelamin</th>
      <th>Alamat</th>
      <th>Tanggal Lahir</th>
      <th>No HP</th>
      <th>Nama Ortu</th>
      <th>No HP Ortu</th>
      <th>Email Ortu</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
      <td><?= $no++ ?></td>
      <td><?= htmlspecialchars($row['nis']) ?></td>
      <td><?= htmlspecialchars($row['nama']) ?></td>
      <td><?= htmlspecialchars($row['username']) ?></td>
      <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
      <td><?= htmlspecialchars($row['alamat']) ?></td>
      <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
      <td><?= htmlspecialchars($row['no_hp']) ?></td>
      <td><?= htmlspecialchars($row['nama_ortu']) ?></td>
      <td><?= htmlspecialchars($row['no_hp_ortu']) ?></td>
      <td><?= htmlspecialchars($row['email_ortu']) ?></td>
      <td>
        <a href="../siswa/edit_siswa.php?id=<?= $row['id_siswa'] ?>" class="action-btn btn-edit">✏ Edit</a>
        <a href="../siswa/hapus_siswa.php?id=<?= $row['id_siswa'] ?>" class="action-btn btn-delete" onclick="return confirm('Yakin ingin menghapus siswa ini?')">🗑 Hapus</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>

</body>
</html>
