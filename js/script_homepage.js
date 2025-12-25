/* ======= Combined JS: script_homepage.js + adapted script.js (scoped) ======= */

/* --------------------
   Navbar scripts (scoped to #navbar)
   -------------------- */
(function() {
    const navbar = document.getElementById('navbar');
    if (!navbar) return;

    const hamburger = navbar.querySelector('.hamburger');
    const navMenu = navbar.querySelector('.nav-menu');

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    }

    // Close menu when clicking nav links
    const navLinks = navbar.querySelectorAll('.nav-link');
    if (navLinks) {
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (hamburger) hamburger.classList.remove('active');
                if (navMenu) navMenu.classList.remove('active');
            });
        });
    }

    // Smooth scrolling for navigation links
    if (navLinks) {
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');
                if (!href) return;
                if (href.startsWith('#')) {
                    const targetSection = document.querySelector(href);
                    if (targetSection) {
                        e.preventDefault();
                        const offsetTop = targetSection.offsetTop - 80;
                        window.scrollTo({ top: offsetTop, behavior: 'smooth' });
                    }
                }
            });
        });
    }

    // Active nav link on scroll
    const sections = document.querySelectorAll('section, #indexpage, #statistik, #galeri');
    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (window.pageYOffset >= (sectionTop - 100)) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    });

    // Navbar background on scroll
    window.addEventListener('scroll', () => {
        if (!navbar) return;
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(15, 42, 29, 0.98)';
            navbar.style.backdropFilter = 'blur(10px)';
        } else {
            navbar.style.background = '#0F2A1D';
            navbar.style.backdropFilter = 'none';
        }
    });

    // Button animations
    const buttons = navbar.querySelectorAll('button');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

})();


/* --------------------
   Hero/Homepage scripts (scoped to #homepage)
   -------------------- */
(function() {
    const homepage = document.getElementById('homepage');
    if (!homepage) return;

    // Animate stats on scroll
    const animateStats = () => {
        const stats = homepage.querySelectorAll('.stat-number');
        if (!stats || stats.length === 0) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    const finalValue = target.textContent;
                    const numericValue = parseInt(finalValue.replace(/\D/g, '')) || 0;
                    const suffix = finalValue.replace(/[0-9]/g, '');
                    let current = 0;
                    const increment = Math.max(1, numericValue / 50);
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= numericValue) {
                            target.textContent = numericValue + suffix;
                            clearInterval(timer);
                        } else {
                            target.textContent = Math.floor(current) + suffix;
                        }
                    }, 30);
                    observer.unobserve(target);
                }
            });
        }, { threshold: 0.5 });

        stats.forEach(stat => observer.observe(stat));
    };

    // Initialize animations on load
    window.addEventListener('load', () => {
        animateStats();
        const heroText = homepage.querySelector('.hero-text');
        if (heroText) {
            heroText.style.opacity = '0';
            heroText.style.transform = 'translateY(30px)';
            setTimeout(() => {
                heroText.style.transition = 'all 1s ease';
                heroText.style.opacity = '1';
                heroText.style.transform = 'translateY(0)';
            }, 200);
        }
    });

    // CTA button click handler
    const ctaButton = homepage.querySelector('.btn-cta');
    if (ctaButton) {
        ctaButton.addEventListener('click', () => {
            const ripple = document.createElement('span');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.6)';
            ripple.style.width = '100px';
            ripple.style.height = '100px';
            ripple.style.marginLeft = '-50px';
            ripple.style.marginTop = '-50px';
            ripple.style.animation = 'ripple 0.6s';
            ctaButton.style.position = 'relative';
            ctaButton.style.overflow = 'hidden';
            ctaButton.appendChild(ripple);
            setTimeout(() => { ripple.remove(); }, 600);
            alert('Fitur ini akan mengarahkan Anda ke halaman daftar aksi terdekat!');
        });
    }

    // Add CSS animation for ripple effect to head (only once)
    (function addRippleStyle(){
        if (document.getElementById('sahabat-bumi-ripple-style')) return;
        const style = document.createElement('style');
        style.id = 'sahabat-bumi-ripple-style';
        style.textContent = `@keyframes ripple { from { opacity: 1; transform: scale(0); } to { opacity: 0; transform: scale(2); } }`;
        document.head.appendChild(style);
    })();

    // Parallax effect for hero background
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        if (homepage) {
            homepage.style.backgroundPositionY = scrolled * 0.5 + 'px';
        }
    });

})();


