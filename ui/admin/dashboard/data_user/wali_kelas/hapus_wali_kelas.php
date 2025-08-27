<?php
include '../../../../../database/config.php';

if (!isset($_GET['id'])) {
  echo "ID wali tidak ditemukan.";
  exit;
}

$id_wali = (int) $_GET['id'];

$delete_users = $conn->prepare("DELETE FROM users WHERE id_ref = ? AND role = 'wali_kelas'");
$delete_users->bind_param("i", $id_wali);
$delete_users->execute();

$delete_wali = $conn->prepare("DELETE FROM wali_kelas WHERE id_wali = ?");
$delete_wali->bind_param("i", $id_wali);

if ($delete_wali->execute()) {
  header("Location: ../../dashboard_admin.php");
  exit;
} else {
  echo "Gagal menghapus data wali kelas.";
}
?>
