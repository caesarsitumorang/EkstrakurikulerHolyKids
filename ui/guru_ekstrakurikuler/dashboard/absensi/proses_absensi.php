<?php
session_start();
include '../../../../database/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_ekstra      = $_POST['id_ekstra'];
    $tanggal        = $_POST['tanggal'];
    $status_list    = $_POST['status'];
    $keterangan_list = $_POST['keterangan'];

    // Cek apakah absensi untuk tanggal & ekstra ini sudah ada
    $cek_ekstra = $conn->prepare("SELECT 1 FROM absensi_ekstrakurikuler WHERE id_ekstra = ? AND tanggal = ?");
    $cek_ekstra->bind_param("is", $id_ekstra, $tanggal);
    $cek_ekstra->execute();
    $cek_result = $cek_ekstra->get_result();

    if ($cek_result->num_rows > 0) {
        echo "<script>alert('Absensi untuk tanggal tersebut sudah dilakukan.'); window.location.href = '../dashboard_guru.php';</script>";
        exit;
    }

    foreach ($status_list as $id_siswa => $status) {
        $keterangan = $keterangan_list[$id_siswa] ?? '';

        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO absensi_ekstrakurikuler (id_siswa, id_ekstra, tanggal, status, keterangan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $id_siswa, $id_ekstra, $tanggal, $status, $keterangan);
        $stmt->execute();

        // Ambil nama & no WA ortu
        $wa_stmt = $conn->prepare("SELECT nama, no_hp_ortu FROM siswa WHERE id_siswa = ?");
        $wa_stmt->bind_param("i", $id_siswa);
        $wa_stmt->execute();
        $wa_result = $wa_stmt->get_result();

        if ($wa_row = $wa_result->fetch_assoc()) {
            $nama_siswa = $wa_row['nama'];
            $no_wa      = $wa_row['no_hp_ortu']; 
            if (!empty($no_wa)) {
                $message = "Yth. Orang Tua/Wali,\n\n".
                           "Kami informasikan bahwa siswa atas nama $nama_siswa ".
                           "pada tanggal $tanggal memiliki status kehadiran: $status.\n\n".
                           "Keterangan: $keterangan\n\nTerima kasih.";

                $ch = curl_init("http://localhost:8000/send-message");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                    "number"  => $no_wa,
                    "message" => $message
                ]));
                $response = curl_exec($ch);
                curl_close($ch);
            }
        }
    }

    echo "<script>alert('Absensi berhasil disimpan dan notifikasi WA terkirim'); window.location.href = '../dashboard_guru.php';</script>";
} else {
    header("Location: absensi_index.php");
    exit;
}
