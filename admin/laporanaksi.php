<?php
// Laporan Aksi Page
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../config.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updateStatus') {
    $id = intval($_POST['id']);
    $status = $_POST['status'];
    
    if (in_array($status, ['pending', 'approved', 'rejected'])) {
        $stmt = $conn->prepare("UPDATE laporan SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $id);
        $stmt->execute();
        $stmt->close();
    }
    header('Location: laporanaksi.php');
    exit();
}

// Get all laporan with user and aksi details
$query = "SELECT 
    l.id,
    l.user_id,
    l.title,
    l.kategori,
    l.description,
    l.image_path,
    l.status,
    l.created_at,
    u.username,
    u.nama_lengkap,
    u.email,
    a.nama_aksi
FROM laporan l
JOIN users u ON l.user_id = u.id
LEFT JOIN aksi a ON l.aksi_id = a.id
ORDER BY l.created_at DESC";

$laporan = $conn->query($query);
if (!$laporan) {
    die("Query Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Aksi</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="../css/laporanaksi.css">
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
      <li>
        <a href="pendaftaran-vol.php">
          <i class="bi bi-person-plus"></i> Pendaftaran Aksi
        </a>
      </li>
      <li class="active">
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
      <input type="text" placeholder="Search laporan...">
      <div class="top-icons">
        <i class="bi bi-bell"></i>
        <span class="profile">Admin</span>
      </div>
    </nav>

    <!-- CONTENT -->
    <div class="container-fluid p-4">
      <!-- HEADER -->
      <div class="mb-4">
        <h5>Daftar Laporan Aksi</h5>
        <small class="text-muted">Total Laporan: <?php echo $laporan->num_rows; ?></small>
      </div>

      <!-- FILTER SECTION -->
      <div class="card mb-4 p-3">
        <div class="row">
          <div class="col-md-4">
            <label class="form-label">Filter Status</label>
            <select class="form-select" id="filterStatus" onchange="filterTable()">
              <option value="">Semua Status</option>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
        </div>
      </div>

      <!-- TABLE VIEW -->
      <div class="card p-3">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>User</th>
                <th>Judul Laporan</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Gambar</th>
                <th>Status</th>
                <th>Tanggal Upload</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              // Reset pointer to fetch again
              $laporan->data_seek(0);
              $no = 1;
              while ($row = $laporan->fetch_assoc()): 
              ?>
              <tr class="status-row" data-status="<?php echo $row['status']; ?>">
                <td><?php echo $no++; ?></td>
                <td>
                  <strong><?php echo htmlspecialchars($row['nama_lengkap']); ?></strong><br>
                  <small class="text-muted">@<?php echo htmlspecialchars($row['username']); ?></small>
                </td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['kategori'] ?? '-'); ?></td>
                <td>
                  <small><?php echo htmlspecialchars(substr($row['description'], 0, 50)) . '...'; ?></small>
                </td>
                <td>
                  <?php if (!empty($row['image_path']) && file_exists('../user/' . $row['image_path'])): ?>
                    <img src="../user/<?php echo htmlspecialchars($row['image_path']); ?>" alt="Laporan" style="width: 50px; height: 50px; border-radius: 5px; object-fit: cover; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal<?php echo $row['id']; ?>">
                  <?php else: ?>
                    <span class="text-muted">Tidak ada</span>
                  <?php endif; ?>
                </td>
                <td>
                  <span class="status-badge status-<?php echo $row['status']; ?>">
                    <?php echo ucfirst($row['status']); ?>
                  </span>
                </td>
                <td><?php echo date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
                <td>
                  <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo $row['id']; ?>">
                    <i class="bi bi-eye"></i> Lihat
                  </button>
                  <form method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="updateStatus">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <select name="status" class="form-select form-select-sm" style="width: auto; display: inline-block; margin-top: 5px;" onchange="this.form.submit();">
                      <option value="pending" <?php echo $row['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                      <option value="approved" <?php echo $row['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                      <option value="rejected" <?php echo $row['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                  </form>
                </td>
              </tr>

              <!-- Detail Modal -->
              <div class="modal fade" id="detailModal<?php echo $row['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Detail Laporan</h5>
                      <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <h6><?php echo htmlspecialchars($row['title']); ?></h6>
                      <p><strong>User:</strong> <?php echo htmlspecialchars($row['nama_lengkap']); ?> (@<?php echo htmlspecialchars($row['username']); ?>)</p>
                      <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                      <p><strong>Aksi:</strong> <?php echo htmlspecialchars($row['nama_aksi'] ?? '-'); ?></p>
                      <p><strong>Kategori:</strong> <?php echo htmlspecialchars($row['kategori'] ?? '-'); ?></p>
                      <p><strong>Deskripsi:</strong></p>
                      <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                      <?php if (!empty($row['image_path']) && file_exists('../user/' . $row['image_path'])): ?>
                        <p><strong>Gambar:</strong></p>
                        <img src="../user/<?php echo htmlspecialchars($row['image_path']); ?>" alt="Laporan" style="max-width: 100%; border-radius: 5px;">
                      <?php endif; ?>
                      <p><strong>Status:</strong> <span class="status-badge status-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></p>
                      <p><strong>Tanggal Upload:</strong> <?php echo date('d-m-Y H:i:s', strtotime($row['created_at'])); ?></p>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Image Modal -->
              <div class="modal fade" id="imageModal<?php echo $row['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Gambar Laporan</h5>
                      <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                      <?php if (!empty($row['image_path']) && file_exists('../user/' . $row['image_path'])): ?>
                        <img src="../user/<?php echo htmlspecialchars($row['image_path']); ?>" alt="Laporan" style="max-width: 100%; border-radius: 5px;">
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>

              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/main.js"></script>

<script>
function filterTable() {
    const filterStatus = document.getElementById('filterStatus').value;
    const rows = document.querySelectorAll('.status-row');
    
    rows.forEach(row => {
        if (filterStatus === '' || row.getAttribute('data-status') === filterStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

</body>
</html>
