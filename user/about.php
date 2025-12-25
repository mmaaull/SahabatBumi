<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Sahabat Bumi</title>
    <link rel="stylesheet" href="../css/style_about.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php
    if (session_status() == PHP_SESSION_NONE) session_start();
    include '../navbar.php';
    ?>

    <!-- Section 1: Definisi -->
    <section id="definisi">
        <div class="definisi-container">
            <div class="definisi-image">
                <img src="../img/about-01.jpg" alt="Tanaman Tumbuh" class="img-plant">
                <img src="../img/about-02.jpg" alt="Logo Sahabat Bumi" class="img-logo-card">
            </div>
            <div class="definisi-content">
                <h1>Apa Itu Sahabat Bumi?</h1>
                <p class="desc-main">
                    Sahabat Bumi adalah platform komunitas yang membantu kamu ikut terlibat dalam aksi nyata untuk lingkungan.
                </p>
                <p class="desc-detail">
                    Mulai dari penanaman, bersih-bersih, daur ulang, sampai edukasi semua bisa kamu lakukan dalam satu tempat yang aman dan mudah digunakan. Kami percaya setiap aksi kecil punya arti besar. Karena itu, setiap kegiatan yang kamu lakukan akan dicatat dan ditampilkan sebagai kontribusi nyata untuk bumi.
                </p>
            </div>
        </div>
    </section>

    <!-- Section 2: Visi dan Misi -->
    <section id="visi-misi">
        <div class="visi-misi-overlay">
            <h2>Visi dan Misi</h2>
            <p class="visi-misi-desc">
                Mendorong terciptanya ekosistem digital yang mampu mempertemukan semangat peduli lingkungan dengan aksi nyata yang terukur. Tujuannya adalah membangun generasi yang lebih sadar, sigap, dan terlibat dalam menjaga keberlanjutan bumi melalui kolaborasi, inovasi, dan kegiatan yang mudah diakses oleh semua kalangan.
            </p>
            
            <div class="misi-cards">
                <div class="misi-card">
                    <div class="misi-number">1</div>
                    <p>Memudahkan semua orang terlibat dalam aksi lingkungan yang aman, dekat, dan menyenangkan.</p>
                </div>
                <div class="misi-card">
                    <div class="misi-number">2</div>
                    <p>Menyajikan dampak yang transparan dan informatif agar setiap partisipasi memiliki nilai yang jelas.</p>
                </div>
                <div class="misi-card">
                    <div class="misi-number">3</div>
                    <p>Membangun ruang kolaborasi yang menghubungkan individu, komunitas, dan organisasi untuk saling menginspirasi dan bergerak bersama.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Tim Pengembang -->
    <section id="tim-pengembang">
        <h2>Tim Pengembang</h2>
        <p class="tim-desc">
            Dikembangkan oleh tim kecil beranggotakan tiga mahasiswa, kami menggabungkan keahlian di bidang pengembangan, desain, dan lingkungan untuk menghadirkan pengalaman yang mudah dipakai dan berdampak.
        </p>
        
        <div class="tim-cards">
            <div class="tim-card">
                <div class="tim-photo">
                    <img src="../img/aftita.jpg" alt="Aftita Choirunnisa">
                </div>
                <h3>Aftita<br>Choirunnisa</h3>
            </div>
            <div class="tim-card">
                <div class="tim-photo">
                    <img src="../img/faiz.jpg" alt="Faiz Maulana">
                </div>
                <h3>Faiz<br>Maulana</h3>
            </div>
            <div class="tim-card">
                <div class="tim-photo">
                    <img src="../img/octa.jpg" alt="Octavia Habeahan">
                </div>
                <h3>Octavia<br>Habeahan</h3>
            </div>
        </div>
    </section>

    <?php include '../footer.php'; ?>

    <script src="../js/script_about.js"></script>
    <script src="../js/footer.js"></script>
</body>
</html>
