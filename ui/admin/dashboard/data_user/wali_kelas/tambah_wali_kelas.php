<?php
include '../../../../../database/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nip = $_POST['nip'];
  $nama = $_POST['nama_lengkap'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $alamat = $_POST['alamat'];
  $tanggal_lahir = $_POST['tanggal_lahir'];
  $no_hp = $_POST['no_hp'];
  $id_kelas = $_POST['id_kelas'];

  // Cek apakah kelas sudah punya wali
  $cek = $conn->prepare("SELECT COUNT(*) FROM wali_kelas WHERE id_kelas = ?");
  $cek->bind_param("i", $id_kelas);
  $cek->execute();
  $cek->bind_result($jumlah);
  $cek->fetch();
  $cek->close();

  if ($jumlah > 0) {
    $error = "❗ Kelas ini sudah memiliki wali kelas. Silakan pilih kelas lain.";
  } else {
    // Simpan ke tabel wali_kelas
    $stmt = $conn->prepare("INSERT INTO wali_kelas (nip, nama_lengkap, username, password, jenis_kelamin, alamat, tanggal_lahir, no_hp, id_kelas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssi", $nip, $nama, $username, $password, $jenis_kelamin, $alamat, $tanggal_lahir, $no_hp, $id_kelas);

    if ($stmt->execute()) {
      $id_wali = $conn->insert_id;

      // Simpan ke tabel users
      $stmtUser = $conn->prepare("INSERT INTO users (username, password, role, id_ref) VALUES (?, ?, 'wali_kelas', ?)");
      $stmtUser->bind_param("ssi", $username, $password, $id_wali);
      $stmtUser->execute();

       header("Location: ../../dashboard_admin.php");
    exit;
    } else {
      $error = "❌ Gagal menambahkan wali kelas.";
    }
  }
}

$kelas = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Wali Kelas</title>
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

    .error-msg {
      background-color: #ffe6e6;
      color: #b30000;
      padding: 12px;
      border-radius: 6px;
      margin-bottom: 20px;
      text-align: center;
      font-weight: 500;
    }

    label {
      display: block;
      margin-top: 14px;
      font-weight: 500;
    }

    input, select, textarea {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      box-sizing: border-box;
      margin-top: 6px;
    }

    .btn-group {
      margin-top: 30px;
      text-align: right;
    }

    button, a {
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 500;
      border: none;
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
  <h2>Tambah Wali Kelas</h2>

  <?php if ($error): ?>
    <div class="error-msg"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

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
    <textarea name="alamat" rows="2" required></textarea>

    <label>Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir" required>

    <label>No HP</label>
    <input type="text" name="no_hp" required>

    <label>Kelas yang Diasuh</label>
    <select name="id_kelas" required>
      <option value="">-- Pilih Kelas --</option>
      <?php while ($row = mysqli_fetch_assoc($kelas)): ?>
        <option value="<?= $row['id_kelas'] ?>"><?= htmlspecialchars($row['nama_kelas']) ?></option>
      <?php endwhile; ?>
    </select>

    <div class="btn-group">
      <a href="../../dashboard_admin.php">Batal</a>
      <button type="submit">Simpan</button>
    </div>
  </form>
</div>

</body>
</html>
