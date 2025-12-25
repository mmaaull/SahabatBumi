document.addEventListener("DOMContentLoaded", () => {

    // Smooth scroll
    document.querySelectorAll('#footer a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const target = document.querySelector(link.getAttribute('href'));
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 70,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Dynamic year
    const copyright = document.querySelector('.footer-copyright p');
    if (copyright) {
        copyright.textContent = `Copyright © ${new Date().getFullYear()} Sahabat Bumi. Semua Hak Dilindungi`;
    }

    console.log("Footer Sahabat Bumi loaded");
});