/* --------------------
   Gallery scripts (adapted from script.js) scoped to #galeri
   -------------------- */
(function() {
    const root = document.getElementById('galeri');
    if (!root) return;

    // Animasi fade-in untuk gallery items
    const galleryItems = root.querySelectorAll('.gallery-item');
    if (!galleryItems || galleryItems.length === 0) return;

    // Observer untuk lazy loading animasi
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    entry.target.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, 100);

                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe setiap gallery item dan tambahkan delay per item
    galleryItems.forEach((item, index) => {
        item.style.transitionDelay = `${index * 0.1}s`;
        observer.observe(item);
    });

    // Click handler untuk gallery items (opsional - untuk lightbox)
    galleryItems.forEach(item => {
        item.addEventListener('click', function() {
            const img = this.querySelector('img');
            console.log('Gambar diklik:', img ? img.alt : '(tidak ada alt)');
            // Placeholder: lightbox dapat ditambahkan di sini
        });
    });

    // Animasi header (scoped)
    const header = root.querySelector('header');
    if (header) {
        header.style.opacity = '0';
        header.style.transform = 'translateY(-20px)';

        setTimeout(() => {
            header.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            header.style.opacity = '1';
            header.style.transform = 'translateY(0)';
        }, 200);
    }

})();


/* --------------------
   Statistik scripts (adapted from script.js, scoped to #statistik)
   -------------------- */
(function() {
    const root = document.getElementById('statistik');
    if (!root) return;

    // Animasi counter untuk angka (scoped)
    function animateCounter(element, target, duration = 2000) {
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = formatNumber(target);
                clearInterval(timer);
            } else {
                element.textContent = formatNumber(Math.floor(current));
            }
        }, 16);
    }

    // Format angka dengan pemisah ribuan
    function formatNumber(num) {
        if (typeof num === 'string' && num.includes('Ton')) {
            return num;
        }
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Inisialisasi animasi ketika bagian statistik dimuat
    function initStatistik() {
        const statCards = root.querySelectorAll('.stat-card');
        
        // Animasi fade-in untuk cards
        statCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 150);
        });
        
        // Animasi counter untuk angka
        setTimeout(() => {
            const numbers = root.querySelectorAll('.stat-number');
            
            numbers.forEach(num => {
                const text = num.textContent.trim();
                
                if (text === '4') {
                    animateCounter(num, 4, 1000);
                } else if (text === '10.230' || text === '10230') {
                    // handle both formats
                    num.textContent = '0';
                    animateCounter(num, 10230, 2000);
                } else if (text === '100 Ton') {
                    const tempText = num.textContent;
                    num.textContent = '0 Ton';
                    let current = 0;
                    const target = 100;
                    const timer = setInterval(() => {
                        current += 2;
                        if (current >= target) {
                            num.textContent = '100 Ton';
                            clearInterval(timer);
                        } else {
                            num.textContent = current + ' Ton';
                        }
                    }, 30);
                } else if (text === '205') {
                    animateCounter(num, 205, 1500);
                }
            });
        }, 600);
    }

    // Efek parallax ringan pada background area statistik
    document.addEventListener('mousemove', (e) => {
        const moveX = (e.clientX - window.innerWidth / 2) * 0.01;
        const moveY = (e.clientY - window.innerHeight / 2) * 0.01;
        
        root.style.backgroundPosition = `${50 + moveX}% ${50 + moveY}%`;
    });

    // Jika DOM sudah siap, inisialisasi
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initStatistik);
    } else {
        initStatistik();
    }

})();


