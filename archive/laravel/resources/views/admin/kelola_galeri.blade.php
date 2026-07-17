@extends('layouts.admin')

@section('title', 'Kelola Galeri Magang - SIMAGANG')

@section('content')
<style>
    .gallery-admin-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(15, 23, 42, 0.03);
        border: 1px solid #E5E7EB;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
        margin-top: 24px;
    }

    .gallery-item-admin {
        background: #FFFFFF;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #E5E7EB;
        transition: all 0.3s ease;
    }

    .gallery-item-admin:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .gallery-item-admin img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #F3F4F6;
    }

    .gallery-item-info {
        padding: 16px;
    }

    .gallery-item-title {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px 0;
    }

    .gallery-item-desc {
        font-size: 14px;
        color: #6B7280;
        margin: 0 0 12px 0;
        line-height: 1.5;
    }

    .gallery-item-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 12px;
        border-top: 1px solid #F3F4F6;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.aktif {
        background: #ECFDF5;
        color: #10B981;
    }

    .status-badge.nonaktif {
        background: #FEF2F2;
        color: #EF4444;
    }

    .gallery-actions {
        display: flex;
        gap: 8px;
    }

    .btn-small {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-edit {
        background: #2563EB;
        color: #FFFFFF;
    }

    .btn-edit:hover {
        background: #1D4ED8;
    }

    .btn-delete {
        background: #EF4444;
        color: #FFFFFF;
    }

    .btn-delete:hover {
        background: #DC2626;
    }

    .empty-state {
        text-align: center;
        padding: 60px 24px;
        color: #6B7280;
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .image-preview {
        width: 100%;
        max-width: 300px;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        margin-top: 12px;
        border: 2px solid #E5E7EB;
    }
</style>

<h1 style="margin: 0 0 24px; font-size: 28px; font-weight: 700;">Kelola Galeri Magang</h1>

@if(session('success'))
    <div style="padding: 16px; background: #D1FAE5; border-left: 4px solid #10B981; border-radius: 8px; margin-bottom: 24px; color: #065F46;">
        {{ session('success') }}
    </div>
@endif

@if(session('error') || $errors->any())
    <div style="padding: 16px; background: #FEE2E2; border-left: 4px solid #EF4444; border-radius: 8px; margin-bottom: 24px; color: #991B1B;">
        @if(session('error'))
            <div>{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif

<!-- Form Tambah Galeri -->
<div class="gallery-admin-card">
    <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700;">Tambah Foto Galeri</h2>
    <form action="{{ route('admin.store_galeri') }}" method="POST" enctype="multipart/form-data" id="form-tambah-galeri">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 16px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Judul <span style="color: #EF4444;">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;" placeholder="Contoh: Workshop Membatik">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Foto <span style="color: #EF4444;">*</span></label>
                <input type="file" name="foto" accept="image/*" required onchange="previewImage(this, 'preview-tambah')" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                <img id="preview-tambah" class="image-preview" style="display: none;" alt="Preview">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Urutan</label>
                <input type="number" name="urutan" value="{{ old('urutan', 0) }}" min="0" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;" placeholder="0">
                <small style="display: block; margin-top: 4px; color: #6B7280; font-size: 12px;">Semakin kecil angka, semakin awal ditampilkan</small>
            </div>
        </div>
        <div style="margin-bottom: 16px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Deskripsi</label>
            <textarea name="deskripsi" rows="3" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; resize: vertical;" placeholder="Deskripsi kegiatan atau foto (opsional)">{{ old('deskripsi') }}</textarea>
        </div>
        <div style="margin-bottom: 16px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="aktif" value="1" {{ old('aktif', true) ? 'checked' : '' }} style="width: 18px; height: 18px;">
                <span style="font-weight: 600; color: #374151;">Aktif (Tampilkan di halaman user)</span>
            </label>
        </div>
        <button type="submit" style="padding: 12px 24px; background: #10B981; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px;">Tambah Foto</button>
    </form>
</div>

<!-- Daftar Galeri -->
<div class="gallery-admin-card">
    <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700;">Daftar Foto Galeri</h2>
    
    @if(isset($galeri) && $galeri->count() > 0)
        <div class="gallery-grid">
            @foreach($galeri as $item)
                <div class="gallery-item-admin">
                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}" onerror="this.src='https://via.placeholder.com/300x200?text=Image+Not+Found'">
                    <div class="gallery-item-info">
                        <h3 class="gallery-item-title">{{ $item->judul }}</h3>
                        @if($item->deskripsi)
                            <p class="gallery-item-desc">{{ Str::limit($item->deskripsi, 100) }}</p>
                        @endif
                        <div class="gallery-item-meta">
                            <span class="status-badge {{ $item->aktif ? 'aktif' : 'nonaktif' }}">
                                {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            <div class="gallery-actions">
                                <button 
                                    class="btn-small btn-edit btn-edit-galeri"
                                    data-id="{{ $item->id }}"
                                    data-judul="{{ htmlspecialchars($item->judul, ENT_QUOTES, 'UTF-8') }}"
                                    data-deskripsi="{{ htmlspecialchars($item->deskripsi ?? '', ENT_QUOTES, 'UTF-8') }}"
                                    data-urutan="{{ $item->urutan }}"
                                    data-aktif="{{ $item->aktif ? '1' : '0' }}"
                                    data-foto="{{ htmlspecialchars(asset('storage/' . $item->foto), ENT_QUOTES, 'UTF-8') }}"
                                >Edit</button>
                                <form action="{{ route('admin.delete_galeri', $item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus foto ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-small btn-delete">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📷</div>
            <h3 style="font-size: 20px; font-weight: 700; color: #111827; margin: 0 0 8px 0;">Belum Ada Foto Galeri</h3>
            <p style="margin: 0; font-size: 14px; color: #6B7280;">Silakan tambahkan foto galeri menggunakan form di atas.</p>
        </div>
    @endif
</div>

<!-- Modal Edit Galeri -->
<div id="modal-edit-galeri" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #FFFFFF; border-radius: 16px; padding: 28px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <h2 style="margin: 0 0 24px; font-size: 24px; font-weight: 700;">Edit Foto Galeri</h2>
        <form action="" method="POST" enctype="multipart/form-data" id="form-edit-galeri">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Judul <span style="color: #EF4444;">*</span></label>
                    <input type="text" name="judul" id="edit-judul" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Foto Baru (opsional)</label>
                    <input type="file" name="foto" accept="image/*" onchange="previewImage(this, 'preview-edit')" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                    <img id="preview-edit" class="image-preview" style="display: none;" alt="Preview">
                    <img id="current-foto" class="image-preview" style="display: block;" alt="Foto Saat Ini">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Urutan</label>
                    <input type="number" name="urutan" id="edit-urutan" min="0" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;">
                </div>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Deskripsi</label>
                <textarea name="deskripsi" id="edit-deskripsi" rows="3" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; resize: vertical;"></textarea>
            </div>
            <div style="margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="aktif" id="edit-aktif" value="1" style="width: 18px; height: 18px;">
                    <span style="font-weight: 600; color: #374151;">Aktif (Tampilkan di halaman user)</span>
                </label>
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="submit" style="flex: 1; padding: 12px 24px; background: #10B981; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px;">Simpan Perubahan</button>
                <button type="button" onclick="closeEditModal()" style="padding: 12px 24px; background: #6B7280; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px;">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function editGaleri(id, judul, deskripsi, urutan, aktif, fotoUrl) {
    document.getElementById('form-edit-galeri').action = '{{ url("/admin/galeri") }}/' + id;
    document.getElementById('edit-judul').value = judul;
    document.getElementById('edit-deskripsi').value = deskripsi || '';
    document.getElementById('edit-urutan').value = urutan;
    document.getElementById('edit-aktif').checked = aktif;
    document.getElementById('current-foto').src = fotoUrl;
    document.getElementById('preview-edit').style.display = 'none';
    document.getElementById('current-foto').style.display = 'block';
    document.getElementById('modal-edit-galeri').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('modal-edit-galeri').style.display = 'none';
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal-edit-galeri');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    }
    
    // Handle edit button clicks using data attributes
    const editButtons = document.querySelectorAll('.btn-edit-galeri');
    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const judul = this.getAttribute('data-judul');
            const deskripsi = this.getAttribute('data-deskripsi') || '';
            const urutan = parseInt(this.getAttribute('data-urutan')) || 0;
            const aktif = this.getAttribute('data-aktif') === '1';
            const fotoUrl = this.getAttribute('data-foto');
            editGaleri(id, judul, deskripsi, urutan, aktif, fotoUrl);
        });
    });
});
</script>
@endsection
