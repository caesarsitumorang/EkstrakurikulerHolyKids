<?php
session_start();
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../../login.php");
    exit;
}

$id_ref = $_SESSION['id_ref']; 
$username = $_SESSION['username'];

$query = "
    SELECT g.nama_lengkap, g.no_hp, u.username, g.tanggal_lahir, g.jenis_kelamin, g.alamat, g.email
    FROM admin g
    JOIN users u ON g.id_admin = u.id_ref
    WHERE g.id_admin = ? AND u.username = ?
    LIMIT 1
";

$stmt = $conn->prepare($query);
$stmt->bind_param("is", $id_ref, $username);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<div class="profile-wrapper">
  <h2>👤 Profil Admin</h2>

  <?php if ($data): ?>
  <div class="profile-card">
    <table>
      <tr><th>Username</th><td><?= htmlspecialchars($data['username']) ?></td></tr>
      <tr><th>Nama Lengkap</th><td><?= htmlspecialchars($data['nama_lengkap']) ?></td></tr>
      <tr><th>No. HP</th><td><?= htmlspecialchars($data['no_hp']) ?></td></tr>
      <tr><th>Tanggal Lahir</th><td><?= htmlspecialchars($data['tanggal_lahir']) ?></td></tr>
      <tr><th>Jenis Kelamin</th><td><?= htmlspecialchars($data['jenis_kelamin']) ?></td></tr>
      <tr><th>Alamat</th><td><?= htmlspecialchars($data['alamat']) ?></td></tr>
      <tr><th>Email</th><td><?= htmlspecialchars($data['email']) ?></td></tr>
    </table>
  </div>
  <?php else: ?>
    <p>Data admin tidak ditemukan.</p>
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
  max-width: 600px;
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
}

.profile-card td {
  padding: 10px;
  border-bottom: 1px solid #eee;
}
</style>
