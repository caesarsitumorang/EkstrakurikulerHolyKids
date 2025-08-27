<?php
session_start();
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../../../../login.php");
    exit;
}

$id_guru   = $_SESSION['id_ref'];
$id_ekstra = isset($_GET['id']) ? intval($_GET['id']) : 0;

// cek apakah guru memang membina ekstra ini
$cek = $conn->prepare("SELECT 1 FROM guru_ekstrakurikuler_map WHERE id_guru = ? AND id_ekstra = ?");
$cek->bind_param("ii", $id_guru, $id_ekstra);
$cek->execute();
$cek_result = $cek->get_result();
if ($cek_result->num_rows === 0) {
    echo "<p>Anda tidak memiliki akses ke ekstrakurikuler ini.</p>";
    exit;
}

// ambil nama ekstrakurikuler
$nama_ekstra = "";
$res = mysqli_query($conn, "SELECT nama_ekstra FROM ekstrakurikuler WHERE id_ekstra = $id_ekstra");
if ($row = mysqli_fetch_assoc($res)) {
    $nama_ekstra = $row['nama_ekstra'];
}

// ambil daftar siswa
$siswa = mysqli_query($conn, "
    SELECT s.id_siswa, s.nama, s.email_ortu
    FROM siswa_ekstrakurikuler se
    JOIN siswa s ON se.id_siswa = s.id_siswa
    WHERE se.id_ekstra = $id_ekstra
");
?>

<h2 class="judul-absensi">Informasi Kegiatan Ekstrakurikuler: <?= htmlspecialchars($nama_ekstra) ?></h2>

<form action="" method="post" class="form-absensi">
  <input type="hidden" name="id_ekstra" value="<?= $id_ekstra ?>">

  <div class="tanggal-wrapper">
    <label for="tanggal">Tanggal:</label>
    <input type="date" id="tanggal" name="tanggal" value="<?= date('Y-m-d') ?>" required>
  </div>

  <table class="tabel-absensi">
    <thead>
      <tr>
        <th>Nama Siswa</th>
        <th>Status</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($siswa)): ?>
        <tr>
          <td><?= htmlspecialchars($row['nama']) ?></td>
          <td>Ditiadakan</td>
          <td>Kegiatan <?= htmlspecialchars($nama_ekstra) ?> hari ini ditiadakan.</td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <div class="btn-wrapper">
    <button type="submit" name="informasi" class="btn-simpan">📧 Informasikan ke Orang Tua</button>
    <a href="../dashboard_guru.php" class="btn-kembali">⬅️ Kembali</a>
  </div>
</form>

<style>
.judul-absensi {
  color: #082465;
  margin-bottom: 20px;
}

.form-absensi {
  background: #f5f7ff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 8px rgba(0,0,0,0.1);
}

.tanggal-wrapper {
  margin-bottom: 20px;
  font-weight: bold;
}

.tabel-absensi {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

.tabel-absensi th, .tabel-absensi td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: center;
}

.tabel-absensi th {
  background-color: #082465;
  color: white;
}

.btn-wrapper {
  display: flex;
  gap: 10px;
  margin-top: 20px;
}

.btn-simpan, .btn-kembali {
  padding: 10px 20px;
  font-size: 15px;
  text-decoration: none;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.btn-simpan {
  background-color: #082465;
  color: white;
}

.btn-kembali {
  background-color: #ccc;
  color: black;
}

.btn-simpan:hover {
  background-color: #0a2d85;
}

.btn-kembali:hover {
  background-color: #b0b0b0;
}
</style>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../../vendor/autoload.php';

if (isset($_POST['informasi'])) {
    $id_ekstra = $_POST['id_ekstra'];
    $tanggal   = $_POST['tanggal'];

    // ambil nama ekstra
    $nama_ex = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_ekstra FROM ekstrakurikuler WHERE id_ekstra=$id_ekstra"))['nama_ekstra'];

    // ambil siswa
    $siswa = mysqli_query($conn, "
        SELECT s.id_siswa, s.nama, s.email_ortu
        FROM siswa_ekstrakurikuler se
        JOIN siswa s ON se.id_siswa = s.id_siswa
        WHERE se.id_ekstra = $id_ekstra
    ");

    while ($row = mysqli_fetch_assoc($siswa)) {
        $nama_siswa = $row['nama'];
        $user_email = $row['email_ortu'];

        if (empty($user_email)) continue;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'makrabimkaunika22@gmail.com'; // ganti
            $mail->Password   = 'tgbuxihctlipwmvs';            // ganti
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('makrabimkaunika22@gmail.com', 'Sekolah Ekstrakurikuler');
            $mail->addAddress($user_email, "Orang Tua $nama_siswa");

            $mail->isHTML(true);
            $mail->Subject = "Informasi Kegiatan $nama_ex - $tanggal";
            $mail->Body    = "
                <p>Yth. Orang Tua/Wali,</p>
                <p>Kami informasikan bahwa kegiatan <strong>$nama_ex</strong> pada tanggal <strong>$tanggal</strong> <span style='color:red'><b>DITIADAKAN</b></span>.</p>
                <p>Siswa: <strong>$nama_siswa</strong></p>
                <p>Terima kasih atas perhatian Bapak/Ibu.</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            echo "<script>alert('Gagal kirim ke $user_email: {$mail->ErrorInfo}');</script>";
        }
    }

    echo "<script>alert('Informasi kegiatan $nama_ex pada tanggal $tanggal sudah dikirim ke orang tua siswa'); window.location.href = '../dashboard_guru.php';</script>";
}
?>
