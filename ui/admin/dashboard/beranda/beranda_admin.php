<?php
session_start();
include '../../../../database/config.php';

$id_guru = $_SESSION['id_ref'] ?? 0;

// Hitung total ekstrakurikuler yang dibina
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM guru_ekstrakurikuler_map WHERE id_guru = ?");
$stmt->bind_param("i", $id_guru);
$stmt->execute();
$result = $stmt->get_result();
$total_ekstra = $result->fetch_assoc()['total'] ?? 0;
?>

<div class="beranda-container">
  <div class="welcome-section">
    <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?> 👋</h2>
    <p class="subtitle">Ini adalah beranda admin. Kelola kegiatanmu dengan lebih mudah di sistem ini.</p>
  </div>

  
<style>
.beranda-container {
  font-family: 'Segoe UI', sans-serif;
  background-color: #f3f6fc;
  padding: 30px;
  color: #333;
}

.welcome-section h2 {
  color: #082465;
  font-size: 24px;
  margin-bottom: 5px;
}

.subtitle {
  font-size: 15px;
  color: #555;
  margin-bottom: 25px;
}

.summary-box {
  background: #fff;
  border-left: 6px solid #082465;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  padding: 20px;
  border-radius: 10px;
  max-width: 600px;
}

.info h3 {
  margin: 0;
  color: #082465;
}

.angka {
  font-size: 36px;
  font-weight: bold;
  margin: 10px 0;
  color: #0a2d85;
}

.keterangan {
  font-size: 13px;
  color: #666;
}
</style>
