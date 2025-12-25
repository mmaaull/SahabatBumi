<?php
// Form handler untuk pendaftaran event
if (session_status() == PHP_SESSION_NONE) session_start();
include '../config.php';

$success_msg = '';
$error_msg = '';
$aksi_id = intval($_GET['aksi_id'] ?? 0);

// Redirect jika aksi_id tidak valid
if ($aksi_id <= 0) {
    header('Location: daftar-event.php');
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $jenis = trim($_POST['jenis_kelamin'] ?? '');
    $umur = intval($_POST['umur'] ?? 0);
    $instansi = trim($_POST['instansi'] ?? '');
    $user_id = $_SESSION['user_id'];

    if ($nama === '' || $jenis === '' || $umur <= 0 || $instansi === '') {
        $error_msg = 'Semua field harus diisi dengan benar.';
    } else {
        // Cek apakah user sudah terdaftar untuk aksi ini
        $check_query = "SELECT id FROM pendaftaran_aksi WHERE aksi_id = ? AND user_id = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("ii", $aksi_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_msg = 'Anda sudah terdaftar untuk aksi ini!';
        } else {
            // Insert ke database
            $insert_query = "INSERT INTO pendaftaran_aksi (aksi_id, user_id, nama, jenis_kelamin, umur, asal_instansi) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("iissss", $aksi_id, $user_id, $nama, $jenis, $umur, $instansi);

            if ($stmt->execute()) {
                $success_msg = 'Pendaftaran berhasil! Anda sekarang terdaftar untuk aksi ini.';
            } else {
                $error_msg = 'Gagal menyimpan pendaftaran: ' . $conn->error;
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Event</title>

    <!-- Font Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/style-form.css">
</head>
<body>

    <!-- CONTAINER UTAMA -->
    <section id="daftar-event-page">

        <!-- HEADER -->
        <div id="daftar-event-header">
            <h1>Daftar Event</h1>
            <p>Lengkapi data untuk mengikuti kegiatan ini!</p>
        </div>

        <!-- FORM -->
        <div id="formpendaftaranevent">
            <a href="daftar-event.php" class="back-button">← Kembali ke Event</a>
            
            <h3>Formulir Pendaftaran</h3>

            <?php 
            // Ambil info aksi
            $aksi_query = "SELECT nama_aksi, tanggal_mulai, lokasi FROM aksi WHERE id = ?";
            $stmt = $conn->prepare($aksi_query);
            $stmt->bind_param("i", $aksi_id);
            $stmt->execute();
            $aksi_result = $stmt->get_result();
            
            if ($aksi_result->num_rows > 0) {
                $aksi = $aksi_result->fetch_assoc();
                $tgl = date('d F Y', strtotime($aksi['tanggal_mulai']));
            } else {
                echo '<div class="alert alert-error">Event tidak ditemukan!</div>';
                exit();
            }
            $stmt->close();
            ?>

            <div class="aksi-info">
                <p><span class="aksi-name"><?php echo htmlspecialchars($aksi['nama_aksi']); ?></span></p>
                <p><strong>Tanggal:</strong> <?php echo $tgl; ?></p>
                <p><strong>Lokasi:</strong> <?php echo htmlspecialchars($aksi['lokasi'] ?? '-'); ?></p>
            </div>

            <?php if ($error_msg): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error_msg); ?></div>
            <?php endif; ?>
            <?php if ($success_msg): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success_msg); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukkan Nama Lengkap..." required>

                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" required>
                    <option value="" disabled selected>Pilih...</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                    <option value="Lainnya">Lainnya</option>
                </select>

                <label>Umur</label>
                <input type="number" name="umur" placeholder="Masukkan Umur..." min="1" required>

                <label>Asal Instansi</label>
                <input type="text" name="instansi" placeholder="Masukkan Asal Instansi..." required>

                <button type="submit">Daftar Event</button>
            </form>

        </div>

    </section>

<script src="../js/script_daftar-event.js"></script>
</body>
</html>
