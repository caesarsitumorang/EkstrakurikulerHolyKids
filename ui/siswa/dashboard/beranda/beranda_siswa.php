<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../../../login.php");
    exit;
}
?>

<div class="beranda-wrapper">
  <h2>👋 Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?></h2>
  <p class="intro">Ini adalah halaman utama siswa. Sistem ini dirancang untuk memudahkan kamu dalam:</p>
  
  <ul class="fitur-list">
    <li>📋 Melihat dan mendaftar ekstrakurikuler</li>
    <li>📆 Melihat jadwal kegiatan ekstrakurikuler</li>
    <li>👤 Mengelola profil dan informasi pribadi</li>
  </ul>

  <div class="pengingat">
    🎯 <strong>Tips:</strong> Aktiflah dalam kegiatan ekstrakurikuler untuk menambah pengalaman dan keterampilan di luar kelas!
  </div>
</div>

<style>
.beranda-wrapper {
  font-family: 'Segoe UI', sans-serif;
  background-color: #f5f8fc;
  padding: 40px;
  border-radius: 10px;
  max-width: 900px;
  margin: 0 auto;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

h2 {
  color: #082465;
  margin-bottom: 10px;
}

.intro {
  font-size: 1rem;
  color: #333;
  margin-bottom: 20px;
}

.fitur-list {
  list-style: none;
  padding-left: 0;
  margin-bottom: 30px;
}

.fitur-list li {
  background: #ffffff;
  border-left: 5px solid #082465;
  padding: 12px 16px;
  margin-bottom: 10px;
  border-radius: 6px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  font-size: 0.95rem;
}

.pengingat {
  background-color: #e0f7fa;
  padding: 14px 18px;
  border-left: 5px solid #00796b;
  border-radius: 6px;
  color: #004d40;
  font-weight: 500;
}
</style>
