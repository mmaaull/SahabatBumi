const modal = new bootstrap.Modal(document.getElementById('aksiModal'));

function resetForm() {
  document.getElementById('aksiForm').reset();
  document.getElementById('editId').value = '';
  document.getElementById('modalTitle').innerText = 'Tambah Aksi Lingkungan';
  const preview = document.getElementById('imagePreview');
  if (preview) {
    preview.src = '';
    preview.style.display = 'none';
  }
}

function previewImage() {
  const fileInput = document.getElementById('image');
  const preview = document.getElementById('imagePreview');
  
  if (fileInput && fileInput.files && fileInput.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
    }
    reader.readAsDataURL(fileInput.files[0]);
  } else if (preview) {
    preview.style.display = 'none';
  }
}

function editAksi(id) {
  fetch('daftar-aksi.php?action=get&id=' + id)
    .then(res => res.json())
    .then(data => {
      document.getElementById('editId').value = data.id;
      document.getElementById('nama_aksi').value = data.nama_aksi;
      document.getElementById('kategori').value = data.kategori || '';
      document.getElementById('tanggal_mulai').value = data.tanggal_mulai;
      document.getElementById('tanggal_selesai').value = data.tanggal_selesai;
      document.getElementById('tanggal_mulai_jam').value = data.tanggal_mulai_jam || '';
      document.getElementById('tanggal_selesai_jam').value = data.tanggal_selesai_jam || '';
      document.getElementById('lokasi').value = data.lokasi || '';
      document.getElementById('deskripsi').value = data.deskripsi || '';
      
      // Show image preview if exists
      if (data.image_path) {
        const preview = document.getElementById('imagePreview');
        preview.src = '../' + data.image_path;
        preview.style.display = 'block';
      }
      
      document.getElementById('modalTitle').innerText = 'Edit Aksi Lingkungan';
      modal.show();
    });
}

function saveAksi() {
  const editId = document.getElementById('editId').value;
  const nama_aksi = document.getElementById('nama_aksi').value;
  const tanggal_mulai = document.getElementById('tanggal_mulai').value;
  const tanggal_selesai = document.getElementById('tanggal_selesai').value;
  
  // Validasi client-side
  if (!nama_aksi || !tanggal_mulai || !tanggal_selesai) {
    alert('Nama aksi, tanggal mulai, dan tanggal selesai wajib diisi!');
    return;
  }
  
  const formData = new FormData();
  
  if (editId) {
    formData.append('action', 'update');
    formData.append('id', editId);
  } else {
    formData.append('action', 'create');
  }
  
  formData.append('nama_aksi', nama_aksi);
  formData.append('kategori', document.getElementById('kategori').value);
  formData.append('tanggal_mulai', tanggal_mulai);
  formData.append('tanggal_selesai', tanggal_selesai);
  formData.append('tanggal_mulai_jam', document.getElementById('tanggal_mulai_jam').value);
  formData.append('tanggal_selesai_jam', document.getElementById('tanggal_selesai_jam').value);
  formData.append('lokasi', document.getElementById('lokasi').value);
  formData.append('deskripsi', document.getElementById('deskripsi').value);
  
  // Add image if exists
  const imageFile = document.getElementById('image').files[0];
  if (imageFile) {
    formData.append('image', imageFile);
  }
  
  console.log('Submitting form, action=' + (editId ? 'update' : 'create') + ', editId=' + editId);
  
  fetch('daftar-aksi.php', {
    method: 'POST',
    body: formData
  })
  .then(async res => {  
    console.log('Response status:', res.status);
    console.log('Content-Type:', res.headers.get('content-type'));
    
    const text = await res.text();
    console.log('Raw response:', text);
    console.log('Raw response length:', text.length);
    
    try {
      const data = JSON.parse(text);
      console.log('Parsed JSON:', data);
      
      if (data.status === 'success') {
        alert(data.message);
        modal.hide();
        location.reload();
      } else {
        alert('Error: ' + data.message);
      }
    } catch (err) {
      console.error('JSON parse error:', err.message);
      console.error('Failed response text:', text);
      alert('Server respons tidak valid.\nLihat browser console (F12) untuk detail.\nRaw: ' + text.substring(0, 100));
    }
  })
  .catch(err => {
    console.error('Fetch error:', err);
    alert('Terjadi kesalahan jaringan: ' + err.message);
  });
}

function deleteAksi(id) {
  if (confirm('Yakin ingin menghapus aksi ini?')) {
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', id);
    
    fetch('daftar-aksi.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        alert(data.message);
        location.reload();
      } else {
        alert('Error: ' + data.message);
      }
    });
  }
}
