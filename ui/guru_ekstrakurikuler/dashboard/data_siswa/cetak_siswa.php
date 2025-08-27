<?php
require_once __DIR__ . '/../../../../vendor/autoload.php'; 
include '../../../../database/config.php';

$id_ekstra = $_GET['id_ekstra'] ?? 0;
if (!$id_ekstra) { exit('Ekstrakurikuler tidak ditemukan.'); }

// 🔹 Ambil data siswa & kehadiran
$query = "
    SELECT s.nama, s.nis, k.nama_kelas,
           COUNT(a.status) AS jumlah_hadir
    FROM siswa_ekstrakurikuler se
    JOIN siswa s ON se.id_siswa = s.id_siswa
    JOIN kelas k ON s.id_kelas = k.id_kelas
    LEFT JOIN absensi_ekstrakurikuler a 
        ON a.id_siswa = s.id_siswa 
        AND a.id_ekstra = se.id_ekstra 
        AND a.status = 'hadir'
    WHERE se.id_ekstra = ?
    GROUP BY s.id_siswa
    ORDER BY s.nama ASC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_ekstra);
$stmt->execute();
$result = $stmt->get_result();

// 🔹 Buat PDF
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


// 🔹 Judul
$html = '<h3 style="text-align:center; margin-top:10px; margin-bottom:15px;">
Data Kehadiran Siswa Ekstrakurikuler
</h3>';

// 🔹 Style Tabel
$html .= '
<style>
table {
  border-collapse: collapse;
  width: 100%;
  font-size: 12px;
}
th {
  background-color: #082465;
  color: white;
  text-align: center;
  padding: 8px;
}
td {
  padding: 8px;
  border: 1px solid #ddd;
}
tr:nth-child(even) {
  background-color: #f9f9f9;
}
</style>
';

$html .= '<table border="1">
<thead>
<tr>
    <th>No</th>
    <th>NIS</th>
    <th>Nama</th>
    <th>Kelas</th>
    <th>Jumlah Hadir</th>
</tr>
</thead>
<tbody>';

$no = 1;
while($row = $result->fetch_assoc()){
    $html .= '<tr>
        <td style="text-align:center;">'.$no++.'</td>
        <td>'.$row['nis'].'</td>
        <td>'.$row['nama'].'</td>
        <td>'.$row['nama_kelas'].'</td>
        <td style="text-align:center;">'.$row['jumlah_hadir'].' dari 24</td>
    </tr>';
}

$html .= '</tbody></table>';

// 🔹 Bagian Tanda Tangan di kanan bawah
$html .= '
<br><br><br>
<div style="width:100%; text-align:right; margin-top:50px;">
  <p style="margin:0;">Medan, ' . date("d-m-Y") . '</p>
  <p style="margin:0;">Kepala Sekolah</p>
  <br><br><br><br><br>
  <p style="margin:0; font-weight:bold; text-decoration:underline;">Berliana Sihotang, S.Pd, M.Pd</p>
</div>
';

// 🔹 Cetak PDF
$mpdf->WriteHTML($html);
$mpdf->Output("kehadiran_siswa.pdf","I");
?>
