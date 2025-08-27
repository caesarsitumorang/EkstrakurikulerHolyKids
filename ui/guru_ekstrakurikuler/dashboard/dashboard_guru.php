<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../../../login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Guru</title>
  <link href="../../../ui/guru_ekstrakurikuler/css/style_dashboard_guru.css" rel="stylesheet" />
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
        <a id="menu-beranda" onclick="loadPage('beranda/beranda_guru.php', this)">Beranda</a>
        <a id="menu-jadwal" onclick="loadPage('jadwal/jadwal_ekstra.php', this)">Jadwal</a>
        <a id="menu-pendaftaran" onclick="loadPage('ekstra/daftar_pendaftar.php', this)">Data Pendaftar Ekstrakurikuler</a>
        <a id="menu-data" onclick="loadPage('data_siswa/data_siswa.php', this)">Data Siswa</a>
        <a id="menu-absensi" onclick="loadPage('absensi/absensi_index.php', this)">Absensi</a>
        <a id="menu-nilai" onclick="loadPage('nilai/nilai_ekstra.php', this)">Input Nilai</a>
        <a id="menu-profil" onclick="loadPage('profil/profil_guru.php', this)">Profil</a>
        <div class="logout"><a href="../../../logout.php">Logout</a></div>
      </div>

      <!-- Konten -->
      <div class="content" id="main-content"></div>
    </div>
  </div>

  <!-- Toast Notification -->
  <div id="toast" style="position: fixed; top: 20px; right: 20px; background: #333; color: white; padding: 10px 20px; border-radius: 6px; display: none; z-index: 9999;"></div>

  <script>
    function loadPage(page, element = null) {
      const xhr = new XMLHttpRequest();
      xhr.open("GET", "./" + page, true);
      xhr.onload = function () {
        document.getElementById("main-content").innerHTML = xhr.responseText;
      };
      xhr.send();

      document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
      if (element) element.classList.add('active');
    }

    function showToast(message, color = "#28a745") {
      const toast = document.getElementById('toast');
      toast.innerText = message;
      toast.style.backgroundColor = color;
      toast.style.display = 'block';
      setTimeout(() => {
        toast.style.display = 'none';
      }, 3000);
    }

    window.onload = function () {
      loadPage("beranda/beranda_guru.php");
    };

    document.addEventListener('submit', function (e) {
      if (e.target.classList.contains('form-setujui') || e.target.classList.contains('form-tolak')) {
        e.preventDefault();

        const formData = new FormData(e.target);
        const isSetujui = e.target.classList.contains('form-setujui');
        const rowId = e.target.closest('tr')?.id;

        const url = isSetujui ? './ekstra/setujui_pendaftaran.php' : './ekstra/tolak_pendaftaran.php';

        fetch(url, {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            showToast(isSetujui ? "✅ Disetujui" : "🚫 Ditolak", isSetujui ? "#28a745" : "#dc3545");

            // Hapus baris dari tampilan
            if (rowId) document.getElementById(rowId).remove();
          } else {
            showToast("❌ Gagal memproses", "#dc3545");
          }
        });
      }
    });
  </script>
</body>
</html>
