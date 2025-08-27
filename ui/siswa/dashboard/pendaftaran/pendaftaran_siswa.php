<?php
session_start();
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../../../login.php");
    exit;
}

$id_siswa = $_SESSION['id_ref'];

// --- Ambil semua ekstrakurikuler aktif siswa ---
$cekAktif = $conn->prepare("
    SELECT e.id_ekstra, e.nama_ekstra 
    FROM siswa_ekstrakurikuler se
    JOIN ekstrakurikuler e ON se.id_ekstra = e.id_ekstra
    WHERE se.id_siswa = ?
");
$cekAktif->bind_param("i", $id_siswa);
$cekAktif->execute();
$resAktif = $cekAktif->get_result();

$aktifIds = [];
while ($row = $resAktif->fetch_assoc()) {
    $aktifIds[] = $row['id_ekstra'];
}

// --- Ambil semua pendaftaran pending siswa ---
$cekPending = $conn->prepare("
    SELECT id_ekstra 
    FROM pendaftaran_ekstrakurikuler 
    WHERE id_siswa = ? AND status = 'pending'
");
$cekPending->bind_param("i", $id_siswa);
$cekPending->execute();
$resPending = $cekPending->get_result();

$pendingIds = [];
while ($row = $resPending->fetch_assoc()) {
    $pendingIds[] = $row['id_ekstra'];
}

// --- Hitung total pendaftaran aktif + pending ---
$totalTerdaftar = count($aktifIds) + count($pendingIds);

// --- Ambil semua ekstrakurikuler ---
$result = mysqli_query($conn, "SELECT * FROM ekstrakurikuler ORDER BY nama_ekstra ASC");
?>

<div class="container">
  <h2>📝 Daftar Ekstrakurikuler</h2>
  <p>Pilih salah satu ekstrakurikuler di bawah ini untuk mendaftar (maksimal 2 ekstrakurikuler):</p>

  <div class="ekstra-grid">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <?php
      $disabled = false;
      $msg = "";

      // Tidak boleh daftar jika sudah terdaftar di ekstra ini
      if (in_array($row['id_ekstra'], $aktifIds)) {
          $disabled = true;
          $msg = "✅ Kamu sudah terdaftar di ekstrakurikuler ini.";
      }

      // Tidak boleh daftar jika punya pending untuk ekstra ini
      if (in_array($row['id_ekstra'], $pendingIds)) {
          $disabled = true;
          $msg = "⏳ Pendaftaran untuk ekstrakurikuler ini sedang menunggu persetujuan.";
      }

      // Jika total ekstrakurikuler aktif + pending = 2, maka tidak bisa daftar tambahan
      if (!$disabled && $totalTerdaftar >= 2) {
          $disabled = true;
          $msg = "⚠️ Kamu sudah mendaftar maksimal 2 ekstrakurikuler.";
      }
      ?>
      <div class="ekstra-card">
        <h3><?= htmlspecialchars($row['nama_ekstra']) ?></h3>
        <p><strong>Hari:</strong> <?= htmlspecialchars($row['hari']) ?> | <strong>Jam:</strong> <?= htmlspecialchars($row['jam']) ?></p>
        <p><strong>Lokasi:</strong> <?= htmlspecialchars($row['lokasi']) ?></p>
        <p><?= htmlspecialchars($row['deskripsi']) ?></p>

        <?php if($disabled): ?>
          <div class="alert"><?= $msg ?></div>
        <?php else: ?>
          <form method="POST" action="pendaftaran/proses_pendaftaran.php" onsubmit="return confirm('Yakin ingin mendaftar ke ekstra ini?')">
            <input type="hidden" name="id_ekstra" value="<?= $row['id_ekstra'] ?>">
            <button type="submit">Daftar</button>
          </form>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<style>
.container {
  font-family: 'Segoe UI', sans-serif;
  padding: 30px;
}

h2 {
  color: #082465;
  margin-bottom: 10px;
}

.ekstra-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
  margin-top: 20px;
}

.ekstra-card {
  background: #fff;
  border-left: 5px solid #082465;
  padding: 16px 20px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.ekstra-card h3 {
  margin-top: 0;
  color: #082465;
}

button {
  margin-top: 10px;
  background-color: #082465;
  color: white;
  padding: 8px 16px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

button:hover {
  background-color: #0a2d85;
}

.alert {
  background-color: #fff3cd;
  border-left: 5px solid #ffc107;
  padding: 12px;
  border-radius: 6px;
  margin-top: 10px;
  color: #856404;
  font-size: 14px;
}
</style>
