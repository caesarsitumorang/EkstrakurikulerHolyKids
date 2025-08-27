<?php
include '../../../../database/config.php';

$id = $_GET['id'] ?? 0;

$hapus = $conn->prepare("DELETE FROM guru_ekstrakurikuler_map WHERE id = ?");
$hapus->bind_param("i", $id);

if ($hapus->execute()) {
    echo "<script>alert('🗑️ Data berhasil dihapus.'); window.location.href='../dashboard_admin.php';</script>";
} else {
    echo "<script>alert('❗ Gagal menghapus data.'); window.location.href='../dashboard_admin.php';</script>";
}
?>
