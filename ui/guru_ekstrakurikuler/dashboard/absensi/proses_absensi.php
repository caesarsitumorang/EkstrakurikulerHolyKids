<?php
session_start();
include '../../../../database/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_ekstra       = $_POST['id_ekstra'];
    $tanggal         = $_POST['tanggal'];
    $status_list     = $_POST['status'];
    $keterangan_list = $_POST['keterangan'];

    // Ambil nama ekstrakurikuler
    $ekstra_stmt = $conn->prepare("SELECT nama_ekstra FROM ekstrakurikuler WHERE id_ekstra = ?");
    $ekstra_stmt->bind_param("i", $id_ekstra);
    $ekstra_stmt->execute();
    $ekstra_result = $ekstra_stmt->get_result();
    $ekstra_row    = $ekstra_result->fetch_assoc();
    $nama_ekstra   = $ekstra_row['nama_ekstra'] ?? 'Ekstrakurikuler';

    // Cek apakah absensi untuk tanggal & ekstra ini sudah ada
    $cek_ekstra = $conn->prepare("SELECT 1 FROM absensi_ekstrakurikuler WHERE id_ekstra = ? AND tanggal = ?");
    $cek_ekstra->bind_param("is", $id_ekstra, $tanggal);
    $cek_ekstra->execute();
    $cek_result = $cek_ekstra->get_result();

    if ($cek_result->num_rows > 0) {
        echo "<script>alert('Absensi untuk $nama_ekstra pada tanggal tersebut sudah dilakukan.'); window.location.href = '../dashboard_guru.php';</script>";
        exit;
    }

    foreach ($status_list as $id_siswa => $status) {
        $keterangan = $keterangan_list[$id_siswa] ?? '';

        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO absensi_ekstrakurikuler (id_siswa, id_ekstra, tanggal, status, keterangan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $id_siswa, $id_ekstra, $tanggal, $status, $keterangan);
        $stmt->execute();

        // Ambil nama & email ortu
        $email_stmt = $conn->prepare("SELECT nama, email_ortu FROM siswa WHERE id_siswa = ?");
        $email_stmt->bind_param("i", $id_siswa);
        $email_stmt->execute();
        $email_result = $email_stmt->get_result();

        if ($email_row = $email_result->fetch_assoc()) {
            $nama_siswa = $email_row['nama'];
            $user_email = $email_row['email_ortu'];

            if (empty($user_email)) {
                echo "<script>alert('Email orang tua $nama_siswa tidak ditemukan!');</script>";
                continue;
            }

            // Kirim email pakai PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'makrabimkaunika22@gmail.com'; // ganti
                $mail->Password   = 'tgbuxihctlipwmvs';      // ganti
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom('makrabimkaunika22@gmail.com', 'Sekolah Ekstrakurikuler');
                $mail->addAddress($user_email, "Orang Tua $nama_siswa");

                $mail->isHTML(true);
                $mail->Subject = "Absensi $nama_ekstra - $tanggal";
                $mail->Body    = "
                    <p>Yth. Orang Tua/Wali,</p>
                    <p>Kami informasikan bahwa siswa atas nama <strong>$nama_siswa</strong> telah mengikuti kegiatan <strong>$nama_ekstra</strong> pada tanggal <strong>$tanggal</strong> dengan status kehadiran: <strong>$status</strong>.</p>
                    <p><strong>Keterangan:</strong> $keterangan</p>
                    <p>Terima kasih.</p>
                ";

                $mail->send();
            } catch (Exception $e) {
                echo "<script>alert('Gagal mengirim email ke $user_email: {$mail->ErrorInfo}');</script>";
            }
        }
    }

    echo "<script>alert('Absensi $nama_ekstra berhasil disimpan dan email notifikasi terkirim'); window.location.href = '../dashboard_guru.php';</script>";
} else {
    header("Location: absensi_index.php");
    exit;
}
