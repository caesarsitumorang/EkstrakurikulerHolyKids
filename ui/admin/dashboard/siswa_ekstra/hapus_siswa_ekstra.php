<?php
include '../../../../database/config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Gunakan prepared statement untuk keamanan
    $hapus = $conn->prepare("DELETE FROM siswa_ekstrakurikuler WHERE id_siswa = ?");
    $hapus->bind_param("i", $id);

    if ($hapus->execute()) {
        echo "<script>alert('🗑️ Data berhasil dihapus.'); window.location.href='../dashboard_admin.php';</script>";
    } else {
        echo "<script>alert('❗ Gagal menghapus data.'); window.location.href='../dashboard_admin.php';</script>";
    }

    $hapus->close();
} else {
    echo "<script>alert('❗ ID tidak valid.'); window.location.href='siswa_ekstra.php';</script>";
}
?>
