<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Role Pengguna</title>
  <style>
    h2 {
      text-align: center;
      color: #082465;
      margin-bottom: 30px;
    }

    .role-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      max-width: 900px;
      margin: auto;
    }

    .role-card {
      background-color: white;
      border-left: 6px solid #082465;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
      padding: 30px;
      text-align: center;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.3s ease;
      text-decoration: none;
      color: inherit;
    }

    .role-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }

    .role-title {
      font-size: 18px;
      font-weight: bold;
      color: #082465;
    }
  </style>
</head>
<body>

<h2>🔐 Manajemen Role Pengguna</h2>

<div class="role-grid">
  <a href="data_user/admin/data_admin.php" class="role-card">
    <div class="role-title">👨‍💼 Admin</div>
  </a>
  <a href="data_user/siswa/data_siswa.php" class="role-card">
    <div class="role-title">🧑‍🎓 Siswa</div>
  </a>
  <a href="data_user/guru_ekstra/data_guru.php" class="role-card">
    <div class="role-title">👨‍🏫 Guru</div>
  </a>
  <a href="data_user/wali_kelas/data_wali_kelas.php" class="role-card">
    <div class="role-title">📘 Wali Kelas</div>
  </a>
</div>

</body>
</html>
