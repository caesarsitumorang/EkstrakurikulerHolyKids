<?php
include '../../../../database/config.php';

// Ambil semua data ekstrakurikuler
$queryEkstra = "SELECT nama_ekstra, hari, jam, lokasi, deskripsi FROM ekstrakurikuler ORDER BY nama_ekstra ASC";
$resultEkstra = mysqli_query($conn, $queryEkstra);

// Ambil semua data semester
$querySemester = "SELECT semester, tanggal_mulai, tanggal_selesai FROM semester ORDER BY semester ASC";
$resultSemester = mysqli_query($conn, $querySemester);
?>

<div class="beranda-container">
  <h2>📚 Selamat Datang di Ekstrakurikuler</h2>
  <p>Berikut adalah informasi ekstrakurikuler yang tersedia di sekolah:</p>

  <div class="ekstra-wrapper">
    <?php if (mysqli_num_rows($resultEkstra) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($resultEkstra)): ?>
        <div class="ekstra-card">
          <h3><?= htmlspecialchars($row['nama_ekstra']) ?></h3>
          <p><strong>Hari:</strong> <?= htmlspecialchars($row['hari']) ?> &nbsp; | &nbsp;
             <strong>Jam:</strong> <?= htmlspecialchars($row['jam']) ?></p>
          <p><strong>Lokasi:</strong> <?= htmlspecialchars($row['lokasi']) ?></p>
          <p><?= htmlspecialchars($row['deskripsi']) ?></p>

          <hr>
          <p><strong>Periode Kegiatan:</strong></p>
          <ul>
            <?php if(mysqli_num_rows($resultSemester) > 0): ?>
              <?php 
                mysqli_data_seek($resultSemester, 0); 
                while($semester = mysqli_fetch_assoc($resultSemester)): 
                  // Format tanggal
                  $tglMulai = date('d, F Y', strtotime($semester['tanggal_mulai']));
                  $tglSelesai = date('d, F Y', strtotime($semester['tanggal_selesai']));
              ?>
                <li>
                  <?= htmlspecialchars($semester['semester']) ?>: <?= $tglMulai ?> sampai <?= $tglSelesai ?>
                </li>
              <?php endwhile; ?>
            <?php else: ?>
              <li>Belum ada data semester.</li>
            <?php endif; ?>
          </ul>

        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>Belum ada data ekstrakurikuler tersedia.</p>
    <?php endif; ?>
  </div>
</div>


<style>
.beranda-container {
  font-family: 'Segoe UI', sans-serif;
  padding: 30px;
  background-color: #f4f6f9;
}

h2 {
  color: #082465;
  margin-bottom: 10px;
}

p {
  margin-bottom: 20px;
}

.ekstra-wrapper {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

.ekstra-card {
  background: white;
  border-left: 5px solid #082465;
  padding: 16px 20px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  transition: 0.2s ease-in-out;
}

.ekstra-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 16px rgba(0,0,0,0.12);
}

.ekstra-card h3 {
  margin-top: 0;
  color: #082465;
}
</style>
