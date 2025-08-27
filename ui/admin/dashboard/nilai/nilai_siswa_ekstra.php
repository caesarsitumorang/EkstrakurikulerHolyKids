<?php
include '../../../../database/config.php';

$ekstra_list = mysqli_query($conn, "SELECT id_ekstra, nama_ekstra FROM ekstrakurikuler ORDER BY nama_ekstra ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Ekstrakurikuler</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f5f7fa;
      color: #333;
    }

    .container {
      max-width: 700px;
      margin: 60px auto;
      padding: 40px;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.05);
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: 600;
      font-size: 24px;
      color: #2c3e50;
    }

    .ekstra-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .ekstra-link {
      display: block;
      padding: 14px 20px;
      background-color: #ecf0f1;
      color: #2c3e50;
      text-decoration: none;
      border-radius: 8px;
      transition: background-color 0.2s ease, transform 0.2s ease;
      box-shadow: inset 0 0 0 1px #dfe6e9;
    }

    .ekstra-link:hover {
      background-color: #dfe6e9;
      transform: translateY(-1px);
    }

    .footer {
      margin-top: 40px;
      text-align: center;
      font-size: 13px;
      color: #999;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>📘 Daftar Ekstrakurikuler</h2>
    <div class="ekstra-list">
      <?php while ($e = mysqli_fetch_assoc($ekstra_list)): ?>
        <a class="ekstra-link" href="nilai/tabel_nilai_siswa.php?ekstra=<?= $e['id_ekstra'] ?>">
          <?= htmlspecialchars($e['nama_ekstra']) ?>
        </a>
      <?php endwhile; ?>
    </div>
    <div class="footer">
      SMA Holy Kids Bersinar &copy; <?= date("Y") ?>
    </div>
  </div>
</body>
</html>
