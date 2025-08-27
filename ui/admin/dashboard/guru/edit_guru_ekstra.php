<?php
include '../../../../database/config.php';

$id = $_GET['id'] ?? 0;

// Ambil data guru ekstra saat ini
$current = $conn->prepare("SELECT * FROM guru_ekstrakurikuler_map WHERE id = ?");
$current->bind_param("i", $id);
$current->execute();
$result = $current->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='../guru_ekstra.php';</script>";
    exit;
}

$guru = mysqli_query($conn, "SELECT id_guru, nama_lengkap FROM guru ORDER BY nama_lengkap ASC");
$ekstra = mysqli_query($conn, "SELECT id_ekstra, nama_ekstra FROM ekstrakurikuler ORDER BY nama_ekstra ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_guru = $_POST['id_guru'];
    $id_ekstra = $_POST['id_ekstra'];

    // Hitung jumlah ekstra guru selain id ini
    $cek = $conn->prepare("SELECT COUNT(*) as total FROM guru_ekstrakurikuler_map WHERE id_guru = ? AND id != ?");
    $cek->bind_param("ii", $id_guru, $id);
    $cek->execute();
    $cek_result = $cek->get_result()->fetch_assoc();

    if ($cek_result['total'] >= 2) {
        echo "<script>alert('❗ Guru ini sudah membina 2 ekstrakurikuler.'); window.location.href='../dashboard_admin.php';</script>";
        exit;
    }

    $update = $conn->prepare("UPDATE guru_ekstrakurikuler_map SET id_guru = ?, id_ekstra = ? WHERE id = ?");
    $update->bind_param("iii", $id_guru, $id_ekstra, $id);
    $update->execute();

    echo "<script>alert('✅ Data berhasil diperbarui!'); window.location.href='../dashboard_admin.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Guru Ekstra</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f9;
      padding: 40px;
    }

    .form-container {
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      max-width: 500px;
      margin: auto;
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

    .back {
      background-color: #ccc;
      text-decoration: none;
      color: black;
      padding: 8px 14px;
      border-radius: 6px;
    }
  </style>
</head>
<body>

<div class="form-container">
  <a href="../dashboard_admin.php" class="back">← Kembali</a>
  <h2>✏️ Edit Guru Pembina</h2>
  <form method="POST">
    <label>Nama Guru</label>
    <select name="id_guru" required>
      <?php while ($g = mysqli_fetch_assoc($guru)): ?>
        <option value="<?= $g['id_guru'] ?>" <?= $g['id_guru'] == $data['id_guru'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($g['nama_lengkap']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <label>Ekstrakurikuler</label>
    <select name="id_ekstra" required>
      <?php while ($e = mysqli_fetch_assoc($ekstra)): ?>
        <option value="<?= $e['id_ekstra'] ?>" <?= $e['id_ekstra'] == $data['id_ekstra'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($e['nama_ekstra']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <button type="submit">Simpan Perubahan</button>
  </form>
</div>

</body>
</html>
