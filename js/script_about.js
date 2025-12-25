// Smooth scrolling untuk navigasi
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            const navHeight = document.querySelector('.navbar').offsetHeight;
            const targetPosition = target.offsetTop - navHeight;
            
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    });
});

// Animasi fade in saat scroll
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

// Tambahkan animasi untuk cards
document.querySelectorAll('.misi-card, .tim-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(card);
});

// Navbar change background on scroll
let lastScroll = 0;
const navbar = document.querySelector('.navbar');

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 100) {
        navbar.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.3)';
    } else {
        navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
    }
    
    lastScroll = currentScroll;
});

// Parallax effect untuk section visi-misi (DINONAKTIFKAN - background fixed)
// window.addEventListener('scroll', () => {
//     const visiMisi = document.getElementById('visi-misi');
//     const scrolled = window.pageYOffset;
//     const rate = scrolled * 0.5;
//     
//     if (visiMisi) {
//         visiMisi.style.backgroundPositionY = rate + 'px';
//     }
// });

// Hover effect untuk tim cards
document.querySelectorAll('.tim-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.4)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.boxShadow = '0 5px 20px rgba(0, 0, 0, 0.2)';
    });
});

// Hover effect untuk misi cards
document.querySelectorAll('.misi-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.5)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.boxShadow = '0 5px 20px rgba(0, 0, 0, 0.3)';
    });
});

// Button functionality
document.querySelector('.btn-masuk')?.addEventListener('click', () => {
    alert('Fitur login akan segera hadir!');
});

document.querySelector('.btn-keluar')?.addEventListener('click', () => {
    alert('Anda akan keluar dari sistem');
});

// Counter animation untuk angka di misi cards
const animateNumber = (element, target) => {
    let current = 0;
    const increment = target / 50;
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current);
        }
    }, 30);
};

// Observe misi numbers untuk animasi
const numberObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
            const number = parseInt(entry.target.textContent);
            entry.target.textContent = '0';
            animateNumber(entry.target, number);
            entry.target.classList.add('animated');
        }
    });
}, { threshold: 0.5 });

document.querySelectorAll('.misi-number').forEach(num => {
    numberObserver.observe(num);
});

console.log('Sahabat Bumi - Website Loaded Successfully!');