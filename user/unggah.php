<?php
// start output buffering so redirects can be sent even if template has HTML
if (session_status() == PHP_SESSION_NONE) ob_start();
if (session_status() == PHP_SESSION_NONE) session_start();
include '../config.php';
// define upload fs dir and base url
$base_url = '/SahabatBumi/user/';
$uploadsFsDir = __DIR__ . '/uploads';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unggah Aksi Sahabat Bumi</title>
    <link rel="stylesheet" href="../css/style_unggah.css">
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

    $error = '';
    $success = '';
    $debug = ''; // For debugging

    // Handle POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['judul'] ?? '');
        $kategori = trim($_POST['kategori'] ?? '');
        $description = trim($_POST['deskripsi'] ?? '');
        $user_id = $_SESSION['user_id'];

        $debug .= "POST received. Title: '$title', Kategori: '$kategori', Description: '" . substr($description, 0, 30) . "...', User ID: $user_id\n";

        // Basic validation
        if (empty($title) || empty($kategori) || empty($description)) {
            $error = 'Semua field harus diisi.';
            $debug .= "Validation failed: Missing fields\n";
        } else {
            // Handle single image upload (optional: multiple)
            $uploadPath = '';
            if (isset($_FILES['photos']) && $_FILES['photos']['error'] !== UPLOAD_ERR_NO_FILE) {
                $debug .= "File upload detected - Error code: " . $_FILES['photos']['error'] . "\n";
                
                if ($_FILES['photos']['error'] !== UPLOAD_ERR_OK) {
                    $error = 'File upload error: Code ' . $_FILES['photos']['error'];
                    $debug .= "Upload error!\n";
                } else {
                    // Ensure absolute uploads folder exists
                    if (!is_dir($uploadsFsDir)) {
                        @mkdir($uploadsFsDir, 0777, true);
                        $debug .= "Created uploads folder: $uploadsFsDir\n";
                    }
                    
                    $tmpName = $_FILES['photos']['tmp_name'];
                    $origName = basename($_FILES['photos']['name']);
                    $fileSize = $_FILES['photos']['size'];
                    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                    $allowed = array('jpg', 'jpeg', 'png', 'gif');
                    
                    $debug .= "File: $origName | Size: $fileSize | Ext: $ext | Tmp: $tmpName\n";
                    
                    if (!in_array($ext, $allowed)) {
                        $error = 'Tipe file tidak didukung. Hanya JPG, PNG, GIF.';
                        $debug .= "Extension NOT allowed\n";
                    } else if ($fileSize > 5242880) {
                        $error = 'File terlalu besar. Maksimal 5MB.';
                        $debug .= "File too large\n";
                    } else if (!is_uploaded_file($tmpName)) {
                        $error = 'File upload invalid atau tidak aman.';
                        $debug .= "Not an uploaded file\n";
                    } else {
                        $newFilename = 'laporan_' . time() . '_' . uniqid() . '.' . $ext;
                        $newNameFs = $uploadsFsDir . '/' . $newFilename;
                        if (move_uploaded_file($tmpName, $newNameFs)) {
                            // store DB path relative to user folder
                            $uploadPath = 'uploads/' . $newFilename;
                            $debug .= "File uploaded to: $newNameFs ✅\n";
                        } else {
                            $error = 'Gagal upload file. Periksa folder uploads.';
                            $debug .= "move_uploaded_file FAILED\n";
                        }
                    }
                }
            } else {
                $debug .= "No file upload\n";
            }

            if (empty($error)) {
                // Additional MIME/type check using finfo
                if (!empty($uploadPath)) {
                    $fileFs = __DIR__ . '/' . $uploadPath;
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $fileFs);
                    finfo_close($finfo);
                    $allowed_mimes = array('image/jpeg', 'image/png', 'image/gif');
                    if (!in_array($mime, $allowed_mimes)) {
                        // remove invalid file
                        @unlink($fileFs);
                        $uploadPath = '';
                        $error = 'Tipe MIME file tidak valid.';
                        $debug .= "Invalid MIME: $mime\n";
                    }
                }

                $stmt = $conn->prepare('INSERT INTO laporan (user_id, title, kategori, description, image_path) VALUES (?, ?, ?, ?, ?)');
                $stmt->bind_param('issss', $user_id, $title, $kategori, $description, $uploadPath);
                $debug .= "Executing query: INSERT with user_id=$user_id, title=$title, image_path=$uploadPath\n";

                if ($stmt->execute()) {
                    $success = 'Laporan berhasil diunggah!';
                    $debug .= "Query executed successfully!\n";
                    // redirect to list page for normal form submit
                    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
                        header('Location: daftar-laporan.php');
                        exit();
                    }
                } else {
                    $error = 'Gagal menyimpan laporan: ' . $stmt->error;
                    $debug .= "Query failed: " . $stmt->error . "\n";
                    // rollback file if DB failed
                    if (!empty($uploadPath)) {
                        $fs = __DIR__ . '/' . $uploadPath;
                        if (file_exists($fs)) {
                            @unlink($fs);
                            $debug .= "Removed uploaded file due to DB error\n";
                        }
                    }
                }
            }
        }
    }

