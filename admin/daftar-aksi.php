<?php
// Daftar Aksi Page
include '../config.php';

// If this request targets the AJAX CRUD endpoints (has an `action`), install
// an error/exception/shutdown handler that returns JSON and logs details.
if (isset($_REQUEST['action'])) {
  // Hide errors from output but log them
  ini_set('display_errors', 0);
  error_reporting(E_ALL);

  set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    error_log("[daftar-aksi] PHP error: $errstr in $errfile:$errline");
    echo json_encode(['status' => 'error', 'message' => 'Internal server error (see server logs)']);
    exit();
  });

  set_exception_handler(function ($ex) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    error_log("[daftar-aksi] Uncaught exception: " . $ex->getMessage() . " in " . $ex->getFile() . ':' . $ex->getLine());
    echo json_encode(['status' => 'error', 'message' => 'Internal server error (see server logs)']);
    exit();
  });

  register_shutdown_function(function () {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
      http_response_code(500);
      header('Content-Type: application/json; charset=utf-8');
      error_log("[daftar-aksi] Shutdown fatal error: " . $err['message'] . " in " . $err['file'] . ':' . $err['line']);
      echo json_encode(['status' => 'error', 'message' => 'Internal server error (see server logs)']);
      exit();
    }
  });
}

// Create uploads folder if doesn't exist
$uploadsDir = __DIR__ . '/uploads';
if (!is_dir($uploadsDir)) {
    @mkdir($uploadsDir, 0777, true);
}

// Handle CRUD operations
$response = [];

// Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    // Ensure error reporting doesn't pollute JSON response
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    
    header('Content-Type: application/json; charset=utf-8');
    
    // Validate required fields
    if (empty($_POST['nama_aksi']) || empty($_POST['tanggal_mulai']) || empty($_POST['tanggal_selesai'])) {
        echo json_encode(['status' => 'error', 'message' => 'Nama aksi, tanggal mulai, dan tanggal selesai wajib diisi']);
        exit();
    }
    
    $nama_aksi = trim($_POST['nama_aksi']);
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $tanggal_mulai = trim($_POST['tanggal_mulai']);
    $tanggal_selesai = trim($_POST['tanggal_selesai']);
    $tanggal_mulai_jam = trim($_POST['tanggal_mulai_jam'] ?? '');
    $tanggal_selesai_jam = trim($_POST['tanggal_selesai_jam'] ?? '');
    $lokasi = trim($_POST['lokasi'] ?? '');
    $image_path = '';
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $fileName = $file['name'];
        $fileTmp = $file['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        
        if (in_array($fileExt, $allowed) && $file['size'] <= 5242880) {
            $newFileName = 'event_' . time() . '_' . uniqid() . '.' . $fileExt;
            $filePath = $uploadsDir . '/' . $newFileName;
            
            if (move_uploaded_file($fileTmp, $filePath)) {
                $image_path = 'admin/uploads/' . $newFileName;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal upload gambar']);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Format gambar tidak didukung atau ukuran terlalu besar (max 5MB)']);
            exit();
        }
    }
    
    $stmt = $conn->prepare("INSERT INTO aksi (nama_aksi, deskripsi, kategori, tanggal_mulai, tanggal_selesai, tanggal_mulai_jam, tanggal_selesai_jam, lokasi, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit();
    }
    
    if (!$stmt->bind_param('sssssssss', $nama_aksi, $deskripsi, $kategori, $tanggal_mulai, $tanggal_selesai, $tanggal_mulai_jam, $tanggal_selesai_jam, $lokasi, $image_path)) {
        echo json_encode(['status' => 'error', 'message' => 'Bind param failed: ' . $stmt->error]);
        exit();
    }
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Aksi berhasil dibuat']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal membuat aksi: ' . $stmt->error]);
    }
    $stmt->close();
    exit();
}

