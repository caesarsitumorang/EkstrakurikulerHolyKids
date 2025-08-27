<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
    <link href="../../../ui/admin/css/style_dashboard_admin.css" rel="stylesheet" />
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
    <a id="menu-beranda" onclick="loadPage('beranda/beranda_admin.php', this)"> Beranda</a>
    <a id="menu-ekstra" onclick="loadPage('ekstra/ekstra_in_admin.php', this)"> Ekstrakurikuler</a>
    <a id="menu-guru-ekstra" onclick="loadPage('guru/guru_ekstra.php', this)"> Guru Ekstrakurikuler</a>
    <a id="menu-siswa" onclick="loadPage('siswa_ekstra/siswa_ekstra.php', this)"> Siswa Ekstrakurikuler </a>
    <a id="menu-ekstra-saya" onclick="loadPage('nilai/nilai_siswa_ekstra.php', this)"> Nilai Siswa Ekstrakurikuler</a>
    <a id="menu-data" onclick="loadPage('data_user/data_user.php', this)"> Data User</a>
    <a id="menu-profil" onclick="loadPage('profil/profil_admin.php', this)"> Profil</a>
    <div class="logout">
        <a href="../../../logout.php">Logout</a>
    </div>
    </div>

      <div class="content" id="main-content">
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
      loadPage("beranda/beranda_admin.php");
    };
  </script>
</body>
</html>
