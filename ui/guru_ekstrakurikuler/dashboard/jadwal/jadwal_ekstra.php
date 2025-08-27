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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Jadwal Ekstrakurikuler</title>
  <style>

    .container {
      max-width: 800px;
      margin: auto;
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    h2 {
      color: #082465;
      margin-bottom: 10px;
    }

    p {
      color: #333;
      margin-bottom: 20px;
    }

    .ekstra-container {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .ekstra-link {
      display: inline-block;
      padding: 14px 20px;
      background-color: #082465;
      color: #fff;
      border-radius: 8px;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .ekstra-link:hover {
      background-color: #0a2d85;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Jadwal Ekstrakurikuler</h2>
    <p>Klik nama ekstrakurikuler untuk melihat jadwal latihannya:</p>

    <div class="ekstra-container">
      <?php while ($row = $result->fetch_assoc()): ?>
        <a class="ekstra-link" href="jadwal/jadwal_detail.php?id=<?= $row['id_ekstra'] ?>">
          📘 <?= htmlspecialchars($row['nama_ekstra']) ?>
        </a>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>
