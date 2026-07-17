@extends('layouts.admin')

@section('title', 'Atur Jadwal Magang - SIMAGANG')

@section('content')
<h1 style="margin:0 0 24px;font-size:28px;font-weight:700">Atur Jadwal Magang</h1>

@if(session('success'))
    <div style="padding:16px;background:#d1fae5;border-left:4px solid #10b981;border-radius:8px;margin-bottom:24px;color:#065f46">
        {{ session('success') }}
    </div>
@endif

@if(session('error') || $errors->any())
    <div style="padding:16px;background:#fee2e2;border-left:4px solid #ef4444;border-radius:8px;margin-bottom:24px;color:#991b1b">
        @if(session('error'))
            <div>{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <ul style="margin:8px 0 0 0;padding-left:20px">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif

<!-- Form Tambah Jadwal -->
<div class="admin-card" style="margin-bottom:24px">
    <h2 style="margin:0 0 16px;font-size:20px;font-weight:700">Tambah Jadwal Baru</h2>
    <div style="padding:12px 16px;background:#FEF3C7;border-left:4px solid #F59E0B;border-radius:8px;margin-bottom:20px">
        <p style="margin:0;color:#92400E;font-size:14px;line-height:1.6">
            <strong>📅 Informasi:</strong> Setiap divisi memiliki jadwal mulai dan selesai sendiri, yang dapat diatur secara terpisah sesuai kebutuhan masing-masing divisi. 
            Periode yang sama dapat digunakan untuk divisi berbeda dengan jadwal yang berbeda.
        </p>
    </div>
    <form action="{{ route('admin.store_jadwal_magang') }}" method="POST">
        @csrf
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:16px;margin-bottom:16px">
            <div>
                <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">Periode</label>
                <input type="text" name="periode" value="{{ old('periode') }}" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px" placeholder="Contoh: Semester Genap 2025">
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">Posisi/Divisi <span style="color:#ef4444">*</span></label>
                <input type="text" name="posisi" value="{{ old('posisi') }}" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px" placeholder="Contoh: Tim IT, Divisi Keuangan, Lab Batik">
                <small style="display:block;margin-top:4px;color:#6b7280;font-size:12px">Setiap divisi memiliki jadwal terpisah</small>
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" value="{{ old('tgl_mulai') }}" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px">
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" value="{{ old('tgl_selesai') }}" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px">
            </div>
        </div>
        <button type="submit" style="padding:12px 24px;background:#ec4899;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:15px">Tambah Jadwal</button>
    </form>
</div>

<!-- Daftar Jadwal -->
<div class="admin-card">
    <h2 style="margin:0 0 16px;font-size:20px;font-weight:700">Daftar Jadwal</h2>
    
    @if(isset($jadwal) && $jadwal->count() > 0)
        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="border-bottom:2px solid #e5e7eb">
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Periode</th>
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Posisi/Divisi</th>
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Tanggal Mulai</th>
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Tanggal Selesai</th>
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Status</th>
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwal as $j)
                        @php
                            $today = now()->toDateString();
                            $isActive = $j->tgl_mulai <= $today && $j->tgl_selesai >= $today;
                            $isPast = $j->tgl_selesai < $today;
                        @endphp
                        <tr style="border-bottom:1px solid #f3f4f6">
                            <td style="padding:12px;color:#1f2937;font-weight:500">{{ $j->periode }}</td>
                            <td style="padding:12px;color:#1f2937;font-weight:600">{{ $j->posisi ?? '-' }}</td>
                            <td style="padding:12px;color:#6b7280">{{ \Carbon\Carbon::parse($j->tgl_mulai)->format('d/m/Y') }}</td>
                            <td style="padding:12px;color:#6b7280">{{ \Carbon\Carbon::parse($j->tgl_selesai)->format('d/m/Y') }}</td>
                            <td style="padding:12px">
                                @if($isActive)
                                    <span style="padding:6px 12px;border-radius:6px;font-size:13px;font-weight:600;background:#d1fae5;color:#10b981">Aktif</span>
                                @elseif($isPast)
                                    <span style="padding:6px 12px;border-radius:6px;font-size:13px;font-weight:600;background:#f3f4f6;color:#6b7280">Selesai</span>
                                @else
                                    <span style="padding:6px 12px;border-radius:6px;font-size:13px;font-weight:600;background:#eff6ff;color:#2563eb">Akan Datang</span>
                                @endif
                            </td>
                            <td style="padding:12px">
                                <div style="display:flex;gap:8px">
                                    <button 
                                        type="button"
                                        class="btn-edit-jadwal"
                                        data-id="{{ $j->id }}"
                                        data-periode="{{ $j->periode }}"
                                        data-posisi="{{ $j->posisi ?? '' }}"
                                        data-tgl-mulai="{{ \Carbon\Carbon::parse($j->tgl_mulai)->format('Y-m-d') }}"
                                        data-tgl-selesai="{{ \Carbon\Carbon::parse($j->tgl_selesai)->format('Y-m-d') }}"
                                        style="padding:6px 12px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer">Edit</button>
                                    <form action="{{ route('admin.delete_jadwal_magang', $j->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
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
        <p style="margin:0;color:#6b7280;text-align:center;padding:24px">Belum ada jadwal yang ditambahkan.</p>
    @endif
