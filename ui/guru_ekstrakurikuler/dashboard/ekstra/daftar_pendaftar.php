<?php
session_start();
include '../../../../database/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'guru') {
    exit("Akses ditolak");
}

$id_guru = $_SESSION['id_ref'];

$stmt = $conn->prepare("
    SELECT e.id_ekstra, e.nama_ekstra
    FROM guru_ekstrakurikuler_map g
    JOIN ekstrakurikuler e ON g.id_ekstra = e.id_ekstra
    WHERE g.id_guru = ?
");
$stmt->bind_param("i", $id_guru);
$stmt->execute();
$ekstra_result = $stmt->get_result();
?>

<h2 style="color: #082465;">Data Pendaftar Ekstrakurikuler</h2>

<?php while ($ekstra = $ekstra_result->fetch_assoc()):
    $id_ekstra = $ekstra['id_ekstra'];

   $stmt2 = $conn->prepare("
    SELECT p.id_pendaftaran, s.id_siswa, s.nama, s.nis, k.nama_kelas
    FROM pendaftaran_ekstrakurikuler p
    JOIN siswa s ON p.id_siswa = s.id_siswa
    JOIN kelas k ON s.id_kelas = k.id_kelas
    WHERE p.id_ekstra = ? AND p.status = 'pending'
");

    $stmt2->bind_param("i", $id_ekstra);
    $stmt2->execute();
    $pendaftar_result = $stmt2->get_result();
?>
<div class="ekstra-box">
  <h3><?= htmlspecialchars($ekstra['nama_ekstra']) ?></h3>
  <?php if ($pendaftar_result->num_rows > 0): ?>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Siswa</th>
        <th>NIS</th>
        <th>Kelas</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; while ($row = $pendaftar_result->fetch_assoc()): ?>
      <tr id="row-<?= $row['id_pendaftaran'] ?>">
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['nis']) ?></td>
        <td><?= htmlspecialchars($row['nama_kelas']) ?></td>

        <td>
          <form class="form-setujui" method="POST" style="display:inline;">
            <input type="hidden" name="id_siswa" value="<?= $row['id_siswa'] ?>">
            <input type="hidden" name="id_ekstra" value="<?= $id_ekstra ?>">
            <input type="hidden" name="id_pendaftaran" value="<?= $row['id_pendaftaran'] ?>">
            <button type="submit" class="btn-accept">✔ Terima</button>
          </form>
          <form class="form-tolak" method="POST" style="display:inline;">
            <input type="hidden" name="id_pendaftaran" value="<?= $row['id_pendaftaran'] ?>">
            <button type="submit" class="btn-reject">✖ Tolak</button>
          </form>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php else: ?>
    <em>Tidak ada pendaftar.</em>
  <?php endif; ?>
</div>
<?php endwhile; ?>

<div id="toast"></div>

<style>
.ekstra-box {
  margin-bottom: 30px;
  padding: 20px;
  background-color: #f9fbff;
  border: 1px solid #ddd;
  border-left: 6px solid #082465;
  border-radius: 8px;
}

.ekstra-box h3 {
  margin-top: 0;
  color: #082465;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

th, td {
  padding: 10px;
  border: 1px solid #ccc;
  text-align: center;
}

th {
  background-color: #082465;
  color: white;
}

button {
  padding: 6px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin: 2px;
}

.btn-accept {
  background-color: #28a745;
  color: white;
}

.btn-reject {
  background-color: #dc3545;
  color: white;
}

#toast {
  position: fixed;
  top: 20px;
  right: 20px;
  background: #333;
  color: white;
  padding: 12px 20px;
  border-radius: 6px;
  display: none;
  z-index: 9999;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.addEventListener('submit', function (e) {
    if (e.target.classList.contains('form-setujui')) {
      e.preventDefault();

      const formData = new FormData(e.target);
      const rowId = formData.get("id_pendaftaran");

      fetch('./ekstra/setujui_pendaftaran.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showToast("✅ Pendaftaran diterima", "#28a745");
          document.getElementById("row-" + rowId).remove();
        } else {
          showToast("❌ Gagal menyetujui", "#dc3545");
        }
      });
    }

    if (e.target.classList.contains('form-tolak')) {
      e.preventDefault();

      const formData = new FormData(e.target);
      const rowId = formData.get("id_pendaftaran");

      fetch('./ekstra/tolak_pendaftaran.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showToast("🚫 Pendaftaran ditolak", "#dc3545");
          document.getElementById("row-" + rowId).remove();
        } else {
          showToast("❌ Gagal menolak", "#dc3545");
        }
      });
    }
  });

  function showToast(message, color = '#333') {
    const toast = document.getElementById('toast');
    toast.innerText = message;
    toast.style.backgroundColor = color;
    toast.style.display = 'block';
    setTimeout(() => {
      toast.style.display = 'none';
    }, 3000);
  }
});
</script>