// Read
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'read') {
    $result = $conn->query("SELECT * FROM aksi ORDER BY created_at DESC");
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

// Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    header('Content-Type: application/json; charset=utf-8');
    
    if (empty($_POST['id']) || empty($_POST['nama_aksi']) || empty($_POST['tanggal_mulai']) || empty($_POST['tanggal_selesai'])) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
        exit();
    }
    
    $id = intval($_POST['id']);
    $nama_aksi = trim($_POST['nama_aksi']);
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $kategori = trim($_POST['kategori'] ?? '');
    $tanggal_mulai = trim($_POST['tanggal_mulai']);
    $tanggal_selesai = trim($_POST['tanggal_selesai']);
    $tanggal_mulai_jam = trim($_POST['tanggal_mulai_jam'] ?? '');
    $tanggal_selesai_jam = trim($_POST['tanggal_selesai_jam'] ?? '');
    $lokasi = trim($_POST['lokasi'] ?? '');
    
    // Get current image_path
    $stmt = $conn->prepare("SELECT image_path FROM aksi WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = stmt_get_assoc($stmt);
    $image_path = $row['image_path'] ?? '';
    $stmt->close();
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $fileName = $file['name'];
        $fileTmp = $file['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        
        if (in_array($fileExt, $allowed) && $file['size'] <= 5242880) {
            // Delete old image
            if (!empty($image_path) && file_exists('../' . $image_path)) {
                @unlink('../' . $image_path);
            }
            
            $newFileName = 'event_' . time() . '_' . uniqid() . '.' . $fileExt;
            $filePath = $uploadsDir . '/' . $newFileName;
            
            if (move_uploaded_file($fileTmp, $filePath)) {
                $image_path = 'admin/uploads/' . $newFileName;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal upload gambar']);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Format gambar tidak didukung atau ukuran terlalu besar (max 5MB)']);
            exit();
        }
    }
    
    $stmt = $conn->prepare("UPDATE aksi SET nama_aksi = ?, deskripsi = ?, kategori = ?, tanggal_mulai = ?, tanggal_selesai = ?, tanggal_mulai_jam = ?, tanggal_selesai_jam = ?, lokasi = ?, image_path = ? WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit();
    }
    if (!$stmt->bind_param('sssssssssi', $nama_aksi, $deskripsi, $kategori, $tanggal_mulai, $tanggal_selesai, $tanggal_mulai_jam, $tanggal_selesai_jam, $lokasi, $image_path, $id)) {
        echo json_encode(['status' => 'error', 'message' => 'Bind param failed: ' . $stmt->error]);
        exit();
    }
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Aksi berhasil diupdate']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate aksi: ' . $stmt->error]);
    }
    $stmt->close();
    exit();
}

// Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    header('Content-Type: application/json; charset=utf-8');
    
    if (empty($_POST['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
        exit();
    }
    
    $id = intval($_POST['id']);
    
    // Get image_path to delete file
    $stmt = $conn->prepare("SELECT image_path FROM aksi WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = stmt_get_assoc($stmt);
    $stmt->close();
    
    // Delete file
    if ($row && !empty($row['image_path']) && file_exists('../' . $row['image_path'])) {
        unlink('../' . $row['image_path']);
    }
    
    $stmt = $conn->prepare("DELETE FROM aksi WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Aksi berhasil dihapus']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus aksi: ' . $conn->error]);
    }
    $stmt->close();
    exit();
}

// Get single aksi for editing
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get') {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    header('Content-Type: application/json; charset=utf-8');
    
    if (empty($_GET['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan']);
        exit();
    }
    
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM aksi WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $data = stmt_get_assoc($stmt);
    $stmt->close();
    
    if (!$data) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        exit();
    }
    
    echo json_encode($data);
    exit();
}

