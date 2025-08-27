<?php
include '../../../../../database/config.php';

if (!isset($_GET['id'])) {
  echo "ID siswa tidak ditemukan.";
  exit;
}

$id_admin = (int) $_GET['id'];

$delete_users = $conn->prepare("DELETE FROM users WHERE id_ref = ? AND role = 'siswa'");
$delete_users->bind_param("i", $id_admin);
$delete_users->execute();

$delete_admin = $conn->prepare("DELETE FROM siswa WHERE id_siswa = ?");
$delete_admin->bind_param("i", $id_admin);

if ($delete_admin->execute()) {
  header("Location: ../../dashboard_admin.php");
  exit;
} else {
  echo "Gagal menghapus data siswa.";
}
?>
