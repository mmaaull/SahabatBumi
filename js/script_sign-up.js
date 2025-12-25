// Form Validation and Submission
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form values
    const fullname = document.getElementById('fullname').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    // Basic validation
    if (!fullname || !email || !password) {
        alert('Mohon lengkapi semua field!');
        return;
    }
    
    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Format email tidak valid!');
        return;
    }
    
    // Password validation (minimum 6 characters)
    if (password.length < 6) {
        alert('Kata sandi minimal 6 karakter!');
        return;
    }
    
    // If validation passes
    console.log('Pendaftaran berhasil!');
    console.log('Nama:', fullname);
    console.log('Email:', email);
    
    // Show success message
    alert('Pendaftaran berhasil! Selamat datang di Sahabat Bumi, ' + fullname + '!');
    
    // Reset form
    this.reset();
    
    // Here you would typically send data to your backend
    // Example: fetch('/api/register', { method: 'POST', body: JSON.stringify({fullname, email, password}) })
});

// Google Sign In Handler
document.querySelector('.google-btn').addEventListener('click', function() {
    console.log('Google Sign In clicked');
    alert('Fitur login dengan Google akan segera tersedia!');
    // Here you would integrate with Google OAuth
});

// Facebook Sign In Handler
document.querySelector('.facebook-btn').addEventListener('click', function() {
    console.log('Facebook Sign In clicked');
    alert('Fitur login dengan Facebook akan segera tersedia!');
    // Here you would integrate with Facebook OAuth
});

// Input focus animation
const inputs = document.querySelectorAll('.form-group input');
inputs.forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
        this.parentElement.style.transition = 'transform 0.3s ease';
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
    });
});

// Add smooth scroll behavior
document.documentElement.style.scrollBehavior = 'smooth';

// Password strength indicator (optional enhancement)
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    let strength = 0;
    
    if (password.length >= 6) strength++;
    if (password.length >= 10) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z\d]/.test(password)) strength++;
    
    // You can add visual feedback here
    console.log('Password strength:', strength);
});