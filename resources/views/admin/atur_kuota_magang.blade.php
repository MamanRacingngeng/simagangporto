@extends('layouts.admin')

@section('title', 'Atur Kuota Magang - SIMAGANG')

@section('content')
<h1 style="margin:0 0 24px;font-size:28px;font-weight:700">Atur Kuota Magang</h1>

<!-- Notification Banner Success -->
@if(session('success'))
    <div id="notification-success" class="notification-banner notification-success" style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;background:#d1fae5;border-left:4px solid #10b981;border-radius:8px;margin-bottom:24px;color:#065f46;box-shadow:0 2px 8px rgba(0,0,0,0.08);transition:all 0.3s ease;position:relative;overflow:hidden">
        <div style="flex:1;display:flex;align-items:center;gap:12px">
            <svg style="width:20px;height:20px;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span style="font-size:14px;font-weight:500;line-height:1.5">{{ session('success') }}</span>
        </div>
        <button onclick="dismissNotification('notification-success')" style="background:transparent;border:none;color:#065f46;cursor:pointer;padding:4px 8px;margin-left:12px;border-radius:4px;transition:background 0.2s;flex-shrink:0;display:flex;align-items:center;justify-content:center" onmouseover="this.style.background='rgba(6,95,70,0.1)'" onmouseout="this.style.background='transparent'">
            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif

<!-- Notification Banner Error -->
@if(session('error') || $errors->any())
    <div id="notification-error" class="notification-banner notification-error" style="display:flex;align-items:flex-start;justify-content:space-between;padding:14px 16px;background:#fee2e2;border-left:4px solid #ef4444;border-radius:8px;margin-bottom:24px;color:#991b1b;box-shadow:0 2px 8px rgba(0,0,0,0.08);transition:all 0.3s ease;position:relative;overflow:hidden">
        <div style="flex:1;display:flex;align-items:flex-start;gap:12px">
            <svg style="width:20px;height:20px;flex-shrink:0;margin-top:2px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div style="flex:1">
                @if(session('error'))
                    <div style="font-size:14px;font-weight:500;line-height:1.5;margin-bottom:8px">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <ul style="margin:0;padding-left:20px;font-size:14px;line-height:1.6">
                        @foreach($errors->all() as $error)
                            <li style="margin-bottom:4px">{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        <button onclick="dismissNotification('notification-error')" style="background:transparent;border:none;color:#991b1b;cursor:pointer;padding:4px 8px;margin-left:12px;border-radius:4px;transition:background 0.2s;flex-shrink:0;display:flex;align-items:center;justify-content:center" onmouseover="this.style.background='rgba(153,27,27,0.1)'" onmouseout="this.style.background='transparent'">
            <svg style="width:18px;height:18px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif

<!-- Form Tambah Kuota -->
<div class="admin-card" style="margin-bottom:24px">
    <h2 style="margin:0 0 16px;font-size:20px;font-weight:700">Tambah Kuota Baru</h2>
    <div style="padding:12px 16px;background:#EFF6FF;border-left:4px solid #3B82F6;border-radius:8px;margin-bottom:20px">
        <p style="margin:0;color:#1E40AF;font-size:14px;line-height:1.6">
            <strong>📋 Informasi:</strong> Sistem memperbolehkan penggunaan periode magang yang sama, selama divisi berbeda. 
            Pengaturan kuota magang dilakukan per divisi, bukan per periode secara keseluruhan.
        </p>
    </div>
    <form action="{{ route('admin.store_kuota_magang') }}" method="POST">
        @csrf
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:16px;margin-bottom:16px">
            <div>
                <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">Periode</label>
                <input type="text" name="periode" value="{{ old('periode') }}" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px" placeholder="Contoh: Semester Genap 2025">
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">Posisi/Divisi <span style="color:#ef4444">*</span></label>
                <input type="text" name="posisi" value="{{ old('posisi') }}" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px" placeholder="Contoh: Tim IT, Divisi Keuangan, Lab Batik">
                <small style="display:block;margin-top:4px;color:#6b7280;font-size:12px">Kuota diatur per divisi, bukan per periode</small>
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">Kuota Maksimal</label>
                <input type="number" name="kuota_max" value="{{ old('kuota_max') }}" required min="1" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px" placeholder="Contoh: 50">
            </div>
        </div>
        <button type="submit" style="padding:12px 24px;background:#10b981;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:15px">Tambah Kuota</button>
    </form>
</div>

<!-- Daftar Kuota -->
<div class="admin-card">
    <h2 style="margin:0 0 16px;font-size:20px;font-weight:700">Daftar Kuota</h2>
    
    @if(isset($kuota) && $kuota->count() > 0)
        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="border-bottom:2px solid #e5e7eb">
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Periode</th>
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Posisi</th>
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Kuota Maksimal</th>
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Kuota Terpakai</th>
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Sisa Kuota</th>
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kuota as $k)
                        @php
                            $sisaKuota = $k->kuota_max - $k->kuota_terpakai;
                            $warnaSisa = $sisaKuota > 0 ? '#10b981' : '#ef4444';
                        @endphp
                        <tr style="border-bottom:1px solid #f3f4f6">
                            <td style="padding:12px;color:#1f2937;font-weight:500">{{ $k->periode }}</td>
                            <td style="padding:12px;color:#1f2937;font-weight:500">{{ $k->posisi ?? '-' }}</td>
                            <td style="padding:12px;color:#6b7280">{{ $k->kuota_max }}</td>
                            <td style="padding:12px;color:#6b7280">{{ $k->kuota_terpakai }}</td>
                            <td @if($sisaKuota > 0) style="padding:12px;color:#10b981;font-weight:600" @else style="padding:12px;color:#ef4444;font-weight:600" @endif>
                                {{ $sisaKuota }}
                            </td>
                            <td style="padding:12px">
                                <div style="display:flex;gap:8px">
                                    <button 
                                        class="btn-edit-kuota"
                                        data-id="{{ $k->id }}"
                                        data-periode="{{ $k->periode }}"
                                        data-posisi="{{ $k->posisi ?? '' }}"
                                        data-kuota-max="{{ $k->kuota_max }}"
                                        data-kuota-terpakai="{{ $k->kuota_terpakai }}"
                                        style="padding:6px 12px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer">Edit</button>
                                    <form action="{{ route('admin.delete_kuota_magang', $k->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus kuota ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="padding:6px 12px;background:#ef4444;color:#fff;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p style="margin:0;color:#6b7280;text-align:center;padding:24px">Belum ada kuota yang ditambahkan.</p>
    @endif
