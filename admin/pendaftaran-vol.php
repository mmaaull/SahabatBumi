<?php
// Pendaftaran Volunteer Page
include '../config.php';

// Get all pendaftaran with aksi details
$query = "SELECT 
    pa.id,
    pa.aksi_id,
    pa.user_id,
    pa.nama,
    pa.jenis_kelamin,
    pa.umur,
    pa.asal_instansi,
    pa.created_at,
    a.nama_aksi,
    u.username,
    u.nama_lengkap,
    u.email
FROM pendaftaran_aksi pa
JOIN aksi a ON pa.aksi_id = a.id
JOIN users u ON pa.user_id = u.id
ORDER BY pa.created_at DESC";

$pendaftaran = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pendaftaran Aksi Volunteer</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="../css/pendaftaran-vol.css">
</head>
<body>

<div class="d-flex">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <h5 class="brand">Admin Dashboard</h5>
    <ul class="menu">
      <li>
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
      <li class="active">
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

  <!-- MAIN -->
  <main class="flex-fill">

    <!-- TOPBAR -->
    <nav class="topbar">
      <input type="text" placeholder="Search pendaftar...">
      <div class="top-icons">
        <i class="bi bi-bell"></i>
        <span class="profile">Admin</span>
      </div>
    </nav>

    <!-- CONTENT -->
    <div class="container-fluid p-4">

      <!-- HEADER -->
      <div class="mb-3">
        <h5>Data Pendaftaran Aksi</h5>
        <small class="text-muted">
          Menampilkan daftar relawan yang telah mendaftar pada setiap aksi
        </small>
      </div>

      <!-- TABLE -->
      <div class="card p-3">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Nama Aksi</th>
              <th>Nama Relawan</th>
              <th>Username</th>
              <th>Jenis Kelamin</th>
              <th>Umur</th>
              <th>Asal Instansi</th>
              <th>Tanggal Daftar</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            while ($row = $pendaftaran->fetch_assoc()): 
            ?>
            <tr>
              <td><?php echo $no++; ?></td>
              <td><?php echo htmlspecialchars($row['nama_aksi']); ?></td>
              <td><?php echo htmlspecialchars($row['nama'] ?? $row['nama_lengkap']); ?></td>
              <td><?php echo htmlspecialchars($row['username']); ?></td>
              <td><?php echo htmlspecialchars($row['jenis_kelamin'] ?? '-'); ?></td>
              <td><?php echo $row['umur'] ?? '-'; ?></td>
              <td><?php echo htmlspecialchars($row['asal_instansi'] ?? '-'); ?></td>
              <td><?php echo date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    </div>
  </main>
</div>

</body>
</html>
