<?php
include '../../../../../database/config.php';

$kelas = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nis = $_POST['nis'];
  $nama = $_POST['nama'];
  $username = $_POST['username'];
  $password = $_POST['password']; 
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $alamat = $_POST['alamat'];
  $tanggal_lahir = $_POST['tanggal_lahir'];
  $no_hp = $_POST['no_hp'];
  $nama_ortu = $_POST['nama_ortu'];
  $no_hp_ortu = $_POST['no_hp_ortu'];
  $id_kelas = $_POST['id_kelas'];
  $email_ortu = $_POST['email_ortu'];


  // Insert ke tabel siswa
  $stmt = $conn->prepare("INSERT INTO siswa (nis, nama, username, password, jenis_kelamin, alamat, tanggal_lahir, no_hp, nama_ortu, no_hp_ortu, email_ortu, id_kelas) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssssi", $nis, $nama, $username, $password, $jenis_kelamin, $alamat, $tanggal_lahir, $no_hp, $nama_ortu, $no_hp_ortu, $email_ortu, $id_kelas);


  if ($stmt->execute()) {
    $id_siswa = $conn->insert_id;

    // Insert ke tabel users
    $stmtUser = $conn->prepare("INSERT INTO users (username, password, role, id_ref) VALUES (?, ?, 'siswa', ?)");
    $stmtUser->bind_param("ssi", $username, $password, $id_siswa);
    $stmtUser->execute();

    header("Location: ../../dashboard_admin.php");
    exit;
  } else {
    echo "Gagal menambahkan siswa.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Siswa</title>
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
  <h2>Tambah Siswa</h2>
  <form method="POST">
    <label>NIS</label>
    <input type="text" name="nis" required>

    <label>Nama</label>
    <input type="text" name="nama" required>

    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="text" name="password" required>

    <label>Jenis Kelamin</label>
    <select name="jenis_kelamin" required>
      <option value="">-- Pilih Jenis Kelamin --</option>
      <option value="L">Laki-laki</option>
      <option value="P">Perempuan</option>
    </select>

    <label>Alamat</label>
    <textarea name="alamat" rows="2" required></textarea>

    <label>Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir" required>

    <label>No HP</label>
    <input type="text" name="no_hp">

    <label>Nama Orang Tua</label>
    <input type="text" name="nama_ortu">

    <label>No HP Orang Tua</label>
    <input type="text" name="no_hp_ortu">

    <label>Email Orang Tua</label>
<input type="email" name="email_ortu">


    <label>Kelas</label>
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
