<?php
session_start();
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../../../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_siswa = $_SESSION['id_ref'];
    $id_ekstra = $_POST['id_ekstra'] ?? null;

    if (!$id_ekstra) {
        echo "<script>alert('ID ekstrakurikuler tidak valid.'); history.back();</script>";
        exit;
    }

    // Simpan pendaftaran
    $stmt = $conn->prepare("INSERT INTO pendaftaran_ekstrakurikuler (id_siswa, id_ekstra, status, tanggal_daftar) VALUES (?, ?, 'pending', NOW())");
    $stmt->bind_param("ii", $id_siswa, $id_ekstra);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Pendaftaran berhasil disimpan. Menunggu persetujuan.'); window.location.href = '../dashboard_siswa.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal menyimpan pendaftaran.'); history.back();</script>";
    }
} else {
    // Jika diakses tanpa POST
    header("Location: ../dashboard_siswa.php");
    exit;
}
