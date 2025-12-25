<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahabat Bumi</title>
    <link rel="stylesheet" href="../css/style_homepage.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/footer.css">
</head>
<body>

    <?php
    if (session_status() == PHP_SESSION_NONE) session_start();
    include '../navbar.php';
    ?>

    <!-- Hero Section -->
    <section id="homepage" class="hero">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        Satu Platform<br>
                        Jutaan Aksi Kebaikan<br>
                        untuk Bumi.
    
                    </h1>
                    <p class="hero-description">
                        Temukan aksi hijau yang menginspirasi dan jadilah bagian dari perubahan bersama SahabatBumi. Jelajahi kegiatan, bergabung sebagai relawan, dan abadikan setiap langkah kebaikan Anda. Mari ciptakan dampak besar bagi bumi melalui aksi sederhana yang Anda mulai hari ini.
                    </p>
                    
                    <div class="stats">
                        <div class="stat-item">
                            <div class="stat-number">10K+</div>
                            <div class="stat-label">Anggota Aktif</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">50+</div>
                            <div class="stat-label">Aksi Terlaksana</div>
                        </div>
                    </div>
                    <a href="daftar-event.php"><button class="btn-cta">Gabung Aksi Terdekat Sekarang</button></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Index section (bawah) -->
    <div id="indexpage">
        <div class="container">
            <div class="header">
                <h1>Kenapa Pilih Sahabat Bumi?</h1>
                <p class="subtitle">Karena setiap aksi kecil bisa menghasilkan dampak besar ketika dilakukan bersama.</p>
            </div>

            <div class="features-wrapper">
                <!-- Baris pertama: 3 cards -->
                <div class="row-top">
                    <div class="feature-card">
                        <div class="icon">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#0F2A1D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22c1.68 0 3.1-.66 3.99-1.68"/>
                                <path d="M3.88 13.35C2.74 12.19 2 10.65 2 8.92c0-4.37 3.58-7.92 8-7.92 4.42 0 8 3.55 8 7.92 0 1.73-.74 3.27-1.88 4.43"/>
                                <path d="M7.5 14.5 10 22l2-3 2 3 2.5-7.5"/>
                            </svg>
                        </div>
                        <h3>Aksi Mudah & Lengkap</h3>
                        <p>Semua kegiatan lingkungan penanaman, bersih-bersih, daur ulang, hingga edukasi tersedia dalam<br>satu platform yang praktis dan ramah pengguna.</p>
                    </div>

                    <div class="divider-vertical"></div>

                    <div class="feature-card">
                        <div class="icon">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#0F2A1D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"/>
                                <rect x="14" y="3" width="7" height="7"/>
                                <rect x="14" y="14" width="7" height="7"/>
                                <rect x="3" y="14" width="7" height="7"/>
                            </svg>
                        </div>
                        <h3>Dampak Nyata Terukur</h3>
                        <p>Setiap aksi yang kamu lakukan dicatat dalam statistik, poin, dan pencapaian, sehingga kamu bisa melihat kontribusi nyatamu bagi bumi.</p>
                    </div>

                    <div class="divider-vertical"></div>

                    <div class="feature-card">
                        <div class="icon">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#0F2A1D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <h3>Komunitas Peduli Bumi</h3>
                        <p>Bergabung dengan komunitas besar yang aktif, positif, dan menginspirasi, sehingga kamu tidak pernah bergerak sendirian.</p>
                    </div>
                </div>

                <!-- Baris kedua: 2 cards di tengah -->
                <div class="row-bottom">
                    <div class="feature-card">
                        <div class="icon">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#0F2A1D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="M9 12l2 2 4-4"/>
                            </svg>
                        </div>
                        <h3>Event Terverifikasi</h3>
                        <p>Setiap event dan aksi sudah dikurasi oleh organisasi dan komunitas terpercaya, sehingga aman untuk diikuti.</p>
                    </div>

                    <div class="divider-vertical"></div>

                    <div class="feature-card">
                        <div class="icon">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#0F2A1D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="7" width="20" height="15" rx="2" ry="2"/>
                                <polyline points="17,2 12,7 7,2"/>
                            </svg>
                        </div>
                        <h3>Bebas Biaya</h3>
                        <p>Tidak ada biaya pendaftaran atau akses layanan. Kamu bisa mulai kapan saja tanpa hambatan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik section -->
    <div id="statistik">
        <div class="container">
            <div class="stats-grid">
                <!-- Card 1: Kategori Aksi -->
                <div class="stat-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="stat-number">4</div>
                    <div class="stat-label">Kategori Aksi</div>
                </div>

                <!-- Card 2: Pohon Tertanam -->
                <div class="stat-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-tree"></i>
                    </div>
                    <div class="stat-number">10.230</div>
                    <div class="stat-label">Pohon Tertanam</div>
                </div>

                <!-- Card 3: Sampah Berkurang -->
                <div class="stat-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <div class="stat-number">100 Ton</div>
                    <div class="stat-label">Sampah Berkurang</div>
                </div>

                <!-- Card 4: Lokakarya Energi -->
                <div class="stat-card">
                    <div class="icon-wrapper">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">205</div>
                    <div class="stat-label">Lokakarya Energi</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Galeri -->
    <div id="galeri">
        <div class="container">
            <header>
                <div class="header-line"></div>
                <h1>Galeri Sahabat Bumi</h1>
            </header>

            <main class="gallery">
                <div class="gallery-item large">
                    <img src="../img/galeri-01.jpg" alt="Penanaman pohon">
                </div>

                <div class="gallery-item medium">
                    <img src="../img/galeri-02.jpg" alt="Aksi peduli lingkungan">
                </div>

                <div class="gallery-item medium">
                    <img src="../img/galeri-03.jpg" alt="Gotong royong menanam">
                </div>

                <div class="gallery-item medium">
                    <img src="../img/galeri-04.jpg" alt="Penanaman di lapangan">
                </div>

                <div class="gallery-item small">
                    <img src="../img/galeri-05.jpg" alt="Diskusi lingkungan">
                </div>
            </main>
        </div>
    </div>
    
    <!-- Testimonial & Inspirasi (digabung dari lanjutan-homepage.html) -->
    <section id="komen">
        <div class="container">
            <h1 class="section-title">Bergabunglah dengan ribuan orang yang sudah menjadi bagian dari gerakan ini.<br>Berikut cerita pengalaman mereka.</h1>

            <div class="testimonial-grid">
                <!-- Testimonial 1 - Feiz -->
                <div class="testimonial-card">
                    <div class="quote-icon">❝</div>
                    <p class="testimonial-text">
                        Pertama ikut aksi bersih-bersih sungai bareng platform ini, nggak nyangka bisa seru banget! Dari yang cuma buang sampah sembarangan, sekarang tiap minggu saya ajak temen-temen kampus bukit ikutan. Bumi kita ternyata bisa kita selamatin bareng-bareng!
                    </p>
                    <div class="stars">
                        <span>⭐</span>
                        <span>⭐</span>
                        <span>⭐</span>
                        <span>⭐</span>
                        <span>⭐</span>
                    </div>
                    <div class="user-info">
                        <div class="user-avatar">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                                <circle cx="20" cy="20" r="20" fill="#6B8E6F"/>
                                <circle cx="20" cy="15" r="7" fill="#E3EED4"/>
                                <path d="M8 35C8 28 13 23 20 23C27 23 32 28 32 35" fill="#E3EED4"/>
                            </svg>
                        </div>
                        <div class="user-details">
                            <p class="user-name">Faiz</p>
                            <p class="user-role">Member</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 - Afifita -->
                <div class="testimonial-card">
                    <div class="quote-icon">❝</div>
                    <p class="testimonial-text">
                        Saya ikut tanam 200 pohon manggis sama anak-anak muda di sini. Mereka bawa bibit, saya kasih lahan. Sekarang tiap lewat kebun, seneng banget lihat pohonnya udah mulai berbuat. Ternyata itu bikin hati tenang.
                    </p>
                    <div class="stars">
                        <span>⭐</span>
                        <span>⭐</span>
                        <span>⭐</span>
                        <span>⭐</span>
                        <span>⭐</span>
                    </div>
                    <div class="user-info">
                        <div class="user-avatar">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                                <circle cx="20" cy="20" r="20" fill="#6B8E6F"/>
                                <circle cx="20" cy="15" r="7" fill="#E3EED4"/>
                                <path d="M8 35C8 28 13 23 20 23C27 23 32 28 32 35" fill="#E3EED4"/>
                            </svg>
                        </div>
                        <div class="user-details">
                            <p class="user-name">Aftita</p>
                            <p class="user-role">Member</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 - Andreas -->
                <div class="testimonial-card">
                    <div class="quote-icon">❝</div>
                    <p class="testimonial-text">
                        Dulu langsung mau mulai dari mana buat kurangi plastik. Setelah ikut tantangan 7 Hari Tanpa Plastik lewat aplikasi ini, sekarang saya bawa tas belanja lipat kemana-mana. Anak saya juga ikutan, bangga banget jadi contoh buat dia.
                    </p>
                    <div class="stars">
                        <span>⭐</span>
                        <span>⭐</span>
                        <span>⭐</span>
                        <span>⭐</span>
                        <span>⭐</span>
                    </div>
                    <div class="user-info">
                        <div class="user-avatar">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                                <circle cx="20" cy="20" r="20" fill="#6B8E6F"/>
                                <circle cx="20" cy="15" r="7" fill="#E3EED4"/>
                                <path d="M8 35C8 28 13 23 20 23C27 23 32 28 32 35" fill="#E3EED4"/>
                            </svg>
                        </div>
                        <div class="user-details">
                            <p class="user-name">Andreas</p>
                            <p class="user-role">Member</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 4 - Cyntia -->
                <div class="testimonial-card">
                    <div class="quote-icon">❝</div>
                    <p class="testimonial-text">
                        Saya sudah ikut 27 aksi di 5 kota lewat platform ini. Yang paling bikin nangis itu waktu anak-anak lihat anak kecil ikut tanam pohon sambil bilang 'Nanti aku mau ajak temen-temenku juga'. Biar bumi nggak panas lagi!' Itu artinya kita berhasil banget menularkannya semangata.
                    </p>
                    <div class="stars">
                        <span>⭐</span>
                        <span>⭐</span>
                        <span>⭐</span>
                        <span>⭐</span>
                        <span>⭐</span>
                    </div>
                    <div class="user-info">
                        <div class="user-avatar">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                                <circle cx="20" cy="20" r="20" fill="#6B8E6F"/>
                                <circle cx="20" cy="15" r="7" fill="#E3EED4"/>
                                <path d="M8 35C8 28 13 23 20 23C27 23 32 28 32 35" fill="#E3EED4"/>
                            </svg>
                        </div>
                        <div class="user-details">
                            <p class="user-name">Cyntia</p>
                            <p class="user-role">Member</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Inspirasi Hijau -->
    <section id="inspirasi">
        <div class="container">
            <h2 class="inspirasi-title">Inspirasi Hijau</h2>
            
            <div class="inspirasi-grid">
                <!-- Article 1 - Featured Large -->
                <div class="inspirasi-item featured">
                    <img src="../img/inpirasi-01.jpg" alt="Mengapa Menjaga Lingkungan Itu Penting" class="inspirasi-image">
                    <div class="inspirasi-content">
                        <h3 class="inspirasi-heading">Mengapa Menjaga Lingkungan Itu Penting untuk Masa Depan Kita?</h3>
                        <p class="inspirasi-excerpt">
                            Lingkungan hidup adalah rumah bagi seluruh makhluk di bumi — termasuk kita. Namun, setiap hari, kerusakan alam terus meningkat akibat sampah, polusi udara, dan menurunnya kualitas air. Jika tidak ada tindakan nyata, dampaknya akan semakin terasa dalam kehidupan sehari-hari...
                        </p>
                        <div class="inspirasi-meta">
                            <span class="meta-date">1 Des 2025 | Jakarta</span>
                            <a href="#" class="meta-link">Baca Lebih Lanjut</a>
                        </div>
                    </div>
                </div>

                <!-- Article 2 -->
                <div class="inspirasi-item">
                    <div class="inspirasi-content-small">
                        <span class="meta-date-small">30 Nov 2025 | Surabaya</span>
                        <h3 class="inspirasi-heading-small">Dampak Positif Menanam Pohon di Sekitar Rumah</h3>
                        <p class="inspirasi-excerpt-small">
                            Menanam pohon bukan hanya membuat lingkungan terlihat lebih hijau dan indah, tetapi juga memberikan banyak manfaat bagi kehidupan sehari-hari...
                        </p>
                        <a href="#" class="meta-link">Baca Lebih Lanjut</a>
                    </div>
                    <img src="../img/inspirasi-02.jpg" alt="Dampak Positif Menanam Pohon" class="inspirasi-image-small">
                </div>

                <!-- Article 3 -->
                <div class="inspirasi-item">
                    <div class="inspirasi-content-small">
                        <span class="meta-date-small">3 Nov 2025 | Semarang</span>
                        <h3 class="inspirasi-heading-small">Cara Sederhana Mengurangi Sampah di Kehidupan Sehari-Hari</h3>
                        <p class="inspirasi-excerpt-small">
                            Sampah adalah salah satu masalah lingkungan terbesar saat ini. Setiap orang menghasilkan sampah, tetapi hanya sedikit yang tahu cara mengurangi...
                        </p>
                        <a href="#" class="meta-link">Baca Lebih Lanjut</a>
                    </div>
                    <img src="../img/inspirasi-03.jpg" alt="Cara Mengurangi Sampah" class="inspirasi-image-small">
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section (digabung dari index.html) -->
    <section id="faq">
        <div class="container">
            <div class="faq-header">
                <h1>FAQ</h1>
                <p class="subtitle">Punya pertanyaan tentang kami, cara berpartisipasi, atau isu lingkungan yang kami fokuskan?</p>
            </div>

            <div class="faq-content">
                <!-- Section: Memulai -->
                <div class="faq-section">
                    <h2 class="section-title">Memulai</h2>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Bagaimana cara saya mendaftar/bergabung dengan SahabatBumi?</span>
                            <span class="icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Untuk bergabung dengan SahabatBumi, Anda dapat mengunjungi halaman pendaftaran di website kami dan mengisi formulir yang tersedia. Setelah mendaftar, Anda akan menerima email konfirmasi dan informasi lebih lanjut tentang program-program yang dapat Anda ikuti.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Apakah ada biaya untuk bergabung dan berpartisipasi?</span>
                            <span class="icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Tidak ada biaya untuk menjadi anggota SahabatBumi. Semua kegiatan dan program kami gratis untuk diikuti. Kami percaya bahwa setiap orang berhak untuk berkontribusi dalam menjaga lingkungan tanpa hambatan finansial.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Bagaimana cara saya menemukan kegiatan lingkungan terdekat?</span>
                            <span class="icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Anda dapat menemukan kegiatan lingkungan terdekat melalui halaman "Kegiatan" di website kami. Tersedia fitur pencarian berdasarkan lokasi, kategori, dan tanggal. Anda juga akan menerima notifikasi email tentang kegiatan di area Anda setelah mendaftar.</p>
                        </div>
                    </div>
                </div>

                <!-- Section: Partisipasi dan Kontribusi -->
                <div class="faq-section">
                    <h2 class="section-title">Partisipasi dan Kontribusi</h2>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Apa bentuk kontribusi yang dapat dilakukan melalui SahabatBumi?</span>
                            <span class="icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>Anda dapat berkontribusi melalui berbagai cara seperti mengikuti kegiatan bersih-bersih lingkungan, program penanaman pohon, edukasi lingkungan, kampanye pengurangan sampah plastik, dan menjadi relawan untuk event-event kami. Setiap kontribusi, sekecil apapun, sangat berarti bagi lingkungan.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            <span>Masalah lingkungan apa saja yang menjadi fokus SahabatBumi?</span>
                            <span class="icon">+</span>
                        </button>
                        <div class="faq-answer">
                            <p>SahabatBumi fokus pada beberapa isu lingkungan utama seperti pengelolaan sampah dan daur ulang, pengurangan penggunaan plastik sekali pakai, konservasi hutan dan penanaman pohon, edukasi lingkungan untuk masyarakat, serta pelestarian keanekaragaman hayati lokal.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include '../footer.php'; ?>

    <script src="../js/script_homepage.js"></script>
    <script src="../js/footer.js"></script>
</body>
</html>