</div>

<!-- Modal Edit -->
<div id="editModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:24px;max-width:500px;width:90%">
        <h2 style="margin:0 0 16px;font-size:20px;font-weight:700">Edit Kuota</h2>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:16px">
                <div>
                    <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">Periode</label>
                    <input type="text" name="periode" id="edit_periode" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px">
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">Posisi/Divisi <span style="color:#ef4444">*</span></label>
                    <input type="text" name="posisi" id="edit_posisi" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px" placeholder="Contoh: Tim IT, Divisi Keuangan, Lab Batik">
                    <small style="display:block;margin-top:4px;color:#6b7280;font-size:12px">Kuota diatur per divisi, bukan per periode</small>
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">Kuota Maksimal</label>
                    <input type="number" name="kuota_max" id="edit_kuota_max" required min="1" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px">
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">Kuota Terpakai</label>
                    <input type="number" name="kuota_terpakai" id="edit_kuota_terpakai" required min="0" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px">
                </div>
            </div>
            <div style="display:flex;gap:12px">
                <button type="submit" style="padding:12px 24px;background:#10b981;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:15px;flex:1">Simpan</button>
                <button type="button" onclick="closeEditModal()" style="padding:12px 24px;background:#6b7280;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:15px;flex:1">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
// Notification Banner Functions
function dismissNotification(notificationId) {
    const notification = document.getElementById(notificationId);
    if (notification) {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-10px)';
        notification.style.marginBottom = '0';
        setTimeout(() => {
            notification.style.display = 'none';
        }, 300);
    }
}

// Auto-dismiss notifications after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const successNotification = document.getElementById('notification-success');
    const errorNotification = document.getElementById('notification-error');
    
    if (successNotification) {
        setTimeout(() => {
            dismissNotification('notification-success');
        }, 5000);
    }
    
    if (errorNotification) {
        // Error notifications stay longer (8 seconds) as they're more important
        setTimeout(() => {
            dismissNotification('notification-error');
        }, 8000);
    }
});

function editKuota(id, periode, posisi, kuotaMax, kuotaTerpakai) {
    try {
        // Set form action menggunakan route helper
        const form = document.getElementById('editForm');
        if (!form) {
            console.error('Form edit tidak ditemukan');
            alert('Terjadi kesalahan. Form edit tidak ditemukan.');
            return;
        }
        
        form.action = '{{ url("admin/atur-kuota-magang") }}/' + id;
        
        // Set nilai form - handle null/undefined dengan aman
        const periodeInput = document.getElementById('edit_periode');
        const posisiInput = document.getElementById('edit_posisi');
        const kuotaMaxInput = document.getElementById('edit_kuota_max');
        const kuotaTerpakaiInput = document.getElementById('edit_kuota_terpakai');
        
        if (periodeInput) periodeInput.value = periode || '';
        if (posisiInput) posisiInput.value = posisi || '';
        if (kuotaMaxInput) kuotaMaxInput.value = kuotaMax || 0;
        if (kuotaTerpakaiInput) kuotaTerpakaiInput.value = kuotaTerpakai || 0;
        
        // Tampilkan modal
        const modal = document.getElementById('editModal');
        if (modal) {
            modal.style.display = 'flex';
        } else {
            console.error('Modal edit tidak ditemukan');
            alert('Terjadi kesalahan. Modal edit tidak ditemukan.');
        }
    } catch (error) {
        console.error('Error dalam editKuota:', error);
        alert('Terjadi kesalahan saat membuka form edit: ' + error.message);
    }
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editModal');
    if (editModal) {
        editModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    }
    
    // Handle edit button clicks using data attributes
    const editButtons = document.querySelectorAll('.btn-edit-kuota');
    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const periode = this.getAttribute('data-periode');
            const posisi = this.getAttribute('data-posisi');
            const kuotaMax = parseInt(this.getAttribute('data-kuota-max')) || 0;
            const kuotaTerpakai = parseInt(this.getAttribute('data-kuota-terpakai')) || 0;
            editKuota(id, periode, posisi, kuotaMax, kuotaTerpakai);
        });
    });
});
</script>
@endsection

