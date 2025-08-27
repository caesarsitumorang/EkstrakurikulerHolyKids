<?php
session_start();
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../../../../login.php");
    exit;
}

$id_guru = $_SESSION['id_ref'];

$query = "
    SELECT e.id_ekstra, e.nama_ekstra
    FROM guru_ekstrakurikuler_map g
    JOIN ekstrakurikuler e ON g.id_ekstra = e.id_ekstra
    WHERE g.id_guru = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_guru);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pilih Ekstrakurikuler</title>
  <style>

    .container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
      color: #082465;
      text-align: center;
    }

    .ekstra-list {
      margin-top: 20px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .ekstra-item {
      background-color: #082465;
      color: white;
      padding: 12px 18px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    .ekstra-item:hover {
      background-color: #0a2d85;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Pilih Ekstrakurikuler</h2>
    <p>Silakan pilih salah satu ekstrakurikuler yang Anda bina untuk menginput nilai siswa:</p>

    <div class="ekstra-list">
      <?php while ($row = $result->fetch_assoc()): ?>
        <a class="ekstra-item" href="nilai/input_nilai.php?id_ekstra=<?= $row['id_ekstra'] ?>">
          📘 <?= htmlspecialchars($row['nama_ekstra']) ?>
        </a>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>
