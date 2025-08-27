<?php
session_start();
require_once __DIR__ . '/../../../../vendor/autoload.php'; 
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'wali_kelas') {
    header("Location: ../../../../login.php");
    exit;
}

$id_wali = $_SESSION['id_ref'];

// Ambil id_kelas wali
$get_kelas = mysqli_query($conn, "SELECT id_kelas FROM wali_kelas WHERE id_wali = '$id_wali' LIMIT 1");
$data_kelas = mysqli_fetch_assoc($get_kelas);
if (!$data_kelas) { exit("Kelas tidak ditemukan untuk wali ini."); }

$id_kelas_wali = $data_kelas['id_kelas'];

// Ambil data siswa + nilai akhir
$query = "
  SELECT 
    s.nama, s.nis, s.jenis_kelamin, s.tanggal_lahir, s.alamat,
    k.nama_kelas,
    GROUP_CONCAT(DISTINCT e.nama_ekstra SEPARATOR ', ') AS ekstrakurikuler,
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

// Nama kelas
$get_nama_kelas = mysqli_query($conn, "SELECT nama_kelas FROM kelas WHERE id_kelas = '$id_kelas_wali'");
$nama_kelas = mysqli_fetch_assoc($get_nama_kelas)['nama_kelas'] ?? 'Tidak Diketahui';

// Buat PDF
$mpdf = new \Mpdf\Mpdf(['margin_top' => 35, 'margin_bottom' => 35]);

// Header
$header = '
<div style="border-bottom: 2px solid #000; padding-bottom:8px;">
    <table width="100%" style="border:none;">
        <tr>
            <td style="width:60px; text-align:left; vertical-align:middle; border:none;">
                <img src="http://localhost/ekstrakurikuler/images/image.png" style="width:60px;">
            </td>
            <td style="text-align:center; border:none;">
                <h2 style="margin:0; font-size:16px;">SMA HOLY KIDS BERSINAR MEDAN</h2>
                <p style="margin:0; font-size:12px;">Jl. Bunga Sedap Malam XII No. 15, Medan</p>
            </td>
        </tr>
    </table>
</div>';
$mpdf->SetHTMLHeader($header);

// Judul
$html = '<h3 style="text-align:center; margin-top:10px; margin-bottom:15px;">
Data Siswa Kelas '.$nama_kelas.' & Ekstrakurikuler
</h3>';

// Tabel
$html .= '
<style>
table { border-collapse: collapse; width: 100%; font-size: 12px; }
th { background-color: #082465; color: white; text-align: center; padding: 6px; }
td { padding: 6px; border: 1px solid #ddd; }
tr:nth-child(even) { background-color: #f9f9f9; }
</style>
';

$html .= '<table border="1">
<thead>
<tr>
  <th>No</th>
  <th>Nama</th>
  <th>NIS</th>
  <th>Kelas</th>
  <th>Ekstrakurikuler</th>
  <th>Jenis Kelamin</th>
  <th>Tanggal Lahir</th>
  <th>Alamat</th>
  <th>Nilai Akhir</th>
</tr>
</thead>
<tbody>';

$no = 1;
while($row = mysqli_fetch_assoc($result)){
    $html .= '<tr>
        <td style="text-align:center;">'.$no++.'</td>
        <td>'.htmlspecialchars($row['nama']).'</td>
        <td>'.htmlspecialchars($row['nis']).'</td>
        <td>'.htmlspecialchars($row['nama_kelas']).'</td>
        <td>'.($row['ekstrakurikuler'] ? htmlspecialchars($row['ekstrakurikuler']) : 'belum terdaftar').'</td>
        <td>'.htmlspecialchars($row['jenis_kelamin']).'</td>
        <td>'.htmlspecialchars($row['tanggal_lahir']).'</td>
        <td>'.htmlspecialchars($row['alamat']).'</td>
        <td>'.($row['nilai_akhir'] !== null ? $row['nilai_akhir'] : 'Belum diisi').'</td>
    </tr>';
}

$html .= '</tbody></table>';

// Tanda tangan
$html .= '
<br><br><br>
<div style="text-align:right; margin-bottom:40px;">
  <p style="margin:0;">Medan, '.date("d-m-Y").'</p>
</div>

<table style="width:100%; border:none; text-align:center; margin-top:20px;">
  <tr>
    <td style="width:50%; border:none;">
      <p style="margin:0;">Kepala Sekolah</p>
      <br><br><br><br><br>
      <p style="margin:0; font-weight:bold; text-decoration:underline;">Berliana Sihotang, S.Pd, M.Pd</p>
    </td>
    <td style="width:50%; border:none;">
      <p style="margin:0;">Wali Kelas '.$nama_kelas.'</p>
      <br><br><br><br><br>
      <p style="margin:0; font-weight:bold; text-decoration:underline;">'.htmlspecialchars($_SESSION['username']).'</p>
    </td>
  </tr>
</table>
';

$mpdf->WriteHTML($html);
$mpdf->Output("data_siswa_kelas_".$nama_kelas.".pdf","I");
?>