/* --------------------
   Testimonial & Inspirasi scripts (from original script.js)
   Scoped to #komen and #inspirasi
   -------------------- */
(function(){
    // Smooth scroll for in-page anchors (non-navbar links handled separately)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const parentNav = this.closest('#navbar');
            if (parentNav) return; // let navbar handler manage offset

            const href = this.getAttribute('href');
            const target = document.querySelector(href);
            if (!target) return;
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // Intersection observer for testimonial cards and inspirasi items
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, (entry.dataset.delay ? parseInt(entry.dataset.delay) : 0));
                revealObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.addEventListener('DOMContentLoaded', () => {
        const testimonialCards = document.querySelectorAll('.testimonial-card');
        testimonialCards.forEach((card, i) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            card.dataset.delay = i * 100;
            revealObserver.observe(card);
        });

        const inspirasiItems = document.querySelectorAll('.inspirasi-item');
        inspirasiItems.forEach((item, i) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(30px)';
            item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            item.dataset.delay = i * 120;
            revealObserver.observe(item);
        });

        // Hover effect for testimonial cards (enhanced)
        testimonialCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });

    // Parallax effect for testimonial background
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const testimonialSection = document.getElementById('komen');
        if (testimonialSection) {
            const cards = testimonialSection.querySelectorAll('.testimonial-card');
            cards.forEach((card, index) => {
                const speed = 0.05 + (index * 0.01);
                const yPos = -(scrolled * speed);
                card.style.backgroundPosition = `center ${yPos}px`;
            });
        }
    });

    // Star animation when komentar section visible
    function animateStars() {
        const starContainers = document.querySelectorAll('.stars');
        starContainers.forEach(container => {
            const stars = container.querySelectorAll('span');
            stars.forEach((star, index) => {
                star.style.opacity = '0';
                star.style.transform = 'scale(0)';
                setTimeout(() => {
                    star.style.transition = 'all 0.3s ease';
                    star.style.opacity = '1';
                    star.style.transform = 'scale(1)';
                }, index * 100);
            });
        });
    }

    const starObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStars();
                starObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.addEventListener('DOMContentLoaded', () => {
        const komenSection = document.getElementById('komen');
        if (komenSection) starObserver.observe(komenSection);
    });

    // Small loading fade-in (non-blocking)
    window.addEventListener('load', () => {
        if (document.body) {
            document.body.style.opacity = '0';
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.5s ease';
                document.body.style.opacity = '1';
            }, 100);
        }
    });

    // Floating / 3D tilt effect for testimonial cards
    function createFloatingElements() {
        const cards = document.querySelectorAll('.testimonial-card');
        cards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const deltaX = (x - centerX) / centerX;
                const deltaY = (y - centerY) / centerY;
                card.style.transform = `translateY(-5px) rotateY(${deltaX * 5}deg) rotateX(${-deltaY * 5}deg)`;
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) rotateY(0) rotateX(0)';
            });
        });
    }

    document.addEventListener('DOMContentLoaded', createFloatingElements);

})();

/* ===== FAQ script (scoped to #faq) - merged from js/script.js ===== */
(function(){
    const root = document.getElementById('faq');
    if (!root) return;

    const faqQuestions = root.querySelectorAll('.faq-question');

    faqQuestions.forEach(question => {
        question.addEventListener('click', () => {
            const faqItem = question.parentElement;
            const isActive = faqItem.classList.contains('active');

            // Tutup semua FAQ item lainnya (opsional)
            faqQuestions.forEach(q => {
                q.parentElement.classList.remove('active');
            });

            // Toggle FAQ item yang diklik
            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });

    // Animasi smooth saat bagian FAQ dimuat (scoped)
    document.addEventListener('DOMContentLoaded', () => {
        const faqItems = root.querySelectorAll('.faq-item');
        faqItems.forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, 50);
            }, index * 100);
        });
    });
})();