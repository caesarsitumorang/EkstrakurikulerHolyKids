<?php
include '../../../../../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nip = $_POST['nip'];
  $nama = $_POST['nama_lengkap'];
  $username = $_POST['username'];
  $password = $_POST['password']; // tidak di-hash
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $alamat = $_POST['alamat'];
  $tanggal_lahir = $_POST['tanggal_lahir'];
  $no_hp = $_POST['no_hp'];

  // Simpan ke tabel guru
  $stmt = $conn->prepare("INSERT INTO guru (nip, nama_lengkap, username, password, jenis_kelamin, alamat, tanggal_lahir, no_hp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssssss", $nip, $nama, $username, $password, $jenis_kelamin, $alamat, $tanggal_lahir, $no_hp);

  if ($stmt->execute()) {
    $id_guru = $conn->insert_id;

    // Simpan juga ke tabel users
    $stmtUser = $conn->prepare("INSERT INTO users (username, password, role, id_ref) VALUES (?, ?, 'guru', ?)");
    $stmtUser->bind_param("ssi", $username, $password, $id_guru);
    $stmtUser->execute();

    header("Location: ../../dashboard_admin.php");
    exit;
  } else {
    echo "❌ Gagal menambahkan guru.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Guru</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6fa;
      padding: 40px;
    }

    .form-container {
      background: white;
      max-width: 700px;
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
      margin-top: 16px;
    }

    input, select {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      margin-top: 5px;
      box-sizing: border-box;
    }

    .btn-group {
      margin-top: 30px;
      text-align: right;
    }

    button, a {
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      margin-left: 10px;
      font-weight: 500;
    }

    button {
      background-color: #082465;
      color: white;
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
  <h2>Tambah Guru</h2>
  <form method="POST">
    <label>NIP</label>
    <input type="text" name="nip" required>

    <label>Nama Lengkap</label>
    <input type="text" name="nama_lengkap" required>

    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="text" name="password" required>

    <label>Jenis Kelamin</label>
    <select name="jenis_kelamin" required>
      <option value="">-- Pilih Jenis Kelamin --</option>
      <option value="Laki-laki">Laki-laki</option>
      <option value="Perempuan">Perempuan</option>
    </select>

    <label>Alamat</label>
    <input type="text" name="alamat" required>

    <label>Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir" required>

    <label>No HP</label>
    <input type="text" name="no_hp" required>

    <div class="btn-group">
      <a href="../../dashboard_admin.php">Batal</a>
      <button type="submit">Simpan</button>
    </div>
  </form>
</div>

</body>
</html>
