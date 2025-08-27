<?php
include '../../../../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_ekstra'];
    $deskripsi = $_POST['deskripsi'];
    $hari = $_POST['hari'];
    $jam = $_POST['jam'];
    $lokasi = $_POST['lokasi'];

    if (!empty($nama) && !empty($deskripsi) && !empty($hari) && !empty($jam) && !empty($lokasi)) {
        $stmt = $conn->prepare("INSERT INTO ekstrakurikuler (nama_ekstra, deskripsi, hari, jam, lokasi) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nama, $deskripsi, $hari, $jam, $lokasi);
        $stmt->execute();

        echo "<script>alert('✅ Ekstrakurikuler berhasil ditambahkan!'); window.location.href='../dashboard_admin.php';</script>";
        exit;
    } else {
        echo "<script>alert('❗ Semua field wajib diisi!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Ekstrakurikuler</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f2f5;
      padding: 20px;
      margin: 0;
    }

    .top-bar {
      display: flex;
      justify-content: flex-start;
      margin-bottom: 30px;
    }

    .back-btn {
      background-color: #ccc;
      color: #000;
      padding: 8px 14px;
      border: none;
      border-radius: 6px;
      text-decoration: none;
      font-size: 14px;
    }

    .form-container {
      background-color: white;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
      max-width: 600px;
      margin: auto;
    }

    h2 {
      margin-bottom: 25px;
      color: #082465;
      text-align: center;
    }

    label {
      display: block;
      margin-top: 18px;
      margin-bottom: 6px;
      font-weight: bold;
      color: #333;
    }

    input[type="text"],
    select {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    select {
      background-color: white;
    }

    button {
      margin-top: 25px;
      background-color: #082465;
      color: white;
      padding: 12px 0;
      width: 100%;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    button:hover {
      background-color: #061a4d;
    }

    @media (max-width: 600px) {
      .form-container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<div class="top-bar">
  <a href="../dashboard_admin.php" class="back-btn">🔙 Kembali</a>
</div>

<div class="form-container">
  <h2>➕ Tambah Ekstrakurikuler</h2>
  <form method="POST">
    <label for="nama_ekstra">Nama Ekstrakurikuler</label>
    <input type="text" name="nama_ekstra" id="nama_ekstra" required>

    <label for="deskripsi">Deskripsi</label>
    <input type="text" name="deskripsi" id="deskripsi" required>

    <label for="hari">Hari</label>
    <select name="hari" id="hari" required>
      <option value="">-- Pilih Hari --</option>
      <option value="Senin">Senin</option>
      <option value="Selasa">Selasa</option>
      <option value="Rabu">Rabu</option>
      <option value="Kamis">Kamis</option>
      <option value="Jumat">Jumat</option>
      <option value="Sabtu">Sabtu</option>
    </select>

    <label for="jam">Jam</label>
    <input type="text" name="jam" id="jam" placeholder="Contoh: 14.00 - 16.00" required>

    <label for="lokasi">Lokasi</label>
    <input type="text" name="lokasi" id="lokasi" required>

    <button type="submit">Simpan Ekstrakurikuler</button>
  </form>
</div>

</body>
</html>
