<?php
session_start();
include '../../../../database/config.php';

// Cek sesi login dan role siswa
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../../../login.php");
    exit;
}

$id_siswa = $_SESSION['id_ref']; // id_ref berisi id_siswa dari tabel siswa

// Ambil data siswa + nama kelas
$query = "
  SELECT s.*, k.nama_kelas 
  FROM siswa s
  LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
  WHERE s.id_siswa = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_siswa);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<div class="profile-wrapper">
  <h2>🧑‍🎓 Profil Siswa</h2>

  <?php if ($data): ?>
    <div class="profile-card">
      <table>
        <tr><th>Username</th><td><?= htmlspecialchars($_SESSION['username']) ?></td></tr>
        <tr><th>Nama Lengkap</th><td><?= htmlspecialchars($data['nama']) ?></td></tr>
        <tr><th>NIS</th><td><?= htmlspecialchars($data['nis']) ?></td></tr>
        <tr><th>Kelas</th><td><?= htmlspecialchars($data['nama_kelas'] ?? '-') ?></td></tr>
        <tr><th>No HP</th><td><?= htmlspecialchars($data['no_hp']) ?></td></tr>
        <tr><th>Jenis Kelamin</th><td><?= htmlspecialchars($data['jenis_kelamin']) ?></td></tr>
        <tr><th>Alamat</th><td><?= htmlspecialchars($data['alamat']) ?></td></tr>
        <tr><th>Nama Orangtua</th><td><?= htmlspecialchars($data['nama_ortu']) ?></td></tr>
        <tr><th>Nomor HP Orangtua</th><td><?= htmlspecialchars($data['no_hp_ortu']) ?></td></tr>
      </table>
    </div>
  <?php else: ?>
    <div class="alert">⚠️ Data siswa tidak ditemukan.</div>
  <?php endif; ?>
</div>

<style>
.profile-wrapper {
  font-family: 'Segoe UI', sans-serif;
  background: #f8f9fb;
  padding: 30px;
}

h2 {
  color: #082465;
  margin-bottom: 20px;
}

.profile-card {
  background: white;
  padding: 24px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  max-width: 700px;
  margin: auto;
}

.profile-card table {
  width: 100%;
  border-collapse: collapse;
}

.profile-card th {
  text-align: left;
  width: 30%;
  padding: 10px;
  background-color: #f1f4f9;
  color: #082465;
  border-bottom: 1px solid #eee;
}

.profile-card td {
  padding: 10px;
  border-bottom: 1px solid #eee;
}

.alert {
  background-color: #fff3cd;
  color: #856404;
  padding: 15px;
  border-left: 5px solid #ffc107;
  border-radius: 5px;
  margin-top: 20px;
  text-align: center;
}
</style>
