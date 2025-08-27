<?php
include '../../../../database/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus ekstrakurikuler berdasarkan ID
    $stmt = $conn->prepare("DELETE FROM ekstrakurikuler WHERE id_ekstra = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo "<script>alert('✅ Ekstrakurikuler berhasil dihapus.'); window.location.href='../dashboard_admin.php';</script>";
    exit;
} else {
    header("Location: ../ekstra_in_admin.php");
    exit;
}
