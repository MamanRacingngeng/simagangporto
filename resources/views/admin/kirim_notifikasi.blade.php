@extends('layouts.admin')

@section('title', 'Kirim Notifikasi - SIMAGANG')

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
        border-color: #DC2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    textarea.form-control {
        min-height: 120px;
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

    .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-check input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .form-check label {
        margin: 0;
        cursor: pointer;
        font-size: 14px;
        color: #374151;
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
        background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
        color: #FFFFFF;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .btn-secondary {
        background: #F3F4F6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #E5E7EB;
    }

    .pendaftar-info {
        background: #F9FAFB;
        border-left: 4px solid #DC2626;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
    }

    .pendaftar-info h4 {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        margin: 0 0 8px 0;
    }

    .pendaftar-info p {
        font-size: 14px;
        color: #6B7280;
        margin: 4px 0;
    }

    .helper-text {
        font-size: 12px;
        color: #6B7280;
        margin-top: 4px;
    }

    .tipe-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .tipe-option {
        padding: 12px 16px;
        border: 2px solid #E5E7EB;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .tipe-option input[type="radio"] {
        display: none;
    }

    .tipe-option input[type="radio"]:checked + label {
        font-weight: 600;
    }

    .tipe-option:has(input[type="radio"]:checked) {
        border-color: #DC2626;
        background: #FEF2F2;
    }

    .tipe-option label {
        margin: 0;
        cursor: pointer;
        font-size: 14px;
        color: #374151;
        display: block;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Kirim Notifikasi</h1>
</div>

@if(session('success'))
    <div style="padding:16px;background:#ECFDF5;border-left:4px solid #10B981;border-radius:8px;margin-bottom:24px;color:#065F46">
        {{ session('success') }}
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

<div class="form-card">
    <form action="{{ route('admin.store_notifikasi') }}" method="POST">
        @csrf

        @if($pendaftar)
            <div class="pendaftar-info">
                <h4>📋 Informasi Pendaftar</h4>
                <p><strong>Nama:</strong> {{ $pendaftar->nama }}</p>
                <p><strong>Email:</strong> {{ $pendaftar->email }}</p>
                @if($permohonan)
                    <p><strong>Status Permohonan:</strong> {{ $permohonan->status }}</p>
                    @if($permohonan->kuotaMagang && $permohonan->kuotaMagang->count() > 0)
                        <p><strong>Divisi:</strong> {{ $permohonan->kuotaMagang->first()->posisi ?? '-' }}</p>
                    @endif
                @endif
            </div>
            <input type="hidden" name="user_id" value="{{ $pendaftar->id }}">
            @if($permohonan)
                <input type="hidden" name="permohonan_magang_id" value="{{ $permohonan->id }}">
            @endif
        @else
            <div class="form-group">
                <label class="form-label">
                    Pilih Pendaftar <span class="required">*</span>
                </label>
                <select name="user_id" class="form-select" required>
                    <option value="">-- Pilih Pendaftar --</option>
                    @foreach($allPendaftar ?? collect() as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->nama }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                <p class="helper-text">Pilih pendaftar yang akan menerima notifikasi</p>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Permohonan (Opsional)
                </label>
                <select name="permohonan_magang_id" class="form-select">
                    <option value="">-- Tidak ada permohonan spesifik --</option>
                </select>
                <p class="helper-text">Pilih permohonan terkait jika ada</p>
            </div>
        @endif

        <div class="form-group">
            <label class="form-label">
                Judul Notifikasi <span class="required">*</span>
            </label>
            <input type="text" name="judul" class="form-control" value="{{ old('judul', 'Pemberitahuan: Kekurangan Dokumen Persyaratan') }}" required placeholder="Contoh: Pemberitahuan Kekurangan Dokumen">
        </div>

        <div class="form-group">
            <label class="form-label">
                Tipe Notifikasi <span class="required">*</span>
            </label>
            <div class="tipe-options">
                <div class="tipe-option">
                    <input type="radio" name="tipe" id="tipe_info" value="info" {{ old('tipe', 'warning') == 'info' ? 'checked' : '' }}>
                    <label for="tipe_info">ℹ️ Info</label>
                </div>
                <div class="tipe-option">
                    <input type="radio" name="tipe" id="tipe_warning" value="warning" {{ old('tipe', 'warning') == 'warning' ? 'checked' : '' }}>
                    <label for="tipe_warning">⚠️ Warning</label>
                </div>
                <div class="tipe-option">
                    <input type="radio" name="tipe" id="tipe_error" value="error" {{ old('tipe') == 'error' ? 'checked' : '' }}>
                    <label for="tipe_error">❌ Error</label>
                </div>
                <div class="tipe-option">
                    <input type="radio" name="tipe" id="tipe_success" value="success" {{ old('tipe') == 'success' ? 'checked' : '' }}>
                    <label for="tipe_success">✅ Success</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">
                Pesan Notifikasi <span class="required">*</span>
            </label>
            <textarea name="pesan" class="form-control" required placeholder="Tuliskan pesan notifikasi yang akan dikirim kepada pendaftar...">{{ old('pesan', 'Kami menemukan bahwa dokumen persyaratan Anda belum lengkap. Silakan lengkapi dokumen berikut:

- CV
- Surat Pengantar
- Proposal

Harap segera melengkapi dokumen tersebut agar proses verifikasi dapat dilanjutkan. Terima kasih.') }}</textarea>
            <p class="helper-text">Pesan akan ditampilkan di dashboard pendaftar dan dikirim via email jika dicentang</p>
        </div>

        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" name="kirim_email" id="kirim_email" value="1" {{ old('kirim_email') ? 'checked' : 'checked' }}>
                <label for="kirim_email">Kirim juga via email</label>
            </div>
            <p class="helper-text">Notifikasi akan dikirim ke email pendaftar selain disimpan di sistem</p>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Kirim Notifikasi</button>
            <a href="{{ route('admin.notifikasi_kekurangan_syarat') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
// Auto-populate permohonan berdasarkan user yang dipilih
document.querySelector('select[name="user_id"]')?.addEventListener('change', function() {
    const userId = this.value;
    const permohonanSelect = document.querySelector('select[name="permohonan_magang_id"]');
    
    if (!userId || !permohonanSelect) return;
    
    // Clear existing options except first
    permohonanSelect.innerHTML = '<option value="">-- Tidak ada permohonan spesifik --</option>';
    
    // Fetch permohonan for this user (simplified - in production use AJAX)
    // For now, we'll leave it as is since we need backend API
});
</script>
@endsection
