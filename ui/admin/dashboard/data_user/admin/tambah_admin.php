<?php
include '../../../../../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $_POST['nama_lengkap'];
  $username = $_POST['username'];
  $no_hp = $_POST['no_hp'];
  $email = $_POST['email'];
  $password = $_POST['password']; // ⛔ Tidak di-hash sesuai permintaan
  $tanggal_lahir = $_POST['tanggal_lahir'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $alamat = $_POST['alamat'];

  // 1. Simpan ke tabel admin
  $query = "INSERT INTO admin (nama_lengkap, username, no_hp, email, password, tanggal_lahir, jenis_kelamin, alamat)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ssssssss", $nama, $username, $no_hp, $email, $password, $tanggal_lahir, $jenis_kelamin, $alamat);

  if ($stmt->execute()) {
    $id_admin = $conn->insert_id;

    // 2. Simpan ke tabel users
    $user_stmt = $conn->prepare("INSERT INTO users (username, password, role, id_ref) VALUES (?, ?, 'admin', ?)");
    $user_stmt->bind_param("ssi", $username, $password, $id_admin);
    $user_stmt->execute();

    header("Location: ../../dashboard_admin.php");
    exit;
  } else {
    echo "Gagal menambahkan admin.";
  }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Admin</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6fa;
      margin: 0;
      padding: 40px;
    }

    .form-container {
      background: white;
      max-width: 600px;
      margin: auto;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    }

    h2 {
      color: #082465;
      margin-bottom: 25px;
      text-align: center;
    }

    label {
      font-weight: 500;
      display: block;
      margin-bottom: 6px;
      margin-top: 14px;
    }

    input[type="text"],
    input[type="password"],
    input[type="email"],
    input[type="date"],
    select,
    textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
    }

    textarea {
      resize: vertical;
    }

    .btn-group {
      margin-top: 25px;
      text-align: right;
    }

    button, a {
      padding: 10px 20px;
      text-decoration: none;
      border: none;
      border-radius: 6px;
      margin-left: 10px;
      font-weight: 500;
    }

    button {
      background-color: #082465;
      color: white;
      cursor: pointer;
    }

    button:hover {
      background-color: #0a2d85;
    }

    a {
      background-color: #ccc;
      color: #333;
    }

    a:hover {
      background-color: #bbb;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Tambah Admin</h2>
  <form method="POST">
    <label>Nama Lengkap</label>
    <input type="text" name="nama_lengkap" required>

    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <label>No HP</label>
    <input type="text" name="no_hp">

    <label>Email</label>
    <input type="email" name="email">

    <label>Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir" required>

    <label>Jenis Kelamin</label>
    <select name="jenis_kelamin" required>
      <option value="">-- Pilih Jenis Kelamin --</option>
      <option value="Laki-laki">Laki-laki</option>
      <option value="Perempuan">Perempuan</option>
    </select>

    <label>Alamat</label>
    <textarea name="alamat" rows="3" required></textarea>

    <div class="btn-group">
      <a href="../../dashboard_admin.php">Batal</a>
      <button type="submit">Simpan</button>
    </div>
  </form>
</div>

</body>
</html>
