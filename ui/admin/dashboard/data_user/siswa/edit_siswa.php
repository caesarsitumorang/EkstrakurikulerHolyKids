<?php
include '../../../../../database/config.php';

if (!isset($_GET['id'])) {
  echo "ID siswa tidak ditemukan.";
  exit;
}

$id_siswa = (int)$_GET['id'];

// Ambil data siswa
$query = "SELECT s.*, u.username FROM siswa s 
          LEFT JOIN users u ON s.id_siswa = u.id_ref AND u.role = 'siswa'
          WHERE s.id_siswa = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_siswa);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
  echo "Data siswa tidak ditemukan.";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nis = $_POST['nis'];
  $nama = $_POST['nama'];
  $username = $_POST['username'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $alamat = $_POST['alamat'];
  $tanggal_lahir = $_POST['tanggal_lahir'];
  $no_hp = $_POST['no_hp'];
  $nama_ortu = $_POST['nama_ortu'];
  $no_hp_ortu = $_POST['no_hp_ortu'];
  $email_ortu = $_POST['email_ortu'];

  $password = $_POST['password']; // Optional

  // Update tabel siswa
  $stmt = $conn->prepare("UPDATE siswa SET nis = ?, nama = ?, jenis_kelamin = ?, alamat = ?, tanggal_lahir = ?, no_hp = ?, nama_ortu = ?, no_hp_ortu = ?, email_ortu = ? WHERE id_siswa = ?");
$stmt->bind_param("sssssssssi", $nis, $nama, $jenis_kelamin, $alamat, $tanggal_lahir, $no_hp, $nama_ortu, $no_hp_ortu, $email_ortu, $id_siswa);

  $stmt->execute();

  // Update tabel users
  if (!empty($password)) {
    // Jika password diubah
    $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id_ref = ? AND role = 'siswa'");
    $stmt->bind_param("ssi", $username, $password, $id_siswa);
  } else {
    // Jika hanya username
    $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id_ref = ? AND role = 'siswa'");
    $stmt->bind_param("si", $username, $id_siswa);
  }
  $stmt->execute();

  header("Location: ../../dashboard_admin.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Siswa</title>
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
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
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

    input, select, textarea {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      box-sizing: border-box;
      margin-top: 5px;
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
  <h2>Edit Data Siswa</h2>
  <form method="POST">
    <label>NIS</label>
    <input type="text" name="nis" value="<?= htmlspecialchars($data['nis']) ?>" required>

    <label>Nama</label>
    <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>

    <label>Username</label>
    <input type="text" name="username" value="<?= htmlspecialchars($data['username']) ?>" required>

    <label>Password (biarkan kosong jika tidak ingin diubah)</label>
    <input type="text" name="password">

    <label>Jenis Kelamin</label>
    <select name="jenis_kelamin" required>
      <option value="L" <?= $data['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki</option>
      <option value="P" <?= $data['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan</option>
    </select>

    <label>Alamat</label>
    <textarea name="alamat" required><?= htmlspecialchars($data['alamat']) ?></textarea>

    <label>Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($data['tanggal_lahir']) ?>" required>

    <label>No HP</label>
    <input type="text" name="no_hp" value="<?= htmlspecialchars($data['no_hp']) ?>">

    <label>Nama Orang Tua</label>
    <input type="text" name="nama_ortu" value="<?= htmlspecialchars($data['nama_ortu']) ?>">

    <label>No HP Orang Tua</label>
    <input type="text" name="no_hp_ortu" value="<?= htmlspecialchars($data['no_hp_ortu']) ?>">

    <label>Email Orang Tua</label>
<input type="email" name="email_ortu" value="<?= htmlspecialchars($data['email_ortu']) ?>">

    <div class="btn-group">
      <a href="../../dashboard_admin.php">Batal</a>
      <button type="submit">Simpan Perubahan</button>
    </div>
  </form>
</div>

</body>
</html>