</div>

<!-- Modal Edit -->
<div id="editModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:24px;max-width:500px;width:90%">
        <h2 style="margin:0 0 16px;font-size:20px;font-weight:700">Edit Jadwal</h2>
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
                    <small style="display:block;margin-top:4px;color:#6b7280;font-size:12px">Setiap divisi memiliki jadwal terpisah</small>
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" id="edit_tgl_mulai" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px">
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-weight:600;color:#374151">Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" id="edit_tgl_selesai" required style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px">
                </div>
            </div>
            <div style="display:flex;gap:12px">
                <button type="submit" style="padding:12px 24px;background:#ec4899;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:15px;flex:1">Simpan</button>
                <button type="button" onclick="closeEditModal()" style="padding:12px 24px;background:#6b7280;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer;font-size:15px;flex:1">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
// Event listener untuk button edit menggunakan data attributes
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.btn-edit-jadwal');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const periode = this.getAttribute('data-periode') || '';
            const posisi = this.getAttribute('data-posisi') || '';
            const tglMulai = this.getAttribute('data-tgl-mulai') || '';
            const tglSelesai = this.getAttribute('data-tgl-selesai') || '';
            
            editJadwal(id, periode, posisi, tglMulai, tglSelesai);
        });
    });
});

function editJadwal(id, periode, posisi, tglMulai, tglSelesai) {
    try {
        const form = document.getElementById('editForm');
        if (!form) {
            console.error('Form edit tidak ditemukan');
            alert('Terjadi kesalahan. Form edit tidak ditemukan.');
            return;
        }
        
        // Set form action menggunakan route helper
        form.action = '{{ url("admin/atur-jadwal-magang") }}/' + id;
        
        // Set nilai form - handle null/undefined dengan aman
        const periodeInput = document.getElementById('edit_periode');
        const posisiInput = document.getElementById('edit_posisi');
        const tglMulaiInput = document.getElementById('edit_tgl_mulai');
        const tglSelesaiInput = document.getElementById('edit_tgl_selesai');
        
        if (periodeInput) periodeInput.value = periode || '';
        if (posisiInput) posisiInput.value = posisi || '';
        if (tglMulaiInput) tglMulaiInput.value = tglMulai || '';
        if (tglSelesaiInput) tglSelesaiInput.value = tglSelesai || '';
        
        // Tampilkan modal
        const modal = document.getElementById('editModal');
        if (modal) {
            modal.style.display = 'flex';
        } else {
            console.error('Modal edit tidak ditemukan');
            alert('Terjadi kesalahan. Modal edit tidak ditemukan.');
        }
    } catch (error) {
        console.error('Error dalam editJadwal:', error);
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
});
</script>
@endsection

