<?php
include '../../../../database/config.php';

$id = $_GET['id'];
$siswa = mysqli_query($conn, "SELECT id_siswa, nama FROM siswa");
$ekstra = mysqli_query($conn, "SELECT id_ekstra, nama_ekstra FROM ekstrakurikuler");

$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa_ekstrakurikuler WHERE id_siswa = $id"));
$nama_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM siswa WHERE id_siswa = $id"))['nama'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_ekstra = $_POST['id_ekstra'];

    $stmt = $conn->prepare("UPDATE siswa_ekstrakurikuler SET id_ekstra = ? WHERE id_siswa = ?");
    $stmt->bind_param("ii", $id_ekstra, $id);
    $stmt->execute();

    echo "<script>alert('✅ Data berhasil diubah'); window.location.href='../dashboard_admin.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Ekstrakurikuler Siswa</title>
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

    select, input[type="text"] {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      margin-bottom: 20px;
      background-color: #f9f9f9;
    }

    button {
      padding: 10px 20px;
      background-color: #007bff;
      border: none;
      color: white;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }

    button:hover {
      background-color: #0056b3;
    }

    a {
      display: inline-block;
      margin-left: 15px;
      text-decoration: none;
      color: #dc3545;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>✏️ Edit Ekstrakurikuler Siswa</h2>
    <form method="POST">
      <label for="nama_siswa">Nama Siswa</label>
      <input type="text" id="nama_siswa" value="<?= htmlspecialchars($nama_siswa) ?>" disabled>

      <label for="id_ekstra">Ekstrakurikuler</label>
      <select name="id_ekstra" id="id_ekstra" required>
        <option value="">-- Pilih Ekstra --</option>
        <?php while ($e = mysqli_fetch_assoc($ekstra)): ?>
          <option value="<?= $e['id_ekstra'] ?>" <?= $e['id_ekstra'] == $data['id_ekstra'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($e['nama_ekstra']) ?>
          </option>
        <?php endwhile; ?>
      </select>

      <button type="submit">Update</button>
      <a href="../dashboard_admin.php">Kembali</a>
    </form>
  </div>
</body>
</html>