// If request is AJAX (fetch), return JSON and exit early
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json; charset=utf-8');
    $resp = array(
        'success' => empty($error) && !empty($success),
        'message' => $success ?: '',
        'error' => $error ?: null,
        'debug' => $debug ?: null,
    );
    echo json_encode($resp);
    exit();
}

    ?>

    <?php include '../navbar.php'; ?>

    <!-- Section Form Unggah -->
    <section id="form-unggah" style="background-image: url('../img/bg-unggah.jpg');">
        <div class="form-wrapper">
            <div class="back-button-container">
                <a href="daftar-laporan.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
            <h1 class="form-title">Unggah Aksi Sahabat Bumi</h1>
            
            <!-- Debug Information (Remove after testing) -->
            <?php if (!empty($debug)): ?>
                <div style="background-color: #f0f0f0; color: #333; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #999; font-family: monospace; font-size: 12px; white-space: pre-wrap; word-break: break-all;">
                    <strong>🔧 DEBUG INFO:</strong><br><?php echo htmlspecialchars($debug); ?>
                </div>
            <?php endif; ?>
            
            <!-- Error/Success Message -->
            <?php if (!empty($error)): ?>
                <div style="background-color: #fee; color: #c33; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fcc;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div style="background-color: #efe; color: #3c3; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #cfc;">
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?> Redirecting...
                </div>
            <?php endif; ?>
            
            <form id="unggahForm" method="POST" enctype="multipart/form-data">
                <div class="form-layout">
                    <!-- Kolom Kiri -->
                    <div class="form-column-left">
                        <!-- Judul Aksi -->
                        <div class="form-group">
                            <label for="judul">Judul Aksi</label>
                            <input type="text" id="judul" name="judul" placeholder="Judul..." required>
                        </div>

                        <!-- Kategori -->
                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <select id="kategori" name="kategori" required>
                                <option value="" disabled selected>Pilih...</option>
                                <option value="Penanaman Pohon">Penanaman Pohon</option>
                                <option value="Pembersihan Lingkungan">Pembersihan Lingkungan</option>
                                <option value="Daur Ulang">Daur Ulang</option>
                                <option value="Edukasi Lingkungan">Edukasi Lingkungan</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group form-group-deskripsi">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" placeholder="Deskripsikan aksimu..." required></textarea>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="form-column-right">
                        <div class="upload-section">
                            <div class="upload-wrapper">
                                <label class="upload-label">Unggah Foto Bukti Aksi</label>
                                
                                <div class="upload-area" id="uploadArea">
                                    <div class="upload-content" id="uploadContent">
                                        <i class="fa-solid fa-camera"></i>
                                        <p class="upload-text">Klik atau Seret File di Sini</p>
                                        <p class="upload-subtext">Upload 1 Foto. Pastikan foto jelas.</p>
                                    </div>
                                    <div class="preview-container" id="previewContainer"></div>
                                    <!-- Invisible file input -->
                                    <input type="file" id="fileInput" name="photos" accept="image/*" style="display: none;" />
                                </div>

                            </div>

                            <button type="submit" class="submit-btn">Unggah</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script src="../js/script_unggah.js"></script>
    <script src="../js/footer.js"></script>
</body>
</html>
