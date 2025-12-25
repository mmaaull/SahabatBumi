<?php
// start output buffering so redirects can be sent even if template has HTML
if (session_status() == PHP_SESSION_NONE) ob_start();
if (session_status() == PHP_SESSION_NONE) session_start();
include '../config.php';
// base url and uploads fs dir
$base_url = '/SahabatBumi/user/';
$uploadsFsDir = __DIR__ . '/uploads';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Aksi Sahabat Bumi</title>
    <link rel="stylesheet" href="../css/style_edit.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php
    // session/config already initialized at top
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $error = '';
    $success = '';

    // load existing laporan
    if (!isset($_GET['id'])) {
        header('Location: daftar-laporan.php');
        exit();
    }
    $id = intval($_GET['id']);
    $stmt = $conn->prepare('SELECT * FROM laporan WHERE id = ? AND user_id = ?');
    $stmt->bind_param('ii', $id, $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) {
        header('Location: daftar-laporan.php');
        exit();
    }
    $lap = $res->fetch_assoc();

    // Handle update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['judul']);
        $kategori = trim($_POST['kategori']);
        $description = trim($_POST['deskripsi']);
        $uploadPath = $lap['image_path'];

        if (!empty($_FILES['photos']) && $_FILES['photos']['error'] === UPLOAD_ERR_OK) {
            // ensure uploads folder
            if (!is_dir($uploadsFsDir)) mkdir($uploadsFsDir, 0755, true);
            $tmpName = $_FILES['photos']['tmp_name'];
            $origName = basename($_FILES['photos']['name']);
            $ext = pathinfo($origName, PATHINFO_EXTENSION);
            $newFilename = 'laporan_' . time() . '_' . uniqid() . '.' . $ext;
            $newNameFs = $uploadsFsDir . '/' . $newFilename;
            if (move_uploaded_file($tmpName, $newNameFs)) {
                // delete old (filesystem)
                if (!empty($uploadPath)) {
                    $oldFs = __DIR__ . '/' . $uploadPath;
                    if (file_exists($oldFs)) unlink($oldFs);
                }
                // store DB path relative to user folder
                $uploadPath = 'uploads/' . $newFilename;
            }
        }

        $up = $conn->prepare('UPDATE laporan SET title = ?, kategori = ?, description = ?, image_path = ?, updated_at = NOW() WHERE id = ? AND user_id = ?');
        $up->bind_param('ssssii', $title, $kategori, $description, $uploadPath, $id, $user_id);
        if ($up->execute()) {
            header('Location: daftar-laporan.php');
            exit();
        } else {
            $error = 'Gagal memperbarui laporan: ' . $up->error;
        }
    }

    ?>

    <?php include '../navbar.php'; ?>

    <!-- Section Form Edit -->
    <section id="form-edit" style="background-image: url('../img/bg-unggah.jpg');">
        <div class="form-wrapper">
            <div class="back-button-container">
                <a href="daftar-laporan.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1 class="form-title">Edit Aksi Sahabat Bumi</h1>

            <!-- Error Message -->
            <?php if (!empty($error)): ?>
                <div style="background-color: #fee; color: #c33; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fcc;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                <div class="form-layout">
                    <!-- Kolom Kiri -->
                    <div class="form-column-left">
                        <!-- Judul Aksi -->
                        <div class="form-group">
                            <label for="judul">Judul Aksi</label>
                            <input type="text" id="judul" name="judul" placeholder="Judul..." value="<?php echo htmlspecialchars($lap['title']); ?>" required>
                        </div>
                        <!-- Kategori -->
                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <select id="kategori" name="kategori" required>
                                <option value="" disabled>Pilih...</option>
                                <option value="Penanaman Pohon" <?php echo $lap['kategori']==='Penanaman Pohon' ? 'selected' : ''; ?>>Penanaman Pohon</option>
                                <option value="Pembersihan Lingkungan" <?php echo $lap['kategori']==='Pembersihan Lingkungan' ? 'selected' : ''; ?>>Pembersihan Lingkungan</option>
                                <option value="Daur Ulang" <?php echo $lap['kategori']==='Daur Ulang' ? 'selected' : ''; ?>>Daur Ulang</option>
                                <option value="Edukasi Lingkungan" <?php echo $lap['kategori']==='Edukasi Lingkungan' ? 'selected' : ''; ?>>Edukasi Lingkungan</option>
                                <option value="Lainnya" <?php echo $lap['kategori']==='Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                            </select>
                        </div>
                        <!-- Deskripsi -->
                        <div class="form-group form-group-deskripsi">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" placeholder="Deskripsikan aksimu..." required><?php echo htmlspecialchars($lap['description']); ?></textarea>
                        </div>
                    </div>
                    <!-- Kolom Kanan -->
                    <div class="form-column-right">
                        <div class="upload-section">
                            <div class="upload-wrapper">
                                <label class="upload-label">Edit Foto Bukti Aksi</label>
                                <div class="upload-area has-images" id="uploadArea">
                                    <div class="upload-content hidden" id="uploadContent">
                                        <img src="../img/camera-icon.png" alt="Camera" class="camera-icon">
                                        <p class="upload-text">Klik atau Seret File di Sini</p>
                                        <p class="upload-subtext">Maksimal 3 Foto. Pastikan foto jelas.</p>
                                    </div>
                                    <div class="preview-container" id="previewContainer">
                                        <!-- Existing photo will be loaded here by PHP -->
                                        <?php if (!empty($lap['image_path']) && file_exists(__DIR__ . '/' . $lap['image_path'])): ?>
                                            <div class="preview-item">
                                                <img src="<?php echo $base_url . htmlspecialchars($lap['image_path']); ?>" alt="Existing Photo">
                                                <button type="button" class="remove-btn">×</button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <input type="file" id="fileInput" name="photos" accept="image/*" multiple style="display: none;">
                                </div>
                                <p class="info-text">Laporan akan diverifikasi untuk mendapatkan Poin.</p>
                            </div>
                            <div class="button-group">
                                <button type="submit" class="submit-btn btn-perbarui">Perbarui</button>
                                <button type="button" class="submit-btn btn-batal" id="btnBatal">Batal</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script src="../js/script_edit.js"></script>
    <script src="../js/footer.js"></script>
</body>
</html>
