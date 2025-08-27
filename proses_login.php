<?php
session_start();
include 'database/config.php'; // koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek username di tabel users
    $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($password === $user['password']) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['id_ref'] = $user['id_ref'];

            if ($user['role'] === 'siswa') {
                header("Location: ui/siswa/dashboard/dashboard_siswa.php");
            } elseif ($user['role'] === 'guru') {
                header("Location: ui/guru_ekstrakurikuler/dashboard/dashboard_guru.php");
            } elseif ($user['role'] === 'admin') {
                header("Location: ui/admin/dashboard/dashboard_admin.php");
            } elseif ($user['role'] === 'wali_kelas') {
                header("Location: ui/wali_kelas/dashboard/dashboard_wali_kelas.php");
            }else {
                echo "Peran tidak dikenali.";
            }
            exit;
        } else {
            echo "<script>alert('Password salah!'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!'); window.location='login.php';</script>";
    }

    $query->close();
    $conn->close();
} else {
    header("Location: login.php");
    exit;
}
