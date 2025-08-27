<?php
include '../../../../../database/config.php';

if (!isset($_GET['id'])) {
  echo "ID admin tidak ditemukan.";
  exit;
}

$id_admin = (int) $_GET['id'];

$delete_users = $conn->prepare("DELETE FROM users WHERE id_ref = ? AND role = 'admin'");
$delete_users->bind_param("i", $id_admin);
$delete_users->execute();

$delete_admin = $conn->prepare("DELETE FROM admin WHERE id_admin = ?");
$delete_admin->bind_param("i", $id_admin);

if ($delete_admin->execute()) {
  header("Location: ../../dashboard_admin.php");
  exit;
} else {
  echo "Gagal menghapus data admin.";
}
?>
