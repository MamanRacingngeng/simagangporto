@extends('layouts.admin')

@section('title', 'Data Pendaftar - SIMAGANG')

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

    .filter-section {
        background: #FFFFFF;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
    }

    .filter-label {
        font-weight: 600;
        color: #374151;
        margin-right: 16px;
        font-size: 14px;
    }

    .filter-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .filter-btn {
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .filter-btn.active {
        color: #FFFFFF;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .filter-btn:not(.active) {
        background: #F3F4F6;
        color: #374151;
    }

    .filter-btn:not(.active):hover {
        background: #E5E7EB;
    }

    .filter-btn.active.all {
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
    }

    .filter-btn.active.diajukan {
        background: linear-gradient(135deg, #06B6D4 0%, #0891B2 100%);
    }

    .filter-btn.active.diverifikasi {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    }

    .filter-btn.active.diterima {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    }

    .filter-btn.active.ditolak {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    }

    .data-table-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(15, 23, 42, 0.03);
        border: 1px solid #E5E7EB;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #F9FAFB;
        border-bottom: 2px solid #E5E7EB;
    }

    th {
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        color: #6B7280;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 16px;
        border-bottom: 1px solid #F3F4F6;
        color: #374151;
        font-size: 14px;
    }

    tbody tr {
        transition: background 0.2s ease;
    }

    tbody tr:hover {
        background: #F9FAFB;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
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

    .btn-delete {
        padding: 8px 16px;
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        color: #FFFFFF;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-block;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    /* Modal Styles */
    .modal-overlay {
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
        animation: fadeIn 0.2s ease-out;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 32px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease-out;
    }

    .modal-header {
        margin-bottom: 20px;
    }

    .modal-title {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px 0;
    }

    .modal-body {
        margin-bottom: 24px;
        color: #374151;
        line-height: 1.6;
    }

    .modal-info {
        background: #F9FAFB;
        padding: 16px;
        border-radius: 8px;
        margin: 16px 0;
        border-left: 4px solid #EF4444;
    }

    .modal-info-item {
        margin: 8px 0;
        font-size: 14px;
    }

    .modal-info-label {
        font-weight: 600;
        color: #6B7280;
        display: inline-block;
        width: 120px;
    }

    .modal-info-value {
        color: #111827;
    }

    .modal-footer {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .btn-cancel {
        padding: 10px 20px;
        background: #F3F4F6;
        color: #374151;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: #E5E7EB;
    }

    .btn-confirm-delete {
        padding: 10px 20px;
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        color: #FFFFFF;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-confirm-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .doc-status {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .doc-icon {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .doc-icon.check {
        color: #10B981;
    }

    .doc-icon.cross {
        color: #EF4444;
    }

    .btn-detail {
        padding: 8px 16px;
        background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
        color: #FFFFFF;
        text-decoration: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-block;
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: #6B7280;
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Data Pendaftar</h1>
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

<!-- Filter Status -->
<div class="filter-section">
    <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 16px;">
        <span class="filter-label">Filter Status:</span>
        <div class="filter-buttons">
            <a href="{{ route('admin.data_pendaftar') }}" class="filter-btn {{ !request('status') ? 'active all' : '' }}">
                Semua
            </a>
            <a href="{{ route('admin.data_pendaftar', ['status' => 'Diajukan']) }}" class="filter-btn {{ request('status') == 'Diajukan' ? 'active diajukan' : '' }}">
                Diajukan
            </a>
            <a href="{{ route('admin.data_pendaftar', ['status' => 'Diverifikasi']) }}" class="filter-btn {{ request('status') == 'Diverifikasi' ? 'active diverifikasi' : '' }}">
                Diverifikasi
            </a>
            <a href="{{ route('admin.data_pendaftar', ['status' => 'Diterima']) }}" class="filter-btn {{ request('status') == 'Diterima' ? 'active diterima' : '' }}">
                Diterima
            </a>
            <a href="{{ route('admin.data_pendaftar', ['status' => 'Ditolak']) }}" class="filter-btn {{ request('status') == 'Ditolak' ? 'active ditolak' : '' }}">
                Ditolak
            </a>
        </div>
    </div>
</div>

<!-- Tabel Data Pendaftar -->
@if(isset($permohonan) && $permohonan->count() > 0)
    <div class="data-table-card">
        <table>
            <thead>
                <tr>
                    <th>Nama Pendaftar</th>
                    <th>Email</th>
                    <th>Divisi/Posisi</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status Dokumen</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permohonan as $p)
                    @php
                        $hasCV = $p->dokumen && !empty($p->dokumen->cv);
                        $hasSurat = $p->dokumen && !empty($p->dokumen->surat_pengantar);
                        $hasProposal = $p->dokumen && !empty($p->dokumen->proposal);
                    @endphp
                    <tr>
                        <td style="font-weight: 600; color: #111827;">{{ $p->user->nama ?? '-' }}</td>
                        <td>{{ $p->user->email ?? '-' }}</td>
                        <td>
                            @if($p->kuotaMagang && $p->kuotaMagang->count() > 0)
                                @php
                                    $kuota = $p->kuotaMagang->first();
                                    $posisi = $kuota->posisi ?? '-';
                                    $periode = $kuota->periode ?? '';
                                @endphp
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <span style="background: #EFF6FF; color: #2563EB; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-block; width: fit-content;">
                                        {{ $posisi }}
                                    </span>
                                    @if($periode)
                                        <span style="color: #6B7280; font-size: 12px;">
                                            Periode: {{ $periode }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span style="color: #9CA3AF; font-style: italic;">Belum memilih divisi</span>
                            @endif
                        </td>
                        <td>{{ $p->tanggal_pengajuan ? \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d/m/Y') : $p->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="doc-status">
                                <div class="doc-icon {{ $hasCV ? 'check' : 'cross' }}" title="CV">
                                    @if($hasCV)
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @else
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="doc-icon {{ $hasSurat ? 'check' : 'cross' }}" title="Surat Pengantar">
                                    @if($hasSurat)
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @else
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="doc-icon {{ $hasProposal ? 'check' : 'cross' }}" title="Proposal">
                                    @if($hasProposal)
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @else
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusClass = strtolower($p->status ?? 'default');
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.detail_pendaftar', $p->id) }}" class="btn-detail">Detail</a>
                                <button type="button" class="btn-delete" 
                                    data-id="{{ $p->id }}"
                                    data-nama="{{ $p->user->nama ?? 'N/A' }}"
                                    data-email="{{ $p->user->email ?? 'N/A' }}"
                                    data-status="{{ $p->status }}">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Pagination -->
        @if(isset($permohonan) && $permohonan->hasPages())
            <div style="margin-top: 24px; display: flex; justify-content: center;">
                {{ $permohonan->links() }}
            </div>
        @endif
    </div>
@else
    <div class="admin-card" style="text-align:center;padding:48px">
        <div class="empty-state-icon">📋</div>
        <p style="margin:0;color:#6b7280;font-size:16px">Tidak ada data pendaftar dengan status ini.</p>
    </div>
@endif

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-overlay" onclick="closeDeleteModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h2 class="modal-title">Konfirmasi Hapus Pendaftar</h2>
        </div>
        <div class="modal-body">
            <p style="margin-bottom: 16px; color: #EF4444; font-weight: 600;">
                ⚠️ Peringatan: Tindakan ini tidak dapat dibatalkan!
            </p>
            <p>Anda yakin ingin menghapus data pendaftar berikut?</p>
            <div class="modal-info">
                <div class="modal-info-item">
                    <span class="modal-info-label">Nama:</span>
                    <span class="modal-info-value" id="modalNama">-</span>
                </div>
                <div class="modal-info-item">
                    <span class="modal-info-label">Email:</span>
                    <span class="modal-info-value" id="modalEmail">-</span>
                </div>
                <div class="modal-info-item">
                    <span class="modal-info-label">Status:</span>
                    <span class="modal-info-value" id="modalStatus">-</span>
                </div>
            </div>
            <p style="font-size: 13px; color: #6B7280; margin-top: 16px;" id="modalWarning">
                Data permohonan magang, dokumen terkait, dan relasi dengan kuota akan dihapus permanen dari sistem. Pengguna dapat mencoba lagi dengan akun yang sama setelah penghapusan.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Batal</button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-confirm-delete">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
// Event delegation untuk tombol hapus
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-delete').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            const email = this.getAttribute('data-email');
            const status = this.getAttribute('data-status');
            
            showDeleteModal(id, nama, email, status);
        });
    });
});

function showDeleteModal(id, nama, email, status) {
    document.getElementById('modalNama').textContent = nama;
    document.getElementById('modalEmail').textContent = email;
    document.getElementById('modalStatus').textContent = status;
    document.getElementById('deleteForm').action = '{{ route("admin.delete_permohonan", ":id") }}'.replace(':id', id);
    
    // Update warning message berdasarkan status
    const warningElement = document.getElementById('modalWarning');
    if (status === 'Diterima') {
        warningElement.innerHTML = 'Data permohonan magang, dokumen terkait, dan relasi dengan kuota akan dihapus permanen dari sistem. <strong style="color: #EF4444;">Jika status "Diterima", kuota akan dikembalikan.</strong> Pengguna dapat mencoba lagi dengan akun yang sama setelah penghapusan.';
    } else {
        warningElement.innerHTML = 'Data permohonan magang, dokumen terkait, dan relasi dengan kuota akan dihapus permanen dari sistem. Pengguna dapat mencoba lagi dengan akun yang sama setelah penghapusan.';
    }
    
    document.getElementById('deleteModal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection
