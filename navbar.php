<!-- Navbar section -->
<nav id="navbar" class="navbar">
    <div class="container">
        <div class="nav-wrapper">
            <div class="logo">
                <img src="/SahabatBumi/img/logo.png" alt="Sahabat Bumi Logo">
                <span>Sahabat Bumi</span>
            </div>
            <ul class="nav-menu">
                <li><a href="/SahabatBumi/user/homepage.php" class="nav-link">BERANDA</a></li>
                <li><a href="/SahabatBumi/user/daftar-event.php" class="nav-link">EVENT</a></li>
                <li><a href="/SahabatBumi/user/about.php" class="nav-link">ABOUT</a></li>
                <li><a href="/SahabatBumi/user/kontak.php" class="nav-link">CONTACT</a></li>
            </ul>
            <div class="nav-buttons">
                <?php
                if (isset($_SESSION['user_id'])) {
                    // User is logged in
                    echo '<div class="user-menu">';
                    echo '<a href="/SahabatBumi/user/profil.php" class="user-name">' . htmlspecialchars($_SESSION['user_name']) . '</a>';
                    echo '<a href="/SahabatBumi/logout.php"><button class="btn-logout">LOGOUT</button></a>';
                    echo '</div>';
                } else {
                    // User is not logged in
                    echo '<a href="/SahabatBumi/login.php"><button class="btn-masuk">MASUK</button></a>';
                    echo '<a href="/SahabatBumi/sign-up.php"><button class="btn-keluar">DAFTAR</button></a>';
                }
                ?>
            </div>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
</nav>

<script>
// Set active nav link berdasarkan halaman yang dibuka
(function() {
    // Dapatkan nama file halaman saat ini
    const currentPage = window.location.pathname.split('/').pop() || 'homepage.php';
    
    // Dapatkan semua nav links
    const navLinks = document.querySelectorAll('.nav-menu .nav-link');
    
    // Hapus class active dari semua links
    navLinks.forEach(link => link.classList.remove('active'));
    
    // Tambahkan class active ke link yang sesuai (bandingkan basename)
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        const hrefPage = href.split('/').pop();
        if (hrefPage === currentPage) {
            link.classList.add('active');
        }
        // Handle root/index mapping
        if (currentPage === '' || currentPage === '/' || currentPage === 'index.php') {
            if (hrefPage === 'homepage.php') {
                link.classList.add('active');
            }
        }
    });
})();
</script>
