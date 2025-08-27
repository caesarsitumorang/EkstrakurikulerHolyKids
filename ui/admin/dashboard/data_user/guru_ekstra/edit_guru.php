<?php
include '../../../../../database/config.php';

if (!isset($_GET['id'])) {
  echo "ID guru tidak ditemukan.";
  exit;
}

$id_guru = (int)$_GET['id'];

// Ambil data guru
$stmt = $conn->prepare("SELECT * FROM guru WHERE id_guru = ?");
$stmt->bind_param("i", $id_guru);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
  echo "Data guru tidak ditemukan.";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nip = $_POST['nip'];
  $nama = $_POST['nama_lengkap'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $alamat = $_POST['alamat'];
  $tanggal_lahir = $_POST['tanggal_lahir'];
  $no_hp = $_POST['no_hp'];

  // Update ke tabel guru
  $stmtUpdate = $conn->prepare("UPDATE guru SET nip = ?, nama_lengkap = ?, username = ?, password = ?, jenis_kelamin = ?, alamat = ?, tanggal_lahir = ?, no_hp = ? WHERE id_guru = ?");
  $stmtUpdate->bind_param("ssssssssi", $nip, $nama, $username, $password, $jenis_kelamin, $alamat, $tanggal_lahir, $no_hp, $id_guru);
  $stmtUpdate->execute();

  // Update ke tabel users jika username/password berubah
  $stmtUser = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id_ref = ? AND role = 'guru'");
  $stmtUser->bind_param("ssi", $username, $password, $id_guru);
  $stmtUser->execute();

  header("Location: ../../dashboard_admin.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Guru</title>
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
  <h2>Edit Guru</h2>
  <form method="POST">
    <label>NIP</label>
    <input type="text" name="nip" value="<?= htmlspecialchars($data['nip']) ?>" required>

    <label>Nama Lengkap</label>
    <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required>

    <label>Username</label>
    <input type="text" name="username" value="<?= htmlspecialchars($data['username']) ?>" required>

    <label>Password</label>
    <input type="text" name="password" value="<?= htmlspecialchars($data['password']) ?>" required>

    <label>Jenis Kelamin</label>
    <select name="jenis_kelamin" required>
      <option value="">-- Pilih Jenis Kelamin --</option>
      <option value="Laki-laki" <?= $data['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
      <option value="Perempuan" <?= $data['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
    </select>

    <label>Alamat</label>
    <input type="text" name="alamat" value="<?= htmlspecialchars($data['alamat']) ?>" required>

    <label>Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($data['tanggal_lahir']) ?>" required>

    <label>No HP</label>
    <input type="text" name="no_hp" value="<?= htmlspecialchars($data['no_hp']) ?>" required>

    <div class="btn-group">
      <a href="../../dashboard_admin.php">Batal</a>
      <button type="submit">Simpan Perubahan</button>
    </div>
  </form>
</div>

</body>
</html>
