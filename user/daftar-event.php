<?php
// Enable error display for debugging — remove or disable in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Buat Kamu - Sahabat Bumi</title>
    <link rel="stylesheet" href="../css/style_daftar-event.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php
    if (session_status() == PHP_SESSION_NONE) session_start();
    include '../navbar.php';
    ?>

    <!-- Section Cari Event -->
    <?php
    include '../config.php';
    
    // Get all events from database ordered by tanggal_mulai DESC
    $query = "SELECT id, nama_aksi, deskripsi, kategori, tanggal_mulai, tanggal_selesai, tanggal_mulai_jam, tanggal_selesai_jam, lokasi, image_path FROM aksi WHERE tanggal_mulai IS NOT NULL ORDER BY tanggal_mulai DESC";
    $result = $conn->query($query);
    $events = [];
    
    if (!$result) {
        die("Error executing query: " . $conn->error);
    }
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    ?>
    <section id="cari-event" style="background-image: url('../img/bg-daftar event.jpg');">
        <div class="search-container">
            <h1 class="search-title">Event Buat Kamu</h1>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Cari Event...">
                <button class="search-btn" id="btnSearch">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Section Daftar Event -->
    <section id="daftar-event">
        <div class="event-header">
            <h2>EVENT YANG AKAN DATANG</h2>
            <h4>Temukan aksi hijau yang menginspirasi dan jadilah bagian dari perubahan bersama.</h4>
        </div>

        <div class="event-cards-container">
            <?php if (count($events) > 0): ?>
                <?php foreach ($events as $event): 
                    // Safely build DateTime objects only if dates exist
                    if (!empty($event['tanggal_mulai'])) {
                        $date = new DateTime($event['tanggal_mulai']);
                    } else {
                        $date = null;
                    }
                    if (!empty($event['tanggal_selesai'])) {
                        $endDate = new DateTime($event['tanggal_selesai']);
                    } else {
                        $endDate = $date;
                    }
                    
                    // Handle image path correctly
                    $image = '../img/placeholder-event.jpg';
                    if (!empty($event['image_path'])) {
                        $imagePath = '../' . $event['image_path'];
                        if (file_exists($imagePath)) {
                            $image = $imagePath;
                        }
                    }
                    
                    $startTime = !empty($event['tanggal_mulai_jam']) ? substr($event['tanggal_mulai_jam'], 0, 5) : '08:00';
                    $endTime = !empty($event['tanggal_selesai_jam']) ? substr($event['tanggal_selesai_jam'], 0, 5) : '12:00';
                ?>
                <div class="event-card">
                    <div class="card-image">
                        <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($event['nama_aksi']); ?>">
                    </div>
                       <a class="btn-daftar" href="form-pendaftaran-aksi.php?aksi_id=<?php echo $event['id']; ?>">Daftar Sekarang</a>
                    <div class="card-content">
                        <div class="date-box">
                            <div class="date-day"><?php echo $date ? $date->format('d') . ($endDate ? ('-' . $endDate->format('d')) : '') : '-'; ?></div>
                            <div class="date-month"><?php echo $date ? $date->format('F') : '-'; ?></div>
                            <div class="date-year"><?php echo $date ? $date->format('Y') : '-'; ?></div>
                        </div>
                        <div class="event-info">
                            <h3 class="event-title"><?php echo htmlspecialchars($event['nama_aksi']); ?></h3>
                            <div class="event-detail">
                                <i class="fa-regular fa-clock"></i>
                                <span><?php echo $startTime; ?> WIB - <?php echo $endTime; ?> WIB</span>
                            </div>
                            <div class="event-detail">
                                <i class="fa-solid fa-location-dot"></i>
                                <span><?php echo htmlspecialchars($event['lokasi'] ?? '-'); ?></span>
                            </div>
                            <p class="event-description">
                                <?php echo htmlspecialchars(substr($event['deskripsi'] ?? '', 0, 200)); ?>...
                            </p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding:px; width: 100%;">
                    <p style="font-size: 18px; color: #666;">Belum ada event yang tersedia. Tunggu event terbaru dari Sahabat Bumi!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <button class="page-btn" id="prevPage">&lt;</button>
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn" id="nextPage">&gt;</button>
        </div>
    </section>

    <?php include '../footer.php'; ?>

    <script src="../js/script_daftar-event.js"></script>
    <script src="../js/footer.js"></script>
    <link rel="stylesheet" href="../css/kontak.css">
</body>
</html>
