<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahabat Bumi - Kontak</title>
    <link rel="stylesheet" href="../css/style_kontak.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php
    if (session_status() == PHP_SESSION_NONE) session_start();
    include '../navbar.php';
    ?>
    
    <!-- Section Hubungi Sahabat Bumi -->
    <section id="hubungi">
        <div class="hubungi-container">
                <div class="hubungi-image">
                <img src="../img/kontak-01.jpg" alt="Tree with roots">
            </div>
            <div class="hubungi-content">
                <h1>Hubungi Sahabat Bumi</h1>
                <p class="hubungi-desc">
                    Kami saluran terbaik untuk pertanyaan, saran, kerjasama atau dukungan apa pun yang ingin kamu berikan.
                </p>
                <p class="hubungi-desc">
                    Kirim pesanmu, dan tim kami akan segera menghubungimu.
                </p>
                <p class="hubungi-desc">
                    Kamu bisa menghubungi kami melalui informasi berikut:
                </p>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope contact-icon"></i>
                        <span>sahabatbumi@gmail.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone-alt contact-icon"></i>
                        <span>+62 895777555</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Form -->
    <section id="form">
        <div class="form-overlay">
            <div class="form-container">
                <h2>Kirim Pesan</h2>
                <p class="form-subtitle">Ada pertanyaan atau masukan? Tinggalkan pesan mu di bawah.</p>
                
                <form id="contactForm">
                    <div class="form-row">
                        <div class="form-column-left">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" placeholder="Masukan Email..." required>
                            </div>
                            
                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" id="nama" name="nama" placeholder="Masukan Nama Lengkap..." required>
                            </div>
                        </div>
                        
                        <div class="form-column-right">
                            <div class="form-group">
                                <label for="pesan">Pertanyaan atau Review</label>
                                <textarea id="pesan" name="pesan" rows="5" placeholder="Masukan Pertanyaan atau Review..." required></textarea>
                            </div>
                            
                            <button type="submit" class="submit-btn">Kirim</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Section CTA -->
    <section id="tim-cta">
        <div class="cta-container">
            <h2>Yuk Mulai Berbuat Baik untuk Bumi</h2>
            <p>Ada banyak aksi yang menunggu kamu, Pilih satu, lakukan, dan beri dampak nyata.</p>
            <a href="daftar-event.php"><button class="cta-btn">Temukan Aksi</button></a>
        </div>
    </section>

    <?php include '../footer.php'; ?>

    <script src="../js/script_kontak.js"></script>
    <script src="../js/footer.js"></script>
</body>
</html>
