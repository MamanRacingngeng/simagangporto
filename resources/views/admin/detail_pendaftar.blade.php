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
    <div style="padding:16px;background:#ECFDF5;border-left:4px solid #10B981;border-radius:8px;margin-bottom:24px;color:#065F46;animation: fadeIn 0.3s ease-out">
        {{ session('success') }}
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
                        $statusClass = strtolower($permohonan->status ?? 'default');
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        {{ $permohonan->status }}
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
            <div class="log-time">{{ $permohonan->created_at->format('d F Y, H:i') }}</div>
            <div class="log-text">Permohonan dibuat dengan status: <strong>{{ $permohonan->status }}</strong></div>
        </div>
        @if($permohonan->updated_at != $permohonan->created_at)
            <div class="log-item">
                <div class="log-time">{{ $permohonan->updated_at->format('d F Y, H:i') }}</div>
                <div class="log-text">Status diperbarui menjadi: <strong>{{ $permohonan->status }}</strong></div>
            </div>
        @endif
        @if($permohonan->alasan_penolakan)
            <div class="log-item" style="border-left-color: #EF4444;">
                <div class="log-time">Alasan Penolakan</div>
                <div class="log-text" style="color: #991B1B;">{{ $permohonan->alasan_penolakan }}</div>
            </div>
        @endif
    </div>
</div>

<!-- Aksi Verifikasi Section -->
<div class="actions-section">
    <h2 class="actions-title">Aksi Verifikasi</h2>
    
    @if($permohonan->status === 'Diajukan')
        <!-- Aksi Verifikasi Dokumen -->
        <div class="action-buttons" style="margin-bottom: 24px;">
            <form action="{{ route('admin.verifikasi_permohonan', $permohonan->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menyetujui dokumen? Status akan diubah menjadi "Diverifikasi".')">
                @csrf
                <button type="submit" class="btn-action btn-verify">
                    ✓ Setujui Dokumen (Status → Diverifikasi)
                </button>
            </form>
            <button type="button" class="btn-action btn-reject" onclick="openRejectionModalFromDiajukan()">
                ✗ Tolak Dokumen (Status → Ditolak)
            </button>
        </div>
        <p style="margin-top: 12px; font-size: 14px; color: #6B7280;">
            Pilih salah satu: <strong>Setujui</strong> jika dokumen lengkap dan valid, atau <strong>Tolak</strong> jika dokumen tidak memenuhi syarat.
        </p>
        <p style="margin-top: 8px; font-size: 13px; color: #F59E0B; font-weight: 600;">
            ⚠️ Peringatan: Keputusan yang Anda pilih akan bersifat FINAL dan tidak dapat diubah kembali.
        </p>
    @endif

    @if($permohonan->status === 'Diverifikasi')
        <!-- Aksi 2: Keputusan (Diterima/Ditolak) -->
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
            Pilih salah satu: Terima permohonan atau Tolak dengan alasan
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
</script>
@endsection
