// DataTransfer untuk menyimpan file yang akan dikirim ke server
let dt = new DataTransfer();
// (optional) array untuk referensi preview
let selectedFiles = [];
const maxFiles = 3;

// Simulasi data aksi yang sedang diedit (dari parameter URL atau storage)
const currentAksi = {
    id: 1,
    judul: "Bersih Hutan Kota: Tanpa Sampah, Lebih Hijau",
    kategori: "Pembersihan Lingkungan",
    deskripsi: "Aksi ini membersihkan hutan kota dari sampah dan limbah agar ekosistem tetap sehat. Membantu menjadikan ruang hijau lebih bersih dan nyaman.",
    existingPhotos: [""]
};

// Elements
const uploadArea = document.getElementById('uploadArea');
const uploadContent = document.getElementById('uploadContent');
const fileInput = document.getElementById('fileInput');
const previewContainer = document.getElementById('previewContainer');
const editForm = document.getElementById('editForm');
const btnBatal = document.getElementById('btnBatal');

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

// Load existing data
window.addEventListener('load', () => {
    // Data sudah diisi di HTML untuk demo
    // Dalam implementasi nyata, ambil dari URL parameter atau API
    
    // Setup existing photos
    setupExistingPhotos();
});

// Setup existing photos with remove functionality
function setupExistingPhotos() {
    const existingPreviewItems = previewContainer.querySelectorAll('.preview-item');
    existingPreviewItems.forEach((item, index) => {
        const removeBtn = item.querySelector('.remove-btn');
        if (removeBtn) {
            removeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                removeExistingPhoto(item);
            });
        }
    });
}

// Remove existing photo
function removeExistingPhoto(previewElement) {
    if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
        previewElement.style.transition = 'all 0.3s ease';
        previewElement.style.opacity = '0';
        previewElement.style.transform = 'scale(0.5)';
        
        setTimeout(() => {
            previewElement.remove();
            updateUploadAreaUI();
        }, 300);
    }
}

// Click pada upload area
uploadArea.addEventListener('click', (e) => {
    if (!e.target.classList.contains('remove-btn')) {
        const currentPhotos = previewContainer.querySelectorAll('.preview-item').length;
        if (currentPhotos < maxFiles) {
            fileInput.click();
        } else {
            alert(`Maksimal ${maxFiles} foto yang dapat diunggah!`);
        }
    }
});

// Handle file selection
fileInput.addEventListener('change', (e) => {
    handleFiles(e.target.files);
});

// Drag and drop functionality
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
    
    const currentPhotos = previewContainer.querySelectorAll('.preview-item').length;
    if (currentPhotos < maxFiles) {
        handleFiles(e.dataTransfer.files);
    } else {
        alert(`Maksimal ${maxFiles} foto yang dapat diunggah!`);
    }
});

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
    const currentPhotos = previewContainer.querySelectorAll('.preview-item').length;
    const remainingSlots = maxFiles - currentPhotos;
    
    if (imageFiles.length > remainingSlots) {
        alert(`Anda hanya dapat menambahkan ${remainingSlots} foto lagi!`);
        return;
    }
    
    // Tambahkan file ke DataTransfer dan array preview
    imageFiles.forEach(file => {
        // avoid duplicates by name+size
        const exists = Array.from(dt.files).some(f => f.name === file.name && f.size === file.size);
        if (!exists) {
            dt.items.add(file);
            selectedFiles.push(file);
            displayPreview(file);
        }
    });

    // Kaitkan kembali ke input file sehingga file akan dikirim saat form submit
    fileInput.files = dt.files;

    // Update UI
    updateUploadAreaUI();
}

// Update Upload Area UI based on photos
function updateUploadAreaUI() {
    const currentPhotos = previewContainer.querySelectorAll('.preview-item').length;
    if (currentPhotos > 0) {
        uploadContent.classList.add('hidden');
        uploadArea.classList.add('has-images');
    } else {
        uploadContent.classList.remove('hidden');
        uploadArea.classList.remove('has-images');
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
    // Rebuild DataTransfer without removed file
    const newDt = new DataTransfer();
    Array.from(dt.files).forEach(f => {
        if (!(f.name === file.name && f.size === file.size)) {
            newDt.items.add(f);
        }
    });
    dt = newDt;
    fileInput.files = dt.files;
    
    // Hapus preview dengan animasi
    previewElement.style.transition = 'all 0.3s ease';
    previewElement.style.opacity = '0';
    previewElement.style.transform = 'scale(0.5)';
    
    setTimeout(() => {
        previewElement.remove();
        updateUploadAreaUI();
    }, 300);
}

// Form submission - Update
// Submit handler: validate client-side then allow normal form submit to server
editForm.addEventListener('submit', (e) => {
    // Ambil data form
    const judul = document.getElementById('judul').value;
    const kategori = document.getElementById('kategori').value;
    const deskripsi = document.getElementById('deskripsi').value;

    // Validasi sederhana; jika gagal, cegah submit
    if (!judul || !kategori || !deskripsi) {
        e.preventDefault();
        alert('Mohon lengkapi semua field!');
        return;
    }

    const currentPhotos = previewContainer.querySelectorAll('.preview-item').length;
    if (currentPhotos === 0) {
        e.preventDefault();
        alert('Mohon sertakan minimal 1 foto bukti aksi!');
        return;
    }

    // Jika lolos validasi, biarkan form submit normal (server akan menangani update dan redirect)
});

// Button Batal
btnBatal.addEventListener('click', () => {
    if (confirm('Apakah Anda yakin ingin membatalkan perubahan?')) {
        // Kembali ke halaman daftar-laporan
        window.location.href = 'daftar-laporan.php';
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
console.log('Form Edit Aksi - Website loaded successfully!');
console.log('Editing aksi ID:', currentAksi.id);
console.log(`Maksimal foto yang dapat diunggah: ${maxFiles}`);