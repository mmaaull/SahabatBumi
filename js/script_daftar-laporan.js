// Data aksi (simulasi data)
let aksiData = [
    {
        id: 1,
        judul: "100 Pohon Demi Keberlangsungan Bumi",
        kategori: "Penanaman Pohon",
        deskripsi: "Ikut volunteer menanam pohon untuk menanam taang hijau dan memberi bumi napas baru. Bersama para relawan lain, aku ikut menanam bibit-bibit pohon di area yang membutuhkan tanaman keci yang semakin membawa dampak besar bagi lingkungan.",
        gambar: "img/tree-sunset.jpg"
    }
];

// Pastikan DOM sudah ter-load
document.addEventListener('DOMContentLoaded', () => {

    // Render card dari aksiData (jika ada container untuk cards)
    const cardsContainer = document.querySelector('.cards-container');
    function renderCards(data) {
        if (!cardsContainer) return;
        cardsContainer.innerHTML = '';
        data.forEach((item, index) => {
            const card = document.createElement('article');
            card.className = 'card';
            card.dataset.id = item.id ?? index + 1;
            card.innerHTML = `
                <img src="${item.gambar}" alt="${item.judul}" class="card-img">
                <div class="card-body">
                    <h3>${item.judul}</h3>
                    <p class="kategori">${item.kategori}</p>
                    <p class="deskripsi">${item.deskripsi}</p>
                    <div class="card-actions">
                        <button class="btn-edit" data-id="${item.id}">Edit</button>
                        <button class="btn-hapus" data-id="${item.id}">Hapus</button>
                    </div>
                </div>
            `;
            // set initial styles for fade-in animation
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            cardsContainer.appendChild(card);
        });
        // update cards NodeList reference for other logic
        setupIntersectionObserver();
        console.log(`Total aksi yang ditampilkan: ${document.querySelectorAll('.card').length}`);
    }

    // Initial render
    renderCards(aksiData);

    // Smooth scrolling untuk navigasi
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const offsetTop = target.offsetTop - 70;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Navbar scroll effect
    let lastScroll = 0;
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        if (!navbar) return;
        
        if (currentScroll > 0) {
            navbar.style.boxShadow = '0 4px 15px rgba(0,0,0,0.2)';
        } else {
            navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        }
        
        lastScroll = currentScroll;
    });

    // Tombol Tambah Aksi Baru
    const tambahBtn = document.querySelector('.tambah-btn');
    if (tambahBtn) {
        tambahBtn.addEventListener('click', function() {
            alert('Fitur Tambah Aksi Baru akan segera hadir!');
            // Redirect ke halaman form tambah aksi jika diperlukan
            // window.location.href = 'tambah-aksi.html';
        });
    }

    // Event delegation untuk Edit/Hapus (aman untuk elemen dinamis)
    if (cardsContainer) {
        cardsContainer.addEventListener('click', (e) => {
            const editBtn = e.target.closest('.btn-edit');
            const hapusBtn = e.target.closest('.btn-hapus');
            if (editBtn) {
                e.stopPropagation();
                const id = editBtn.dataset.id;
                const card = editBtn.closest('.card');
                const judul = card ? card.querySelector('h3')?.textContent : '';
                if (confirm(`Edit aksi: "${judul}"?`)) {
                    alert('Mengarahkan ke halaman edit...');
                    // contoh redirect:
                    // window.location.href = `edit-aksi.html?id=${encodeURIComponent(id)}`;
                }
            } else if (hapusBtn) {
                e.stopPropagation();
                const id = hapusBtn.dataset.id;
                const card = hapusBtn.closest('.card');
                const judul = card ? card.querySelector('h3')?.textContent : '';
                if (confirm(`Apakah Anda yakin ingin menghapus aksi: "${judul}"?`)) {
                    // Animasi fade out
                    if (card) {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            // hapus dari DOM
                            card.remove();
                            // hapus dari data (opsional)
                            const idx = aksiData.findIndex(a => String(a.id) === String(id));
                            if (idx !== -1) aksiData.splice(idx, 1);
                            alert('Aksi berhasil dihapus!');
                        }, 500);
                    }
                }
            }
        });
    }

    // Card hover animation enhancement (delegated via CSS is better; but keep minimal JS)
    document.addEventListener('mouseover', (e) => {
        const card = e.target.closest('.card');
        if (card) {
            card.style.transition = card.style.transition || 'all 0.3s ease';
        }
    });

    // Prevent default for keluar button
    const keluarBtn = document.querySelector('.keluar-btn');
    if (keluarBtn) {
        keluarBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                alert('Logout berhasil!');
                // window.location.href = 'login.html';
            }
        });
    }

    // Animasi fade in saat scroll menggunakan IntersectionObserver
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    let observer;
    function setupIntersectionObserver() {
        // disconnect previous observer jika ada
        if (observer) observer.disconnect();

        observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card').forEach(card => {
            observer.observe(card);
        });
    }

    // Active nav link (menambahkan class active saat diklik)
    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Fungsi untuk memfilter card berdasarkan kategori
    function filterCards(kategori) {
        // jika kategori falsy -> tampilkan semua
        if (!cardsContainer) return;
        const filtered = kategori ? aksiData.filter(a => a.kategori === kategori) : aksiData;
        renderCards(filtered);
    }

    // Contoh: jika ada select filter dengan class .filter-kategori
    const filterSelect = document.querySelector('.filter-kategori');
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            const val = this.value;
            if (val === 'all') filterCards(null);
            else filterCards(val);
        });
    }

    // Log info saat halaman dimuat
    console.log('Riwayat Lingkungan Aksi - Website loaded successfully!');
});
