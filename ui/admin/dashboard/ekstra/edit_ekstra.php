<?php
include '../../../../database/config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: ekstra_in_admin.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM ekstrakurikuler WHERE id_ekstra = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_ekstra'];
    $deskripsi = $_POST['deskripsi'];
    $hari = $_POST['hari'];
    $jam = $_POST['jam'];
    $lokasi = $_POST['lokasi'];

    if ($nama && $deskripsi && $hari && $jam && $lokasi) {
        $update = $conn->prepare("UPDATE ekstrakurikuler SET nama_ekstra = ?, deskripsi = ?, hari = ?, jam = ?, lokasi = ? WHERE id_ekstra = ?");
        $update->bind_param("sssssi", $nama, $deskripsi, $hari, $jam, $lokasi, $id);
        $update->execute();

        echo "<script>alert('✅ Data ekstrakurikuler berhasil diperbarui!'); window.location.href='../dashboard_admin.php';</script>";
        exit;
    } else {
        echo "<script>alert('❗ Semua field harus diisi.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Ekstrakurikuler</title>
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
  </style>
</head>
<body>

<div class="top-bar">
  <a href="../dashboard_admin.php" class="back-btn">🔙 Kembali</a>
</div>

<div class="form-container">
  <h2>✏️ Edit Ekstrakurikuler</h2>
  <form method="POST">
    <label for="nama_ekstra">Nama Ekstrakurikuler</label>
    <input type="text" name="nama_ekstra" id="nama_ekstra" value="<?= htmlspecialchars($data['nama_ekstra']) ?>" required>

    <label for="deskripsi">Deskripsi</label>
    <input type="text" name="deskripsi" id="deskripsi" value="<?= htmlspecialchars($data['deskripsi']) ?>" required>

    <label for="hari">Hari</label>
    <select name="hari" id="hari" required>
      <option value="">-- Pilih Hari --</option>
      <?php
      $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
      foreach ($hariList as $h) {
        $selected = $data['hari'] === $h ? 'selected' : '';
        echo "<option value='$h' $selected>$h</option>";
      }
      ?>
    </select>

    <label for="jam">Jam</label>
    <input type="text" name="jam" id="jam" value="<?= htmlspecialchars($data['jam']) ?>" required>

    <label for="lokasi">Lokasi</label>
    <input type="text" name="lokasi" id="lokasi" value="<?= htmlspecialchars($data['lokasi']) ?>" required>

    <button type="submit">Simpan Perubahan</button>
  </form>
</div>

</body>
</html>
