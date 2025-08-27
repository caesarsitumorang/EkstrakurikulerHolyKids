<?php
session_start();
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../../../login.php");
    exit;
}

$id_siswa = $_SESSION['id_ref'];
$id_ekstra = $_GET['id_ekstra'] ?? null;

if (!$id_ekstra) {
    echo "ID Ekstrakurikuler tidak ditemukan.";
    exit;
}

// Ambil data absensi hadir siswa
$stmt = $conn->prepare("SELECT tanggal FROM absensi_ekstrakurikuler WHERE id_siswa = ? AND id_ekstra = ? AND status = 'hadir' ORDER BY tanggal ASC");
$stmt->bind_param("ii", $id_siswa, $id_ekstra);
$stmt->execute();
$result = $stmt->get_result();

// Fungsi format tanggal dalam bahasa Indonesia
function formatTanggalIndo($tanggal) {
    $bulan = [
        1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];
    $tgl = date('d', strtotime($tanggal));
    $bln = $bulan[(int)date('m', strtotime($tanggal))];
    $thn = date('Y', strtotime($tanggal));
    return $tgl . ' ' . $bln . ' ' . $thn;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Kehadiran Ekstrakurikuler</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f7f8fb;
      margin: 0;
      padding: 30px;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      animation: fadeIn 0.6s ease-in-out;
    }
    h2 {
      color: #082465;
      text-align: center;
      margin-bottom: 25px;
    }
    ul {
      list-style: none;
      padding: 0;
    }
    li {
      background-color: #e8f0fe;
      padding: 12px 15px;
      margin-bottom: 10px;
      border-radius: 8px;
      border-left: 5px solid #082465;
      font-size: 15px;
      color: #333;
      display: flex;
      align-items: center;
    }
    li::before {
      content: "✅";
      margin-right: 10px;
    }
    p.empty {
      text-align: center;
      font-size: 16px;
      color: #555;
    }
    a.back {
      display: inline-block;
      margin-top: 20px;
      background-color: #082465;
      color: #fff;
      padding: 10px 18px;
      text-decoration: none;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }
    a.back:hover {
      background-color: #061a4d;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    @media screen and (max-width: 500px) {
      body {
        padding: 15px;
      }
      .container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <h2>📅 Daftar Kehadiran Ekstrakurikuler</h2>

  <?php if ($result->num_rows > 0): ?>
    <ul>
      <?php while ($row = $result->fetch_assoc()): ?>
        <li><?= formatTanggalIndo($row['tanggal']) ?></li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <p class="empty">🙁 Belum ada kehadiran yang tercatat.</p>
  <?php endif; ?>

  <a href="javascript:history.back()" class="back">← Kembali</a>
</div>

</body>
</html>
