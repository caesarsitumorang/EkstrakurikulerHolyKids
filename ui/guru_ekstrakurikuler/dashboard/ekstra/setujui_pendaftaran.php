<?php
include '../../../../database/config.php';

$id_siswa = $_POST['id_siswa'];
$id_ekstra = $_POST['id_ekstra'];
$id_pendaftaran = $_POST['id_pendaftaran'];

// Masukkan ke siswa_ekstrakurikuler
$insert = $conn->prepare("INSERT INTO siswa_ekstrakurikuler (id_siswa, id_ekstra) VALUES (?, ?)");
$insert->bind_param("ii", $id_siswa, $id_ekstra);
$success = $insert->execute();

if ($success) {
    // Update status pendaftaran
    $update = $conn->prepare("UPDATE pendaftaran_ekstrakurikuler SET status = 'diterima' WHERE id_pendaftaran = ?");
    $update->bind_param("i", $id_pendaftaran);
    $update->execute();
}

echo json_encode(['success' => $success]);
