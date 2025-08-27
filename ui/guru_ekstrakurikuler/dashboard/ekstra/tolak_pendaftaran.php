<?php
include '../../../../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pendaftaran = $_POST['id_pendaftaran'];

    $update = $conn->prepare("UPDATE pendaftaran_ekstrakurikuler SET status = 'ditolak' WHERE id_pendaftaran = ?");
    $update->bind_param("i", $id_pendaftaran);
    $success = $update->execute();

    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false]);
}
