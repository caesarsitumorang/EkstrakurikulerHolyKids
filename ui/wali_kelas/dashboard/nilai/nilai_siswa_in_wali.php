<?php
session_start();
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'wali_kelas') {
    header("Location: ../../../../login.php");
    exit;
}

$id_wali = $_SESSION['id_ref'];

// Ambil id_kelas dari wali_kelas
$get_kelas = mysqli_query($conn, "SELECT id_kelas FROM wali_kelas WHERE id_wali = '$id_wali' LIMIT 1");
$data_kelas = mysqli_fetch_assoc($get_kelas);

if (!$data_kelas) {
  echo "Kelas tidak ditemukan untuk wali ini.";
  exit;
}

$id_kelas_wali = $data_kelas['id_kelas'];

// Ambil nama kelas
$get_nama_kelas = mysqli_query($conn, "SELECT nama_kelas FROM kelas WHERE id_kelas = '$id_kelas_wali'");
$nama_kelas = mysqli_fetch_assoc($get_nama_kelas)['nama_kelas'] ?? 'Tidak Diketahui';

// Ambil data siswa + ekstrakurikuler + nilai
$query = "
SELECT 
  s.id_siswa, s.nama, s.nis, s.jenis_kelamin, s.tanggal_lahir, s.alamat,
  k.nama_kelas,
  GROUP_CONCAT(DISTINCT e.nama_ekstra SEPARATOR ', ') AS ekstrakurikuler,
  MAX(n.nilai_user) AS nilai_user,
  MAX(n.jumlah_hadir) AS jumlah_hadir,
  MAX(n.nilai_akhir) AS nilai_akhir
FROM siswa s
JOIN kelas k ON s.id_kelas = k.id_kelas
LEFT JOIN siswa_ekstrakurikuler es ON s.id_siswa = es.id_siswa
LEFT JOIN ekstrakurikuler e ON es.id_ekstra = e.id_ekstra
LEFT JOIN nilai_siswa n ON n.id_siswa = s.id_siswa
WHERE s.id_kelas = '$id_kelas_wali'
GROUP BY s.id_siswa
ORDER BY s.nama ASC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Nilai Siswa</title>
  <style>

    .container {
      max-width: 1100px;
      margin: 50px auto;
      background: #ffffff;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #1a1a1a;
      font-size: 24px;
      border-bottom: 2px solid #e0e0e0;
      padding-bottom: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 14px 12px;
      border-bottom: 1px solid #e9ecef;
      text-align: center;
      font-size: 14px;
    }

    th {
      background-color: #f0f2f5;
      font-weight: 600;
      color: #2c3e50;
    }

    tr:nth-child(even) {
      background-color: #fafafa;
    }

    tr:hover {
      background-color: #f5f9ff;
    }

    .no-data {
      text-align: center;
      padding: 25px;
      font-style: italic;
      color: #888;
    }

    @media (max-width: 768px) {
      th, td {
        padding: 10px;
        font-size: 12px;
      }

      .container {
        padding: 20px 15px;
      }

      h2 {
        font-size: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div style="margin-bottom:20px; text-align:right;">
    <!-- <a href="nilai/cetak_data_nilai.php" target="_blank" 
       style="background:#082465; color:white; padding:10px 16px; border-radius:6px; text-decoration:none; font-size:14px;">
      🖨 Cetak PDF
    </a> -->
  </div>
    <h2>📊 Data Nilai Siswa Kelas <?= htmlspecialchars($nama_kelas) ?></h2>
    
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>NIS</th>
          <th>Jenis Kelamin</th>
          <th>Kelas</th>
          <th>Ekstrakurikuler</th>
          <th>Jumlah Hadir</th>
          <th>Nilai Akhir</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['nama']) ?></td>
              <td><?= htmlspecialchars($row['nis']) ?></td>
              <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
              <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
              <td><?= $row['ekstrakurikuler'] ? htmlspecialchars($row['ekstrakurikuler']) : '<em>Belum terdaftar</em>' ?></td>
              <td><?= $row['jumlah_hadir'] !== null ? $row['jumlah_hadir'] : '<em>Belum diisi</em>' ?></td>
             <td>
    <?= $row['nilai_akhir'] !== null 
        ? number_format(min($row['nilai_akhir'], 100), 1, '.', '') 
        : '<em>Belum diisi</em>' ?>
</td>

            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="8" class="no-data">Tidak ada data siswa di kelas Anda.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