$aksi_list = $conn->query("SELECT * FROM aksi ORDER BY created_at DESC");
if ($aksi_list === false) {
  // prevent fatal errors when rendering the table; log and use empty iterator
  error_log('[daftar-aksi] Query failed: ' . $conn->error);
  $aksi_list = new class {
    public function fetch_assoc() { return false; }
  };
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Aksi</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom CSS (LAMA) -->
  <link rel="stylesheet" href="../css/daftar-aksi.css">
</head>
<body>

<div class="d-flex">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <h5 class="brand">Admin Dashboard</h5>
    <ul class="menu">
      <li><a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
      <li><a href="users.php"><i class="bi bi-people"></i> User</a></li>
      <li class="active"><a href="daftar-aksi.php"><i class="bi bi-clipboard-check"></i> Daftar Aksi</a></li>
      <li><a href="pendaftaran-vol.php"><i class="bi bi-person-plus"></i> Pendaftaran Aksi</a></li>
      <li><a href="laporanaksi.php"><i class="bi bi-file-earmark-text"></i> Laporan</a></li>
    </ul>
  </aside>

  <!-- MAIN -->
  <main class="flex-fill">

    <!-- TOPBAR -->
    <nav class="topbar">
      <input type="text" placeholder="Search aksi...">
      <div class="top-icons">
        <i class="bi bi-bell"></i>
        <span class="profile">Admin</span>
      </div>
    </nav>

    <!-- CONTENT -->
    <div class="container-fluid p-4">

      <!-- HEADER -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>Daftar Aksi Lingkungan</h5>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#aksiModal" onclick="resetForm()">
          <i class="bi bi-plus-circle"></i> Tambah Aksi
        </button>
      </div>

      <!-- TABLE -->
      <div class="card p-3">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Gambar</th>
              <th>Nama Aksi</th>
              <th>Kategori</th>
              <th>Tanggal</th>
              <th>Lokasi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; while ($row = $aksi_list->fetch_assoc()): ?>
            <tr>
              <td><?php echo $no++; ?></td>
              <td>
                <?php if (!empty($row['image_path']) && file_exists('../' . $row['image_path'])): ?>
                  <img src="../<?php echo htmlspecialchars($row['image_path']); ?>" alt="Event" style="width: 50px; height: 50px; border-radius: 5px; object-fit: cover;">
                <?php else: ?>
                  <span class="text-muted">Tidak ada</span>
                <?php endif; ?>
              </td>
              <td><?php echo htmlspecialchars($row['nama_aksi']); ?></td>
              <td><?php echo htmlspecialchars($row['kategori'] ?? '-'); ?></td>
              <td><?php echo date('d-m-Y', strtotime($row['tanggal_mulai'])) . ' s/d ' . date('d-m-Y', strtotime($row['tanggal_selesai'])); ?></td>
              <td><?php echo htmlspecialchars($row['lokasi'] ?? '-'); ?></td>
              <td>
                <button class="btn btn-warning btn-sm" onclick="editAksi(<?php echo $row['id']; ?>)">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-danger btn-sm" onclick="deleteAksi(<?php echo $row['id']; ?>)">
                  <i class="bi bi-trash"></i>
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

<!-- ================= MODAL CRUD (BARU) ================= -->
<div class="modal fade" id="aksiModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Form Aksi Lingkungan</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="aksiForm" enctype="multipart/form-data">
          <input type="hidden" id="editId">

          <div class="mb-3">
            <label class="form-label">Nama Aksi</label>
            <input type="text" class="form-control" id="nama_aksi" name="nama_aksi" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Kategori</label>
            <input type="text" class="form-control" id="kategori" name="kategori" placeholder="e.g. Pembersihan, Penanaman, dll">
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Tanggal Mulai</label>
              <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Jam Mulai</label>
              <input type="time" class="form-control" id="tanggal_mulai_jam" name="tanggal_mulai_jam">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Tanggal Selesai</label>
              <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Jam Selesai</label>
              <input type="time" class="form-control" id="tanggal_selesai_jam" name="tanggal_selesai_jam">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Lokasi</label>
            <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Lokasi aksi">
          </div>

          <div class="mb-3">
            <label class="form-label">Gambar Event</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage()">
            <small class="text-muted">Format: JPG, PNG, GIF (Max 5MB)</small>
            <img id="imagePreview" style="max-width: 100%; max-height: 200px; margin-top: 10px; display: none; border-radius: 5px;">
          </div>

          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-success" onclick="saveAksi()">Simpan</button>
      </div>

    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- External JS -->
<script src="../js/script_daftar-aksi.js"></script>

</body>
</html>
