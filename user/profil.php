<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahabat Bumi</title>
    <link rel="stylesheet" href="../css/style_profil.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

<!-- Welcome Section -->
    <section id="welcome" class="welcome-section">
        <div class="welcome-overlay"></div>
        <div class="welcome-content">
            <div class="welcome-text">
                <h1>Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!!</h1>
                <p>Selamat Datang di Sahabat Bumi</p>
                <p>Bumi masih memunggu kontribusi terbaikmu darmu. Aksimu mungkin seserhana, tapi efeknya luar biasa.</p>
                <p>Jyo kita lestarikan masa depan bumi ini bersama-sama.</p>
                <a href="#gerakan-hijau">
                    <button class="btn-action">
                    Aksi Hijau Kamu <i class="fas fa-play"></i>
                    </button>
                </a>
            </div>
        </div>
        
        <!-- Stats Section -->
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-info">
                    <h3>15K+</h3>
                    <p>Poin</p>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-tree"></i>
                </div>
                <div class="stat-info">
                    <h3>20</h3>
                    <p>Aksi Selesai</p>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-info">
                    <h3>5</h3>
                    <p>Peringkat</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Profile Section -->
    <section id="profil" class="profile-section">
        <div class="profile-container">
                <div class="profile-image">
                <img src="../img/foto profil.jpg" alt="Eco Profile">
            </div>
            <div class="profile-content">
                <h2>Profil Eco Kamu</h2>
                <p>Lencolu ini untuk lalu anggota dari Sahabat Bumi! Misi Octavia A Binti menghubungi dan melewati Instag@mail.com dan akosirp, untuk tidak rendahan Nyas. Apa teman kamu untuk qua alin hijau, panas ini untuk bumi.</p>
                <a href="daftar-laporan.php"><button class="btn-profile-action">Unggah Aksi Anda</button></a>
                <div class="profile-share">
                    <i class="fas fa-share-alt"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Gerakan Hijau Section -->
    <section id="gerakan-hijau" class="gerakan-section">
        <div class="gerakan-header">
            <h2>Gerakan Hijau<br>Kamu</h2>
        </div>
        
        <div class="gerakan-cards">
            <div class="gerakan-card">
                <div class="card-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3>Aksi Aktif</h3>
                <p>Masih ada 5 misi yang menunggu untuk kamu selesaikan</p>
            </div>
            
            <div class="gerakan-card">
                <div class="card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>Aksi Selesai</h3>
                <p>Kamu telah menyelesaikan 20 misi untuk Bumi Misi Octor jadi</p>
            </div>
            
            <div class="gerakan-card">
                <div class="card-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Aksi Favorit</h3>
                <p>Yanna paling sering dilaksanakan tanaman dia menanam aksi</p>
            </div>
            
            <div class="gerakan-card">
                <div class="card-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Streak Harian</h3>
                <p>Kamu menjaga konsistensi yang luar biasa dengan</p>
            </div>
        </div>

        <!-- Environmental Impact -->
        <div class="impact-section">
            <h2>Jejak Lingkunganmu</h2>
            
            <div class="impact-container">
                <div class="impact-item">
                    <i class="fas fa-recycle"></i>
                    <h3>108 kg Sampah Terkumpul</h3>
                    <p>Atas kontribusi kamu, sampah sebanyak ini berhasil dikelola dan tidak berakhir di tempat pembuangan akhir, dimana dapat mengurangi sampah tersebut. Juga dapat di Ayuni secara daur ulang dan tidak membebani lingkungan. Semangat terus dan lanjutkan aksimu tetap bemis.</p>
                </div>
                
                <div class="impact-center">
                    <div class="co2-circle">
                        <i class="fas fa-industry"></i>
                        <i class="fas fa-globe"></i>
                        <i class="fas fa-wind"></i>
                        <i class="fas fa-seedling"></i>
                        <i class="fas fa-bolt"></i>
                        <i class="fas fa-recycle"></i>
                        <div class="co2-text">
                            <span>CO₂</span>
                        </div>
                    </div>
                </div>
                
                <div class="impact-item">
                    <i class="fas fa-tree"></i>
                    <h3>38 Pohon Terselamatkan</h3>
                    <p>Dengan mengurangi kertas yang kamu gunakan dengan beralih ke digital, kamu telah berkontribusi dalam mengurangi jumlah pohon yang ditebang. Hutan adalah paru-paru dunia lanjutkan aksi ini.</p>
                </div>
            </div>

            <div class="carbon-info">
                <i class="fas fa-leaf"></i>
                <h3>2.350 Kg Karbon Berkurang (CO₂)</h3>
                <p>Dengan aksi hijau kamu, emisi karbon berkurang setara dengan 3 tahun yang dihasilkan 1 manusia. Ini berarti kamu ikut menurunkan Gas Rumah Kaca dalam mengatasi krisis iklim Dunia yang berdampak pada pemanasan global. Ini luar biasa, terus lanjutkan aksimu sekarang ini lagi.</p>
            </div>
        </div>
    </section>

    <!-- Peringkat Section -->
    <section id="peringkat" class="peringkat-section">
        <h2>Peringkat Eco</h2>
        
        <div class="peringkat-container">
            <div class="podium">
                <div class="podium-item second">
                    <div class="podium-number">2</div>
                </div>
                <div class="podium-item first">
                    <div class="podium-number">1</div>
                </div>
                <div class="podium-item third">
                    <div class="podium-number">3</div>
                </div>
            </div>
            
            <div class="leaderboard">
                <div class="leaderboard-item rank-1">
                    <div class="rank">1</div>
                    <div class="user-info">
                        <div class="user-icon" aria-hidden="true"><i class="fas fa-user"></i></div>
                        <span>Samuel</span>
                    </div>
                    <div class="points">
                        <i class="fas fa-star"></i>
                        <span>14.7k+ Poin</span>
                    </div>
                </div>
                
                <div class="leaderboard-item rank-2">
                    <div class="rank">2</div>
                    <div class="user-info">
                        <div class="user-icon" aria-hidden="true"><i class="fas fa-user"></i></div>
                        <span>Octavia</span>
                    </div>
                    <div class="points">
                        <i class="fas fa-star"></i>
                        <span>14.3k+ Poin</span>
                    </div>
                </div>
                
                <div class="leaderboard-item rank-3">
                    <div class="rank">3</div>
                    <div class="user-info">
                        <div class="user-icon" aria-hidden="true"><i class="fas fa-user"></i></div>
                        <span>Attila</span>
                    </div>
                    <div class="points">
                        <i class="fas fa-star"></i>
                        <span>13k+ Poin</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include '../footer.php'; ?>

    <script src="../js/script_profil.js"></script>
    <script src="../js/footer.js"></script>
</body>
</html>
