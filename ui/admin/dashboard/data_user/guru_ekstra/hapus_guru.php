<?php
include '../../../../../database/config.php';

if (!isset($_GET['id'])) {
  echo "ID guru tidak ditemukan.";
  exit;
}

$id_guru = (int) $_GET['id'];

$delete_users = $conn->prepare("DELETE FROM users WHERE id_ref = ? AND role = 'guru'");
$delete_users->bind_param("i", $id_guru);
$delete_users->execute();

$delete_guru = $conn->prepare("DELETE FROM guru WHERE id_guru = ?");
$delete_guru->bind_param("i", $id_guru);

if ($delete_guru->execute()) {
  header("Location: ../../dashboard_admin.php");
  exit;
} else {
  echo "Gagal menghapus data guru.";
}
?>
