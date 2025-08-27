<?php
session_start();
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../../../../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_ekstra = $_POST['id_ekstra'];
    $nilai_user = $_POST['nilai_user']; // array
    $jumlah_hadir = $_POST['jumlah_hadir']; // array

    foreach ($nilai_user as $id_siswa => $nilai) {
        $hadir = isset($jumlah_hadir[$id_siswa]) ? (int)$jumlah_hadir[$id_siswa] : 0;

        // Bobot: Kehadiran 70%, Nilai Guru 30%
        $bobot_kehadiran = 0.7;
        $bobot_nilai = 0.3;

        $skor_kehadiran = ($hadir / 24) * 100 * $bobot_kehadiran;
        $skor_nilai = $nilai * $bobot_nilai;
        $nilai_akhir = min(100, $skor_kehadiran + $skor_nilai);

        // Cek apakah data sudah ada
        $cek = $conn->prepare("SELECT id_nilai FROM nilai_siswa WHERE id_siswa = ? AND id_ekstra = ?");
        $id_siswa = (int)$id_siswa;
        $cek->bind_param("ii", $id_siswa, $id_ekstra);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            // Update
            $update = $conn->prepare("UPDATE nilai_siswa SET nilai_user = ?, jumlah_hadir = ?, nilai_akhir = ?, tanggal_input = NOW() WHERE id_siswa = ? AND id_ekstra = ?");
            $update->bind_param("iddii", $nilai, $hadir, $nilai_akhir, $id_siswa, $id_ekstra);
            $update->execute();
        } else {
            // Insert
            $insert = $conn->prepare("INSERT INTO nilai_siswa (id_siswa, id_ekstra, nilai_user, jumlah_hadir, nilai_akhir) VALUES (?, ?, ?, ?, ?)");
            $insert->bind_param("iiiid", $id_siswa, $id_ekstra, $nilai, $hadir, $nilai_akhir);
            $insert->execute();
        }
    }

     echo "<script>alert('Nilai berhasil disimpan'); window.location.href = '../dashboard_guru.php';</script>";
} else {
    header("Location: nilai_ekstra.php");
    exit;
}
