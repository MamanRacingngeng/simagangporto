@extends('layouts.admin')

@section('title', 'Pengawasan Sumber Daya - SIMAGANG')

@section('content')
<style>
    .page-header {
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .summary-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(15, 23, 42, 0.03);
        border: 1px solid #E5E7EB;
        border-left: 4px solid #3B82F6;
    }

    .summary-card h3 {
        font-size: 13px;
        font-weight: 600;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0 0 12px 0;
    }

    .summary-card .value {
        font-size: 32px;
        font-weight: 800;
        color: #111827;
        margin: 0;
        line-height: 1;
    }

    .divisi-section {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(15, 23, 42, 0.03);
        border: 1px solid #E5E7EB;
    }

    .divisi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #F3F4F6;
    }

    .divisi-title {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .divisi-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-item {
        padding: 16px;
        background: #F9FAFB;
        border-radius: 12px;
        border-left: 3px solid #3B82F6;
    }

    .stat-item .label {
        font-size: 12px;
        color: #6B7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 8px;
    }

    .stat-item .value {
        font-size: 24px;
        font-weight: 800;
        color: #111827;
        margin: 0;
    }

    .progress-bar-container {
        margin-bottom: 24px;
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
        color: #374151;
        font-weight: 500;
    }

    .progress-bar-wrapper {
        height: 16px;
        background: #F3F4F6;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #10B981 0%, #059669 100%);
        border-radius: 10px;
        transition: width 0.6s ease-out;
    }

    .distribusi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .distribusi-item {
        padding: 16px;
        border-radius: 12px;
        text-align: center;
    }

    .distribusi-item.diajukan {
        background: #EFF6FF;
        border: 2px solid #2563EB;
    }

    .distribusi-item.diverifikasi {
        background: #FFFBEB;
        border: 2px solid #F59E0B;
    }

    .distribusi-item.diterima {
        background: #ECFDF5;
        border: 2px solid #10B981;
    }

    .distribusi-item.ditolak {
        background: #FEF2F2;
        border: 2px solid #EF4444;
    }

    .distribusi-item .label {
        font-size: 12px;
        font-weight: 600;
        color: #6B7280;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .distribusi-item .value {
        font-size: 28px;
        font-weight: 800;
        margin: 0;
    }

    .distribusi-item.diajukan .value { color: #2563EB; }
    .distribusi-item.diverifikasi .value { color: #F59E0B; }
    .distribusi-item.diterima .value { color: #10B981; }
    .distribusi-item.ditolak .value { color: #EF4444; }

    .periode-detail {
        margin-top: 24px;
    }

    .periode-detail h4 {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 16px 0;
    }

    .periode-table {
        width: 100%;
        border-collapse: collapse;
        background: #FFFFFF;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .periode-table thead {
        background: #F9FAFB;
    }

    .periode-table th {
        padding: 12px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #E5E7EB;
    }

    .periode-table td {
        padding: 14px 16px;
        font-size: 14px;
        color: #374151;
        border-bottom: 1px solid #F3F4F6;
    }

    .periode-table tbody tr:hover {
        background: #F9FAFB;
    }

    .badge-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        margin-right: 4px;
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

    .menu-action-card {
        display: block;
        background: #FFFFFF;
        border: 2px solid #E5E7EB;
        border-radius: 16px;
        padding: 32px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .menu-action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3B82F6, #EC4899);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .menu-action-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        border-color: #3B82F6;
    }

    .menu-action-card:hover::before {
        transform: scaleX(1);
    }

    .menu-action-card:first-child:hover {
        border-color: #3B82F6;
    }

    .menu-action-card:first-child:hover .menu-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    }

    .menu-action-card:first-child:hover .menu-icon-wrapper svg {
        color: #FFFFFF;
    }

    .menu-action-card:last-child:hover {
        border-color: #EC4899;
    }

    .menu-action-card:last-child:hover .menu-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
        background: linear-gradient(135deg, #EC4899 0%, #DB2777 100%);
    }

    .menu-action-card:last-child:hover .menu-icon-wrapper svg {
        color: #FFFFFF;
    }

    .menu-action-card:hover .menu-link-arrow {
        transform: translateX(4px);
    }

    .menu-icon-wrapper {
        transition: all 0.3s ease;
    }

    .menu-link-arrow {
        transition: transform 0.3s ease;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Pengawasan Sumber Daya Magang</h1>
</div>

<!-- Statistik Keseluruhan -->
<div class="summary-cards">
    <div class="summary-card">
        <h3>Total Divisi</h3>
        <p class="value">{{ $statistikKeseluruhan['total_divisi'] }}</p>
    </div>
    <div class="summary-card" style="border-left-color: #10B981;">
        <h3>Total Kuota</h3>
        <p class="value">{{ $statistikKeseluruhan['total_kuota_keseluruhan'] }}</p>
    </div>
    <div class="summary-card" style="border-left-color: #3B82F6;">
        <h3>Kuota Terpakai</h3>
        <p class="value">{{ $statistikKeseluruhan['total_terpakai_keseluruhan'] }}</p>
    </div>
    <div class="summary-card" style="border-left-color: #F59E0B;">
        <h3>Kuota Tersedia</h3>
        <p class="value">{{ $statistikKeseluruhan['total_tersedia_keseluruhan'] }}</p>
    </div>
    <div class="summary-card" style="border-left-color: #EC4899;">
        <h3>Total Pendaftar</h3>
        <p class="value">{{ $statistikKeseluruhan['total_pendaftar_keseluruhan'] }}</p>
    </div>
</div>

<!-- Data Per Divisi -->
@if(isset($dataPerDivisi) && $dataPerDivisi->count() > 0)
    @foreach($dataPerDivisi as $divisi)
        <div class="divisi-section">
            <div class="divisi-header">
                <h2 class="divisi-title">{{ $divisi['posisi'] }}</h2>
                <span style="background: #EFF6FF; color: #2563EB; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 600;">
                    {{ $divisi['total_pendaftar'] }} Pendaftar
                </span>
            </div>

            <!-- Statistik Divisi -->
            <div class="divisi-stats">
                <div class="stat-item" style="border-left-color: #10B981;">
                    <div class="label">Total Kuota</div>
                    <div class="value">{{ $divisi['total_kuota'] }}</div>
                </div>
                <div class="stat-item" style="border-left-color: #3B82F6;">
                    <div class="label">Terpakai</div>
                    <div class="value">{{ $divisi['total_terpakai'] }}</div>
                </div>
                <div class="stat-item" style="border-left-color: #F59E0B;">
                    <div class="label">Tersedia</div>
                    <div class="value">{{ $divisi['total_tersedia'] }}</div>
                </div>
                <div class="stat-item" style="border-left-color: #EC4899;">
                    <div class="label">Total Pendaftar</div>
                    <div class="value">{{ $divisi['total_pendaftar'] }}</div>
                </div>
            </div>

            <!-- Progress Bar Kuota -->
            <div class="progress-bar-container">
                <div class="progress-label">
                    <span>Penggunaan Kuota</span>
                    <span><strong>{{ $divisi['total_terpakai'] }}</strong> / {{ $divisi['total_kuota'] }}</span>
                </div>
                <div class="progress-bar-wrapper">
                    @php
                        $percentage = $divisi['total_kuota'] > 0 ? ($divisi['total_terpakai'] / $divisi['total_kuota']) * 100 : 0;
                        $barColor = $percentage >= 90 ? '#EF4444' : ($percentage >= 70 ? '#F59E0B' : '#10B981');
                        $barColorDd = $barColor . 'dd';
                        $widthPercent = number_format($percentage, 2);
                    @endphp
                    <div class="progress-bar" data-width="{{ $widthPercent }}" data-color="{{ $barColor }}" data-color-dd="{{ $barColorDd }}"></div>
                </div>
                <p style="margin-top: 8px; font-size: 13px; color: #6B7280;">
                    Tingkat penggunaan: <strong>{{ number_format($percentage, 1) }}%</strong>
                </p>
            </div>

            <!-- Distribusi Status -->
            <div class="distribusi-grid">
                <div class="distribusi-item diajukan">
                    <div class="label">Diajukan</div>
                    <div class="value">{{ $divisi['distribusi_status']['Diajukan'] }}</div>
                </div>
                <div class="distribusi-item diverifikasi">
                    <div class="label">Diverifikasi</div>
                    <div class="value">{{ $divisi['distribusi_status']['Diverifikasi'] }}</div>
                </div>
                <div class="distribusi-item diterima">
                    <div class="label">Diterima</div>
                    <div class="value">{{ $divisi['distribusi_status']['Diterima'] }}</div>
                </div>
                <div class="distribusi-item ditolak">
                    <div class="label">Ditolak</div>
                    <div class="value">{{ $divisi['distribusi_status']['Ditolak'] }}</div>
                </div>
            </div>

            <!-- Detail Per Periode -->
            @if($divisi['detail_periode']->count() > 0)
                <div class="periode-detail">
                    <h4>Detail Per Periode</h4>
                    <table class="periode-table">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Kuota Max</th>
                                <th>Terpakai</th>
                                <th>Tersedia</th>
                                <th>Pendaftar</th>
                                <th>Distribusi Status</th>
                                <th>Jadwal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($divisi['detail_periode'] as $detail)
                                <tr>
                                    <td style="font-weight: 600;">{{ $detail['periode'] }}</td>
                                    <td>{{ $detail['kuota_max'] }}</td>
                                    <td>{{ $detail['kuota_terpakai'] }}</td>
                                    <td @if($detail['kuota_tersedia'] > 0) style="color: #10B981; font-weight: 600;" @else style="color: #EF4444; font-weight: 600;" @endif>
                                        {{ $detail['kuota_tersedia'] }}
                                    </td>
                                    <td>{{ $detail['total_pendaftar'] }}</td>
                                    <td>
                                        @if($detail['status_distribusi']['Diajukan'] > 0)
                                            <span class="badge-status" style="background: #EFF6FF; color: #2563EB;">
                                                Diajukan: {{ $detail['status_distribusi']['Diajukan'] }}
                                            </span>
                                        @endif
                                        @if($detail['status_distribusi']['Diverifikasi'] > 0)
                                            <span class="badge-status" style="background: #FFFBEB; color: #F59E0B;">
                                                Diverifikasi: {{ $detail['status_distribusi']['Diverifikasi'] }}
                                            </span>
                                        @endif
                                        @if($detail['status_distribusi']['Diterima'] > 0)
                                            <span class="badge-status" style="background: #ECFDF5; color: #10B981;">
                                                Diterima: {{ $detail['status_distribusi']['Diterima'] }}
                                            </span>
                                        @endif
                                        @if($detail['status_distribusi']['Ditolak'] > 0)
                                            <span class="badge-status" style="background: #FEF2F2; color: #EF4444;">
                                                Ditolak: {{ $detail['status_distribusi']['Ditolak'] }}
                                            </span>
                                        @endif
                                        @if(array_sum($detail['status_distribusi']) == 0)
                                            <span style="color: #9CA3AF; font-style: italic;">Tidak ada pendaftar</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($detail['jadwal'])
                                            {{ \Carbon\Carbon::parse($detail['jadwal']->tgl_mulai)->format('d/m/Y') }} - 
                                            {{ \Carbon\Carbon::parse($detail['jadwal']->tgl_selesai)->format('d/m/Y') }}
                                        @else
                                            <span style="color: #9CA3AF;">Tidak ada jadwal</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endforeach
@else
    <div class="divisi-section">
        <div class="empty-state">
            <div class="empty-state-icon">📊</div>
            <h3 style="font-size: 20px; font-weight: 700; color: #111827; margin: 0 0 8px 0;">
                Belum Ada Data Sumber Daya
            </h3>
            <p style="margin: 0 0 32px; font-size: 14px; color: #6B7280;">
                Untuk mengatur sumber daya magang, silakan atur kuota dan jadwal magang terlebih dahulu.
            </p>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; max-width: 800px; margin: 0 auto;">
                <!-- Menu Atur Kuota Magang -->
                <a href="{{ route('admin.atur_kuota_magang') }}" class="menu-action-card">
                    <div class="menu-icon-wrapper" style="width: 64px; height: 64px; border-radius: 14px; background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #3B82F6;">
                            <rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" stroke-width="2"/>
                            <path d="M16 7V6a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M21 11l-9 5-9-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h4 style="font-size: 18px; font-weight: 700; color: #111827; margin: 0 0 8px 0;">
                        Atur Kuota Magang
                    </h4>
                    <p style="font-size: 14px; color: #6B7280; margin: 0; line-height: 1.6;">
                        Kelola kuota magang untuk setiap divisi dan periode
                    </p>
                    <div class="menu-link-arrow" style="margin-top: 20px; display: inline-flex; align-items: center; gap: 8px; color: #3B82F6; font-weight: 600; font-size: 14px;">
                        <span>Buka menu</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12h14m-7-7l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </a>

                <!-- Menu Atur Jadwal Magang -->
                <a href="{{ route('admin.atur_jadwal_magang') }}" class="menu-action-card">
                    <div class="menu-icon-wrapper" style="width: 64px; height: 64px; border-radius: 14px; background: linear-gradient(135deg, #FDF2F8 0%, #FCE7F3 100%); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #EC4899;">
                            <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                            <path d="M3 10h18M8 2v4m8-4v4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h4 style="font-size: 18px; font-weight: 700; color: #111827; margin: 0 0 8px 0;">
                        Atur Jadwal Magang
                    </h4>
                    <p style="font-size: 14px; color: #6B7280; margin: 0; line-height: 1.6;">
                        Kelola jadwal mulai dan selesai magang untuk setiap divisi
                    </p>
                    <div class="menu-link-arrow" style="margin-top: 20px; display: inline-flex; align-items: center; gap: 8px; color: #EC4899; font-weight: 600; font-size: 14px;">
                        <span>Buka menu</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12h14m-7-7l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set progress bar width and background from data attributes
    document.querySelectorAll('.progress-bar[data-width]').forEach(function(bar) {
        const width = bar.getAttribute('data-width');
        const color = bar.getAttribute('data-color');
        const colorDd = bar.getAttribute('data-color-dd');
        
        if (width) {
            bar.style.width = width + '%';
        }
        
        if (color && colorDd) {
            bar.style.background = 'linear-gradient(90deg, ' + color + ' 0%, ' + colorDd + ' 100%)';
        }
    });
});
</script>
@endsection
