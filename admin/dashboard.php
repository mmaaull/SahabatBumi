<?php
// Dashboard Page
include '../config.php';

// Get statistics
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_aksi = $conn->query("SELECT COUNT(*) as count FROM aksi")->fetch_assoc()['count'];
$total_pendaftaran = $conn->query("SELECT COUNT(*) as count FROM pendaftaran_aksi")->fetch_assoc()['count'];
$total_laporan = $conn->query("SELECT COUNT(*) as count FROM laporan")->fetch_assoc()['count'];
$pending_laporan = $conn->query("SELECT COUNT(*) as count FROM laporan WHERE status='pending'")->fetch_assoc()['count'];

// Get recent aksi
$recent_aksi = $conn->query("SELECT * FROM aksi ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="d-flex">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <h5 class="brand">Admin Dashboard</h5>
    <ul class="menu">
        <li class="active">
          <a href="dashboard.php">
            <i class="bi bi-speedometer2"></i> Dashboard
          </a>
        </li>
        <li>
          <a href="users.php">
            <i class="bi bi-people"></i> User
          </a>
        </li>
        <li>
          <a href="daftar-aksi.php">
            <i class="bi bi-clipboard-check"></i> Daftar Aksi
          </a>
        </li>
        <li>
          <a href="pendaftaran-vol.php">
            <i class="bi bi-person-plus"></i> Pendaftaran Aksi
          </a>
        </li>
        <li>
          <a href="laporanaksi.php">
            <i class="bi bi-file-earmark-text"></i> Laporan
          </a>
        </li>
    </ul>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="flex-fill">

    <!-- TOP NAVBAR -->
    <nav class="topbar">
      <input type="text" placeholder="Search...">
      <div class="top-icons">
        <i class="bi bi-bell"></i>
        <span class="profile">Admin</span>
      </div>
    </nav>

    <!-- CONTENT -->
    <div class="container-fluid p-4">

      <!-- STAT CARDS -->
      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <div class="card stat-card">
            <i class="bi bi-people icon"></i>
            <h6>Total User</h6>
            <h3><?php echo $total_users; ?></h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card stat-card">
            <i class="bi bi-clipboard-check icon"></i>
            <h6>Total Aksi</h6>
            <h3><?php echo $total_aksi; ?></h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card stat-card">
            <i class="bi bi-person-plus icon"></i>
            <h6>Pendaftaran Aksi</h6>
            <h3><?php echo $total_pendaftaran; ?></h3>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card stat-card">
            <i class="bi bi-file-earmark-text icon"></i>
            <h6>Laporan Aksi</h6>
            <h3><?php echo $total_laporan; ?></h3>
            <small class="text-muted"><?php echo $pending_laporan; ?> pending</small>
          </div>
        </div>
      </div>

      <!-- CHART & ACTIVITY -->
      <div class="row g-4 mb-4">
        <div class="col-md-8">
          <div class="card p-3">
            <h6>Aksi Bulanan</h6>
            <canvas id="aksiChart"></canvas>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-3">
            <h6>Aktivitas Terbaru</h6>
            <ul class="activity">
              <li>User baru mendaftar</li>
              <li>User mengikuti aksi</li>
              <li>Laporan aksi dikirim</li>
              <li>Laporan diverifikasi admin</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- TABLE -->
      <div class="card p-3">
        <h6>Daftar Aksi Terbaru</h6>
        <table class="table table-hover mt-2">
          <thead>
            <tr>
              <th>Judul Aksi</th>
              <th>Kategori</th>
              <th>Tanggal Mulai</th>
              <th>Lokasi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $recent_aksi->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['nama_aksi']); ?></td>
              <td><?php echo htmlspecialchars($row['kategori'] ?? '-'); ?></td>
              <td><?php echo date('d-m-Y', strtotime($row['tanggal_mulai'])); ?></td>
              <td><?php echo htmlspecialchars($row['lokasi'] ?? '-'); ?></td>
              <td>
                <a href="daftar-aksi.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    </div>
  </main>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../js/main.js"></script>

</body>
</html>
