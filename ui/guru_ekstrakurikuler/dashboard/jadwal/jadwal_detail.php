<?php
include '../../../../database/config.php';

$id_ekstra = isset($_GET['id']) ? intval($_GET['id']) : 0;

$result = mysqli_query($conn, "SELECT nama_ekstra, hari, jam, lokasi FROM ekstrakurikuler WHERE id_ekstra = $id_ekstra");
if (!$row = mysqli_fetch_assoc($result)) {
    echo "<div class='alert'>❌ Data tidak ditemukan.</div>";
    exit;
}

$hari = $row['hari'];
$hariMap = ['Minggu'=>0,'Senin'=>1,'Selasa'=>2,'Rabu'=>3,'Kamis'=>4,'Jumat'=>5,'Sabtu'=>6];
$targetDay = $hariMap[$hari] ?? 0;

// Fungsi tanggal Indonesia
function formatTanggalIndonesia($tanggal) {
    $bulanIndo = [
        'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember'
    ];
    $hariIndo = [
        'Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'
    ];

    $hari = $hariIndo[date('w', strtotime($tanggal))];
    $tgl = date('d', strtotime($tanggal));
    $bulan = $bulanIndo[(int)date('m', strtotime($tanggal)) - 1];
    $tahun = date('Y', strtotime($tanggal));

    return "$hari, $tgl $bulan $tahun";
}

// Cari hari pertama latihan
$today = new DateTime();
while ((int)$today->format('w') !== $targetDay) {
    $today->modify('+1 day');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Jadwal Ekstrakurikuler</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f9;
      margin: 0;
      padding: 20px;
    }

    .jadwal-container {
      max-width: 900px;
      margin: auto;
      background: #fff;
      border-radius: 8px;
      padding: 25px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .header h2 {
      color: #082465;
      margin: 0;
    }

    .btn-kembali {
      background: #082465;
      color: white;
      padding: 10px 16px;
      text-decoration: none;
      border-radius: 6px;
    }

    .btn-kembali:hover {
      background: #0a2d85;
    }

    .info-box {
      background: #eef1f7;
      border-left: 5px solid #082465;
      padding: 12px 16px;
      margin-bottom: 20px;
      border-radius: 6px;
    }

    .jadwal-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
    }

    .jadwal-item {
      background-color: #f9f9f9;
      border-left: 5px solid #082465;
      padding: 12px;
      border-radius: 6px;
      font-weight: bold;
    }

    .alert {
      color: red;
      font-weight: bold;
      background: #ffeeee;
      padding: 15px;
      border: 1px solid #ffcccc;
      border-radius: 6px;
      margin: 30px auto;
      max-width: 600px;
    }
  </style>
</head>
<body>
  <div class="jadwal-container">
    <div class="header">
        <a href="../dashboard_guru.php" class="btn-kembali">← Kembali</a>
      <h2><?= htmlspecialchars($row['nama_ekstra']) ?></h2>
      
    </div>

    <div class="info-box">
      <p><strong>Hari Latihan:</strong> <?= $row['hari'] ?></p>
      <p><strong>Jam:</strong> <?= $row['jam'] ?></p>
      <p><strong>Lokasi:</strong> <?= $row['lokasi'] ?></p>
    </div>

    <h4>Jadwal Pertemuan (24 minggu):</h4>
    <div class="jadwal-grid">
      <?php
        for ($i = 0; $i < 24; $i++) {
            $date = clone $today;
            $date->modify("+{$i} weeks");
            echo "<div class='jadwal-item'>🗓️ " . formatTanggalIndonesia($date->format('Y-m-d')) . "</div>";
        }
      ?>
    </div>
  </div>
</body>
</html>
