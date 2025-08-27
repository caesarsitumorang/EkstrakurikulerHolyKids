<?php
include '../../../../database/config.php';

// Ambil semua guru dan ekstrakurikuler
$guru = mysqli_query($conn, "SELECT id_guru, nama_lengkap FROM guru ORDER BY nama_lengkap ASC");
$ekstra = mysqli_query($conn, "SELECT id_ekstra, nama_ekstra FROM ekstrakurikuler ORDER BY nama_ekstra ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_guru = $_POST['id_guru'];
    $id_ekstra = $_POST['id_ekstra'];

    // Cek jika guru sudah membina 2 ekstrakurikuler
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM guru_ekstrakurikuler_map WHERE id_guru = ?");
    $stmt->bind_param("i", $id_guru);
    $stmt->execute();
    $totalEkstraGuru = $stmt->get_result()->fetch_assoc();

    if ($totalEkstraGuru['total'] >= 2) {
        echo "<script>alert('❗ Guru ini sudah membina 2 ekstrakurikuler.'); window.location.href='../dashboard_admin.php';</script>";
        exit;
    }

    // Cek jika ekstrakurikuler sudah memiliki guru
    $cekEkstra = $conn->prepare("SELECT id FROM guru_ekstrakurikuler_map WHERE id_ekstra = ?");
    $cekEkstra->bind_param("i", $id_ekstra);
    $cekEkstra->execute();
    $resEkstra = $cekEkstra->get_result();

    if ($resEkstra->num_rows > 0) {
        echo "<script>alert('❗ Ekstrakurikuler ini sudah memiliki guru pembina.'); window.location.href='../dashboard_admin';</script>";
        exit;
    }

    // Simpan ke database
    $insert = $conn->prepare("INSERT INTO guru_ekstrakurikuler_map (id_guru, id_ekstra) VALUES (?, ?)");
    $insert->bind_param("ii", $id_guru, $id_ekstra);
    $insert->execute();

    echo "<script>alert('✅ Guru berhasil ditugaskan sebagai pembina.'); window.location.href='../dashboard_admin.php';</script>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Guru Pembina</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f9;
      padding: 40px;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
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
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      max-width: 500px;
      margin: auto;
    }

    h2 {
      margin-bottom: 20px;
      color: #082465;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    select {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    button {
      margin-top: 20px;
      background-color: #082465;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
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
  <h2>➕ Tambah Guru Pembina</h2>
  <form method="POST">
    <label>Nama Guru</label>
    <select name="id_guru" required>
      <option value="">-- Pilih Guru --</option>
      <?php while ($g = mysqli_fetch_assoc($guru)): ?>
        <option value="<?= $g['id_guru'] ?>"><?= htmlspecialchars($g['nama_lengkap']) ?></option>
      <?php endwhile; ?>
    </select>

    <label>Ekstrakurikuler</label>
    <select name="id_ekstra" required>
      <option value="">-- Pilih Ekstrakurikuler --</option>
      <?php while ($e = mysqli_fetch_assoc($ekstra)): ?>
        <option value="<?= $e['id_ekstra'] ?>"><?= htmlspecialchars($e['nama_ekstra']) ?></option>
      <?php endwhile; ?>
    </select>

    <button type="submit">Simpan</button>
  </form>
</div>

</body>
</html>
