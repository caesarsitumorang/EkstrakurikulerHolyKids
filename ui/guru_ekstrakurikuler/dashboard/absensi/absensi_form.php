<?php
session_start();
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../../../../login.php");
    exit;
}

$id_guru = $_SESSION['id_ref'];
$id_ekstra = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Cek apakah guru memang membina ekstra ini
$cek = $conn->prepare("SELECT * FROM guru_ekstrakurikuler_map WHERE id_guru = ? AND id_ekstra = ?");
$cek->bind_param("ii", $id_guru, $id_ekstra);
$cek->execute();
$cek_result = $cek->get_result();
if ($cek_result->num_rows === 0) {
    echo "<p>Anda tidak memiliki akses ke ekstrakurikuler ini.</p>";
    exit;
}

$nama_ekstra = "";
$nama_result = mysqli_query($conn, "SELECT nama_ekstra FROM ekstrakurikuler WHERE id_ekstra = $id_ekstra");
if ($row = mysqli_fetch_assoc($nama_result)) {
    $nama_ekstra = $row['nama_ekstra'];
}

$siswa = mysqli_query($conn, "
    SELECT s.id_siswa, s.nama 
    FROM siswa_ekstrakurikuler se 
    JOIN siswa s ON se.id_siswa = s.id_siswa 
    WHERE se.id_ekstra = $id_ekstra
");
?>

<h2 class="judul-absensi">Absensi Ekstrakurikuler: <?= htmlspecialchars($nama_ekstra) ?></h2>

<form action="proses_absensi.php" method="post" class="form-absensi">
  <input type="hidden" name="id_ekstra" value="<?= $id_ekstra ?>">

<div class="tanggal-wrapper">
  <label for="tanggal">Tanggal:</label>
  <input type="date" name="tanggal" required value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>">
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
          <td>
            <select name="status[<?= $row['id_siswa'] ?>]" required>
              <option value="Hadir">Hadir</option>
              <option value="Izin">Izin</option>
              <option value="Sakit">Sakit</option>
              <option value="Alpa">Alpa</option>
            </select>
          </td>
          <td>
            <input type="text" name="keterangan[<?= $row['id_siswa'] ?>]" placeholder="Opsional">
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <div class="btn-wrapper">
    <button type="submit" class="btn-simpan">✅ Simpan Absensi</button>
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

input[type="date"] {
  padding: 6px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.tabel-absensi {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
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

.tabel-absensi select,
.tabel-absensi input[type="text"] {
  padding: 6px;
  width: 100%;
  border-radius: 4px;
  border: 1px solid #ccc;
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
