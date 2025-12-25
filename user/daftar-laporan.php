<?php
// start output buffering so redirects can be sent even if template has HTML
if (session_status() == PHP_SESSION_NONE) ob_start();
if (session_status() == PHP_SESSION_NONE) session_start();
include '../config.php';
// base url
$base_url = '/SahabatBumi/user/';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Lingkungan Aksi Anda</title>
    <link rel="stylesheet" href="../css/style_daftar-laporan.css">
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
    ?>

    <?php include '../navbar.php'; ?>
    <?php

    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT id, title, kategori, description, image_path, status, created_at FROM laporan WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

        <!-- Section Riwayat Aksi -->
    <section id="riwayat-section">
        <!-- Judul Section -->
        <div id="judul">
            <h1>Riwayat<br>Lingkungan Aksi Anda</h1>
            <a href="unggah.php">
                <button class="tambah-btn">
                <span class="icon-plus">+</span>
                Tambah Aksi Baru
                </button>
            </a>
        </div>

        <!-- Card Aksi Container -->
        <div id="card-aksi">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <div class="card-image">
                        <?php if (!empty($row['image_path']) && file_exists(__DIR__ . '/' . $row['image_path'])): ?>
                            <img src="<?php echo $base_url . htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <?php else: ?>
                            <img src="../img/tree-sunset.jpg" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="kategori">Kategori: <?php echo htmlspecialchars($row['kategori']); ?></p>
                        <p class="deskripsi"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                        <div class="card-buttons">
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-edit"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            <a href="daftar-laporan.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Hapus laporan ini?')" class="btn-hapus"><i class="fa-solid fa-trash-can"></i> Hapus</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

<?php
// Handle delete action
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    // Ensure the report belongs to current user
    $check = $conn->prepare('SELECT image_path FROM laporan WHERE id = ? AND user_id = ?');
    $check->bind_param('ii', $del_id, $user_id);
    $check->execute();
    $res = $check->get_result();
        if ($res->num_rows > 0) {
        $r = $res->fetch_assoc();
        if (!empty($r['image_path'])) {
            $fsPath = __DIR__ . '/' . $r['image_path'];
            if (file_exists($fsPath)) unlink($fsPath);
        }
        $del = $conn->prepare('DELETE FROM laporan WHERE id = ? AND user_id = ?');
        $del->bind_param('ii', $del_id, $user_id);
        $del->execute();
    }
    header('Location: daftar-laporan.php');
    exit();
}
?>
    </section>

    <?php include '../footer.php'; ?>

    <script src="../js/script_daftar-laporan.js"></script>
    <script src="../js/footer.js"></script>
</body>
</html>
