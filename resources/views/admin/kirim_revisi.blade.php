@extends('layouts.admin')

@section('title', 'Kirim Revisi - SIMAGANG')

@section('content')
<style>
    .page-header {
        margin-bottom: 24px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px 0;
    }

    .page-subtitle {
        font-size: 14px;
        color: #6B7280;
        margin: 0;
    }

    .form-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(15, 23, 42, 0.03);
        border: 1px solid #E5E7EB;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-label .required {
        color: #EF4444;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #F59E0B;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    textarea.form-control {
        min-height: 150px;
        resize: vertical;
    }

    .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        font-size: 14px;
        background-color: #FFFFFF;
        cursor: pointer;
    }

    .helper-text {
        font-size: 12px;
        color: #6B7280;
        margin-top: 4px;
    }

    .btn-group {
        display: flex;
        gap: 12px;
        margin-top: 32px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        color: #FFFFFF;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-secondary {
        background: #F3F4F6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #E5E7EB;
    }

    .pendaftar-info {
        background: #FEF7ED;
        border-left: 4px solid #F59E0B;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
    }

    .pendaftar-info h4 {
        font-size: 16px;
        font-weight: 600;
        color: #92400E;
        margin: 0 0 8px 0;
    }

    .pendaftar-info p {
        font-size: 14px;
        color: #78350F;
        margin: 4px 0;
    }

    .info-box {
        background: #EFF6FF;
        border-left: 4px solid #3B82F6;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
    }

    .info-box p {
        margin: 0;
        font-size: 14px;
        color: #1E40AF;
        line-height: 1.6;
    }
</style>

<div class="page-header">
    <h1 class="page-title">📝 Kirim Revisi</h1>
    <p class="page-subtitle">Kirim permintaan revisi kepada pendaftar. Status permohonan akan otomatis berubah menjadi "Revisi" dan pendaftar akan menerima notifikasi serta email.</p>
</div>

@if(session('success'))
    <div style="padding:20px;background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);border-left:4px solid #10B981;border-radius:12px;margin-bottom:24px;color:#065F46;box-shadow:0 4px 12px rgba(16, 185, 129, 0.15);">
        <div style="display:flex;align-items:flex-start;gap:12px;">
            <div style="flex-shrink:0;margin-top:2px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color:#10B981;">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                    <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div style="flex:1;">
                <div style="font-weight:700;font-size:16px;margin-bottom:4px;color:#065F46;">✅ Berhasil</div>
                <div style="font-size:14px;line-height:1.6;color:#047857;">{{ session('success') }}</div>
            </div>
        </div>
    </div>
@endif

@if(session('error') || $errors->any())
    <div style="padding:16px;background:#FEF2F2;border-left:4px solid #EF4444;border-radius:8px;margin-bottom:24px;color:#991B1B">
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

<div class="info-box">
    <p><strong>ℹ️ Informasi:</strong> Ketika Anda mengirim revisi, sistem akan secara otomatis:</p>
    <ul style="margin:8px 0 0 0;padding-left:20px;color:#1E40AF;">
        <li>Mengubah status permohonan menjadi "Revisi"</li>
        <li>Membuat notifikasi di dashboard pendaftar</li>
        <li>Mengirim email ke pendaftar</li>
        <li>Menyimpan catatan revisi untuk referensi</li>
    </ul>
</div>

<div class="form-card">
    <form action="{{ route('admin.store_revisi') }}" method="POST" id="revisiForm">
        @csrf

        <div class="form-group">
            <label class="form-label">
                Pilih Pendaftar <span class="required">*</span>
            </label>
            <select name="user_id" id="user_id" class="form-select" required onchange="loadPermohonan(this.value)">
                <option value="">-- Pilih Pendaftar --</option>
                @foreach($allPendaftar ?? collect() as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $pendaftar->id ?? '') == $user->id ? 'selected' : '' }}>
                        {{ $user->nama }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            <p class="helper-text">Pilih pendaftar yang akan menerima permintaan revisi</p>
        </div>

        <div class="form-group">
            <label class="form-label">
                Pilih Permohonan <span class="required">*</span>
            </label>
            <select name="permohonan_magang_id" id="permohonan_magang_id" class="form-select" required>
                <option value="">-- Pilih Permohonan --</option>
                @if($pendaftar && $permohonan)
                    <option value="{{ $permohonan->id }}" selected>
                        Permohonan #{{ $permohonan->id }} - Status: {{ $permohonan->status }} ({{ $permohonan->created_at->format('d M Y') }})
                    </option>
                @endif
            </select>
            <p class="helper-text">Pilih permohonan yang akan direvisi</p>
        </div>

        @if($pendaftar)
            <div class="pendaftar-info">
                <h4>📋 Informasi Pendaftar</h4>
                <p><strong>Nama:</strong> {{ $pendaftar->nama }}</p>
                <p><strong>Email:</strong> {{ $pendaftar->email }}</p>
                @if($permohonan)
                    <p><strong>Status Permohonan:</strong> <span style="color: #F59E0B; font-weight: 600;">{{ $permohonan->status }}</span></p>
                    <p><strong>Tanggal Pengajuan:</strong> {{ $permohonan->created_at->format('d F Y') }}</p>
                @endif
            </div>
        @endif

        <div class="form-group">
            <label class="form-label">
                Catatan Revisi <span class="required">*</span>
            </label>
            <textarea name="catatan_revisi" class="form-control" required placeholder="Tuliskan instruksi atau catatan revisi untuk pendaftar. Contoh: Mohon perbaiki bagian halaman 2 pada proposal, lampirkan transkrip nilai jika perlu.">{{ old('catatan_revisi') }}</textarea>
            <p class="helper-text">Catatan ini akan ditampilkan di dashboard pendaftar dan dikirim via email</p>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">📧 Kirim Revisi & Email</button>
            <a href="{{ route('admin.data_pendaftar') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
function loadPermohonan(userId) {
    const permohonanSelect = document.getElementById('permohonan_magang_id');
    
    if (!userId) {
        permohonanSelect.innerHTML = '<option value="">-- Pilih Permohonan --</option>';
        return;
    }
    
    // Fetch permohonan untuk user ini
    fetch(`{{ url('/admin/api/permohonan-user') }}/${userId}`)
        .then(response => response.json())
        .then(data => {
            permohonanSelect.innerHTML = '<option value="">-- Pilih Permohonan --</option>';
            
            if (data.success && data.permohonan && data.permohonan.length > 0) {
                data.permohonan.forEach(permohonan => {
                    const option = document.createElement('option');
                    option.value = permohonan.id;
                    const date = new Date(permohonan.created_at);
                    const formattedDate = date.toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
                    option.textContent = `Permohonan #${permohonan.id} - Status: ${permohonan.status} (${formattedDate})`;
                    permohonanSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = '-- Tidak ada permohonan yang bisa direvisi --';
                permohonanSelect.appendChild(option);
            }
        })
        .catch(error => {
            console.error('Error loading permohonan:', error);
            permohonanSelect.innerHTML = '<option value="">-- Error memuat permohonan --</option>';
        });
}

// Load permohonan saat halaman dimuat jika user sudah dipilih
document.addEventListener('DOMContentLoaded', function() {
    const userIdSelect = document.getElementById('user_id');
    if (userIdSelect && userIdSelect.value) {
        loadPermohonan(userIdSelect.value);
    }
});
</script>
@endsection

