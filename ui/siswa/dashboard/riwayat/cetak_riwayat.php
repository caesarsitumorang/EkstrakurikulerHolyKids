<?php
require_once __DIR__ . '/../../../../vendor/autoload.php'; 
include '../../../../database/config.php';

session_start();
$id_siswa = $_SESSION['id_ref'];
$tanggal_sekarang = date('Y-m-d');

// --- Ambil data kelas siswa ---
$qKelas = $conn->prepare("
    SELECT s.id_kelas, k.nama_kelas 
    FROM siswa s
    JOIN kelas k ON s.id_kelas = k.id_kelas
    WHERE s.id_siswa = ?
");
$qKelas->bind_param("i", $id_siswa);
$qKelas->execute();
$rKelas = $qKelas->get_result();
$dataKelas = $rKelas->fetch_assoc();

$id_kelas   = $dataKelas['id_kelas'] ?? null;
$nama_kelas = $dataKelas['nama_kelas'] ?? "-";

// --- Ambil nama wali kelas berdasarkan kelas siswa ---
$nama_wali_kelas = "-";
$nohp_wali_kelas = "-";
if ($id_kelas) {
    $qWali = $conn->prepare("SELECT nama_lengkap, no_hp FROM wali_kelas WHERE id_kelas = ?");
    $qWali->bind_param("i", $id_kelas);
    $qWali->execute();
    $rWali = $qWali->get_result();
    if ($rowWali = $rWali->fetch_assoc()) {
        $nama_wali_kelas = $rowWali['nama_lengkap'];
        $nohp_wali_kelas = $rowWali['no_hp'];
    }
}


// 🔹 Ambil semester terakhir
$semResult = $conn->query("SELECT id, semester, tanggal_mulai, tanggal_selesai FROM semester ORDER BY id DESC LIMIT 1");
$semesterData = $semResult->fetch_assoc();

$periodeMulai   = $semesterData['tanggal_mulai'];
$periodeSelesai = $semesterData['tanggal_selesai'];
$namaSemester   = $semesterData['semester'];

// 🔹 Buat object mPDF
$mpdf = new \Mpdf\Mpdf([
    'margin_top' => 35,
    'margin_bottom' => 35
]);

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


$html = '<h3 style="text-align:center; margin-top:10px; margin-bottom:5px;">Data Riwayat Ekstrakurikuler</h3>';
$html .= '<p style="text-align:center; margin:0; font-size:12px;">Semester: <b>' . htmlspecialchars($namaSemester) . '</b> (' . $periodeMulai . ' s/d ' . $periodeSelesai . ')</p><br>';

$query = "
  SELECT 
      e.id_ekstra, e.nama_ekstra, e.hari, e.jam, e.lokasi, p.tanggal_daftar,
      g.nama_lengkap AS nama_guru, g.no_hp,
      (
          SELECT COUNT(*) 
          FROM absensi_ekstrakurikuler a 
          WHERE a.id_siswa = ? 
            AND a.id_ekstra = p.id_ekstra 
            AND a.status = 'hadir'
      ) AS jumlah_hadir,
      ns.nilai_akhir
  FROM pendaftaran_ekstrakurikuler p
  JOIN ekstrakurikuler e ON p.id_ekstra = e.id_ekstra
  JOIN guru_ekstrakurikuler_map gm ON e.id_ekstra = gm.id_ekstra
  JOIN guru g ON gm.id_guru = g.id_guru
  LEFT JOIN nilai_siswa ns 
      ON ns.id_siswa = p.id_siswa 
      AND ns.id_ekstra = p.id_ekstra
  WHERE p.id_siswa = ? 
    AND p.status = 'diterima'
  ORDER BY e.nama_ekstra ASC
";

 $stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id_siswa, $id_siswa);
    $stmt->execute();
    $result = $stmt->get_result();

// 🔹 Tabel Data
$html .= '
<style>
table {
  border-collapse: collapse;
  width: 100%;
  font-size: 12px;
}
table th {
  background-color: #667eea;
  color: white;
  text-align: center;
  padding: 8px;
}
table td {
  padding: 8px;
  vertical-align: middle;
}
table tr:nth-child(even) {
  background-color: #f9f9f9;
}
.signature-table {
  border: none;
  width: 100%;
  margin-top: 40px;
}
.signature-table td {
  border: none;
  text-align: center;
  vertical-align: top;
  padding: 20px;
  width: 50%;
}
</style>
<table border="1">
<thead>
<tr>
    <th>No</th>
    <th>Nama Ekstrakurikuler</th>
    <th>Hari</th>
    <th>Jam</th>
    <th>Lokasi</th>
    <th>Guru Pembina</th>
    <th>No HP Guru</th>
    <th>Jumlah Hadir</th>
    <th>Nilai Akhir</th>
</tr>
</thead>
<tbody>
';

$no = 1;
$nama_pembina = '-'; 
while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
        <td style="text-align:center;">' . $no++ . '</td>
        <td>' . htmlspecialchars($row['nama_ekstra']) . '</td>
        <td>' . htmlspecialchars($row['hari']) . '</td>
        <td>' . htmlspecialchars($row['jam']) . '</td>
        <td>' . htmlspecialchars($row['lokasi']) . '</td>
        <td>' . htmlspecialchars($row['nama_guru']) . '</td>
        <td>' . htmlspecialchars($row['no_hp']) . '</td>
        <td style="text-align:center;">' . ($row['jumlah_hadir'] ?: 0) . '</td>
       <td style="text-align:center;">' . 
    ($row['nilai_akhir'] !== null 
        ? rtrim(rtrim(number_format(min(100, $row['nilai_akhir']), 1), '0'), '.') 
        : '-') 
. '</td>

    </tr>';
    $nama_pembina = $row['nama_guru']; 
}
$html .= '</tbody></table>';

// 🔹 Bagian Tanda Tangan
$html .= '
<br><br><br>
<div style="text-align:right; margin-bottom:40px;">
  <p style="margin:0;">SMA HOLY KIDS BERSINAR MEDAN, ' . date("d-m-Y") . '</p>
</div>
<div style="text-align:center; margin-bottom:60px;">
  <p style="margin:0;">Kepala Sekolah</p>
  <br><br><br><br><br><br>
  <p style="margin:0; font-weight:bold; text-decoration:underline;">Berliana Sihotang, S.Pd, M.Pd</p>
</div>
<table class="signature-table">
<tr>
 <td>
    <p style="margin:0;">Wali Kelas</p>
    <br><br><br><br><br><br>
    <p style="margin:0; font-weight:bold; text-decoration:underline;">' . htmlspecialchars($nama_wali_kelas) . '</p>
  </td>
  <td>
    <p style="margin:0;">Guru Pembina</p>
    <br><br><br><br><br><br>
    <p style="margin:0; font-weight:bold; text-decoration:underline;">' . htmlspecialchars($nama_pembina) . '</p>
  </td>
</tr>
</table>
';

$mpdf->WriteHTML($html);
$mpdf->Output("riwayat_ekstrakurikuler.pdf", "I");
?>
