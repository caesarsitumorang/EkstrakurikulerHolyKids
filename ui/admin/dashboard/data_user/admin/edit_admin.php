<?php
include '../../../../../database/config.php';

// Ambil ID admin dari URL
$id = $_GET['id'] ?? 0;

// Ambil data admin
$stmt = $conn->prepare("SELECT * FROM admin WHERE id_admin = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
  echo "Admin tidak ditemukan.";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $_POST['nama_lengkap'];
  $username = $_POST['username'];
  $no_hp = $_POST['no_hp'];
  $email = $_POST['email'];
  $password = $_POST['password']; // Tidak di-hash
  $tanggal_lahir = $_POST['tanggal_lahir'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $alamat = $_POST['alamat'];

  // Update ke tabel admin
  $update = $conn->prepare("UPDATE admin SET nama_lengkap=?, username=?, no_hp=?, email=?, password=?, tanggal_lahir=?, jenis_kelamin=?, alamat=? WHERE id_admin=?");
  $update->bind_param("ssssssssi", $nama, $username, $no_hp, $email, $password, $tanggal_lahir, $jenis_kelamin, $alamat, $id);
  $update->execute();

  // Update ke tabel users
  $update_user = $conn->prepare("UPDATE users SET username=?, password=? WHERE id_ref=? AND role='admin'");
  $update_user->bind_param("ssi", $username, $password, $id);
  $update_user->execute();

  header("Location: ../../dashboard_admin.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Admin</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6fa;
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
      display: block;
      margin-top: 15px;
      font-weight: 500;
    }
    input, select, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .btn-group {
      margin-top: 25px;
      text-align: right;
    }
    button, a {
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      margin-left: 10px;
      text-decoration: none;
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
  <h2>Edit Admin</h2>
  <form method="POST">
    <label>Nama Lengkap</label>
    <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required>

    <label>Username</label>
    <input type="text" name="username" value="<?= htmlspecialchars($data['username']) ?>" required>

    <label>Password</label>
    <input type="text" name="password" value="<?= htmlspecialchars($data['password']) ?>" required>

    <label>No HP</label>
    <input type="text" name="no_hp" value="<?= htmlspecialchars($data['no_hp']) ?>">

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>">

    <label>Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($data['tanggal_lahir']) ?>" required>

    <label>Jenis Kelamin</label>
    <select name="jenis_kelamin" required>
      <option value="">-- Pilih --</option>
      <option value="Laki-laki" <?= $data['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
      <option value="Perempuan" <?= $data['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
    </select>

    <label>Alamat</label>
    <textarea name="alamat" rows="3"><?= htmlspecialchars($data['alamat']) ?></textarea>

    <div class="btn-group">
      <a href="../../dashboard_admin.php">Batal</a>
      <button type="submit">Simpan Perubahan</button>
    </div>
  </form>
</div>

</body>
</html>
