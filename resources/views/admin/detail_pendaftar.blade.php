@extends('layouts.admin')

@section('title', 'Detail Pendaftar - SIMAGANG')

@section('content')
<style>
    .page-header {
        margin-bottom: 24px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .tabs-container {
        background: #FFFFFF;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(15, 23, 42, 0.03);
        border: 1px solid #E5E7EB;
        overflow: hidden;
    }

    .tabs-header {
        display: flex;
        border-bottom: 2px solid #F3F4F6;
        background: #F9FAFB;
    }

    .tab-button {
        flex: 1;
        padding: 16px 24px;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        font-size: 15px;
        font-weight: 600;
        color: #6B7280;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .tab-button:hover {
        background: #F3F4F6;
        color: #374151;
    }

    .tab-button.active {
        color: #DC2626;
        border-bottom-color: #DC2626;
        background: #FFFFFF;
    }

    .tab-content {
        display: none;
        padding: 32px;
    }

    .tab-content.active {
        display: block;
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
    }

    .info-card {
        padding: 20px;
        background: #F9FAFB;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
    }

    .info-label {
        font-size: 13px;
        color: #6B7280;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .info-value {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
    }

    .doc-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
    }

    .doc-card {
        padding: 20px;
        background: #F9FAFB;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
        transition: all 0.2s ease;
    }

    .doc-card:hover {
        background: #F3F4F6;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .doc-title {
        font-weight: 600;
        margin-bottom: 12px;
        color: #111827;
    }

    .doc-status {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .status-icon {
        width: 20px;
        height: 20px;
    }

    .btn-download {
        padding: 10px 20px;
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
        color: #FFFFFF;
        text-decoration: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
        transition: all 0.2s ease;
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .actions-section {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(15, 23, 42, 0.03);
        border: 1px solid #E5E7EB;
        margin-top: 24px;
    }

    .actions-title {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 24px;
    }

    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .btn-action {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .btn-verify {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: #FFFFFF;
    }

    .btn-accept {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: #FFFFFF;
    }

    .btn-warning {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        color: #FFFFFF;
    }

    .btn-reject {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        color: #FFFFFF;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease-out;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 32px;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
        margin-bottom: 20px;
    }

    .modal-title {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px 0;
    }

    .modal-subtitle {
        font-size: 14px;
        color: #6B7280;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #D1D5DB;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
        min-height: 120px;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #DC2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
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
        border-color: #F59E0B;
        background: #FEF7ED;
    }

    .tipe-option label {
        margin: 0;
        cursor: pointer;
        font-size: 14px;
        color: #374151;
        display: block;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
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

    .modal-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 24px;
    }

    .btn-cancel {
        padding: 10px 20px;
        background: #F3F4F6;
        color: #374151;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: #E5E7EB;
    }

    .btn-submit-modal {
        padding: 10px 20px;
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        color: #FFFFFF;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-submit-modal:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 8px;
    }

    .status-badge.diajukan {
        background: #EFF6FF;
        color: #2563EB;
    }

    .status-badge.diverifikasi {
        background: #FFFBEB;
        color: #F59E0B;
    }

    .status-badge.diterima {
        background: #ECFDF5;
        color: #10B981;
    }

    .status-badge.ditolak {
        background: #FEF2F2;
        color: #EF4444;
    }

    .status-badge.default {
        background: #F3F4F6;
        color: #6B7280;
    }

    .doc-status-text.available {
        color: #10B981;
        font-weight: 600;
    }

    .doc-status-text.unavailable {
        color: #EF4444;
        font-weight: 600;
    }

    .log-item {
        padding: 16px;
        background: #F9FAFB;
        border-radius: 10px;
        border-left: 3px solid #E5E7EB;
        margin-bottom: 12px;
    }

    .log-time {
        font-size: 12px;
        color: #6B7280;
        margin-bottom: 4px;
    }

    .log-text {
        font-size: 14px;
        color: #374151;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Detail Pendaftar</h1>
</div>

@if(session('success'))
    <div style="padding:20px;background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);border-left:4px solid #10B981;border-radius:12px;margin-bottom:24px;color:#065F46;animation: fadeIn 0.3s ease-out;box-shadow:0 4px 12px rgba(16, 185, 129, 0.15);">
        <div style="display:flex;align-items:flex-start;gap:12px;">
            <div style="flex-shrink:0;margin-top:2px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color:#10B981;">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                    <path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div style="flex:1;">
                <div style="font-weight:700;font-size:16px;margin-bottom:4px;color:#065F46;">
                    ✅ Berhasil
                </div>
                <div style="font-size:14px;line-height:1.6;color:#047857;">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div style="padding:16px;background:#FEF2F2;border-left:4px solid #EF4444;border-radius:8px;margin-bottom:24px;color:#991B1B;animation: fadeIn 0.3s ease-out">
        {{ session('error') }}
    </div>
@endif

<!-- Tabs Container -->
<div class="tabs-container">
    <div class="tabs-header">
        <button class="tab-button active" onclick="switchTab('data-diri', this)">Data Diri</button>
        <button class="tab-button" onclick="switchTab('dokumen', this)">Dokumen</button>
        <button class="tab-button" onclick="switchTab('log-status', this)">Log Status</button>
    </div>

    <!-- Tab: Data Diri -->
    <div id="tab-data-diri" class="tab-content active">
        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value">{{ $permohonan->user->nama ?? '-' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $permohonan->user->email ?? '-' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">No. Telepon</div>
                <div class="info-value">{{ $permohonan->user->no_telepon ?? '-' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Instansi</div>
                <div class="info-value">{{ $permohonan->user->instansi ?? $permohonan->user->universitas ?? '-' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Divisi/Posisi yang Dipilih</div>
                <div class="info-value">
                    @if($permohonan->kuotaMagang && $permohonan->kuotaMagang->count() > 0)
                        @php
                            $kuota = $permohonan->kuotaMagang->first();
                            $posisi = $kuota->posisi ?? '-';
                            $periode = $kuota->periode ?? '';
                        @endphp
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <span style="background: #EFF6FF; color: #2563EB; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 600; display: inline-block; width: fit-content;">
                                {{ $posisi }}
                            </span>
                            @if($periode)
                                <span style="color: #6B7280; font-size: 13px;">
                                    Periode: {{ $periode }}
                                </span>
                            @endif
                        </div>
                    @else
                        <span style="color: #9CA3AF; font-style: italic;">Belum memilih divisi</span>
                    @endif
                </div>
            </div>
            <div class="info-card">
                <div class="info-label">Tanggal Pengajuan</div>
                <div class="info-value">{{ $permohonan->tanggal_pengajuan ? \Carbon\Carbon::parse($permohonan->tanggal_pengajuan)->format('d F Y') : $permohonan->created_at->format('d F Y') }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Status</div>
                <div>
                    @php
                    @endphp
                    @php
                        $statusClass = strtolower(optional($permohonan)->status ?? 'default');
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        {{ optional($permohonan)->status }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab: Dokumen -->
    <div id="tab-dokumen" class="tab-content">
        @if($permohonan->dokumen)
            <div class="doc-grid">
                @php
                    $hasCV = !empty($permohonan->dokumen->cv);
                    $hasSurat = !empty($permohonan->dokumen->surat_pengantar);
                    $hasProposal = !empty($permohonan->dokumen->proposal);
                @endphp
                
                <div class="doc-card">
                    <div class="doc-title">CV</div>
                    <div class="doc-status">
                        <span class="status-icon">
                            @if($hasCV)
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 13l4 4L19 7" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @else
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 18L18 6M6 6l12 12" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @endif
                        </span>
                        <span class="doc-status-text {{ $hasCV ? 'available' : 'unavailable' }}">
                            {{ $hasCV ? 'Tersedia' : 'Belum Diunggah' }}
                        </span>
                    </div>
                    @if($hasCV)
                        <a href="{{ asset('storage/' . $permohonan->dokumen->cv) }}" target="_blank" class="btn-download">Download CV</a>
                    @endif
                </div>

                <div class="doc-card">
                    <div class="doc-title">Surat Pengantar</div>
                    <div class="doc-status">
                        <span class="status-icon">
                            @if($hasSurat)
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 13l4 4L19 7" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @else
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 18L18 6M6 6l12 12" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @endif
                        </span>
                        <span class="doc-status-text {{ $hasSurat ? 'available' : 'unavailable' }}">
                            {{ $hasSurat ? 'Tersedia' : 'Belum Diunggah' }}
                        </span>
                    </div>
                    @if($hasSurat)
                        <a href="{{ asset('storage/' . $permohonan->dokumen->surat_pengantar) }}" target="_blank" class="btn-download">Download Surat</a>
                    @endif
                </div>

                <div class="doc-card">
                    <div class="doc-title">Proposal</div>
                    <div class="doc-status">
                        <span class="status-icon">
                            @if($hasProposal)
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 13l4 4L19 7" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @else
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 18L18 6M6 6l12 12" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            @endif
                        </span>
                        <span class="doc-status-text {{ $hasProposal ? 'available' : 'unavailable' }}">
                            {{ $hasProposal ? 'Tersedia' : 'Belum Diunggah' }}
                        </span>
                    </div>
                    @if($hasProposal)
                        <a href="{{ asset('storage/' . $permohonan->dokumen->proposal) }}" target="_blank" class="btn-download">Download Proposal</a>
                    @endif
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 48px; color: #6B7280;">
                <p>Tidak ada dokumen tersedia</p>
            </div>
        @endif
    </div>

    <!-- Tab: Log Status -->
    <div id="tab-log-status" class="tab-content">
        <div class="log-item">
            <div class="log-time">{{ optional($permohonan->created_at)->format('d F Y, H:i') }}</div>
            <div class="log-text">Permohonan dibuat dengan status: <strong>{{ optional($permohonan)->status }}</strong></div>
        </div>
        @if(optional($permohonan->updated_at) && optional($permohonan->updated_at) != optional($permohonan->created_at))
            <div class="log-item">
                <div class="log-time">{{ optional($permohonan->updated_at)->format('d F Y, H:i') }}</div>
                <div class="log-text">Status diperbarui menjadi: <strong>{{ optional($permohonan)->status }}</strong></div>
            </div>
        @endif
        @if($permohonan->alasan_penolakan)
            <div class="log-item" style="border-left-color: #EF4444;">
                <div class="log-time">Alasan Penolakan</div>
                <div class="log-text" style="color: #991B1B;">{{ $permohonan->alasan_penolakan }}</div>
            </div>
        @endif
        @if($permohonan->catatan_revisi)
            <div class="log-item" style="border-left-color: #F59E0B;">
                <div class="log-time">Catatan Revisi</div>
                <div class="log-text" style="color: #92400E;">{{ $permohonan->catatan_revisi }}</div>
            </div>
        @endif
    </div>
</div>

<!-- Aksi Verifikasi Section -->
<div class="actions-section">
    <h2 class="actions-title">Aksi Verifikasi</h2>
    
    @if(optional($permohonan)->status === 'Diajukan')
        <!-- Aksi Verifikasi Dokumen -->
        <div class="action-buttons" style="margin-bottom: 24px;">
            <form action="{{ route('admin.verifikasi_permohonan', $permohonan->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menyetujui dokumen? Status akan diubah menjadi Diverifikasi.')">
                @csrf
                <button type="submit" class="btn-action btn-verify">
                    ✓ Setujui Dokumen (Status → Diverifikasi)
                </button>
            </form>
            <button type="button" class="btn-action btn-warning" onclick="openRevisionModal()">
                ↺ Minta Revisi (Status → Revisi)
            </button>
        </div>
        <p style="margin-top: 12px; font-size: 14px; color: #6B7280;">
            Pilih salah satu: <strong>Setujui</strong> jika dokumen lengkap dan valid, atau <strong>Minta Revisi</strong> jika dokumen perlu diperbaiki.
        </p>
        <p style="margin-top: 8px; font-size: 13px; color: #F59E0B; font-weight: 600;">
            ⚠️ Peringatan: Keputusan yang Anda pilih akan bersifat FINAL dan tidak dapat diubah kembali.
        </p>
    @endif

    @if(optional($permohonan)->status === 'Diverifikasi')
        <!-- Aksi Final: Hanya Diterima atau Ditolak (MUTLAK) -->
        <div class="action-buttons">
            <form action="{{ route('admin.update_status_permohonan', $permohonan->id) }}" method="POST" style="display: inline;">
                @csrf
                <input type="hidden" name="status" value="Diterima">
                <button type="submit" class="btn-action btn-accept" onclick="return confirm('Yakin ingin menerima permohonan ini? Status yang dipilih akan bersifat FINAL dan tidak dapat diubah kembali.')">
                    ✓ Diterima (Final)
                </button>
            </form>
            <button type="button" class="btn-action btn-reject" onclick="openRejectionModal()">
                ✗ Ditolak (Final)
            </button>
        </div>
        <p style="margin-top: 12px; font-size: 14px; color: #F59E0B; font-weight: 600;">
            ⚠️ Peringatan: Keputusan yang Anda pilih akan bersifat FINAL dan tidak dapat diubah kembali.
        </p>
        <p style="margin-top: 8px; font-size: 13px; color: #6B7280;">
            Pilih salah satu: <strong>Diterima</strong> atau <strong>Ditolak</strong> (MUTLAK).
        </p>
    @endif

    @php
        $statusFinal = in_array($permohonan->status, ['Diterima', 'Ditolak']);
    @endphp

    @if($statusFinal)
        <!-- Status Final: Tidak bisa diubah -->
        <div style="padding: 20px; background: #FEF3C7; border-left: 4px solid #F59E0B; border-radius: 8px; margin-top: 16px;">
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div style="flex-shrink: 0;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div style="flex: 1;">
                    <h4 style="margin: 0 0 8px 0; color: #92400E; font-size: 16px; font-weight: 700;">
                        Status Final - Tidak Dapat Diubah
                    </h4>
                    <p style="margin: 0 0 8px 0; color: #92400E; font-size: 14px; line-height: 1.6;">
                        Status "<strong>{{ $permohonan->status }}</strong>" bersifat final dan tidak dapat diubah kembali oleh admin. 
                        Status "Diterima" dan "Ditolak" adalah keputusan permanen.
                    </p>
                    @if($permohonan->status === 'Ditolak')
                        <p style="margin: 0; color: #92400E; font-size: 13px;">
                            <strong>Catatan:</strong> Admin tetap dapat menghapus atau mengubah kuota dan jadwal magang tanpa terpengaruh oleh status final ini.
                        </p>
                    @endif
                </div>
            </div>
        </div>
        
        @if($permohonan->status === 'Diterima')
            <!-- Upload Surat Kerja (SK) untuk Peserta Diterima -->
            <div style="padding: 24px; background: #FFFFFF; border-radius: 12px; border: 2px solid #10B981; margin-top: 24px; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.1);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #10B981 0%, #059669 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #FFFFFF;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 13H8M16 17H8M10 9H8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div>
                        <h4 style="margin: 0 0 4px 0; color: #065F46; font-size: 18px; font-weight: 700;">
                            Upload Surat Kerja (SK)
                        </h4>
                        <p style="margin: 0; color: #047857; font-size: 13px;">
                            Unggah Surat Kerja dari instansi untuk peserta yang diterima. File ini akan dikirim ke email peserta dan tersedia untuk diunduh di dashboard.
                        </p>
                    </div>
                </div>
                
                @if($permohonan->surat_kerja)
                    <div style="padding: 16px; background: #ECFDF5; border-radius: 8px; margin-bottom: 16px; border: 1px solid #A7F3D0;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #10B981;">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div>
                                    <p style="margin: 0; font-size: 14px; font-weight: 600; color: #065F46;">
                                        Surat Kerja sudah diunggah
                                    </p>
                                    <p style="margin: 4px 0 0 0; font-size: 12px; color: #047857;">
                                        {{ basename($permohonan->surat_kerja) }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $permohonan->surat_kerja) }}" target="_blank" style="padding: 8px 16px; background: #10B981; color: #FFFFFF; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <polyline points="7 10 12 15 17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Lihat File
                            </a>
                        </div>
                    </div>
                @endif
                
                <form action="{{ route('admin.upload_sk', $permohonan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #374151;">
                            Pilih File Surat Kerja (PDF) <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="file" name="surat_kerja" accept=".pdf" required style="width: 100%; padding: 12px; border: 2px dashed #D1D5DB; border-radius: 8px; background: #F9FAFB; font-size: 14px; cursor: pointer; transition: all 0.2s ease;" onchange="this.style.borderColor='#10B981'; this.style.background='#ECFDF5';">
                        <p style="margin: 8px 0 0 0; font-size: 12px; color: #6B7280;">
                            Format file: PDF. Maksimal ukuran: 5MB. File akan dikirim ke email peserta secara otomatis.
                        </p>
                    </div>
                    <button type="submit" style="padding: 12px 24px; background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: #FFFFFF; border: none; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                        @if($permohonan->surat_kerja)
                            Ganti Surat Kerja
                        @else
                            Upload Surat Kerja
                        @endif
                    </button>
                </form>
            </div>
        @endif
    @endif
</div>

<!-- Modal Alasan Penolakan -->
<div id="rejectionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Alasan Penolakan</h3>
            <p class="modal-subtitle">Mohon berikan alasan penolakan permohonan ini. Alasan ini akan dikirimkan kepada pendaftar.</p>
        </div>
        <form action="{{ route('admin.update_status_permohonan', $permohonan->id) }}" method="POST" id="rejectionForm">
            @csrf
            <input type="hidden" name="status" value="Ditolak">
            <div class="form-group">
                <label class="form-label">Alasan Penolakan <span style="color: #EF4444;">*</span></label>
                <textarea 
                    name="alasan_penolakan" 
                    class="form-textarea" 
                    placeholder="Contoh: Dokumen tidak lengkap, kuota sudah penuh, dll."
                    required
                >{{ old('alasan_penolakan', $permohonan->alasan_penolakan ?? '') }}</textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeRejectionModal()">Batal</button>
                <button type="submit" class="btn-submit-modal" onclick="return confirm('Yakin ingin menolak permohonan ini? Status Ditolak bersifat FINAL dan tidak dapat diubah kembali.')">
                    @if($permohonan->status === 'Diterima')
                        Ubah ke Ditolak (Final)
                    @else
                        Tolak Permohonan (Final)
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Revisi -->
<div id="revisionModal" class="modal">
    <div class="modal-content" style="max-width: 700px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h3 class="modal-title">📝 Minta Revisi</h3>
            <p class="modal-subtitle">Kirim permintaan revisi kepada pendaftar. Status permohonan akan otomatis berubah menjadi "Perlu di Revisi" dan pendaftar akan menerima notifikasi di dashboard serta email.</p>
        </div>
        
        @if($permohonan && $permohonan->user)
            <div style="background: #FEF7ED; border-left: 4px solid #F59E0B; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
                <h4 style="font-size: 14px; font-weight: 600; color: #92400E; margin: 0 0 8px 0;">📋 Informasi Pendaftar</h4>
                <p style="font-size: 13px; color: #78350F; margin: 4px 0;"><strong>Nama:</strong> {{ $permohonan->user->nama }}</p>
                <p style="font-size: 13px; color: #78350F; margin: 4px 0;"><strong>Email:</strong> {{ $permohonan->user->email }}</p>
                <p style="font-size: 13px; color: #78350F; margin: 4px 0;"><strong>Status Permohonan:</strong> <span style="color: #F59E0B; font-weight: 600;">{{ $permohonan->status }}</span></p>
                <p style="font-size: 13px; color: #78350F; margin: 4px 0;"><strong>Tanggal Pengajuan:</strong> {{ $permohonan->created_at->format('d F Y') }}</p>
            </div>
        @endif

        <form action="{{ route('admin.store_revisi') }}" method="POST" id="revisionForm" onsubmit="return confirm('Yakin ingin mengirim revisi? Status permohonan akan diubah menjadi \'Revisi\' dan pendaftar akan menerima notifikasi serta email.');">
            @csrf
            <input type="hidden" name="user_id" value="{{ $permohonan->user->id ?? '' }}">
            <input type="hidden" name="permohonan_magang_id" value="{{ $permohonan->id ?? '' }}">
            
            <div class="form-group">
                <label class="form-label">
                    Judul Notifikasi <span style="color: #EF4444;">*</span>
                </label>
                <input type="text" name="judul" id="judul_notifikasi" class="form-control" value="{{ old('judul', 'Permohonan Magang Memerlukan Revisi') }}" required placeholder="Contoh: Permohonan Magang Memerlukan Revisi">
                <p style="font-size: 12px; color: #6B7280; margin-top: 4px;">Judul notifikasi yang akan ditampilkan di dashboard pendaftar</p>
            </div>

            <input type="hidden" name="tipe" value="revisi">
            
            <div class="form-group">
                <label class="form-label">
                    Pesan Notifikasi <span style="color: #EF4444;">*</span>
                </label>
                <textarea 
                    name="pesan" 
                    id="pesan_notifikasi"
                    class="form-textarea" 
                    placeholder="Tuliskan pesan notifikasi yang akan dikirim kepada pendaftar..."
                    required
                    style="min-height: 150px;"
                >{{ old('pesan', 'Kepada Yth. Pendaftar Magang,

Permohonan magang Anda memerlukan revisi. Silakan perbaiki dokumen sesuai instruksi di bawah ini.

📋 Catatan Revisi:

[Mohon sebutkan bagian dokumen yang perlu diperbaiki, contoh: CV, Surat Pengantar, atau Proposal]

Mohon perbaiki dokumen yang disebutkan di atas. Setelah dokumen diperbaiki, silakan unggah ulang melalui dashboard Anda. Status lamaran Anda akan berubah menjadi "Perlu di Revisi" sampai dokumen yang diperbaiki telah diunggah ulang.

⚠️ Penting: Pastikan semua dokumen yang diminta telah dilengkapi dengan benar sebelum mengunggah ulang.

Jika ada pertanyaan, silakan hubungi kami melalui email atau hubungi admin sistem.

Terima kasih.') }}</textarea>
                <p style="font-size: 12px; color: #6B7280; margin-top: 4px;">Pesan ini akan ditampilkan di dashboard pendaftar, dikirim via email, dan status lamaran akan berubah menjadi "Perlu di Revisi"</p>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" name="kirim_email" id="kirim_email" value="1" {{ old('kirim_email', true) ? 'checked' : '' }}>
                    <label for="kirim_email">Kirim juga via email</label>
                </div>
                <p style="font-size: 12px; color: #6B7280; margin-top: 4px;">Notifikasi akan dikirim ke email pendaftar selain disimpan di sistem</p>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeRevisionModal()">Batal</button>
                <button type="submit" class="btn-submit-modal" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
                    Kirim Revisi
                </button>
            </div>
        </form>
    </div>
</div>

<div style="margin-top: 24px;">
    <a href="{{ route('admin.data_pendaftar') }}" style="padding: 12px 24px; background: #6B7280; color: #FFFFFF; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-block;">
        ← Kembali ke Data Pendaftar
    </a>
</div>

<script>
function switchTab(tabName, buttonElement) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });

    // Show selected tab
    document.getElementById('tab-' + tabName).classList.add('active');
    buttonElement.classList.add('active');
}

function openRejectionModal() {
    document.getElementById('rejectionModal').classList.add('active');
}

function openRejectionModalFromDiajukan() {
    // Pastikan form action menggunakan route yang benar untuk status Diajukan
    const form = document.getElementById('rejectionForm');
    if (form) {
        form.action = '{{ route("admin.update_status_permohonan", $permohonan->id) }}';
    }
    document.getElementById('rejectionModal').classList.add('active');
}

function closeRejectionModal() {
    document.getElementById('rejectionModal').classList.remove('active');
}

// Close modal when clicking outside
document.getElementById('rejectionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectionModal();
    }
});

// Revision modal handlers
function openRevisionModal() {
    document.getElementById('revisionModal').classList.add('active');
}

function openRevisionModalFromDiajukan() {
    // Pastikan form action tetap menggunakan route store_revisi
    const form = document.getElementById('revisionForm');
    if (form) {
        // Form action sudah benar di HTML, tidak perlu diubah
        // form.action = '{{ route("admin.store_revisi") }}';
    }
    openRevisionModal();
}

function openRevisionModalFromDiverifikasi() {
    // Pastikan form action tetap menggunakan route store_revisi
    const form = document.getElementById('revisionForm');
    if (form) {
        // Form action sudah benar di HTML, tidak perlu diubah
        // form.action = '{{ route("admin.store_revisi") }}';
    }
    openRevisionModal();
}

function closeRevisionModal() {
    document.getElementById('revisionModal').classList.remove('active');
}

// Close modal when clicking outside
document.getElementById('revisionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRevisionModal();
    }
});
</script>
@endsection
