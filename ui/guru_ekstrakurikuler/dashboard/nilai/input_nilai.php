<?php
session_start();
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../../../../login.php");
    exit;
}

$id_guru = $_SESSION['id_ref'];

if (!isset($_GET['id_ekstra'])) {
    echo "Ekstrakurikuler tidak ditemukan.";
    exit;
}

$id_ekstra = $_GET['id_ekstra'];

// Ambil nama ekstrakurikuler
$stmt = $conn->prepare("SELECT nama_ekstra FROM ekstrakurikuler WHERE id_ekstra = ?");
$stmt->bind_param("i", $id_ekstra);
$stmt->execute();
$stmt->bind_result($nama_ekstra);
$stmt->fetch();
$stmt->close();

// Ambil daftar siswa yang ikut ekstra
$query = "
    SELECT s.id_siswa, s.nama
    FROM siswa_ekstrakurikuler m
    JOIN siswa s ON m.id_siswa = s.id_siswa
    WHERE m.id_ekstra = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_ekstra);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Input Nilai Ekstrakurikuler</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f3f9;
      padding: 40px;
    }

    .container {
      max-width: 900px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    h2 {
      color: #082465;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }

    th {
      background-color: #082465;
      color: white;
    }

    input[type="number"] {
      width: 70px;
      padding: 5px;
    }

    button {
      margin-top: 20px;
      padding: 10px 18px;
      background-color: #082465;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0a2d85;
    }

    .btn-back {
      display: inline-block;
      margin-top: 25px;
      padding: 10px 20px;
      background-color: #999;
      color: white;
      text-decoration: none;
      border-radius: 6px;
    }

    .btn-back:hover {
      background-color: #777;
    }

    .disabled {
      background-color: #eee !important;
      color: #999 !important;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Input Nilai - <?= htmlspecialchars($nama_ekstra) ?></h2>

    <form action="simpan_nilai.php" method="POST">
      <input type="hidden" name="id_ekstra" value="<?= $id_ekstra ?>">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Jumlah Hadir</th>
            <th>Nilai (0-100)</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = $result->fetch_assoc()): 
            $id_siswa = $row['id_siswa'];

            // Ambil jumlah hadir dari absensi_ekstrakurikuler
            $hadir_q = $conn->prepare("SELECT COUNT(*) FROM absensi_ekstrakurikuler WHERE id_siswa = ? AND id_ekstra = ? AND status = 'hadir'");
            $hadir_q->bind_param("ii", $id_siswa, $id_ekstra);
            $hadir_q->execute();
            $hadir_q->bind_result($jumlah_hadir);
            $hadir_q->fetch();
            $hadir_q->close();

            // Cek apakah sudah ada nilai
            $nilai_q = $conn->prepare("SELECT nilai_user FROM nilai_siswa WHERE id_siswa = ? AND id_ekstra = ?");
            $nilai_q->bind_param("ii", $id_siswa, $id_ekstra);
            $nilai_q->execute();
            $nilai_q->store_result();

            $sudah_input = $nilai_q->num_rows > 0;
            $nilai_q->close();
          ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td>
              <input type="number" value="<?= $jumlah_hadir ?>" readonly class="disabled">
              <input type="hidden" name="jumlah_hadir[<?= $id_siswa ?>]" value="<?= $jumlah_hadir ?>">
            </td>
            <td>
              <?php if ($sudah_input): ?>
                <input type="number" value="Sudah diisi" class="disabled" readonly>
              <?php else: ?>
                <input type="number" name="nilai_user[<?= $id_siswa ?>]" min="0" max="100" required>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <button type="submit">Simpan Nilai</button>
    </form>

    <a href="../dashboard_guru.php" class="btn-back">← Kembali ke Dashboard</a>
  </div>
</body>
</html>
