<?php
// Users Page
include '../config.php';

// Get all users
$users = $conn->query("SELECT id, username, nama_lengkap, email, created_at FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pendaftaran User</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="../css/users.css">
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
            <li class="active">
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

    <!-- TOP NAVBAR (SAMA) -->
    <nav class="topbar">
      <input type="text" placeholder="Search user...">
      <div class="top-icons">
        <i class="bi bi-bell"></i>
        <span class="profile">Admin</span>
      </div>
    </nav>

    <!-- CONTENT -->
    <div class="container-fluid p-4">
      <!-- HEADER -->
      <div class="mb-3">
        <h5>Daftar User Terdaftar</h5>
        <small class="text-muted">Total User: <?php echo $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count']; ?></small>
      </div>

      <!-- TABLE USER -->
      <div class="card p-3">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Nama Lengkap</th>
              <th>Username</th>
              <th>Email</th>
              <th>Tanggal Daftar</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = 1;
            while ($row = $users->fetch_assoc()): 
            ?>
            <tr>
              <td><?php echo $no++; ?></td>
              <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
              <td><?php echo htmlspecialchars($row['username']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><?php echo date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
              <td>
                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal" onclick="showDetail(<?php echo $row['id']; ?>)">
                  <i class="bi bi-eye"></i> Lihat
                </button>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    </div>
  </main>
</div>

<!-- Modal Detail User -->
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail User</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="detailContent">
        <p>Loading...</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/main.js"></script>

<script>
function showDetail(userId) {
  const modal = document.getElementById('detailModal');
  const content = document.getElementById('detailContent');
  
  // Get user data via AJAX
  fetch(`users.php?action=getUser&id=${userId}`)
    .then(res => res.json())
      .then(user => {
      let html = `
        <p><strong>Nama Lengkap:</strong> ${user.nama_lengkap}</p>
        <p><strong>Username:</strong> ${user.username}</p>
        <p><strong>Email:</strong> ${user.email}</p>
        <p><strong>Role:</strong> ${user.role}</p>
        <p><strong>Tanggal Daftar:</strong> ${new Date(user.created_at).toLocaleDateString('id-ID', {year: 'numeric', month: 'long', day: 'numeric'})}</p>
      `;
      content.innerHTML = html;
    });
}
</script>

<?php
// Handle AJAX request
if (isset($_GET['action']) && $_GET['action'] === 'getUser') {
  $user_id = intval($_GET['id']);
  $stmt = $conn->prepare("SELECT id, username, nama_lengkap, email, created_at, role FROM users WHERE id = ?");
  $stmt->bind_param('i', $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  $stmt->close();
  
  header('Content-Type: application/json');
  echo json_encode($user);
  exit();
}
?>
