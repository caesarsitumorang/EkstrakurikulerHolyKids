<?php
include '../../../../database/config.php';

$siswa = mysqli_query($conn, "SELECT id_siswa, nama FROM siswa ORDER BY nama ASC");
$ekstra = mysqli_query($conn, "SELECT id_ekstra, nama_ekstra FROM ekstrakurikuler ORDER BY nama_ekstra ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_siswa = $_POST['id_siswa'];
    $id_ekstra = $_POST['id_ekstra'];

    $cek = $conn->prepare("SELECT * FROM siswa_ekstrakurikuler WHERE id_siswa = ?");
    $cek->bind_param("i", $id_siswa);
    $cek->execute();
    $res = $cek->get_result();

    if ($res->num_rows > 0) {
        echo "<script>alert('❗ Siswa ini sudah terdaftar dalam ekstrakurikuler.'); window.location.href='../dashboard_admin.php';</script>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO siswa_ekstrakurikuler (id_siswa, id_ekstra) VALUES (?, ?)");
    $stmt->bind_param("ii", $id_siswa, $id_ekstra);
    $stmt->execute();

    echo "<script>alert('✅ Data berhasil ditambahkan'); window.location.href='../dashboard_admin.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Siswa Ekstrakurikuler</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      padding: 40px;
    }

    .container {
      background-color: #fff;
      border-radius: 12px;
      padding: 30px;
      max-width: 500px;
      margin: auto;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
    }

    select {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      margin-bottom: 20px;
    }

    button {
      padding: 10px 20px;
      background-color: #28a745;
      border: none;
      color: white;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }

    button:hover {
      background-color: #218838;
    }

    a {
      display: inline-block;
      margin-left: 15px;
      text-decoration: none;
      color: #007bff;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>➕ Tambah Siswa Ekstrakurikuler</h2>
    <form method="POST">
      <label for="id_siswa">Nama Siswa</label>
      <select name="id_siswa" id="id_siswa" required>
        <option value="">-- Pilih Siswa --</option>
        <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
          <option value="<?= $s['id_siswa'] ?>"><?= htmlspecialchars($s['nama']) ?></option>
        <?php endwhile; ?>
      </select>

      <label for="id_ekstra">Ekstrakurikuler</label>
      <select name="id_ekstra" id="id_ekstra" required>
        <option value="">-- Pilih Ekstra --</option>
        <?php while ($e = mysqli_fetch_assoc($ekstra)): ?>
          <option value="<?= $e['id_ekstra'] ?>"><?= htmlspecialchars($e['nama_ekstra']) ?></option>
        <?php endwhile; ?>
      </select>

      <button type="submit">Simpan</button>
      <a href="../dashboard_admin.php">Kembali</a>
    </form>
  </div>
</body>
</html>
