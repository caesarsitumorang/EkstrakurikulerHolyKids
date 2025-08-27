<?php
session_start();
include '../../../../database/config.php';

$id_guru = $_SESSION['id_ref'] ?? 0;
?>

<div class="beranda-wrapper">
  <div class="welcome-box">
    <h1>👋 Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?></h1>
    <p class="desc">Ini adalah beranda wali kelas. Silakan kelola informasi siswa dan ekstrakurikuler di sini.</p>
  </div>

  <div class="info-section">
    <div class="info-box">
      <h3>Jumlah Siswa</h3>
      <div class="angka">
        <?php
          $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa WHERE id_kelas IN (SELECT id_kelas FROM wali_kelas WHERE id_wali = '$id_guru')");
          $jumlah = mysqli_fetch_assoc($q)['total'] ?? 0;
          echo $jumlah;
        ?>
      </div>
      <p class="keterangan">Total siswa yang berada dalam kelas yang Anda bina</p>
    </div>
  </div>
</div>

<style>
body {
  margin: 0;
  background-color: #f0f4f8;
  font-family: 'Segoe UI', sans-serif;
}

.beranda-wrapper {
  padding: 40px 20px;
  max-width: 900px;
  margin: auto;
}

.welcome-box {
  background: linear-gradient(to right, #0a2d85, #4169e1);
  padding: 30px;
  border-radius: 12px;
  color: white;
  margin-bottom: 30px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.welcome-box h1 {
  font-size: 28px;
  margin-bottom: 10px;
}

.desc {
  font-size: 16px;
  color: #dbe8ff;
}

.info-section {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
}

.info-box {
  flex: 1 1 300px;
  background: white;
  border-left: 6px solid #0a2d85;
  border-radius: 10px;
  box-shadow: 0 3px 8px rgba(0,0,0,0.05);
  padding: 24px;
}

.info-box h3 {
  font-size: 18px;
  margin-bottom: 10px;
  color: #0a2d85;
}

.angka {
  font-size: 36px;
  font-weight: bold;
  color: #0a2d85;
}

.keterangan {
  font-size: 14px;
  color: #666;
}
</style>
