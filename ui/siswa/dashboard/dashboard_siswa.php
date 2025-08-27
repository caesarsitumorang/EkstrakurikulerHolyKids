<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Siswa</title>
    <link href="../../../ui/siswa/css/style_dashboard_siswa.css" rel="stylesheet" />
</head>
<body>
  <div class="main-wrapper">
    <!-- Navbar -->
    <div class="navbar">
      <div class="navbar-title">SMA HOLY KIDS BERSINAR MEDAN</div>
      <div class="navbar-user">Halo, <?= htmlspecialchars($_SESSION['username']) ?></div>
    </div>

    <div class="dashboard-container">
      <!-- Sidebar -->
      <div class="sidebar">
    <a id="menu-beranda" onclick="loadPage('beranda/beranda_siswa.php', this)"> Beranda</a>
    <a id="menu-ekstra" onclick="loadPage('ekstra/ekstrakurikuler_db_siswa.php', this)"> Ekstrakurikuler</a>
    <a id="menu-pendaftaran" onclick="loadPage('pendaftaran/pendaftaran_siswa.php', this)"> Pendaftaran</a>
    <a id="menu-status" onclick="loadPage('pendaftaran/status_pendaftaran.php', this)"> Status Pendaftaran </a>
    <a id="menu-ekstra-saya" onclick="loadPage('ekstra/ekstrakurikuler_saya.php', this)"> Ekstrakurikuler Saya</a>
    <a id="menu-riwayat-saya" onclick="loadPage('riwayat/riwayat_ekstrakurikuler.php', this)"> Riwayat Ekstrakurikuler</a>
    <a id="menu-profil" onclick="loadPage('profill/profil_siswa.php', this)"> Profil</a>
    <div class="logout">
        <a href="../../../logout.php">Logout</a>
    </div>
    </div>


      <!-- Konten -->
      <div class="content" id="main-content">
        <!-- Konten akan dimuat di sini -->
      </div>
    </div>
  </div>

  <script>
   function loadPage(page, element = null) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "./" + page, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
        document.getElementById("main-content").innerHTML = xhr.responseText;
        } else {
        document.getElementById("main-content").innerHTML = "<p>Halaman tidak ditemukan.</p>";
        }
    };
    xhr.send();
    document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));

    if (element) {
        element.classList.add('active');
    }
    }


    window.onload = function () {
      loadPage("beranda/beranda_siswa.php");
    //   loadPage("ekstra/ekstrakurikuler_db_siswa.php");
    };
  </script>
</body>
</html>
