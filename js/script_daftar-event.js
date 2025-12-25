// Data event (simulasi)
const eventsData = [
    {
        id: 1,
        title: "Aksi Penanaman di Hutan Kota",
        date: { day: "26-29", month: "Januari", year: "2026" },
        time: "10:00 WIB - 16:00 WIB",
        location: "Taman Hutan Raya, Jakarta",
        description: "Ikut volunteer menanam pohon untuk menghijau hutan kota dan memberi bumi napas baru. Bersama para relawan lain, aku ikut menanam bibit-bibit pohon di area yang membutuhkan tanaman kecil yang semakin membawa dampak besar bagi lingkungan.",
        image: "img/hands-plant.jpg"
    }
    // Tambahkan data event lainnya di sini
];

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
    
    if (currentScroll > 0) {
        navbar.style.boxShadow = '0 4px 15px rgba(0,0,0,0.2)';
    } else {
        navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
    }
    
    lastScroll = currentScroll;
});

// Search functionality
const searchInput = document.getElementById('searchInput');
const btnSearch = document.getElementById('btnSearch');

btnSearch.addEventListener('click', performSearch);
searchInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        performSearch();
    }
});

function performSearch() {
    const searchTerm = searchInput.value.trim().toLowerCase();
    
    if (searchTerm === '') {
        alert('Masukkan kata kunci untuk mencari event!');
        return;
    }
    
    // Filter event cards
    const eventCards = document.querySelectorAll('.event-card');
    let foundCount = 0;
    
    eventCards.forEach(card => {
        const title = card.querySelector('.event-title').textContent.toLowerCase();
        const location = card.querySelector('.event-detail:nth-child(3) span').textContent.toLowerCase();
        const description = card.querySelector('.event-description').textContent.toLowerCase();
        
        if (title.includes(searchTerm) || location.includes(searchTerm) || description.includes(searchTerm)) {
            card.style.display = 'block';
            foundCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    if (foundCount === 0) {
        alert(Tidak ditemukan event dengan kata kunci "${searchTerm}");
        // Reset tampilan
        eventCards.forEach(card => card.style.display = 'block');
    } else {
        // Scroll ke section daftar event
        document.getElementById('daftar-event').scrollIntoView({ behavior: 'smooth' });
    }
}

// Clear search on input change
searchInput.addEventListener('input', () => {
    if (searchInput.value === '') {
        const eventCards = document.querySelectorAll('.event-card');
        eventCards.forEach(card => card.style.display = 'block');
    }
});

// Daftar button functionality
function daftarEvent(eventId) {
    // Redirect ke form pendaftaran dengan aksi_id
    console.log('daftarEvent dipanggil dengan ID:', eventId);
    window.location.href = 'form-pendaftaran-aksi.php?aksi_id=' + eventId;
}


// Pagination functionality
const pageButtons = document.querySelectorAll('.page-btn');
const prevBtn = document.getElementById('prevPage');
const nextBtn = document.getElementById('nextPage');
let currentPage = 1;

pageButtons.forEach(btn => {
    if (!btn.id) { // Skip prev/next buttons
        btn.addEventListener('click', () => {
            // Remove active class from all buttons
            pageButtons.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            btn.classList.add('active');
            
            // Update current page
            currentPage = parseInt(btn.textContent);
            
            // Scroll to top of event section
            document.getElementById('daftar-event').scrollIntoView({ behavior: 'smooth' });
            
            // In real application, load new events here
            console.log('Loading page:', currentPage);
        });
    }
});

// Previous page button
if (prevBtn) {
    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
        }
    });
}

// Next page button
if (nextBtn) {
    nextBtn.addEventListener('click', () => {
        const maxPages = document.querySelectorAll('.page-btn:not([id])').length;
        if (currentPage < maxPages) {
            currentPage++;
            updatePagination();
        }
    });
}

function updatePagination() {
    pageButtons.forEach(btn => {
        if (!btn.id && btn.textContent == currentPage) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
    
    document.getElementById('daftar-event').scrollIntoView({ behavior: 'smooth' });
    console.log('Current page:', currentPage);
}

// Card hover effect enhancement
const eventCards = document.querySelectorAll('.event-card');
eventCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transition = 'all 0.3s ease';
    });
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

// Animate cards on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Apply fade in animation to cards
eventCards.forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
    observer.observe(card);
});

// Log info
console.log('Event Buat Kamu - Website loaded successfully!');
console.log(`Total events displayed: ${eventCards.length}`);