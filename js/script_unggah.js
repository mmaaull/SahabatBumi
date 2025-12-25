// Array untuk menyimpan file yang dipilih
let selectedFiles = [];
const maxFiles = 3;

// Elements
const uploadArea = document.getElementById('uploadArea');
const uploadContent = document.getElementById('uploadContent');
const fileInput = document.getElementById('fileInput');
const previewContainer = document.getElementById('previewContainer');
const unggahForm = document.getElementById('unggahForm');

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

// Click pada upload area (only attach if elements exist)
if (uploadArea && fileInput) {
    uploadArea.addEventListener('click', (e) => {
        // Don't trigger file input if clicking on preview items or remove buttons
        if (e.target.closest('.preview-item') || e.target.closest('.remove-btn')) {
            return;
        }
        
        if (selectedFiles.length < maxFiles) {
            fileInput.click();
        } else {
            alert(`Maksimal ${maxFiles} foto yang dapat diunggah!`);
        }
    });
}

// Handle file selection
if (fileInput) {
    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });
}

// Drag and drop functionality
if (uploadArea) {
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        if (selectedFiles.length < maxFiles) {
            handleFiles(e.dataTransfer.files);
        } else {
            alert(`Maksimal ${maxFiles} foto yang dapat diunggah!`);
        }
    });
}

// Handle files function
function handleFiles(files) {
    const fileArray = Array.from(files);
    
    // Filter hanya file gambar
    const imageFiles = fileArray.filter(file => file.type.startsWith('image/'));
    
    if (imageFiles.length === 0) {
        alert('Hanya file gambar yang diperbolehkan!');
        return;
    }
    
    // Cek jumlah file
    const remainingSlots = maxFiles - selectedFiles.length;
    if (imageFiles.length > remainingSlots) {
        alert(`Anda hanya dapat menambahkan ${remainingSlots} foto lagi!`);
        return;
    }
    
    // Tambahkan file ke array
    imageFiles.forEach(file => {
        if (selectedFiles.length < maxFiles) {
            selectedFiles.push(file);
            displayPreview(file);
        }
    });
    
    // Update UI
    updateUploadAreaUI();
    
    // Reset input
    fileInput.value = '';
}

// Update Upload Area UI based on photos
function updateUploadAreaUI() {
    if (selectedFiles.length > 0) {
        uploadContent.classList.add('hidden');
        uploadArea.classList.add('has-images');
        uploadArea.style.minHeight = 'auto';
        uploadArea.style.justifyContent = 'flex-start';
    } else {
        uploadContent.classList.remove('hidden');
        uploadArea.classList.remove('has-images');
        uploadArea.style.minHeight = '250px';
        uploadArea.style.justifyContent = 'center';
    }
}

// Display preview function
function displayPreview(file) {
    const reader = new FileReader();
    
    reader.onload = (e) => {
        const previewItem = document.createElement('div');
        previewItem.className = 'preview-item';
        
        const img = document.createElement('img');
        img.src = e.target.result;
        
        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-btn';
        removeBtn.innerHTML = '×';
        removeBtn.type = 'button';
        removeBtn.onclick = (event) => {
            event.stopPropagation();
            removeFile(file, previewItem);
        };
        
        previewItem.appendChild(img);
        previewItem.appendChild(removeBtn);
        previewContainer.appendChild(previewItem);
    };
    
    reader.readAsDataURL(file);
}

// Remove file function
function removeFile(file, previewElement) {
    // Hapus dari array
    selectedFiles = selectedFiles.filter(f => f !== file);
    
    // Hapus preview dengan animasi
    previewElement.style.transition = 'all 0.3s ease';
    previewElement.style.opacity = '0';
    previewElement.style.transform = 'scale(0.5)';
    
    setTimeout(() => {
        previewElement.remove();
        updateUploadAreaUI();
    }, 300);
}

// Form submission — send real POST with FormData including file(s)
unggahForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const judul = document.getElementById('judul').value;
    const kategori = document.getElementById('kategori').value;
    const deskripsi = document.getElementById('deskripsi').value;

    if (!judul || !kategori || !deskripsi) {
        alert('Mohon lengkapi semua field!');
        return;
    }

    if (selectedFiles.length === 0) {
        alert('Mohon unggah minimal 1 foto bukti aksi!');
        return;
    }

    const submitBtn = document.querySelector('.submit-btn');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Mengunggah...';
    submitBtn.disabled = true;

    // Build FormData
    const fd = new FormData();
    fd.append('judul', judul);
    fd.append('kategori', kategori);
    fd.append('deskripsi', deskripsi);
    // append first selected file as 'photos' (server expects single file)
    fd.append('photos', selectedFiles[0], selectedFiles[0].name);

    fetch('unggah.php', {
        method: 'POST',
        body: fd,
        credentials: 'same-origin'
    }).then(async (res) => {
        // If server performed a redirect (e.g. header Location), fetch follows it.
        if (res.redirected || (res.url && res.url.includes('daftar-laporan.php'))) {
            window.location.href = res.url;
            return;
        }

        const text = await res.text();
        // If server returned success message, redirect to daftar
        if (text.includes('Laporan berhasil diunggah')) {
            window.location.href = 'daftar-laporan.php';
            return;
        }

        // Otherwise log server response and show concise message
        console.warn('Server response (debug):', text);
        alert('Terjadi masalah saat unggah. Periksa debug (Console) atau coba lagi.');
    }).catch((err) => {
        alert('Gagal mengirim data: ' + err.message);
    }).finally(() => {
        // Reset UI
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        unggahForm.reset();
        selectedFiles = [];
        previewContainer.innerHTML = '';
        updateUploadAreaUI();
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

// Form field animations
const inputs = document.querySelectorAll('input, select, textarea');
inputs.forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.01)';
        this.parentElement.style.transition = 'transform 0.2s';
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
    });
});

// Animate form on load
window.addEventListener('load', () => {
    const formWrapper = document.querySelector('.form-wrapper');
    formWrapper.style.opacity = '0';
    formWrapper.style.transform = 'translateY(30px)';
    
    setTimeout(() => {
        formWrapper.style.transition = 'all 0.6s ease';
        formWrapper.style.opacity = '1';
        formWrapper.style.transform = 'translateY(0)';
    }, 100);
});

// Log info
console.log('Form Unggah Aksi - Website loaded successfully!');
console.log(`Maksimal foto yang dapat diunggah: ${maxFiles}`);