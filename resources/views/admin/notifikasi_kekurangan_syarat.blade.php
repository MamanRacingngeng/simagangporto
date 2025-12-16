@extends('layouts.admin')

@section('title', 'Notifikasi Kekurangan Syarat - SIMAGANG')

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

    .btn-primary {
        padding: 12px 24px;
        background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
        color: #FFFFFF;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .info-box {
        background: #FEF3C7;
        border-left: 4px solid #F59E0B;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        color: #92400E;
    }

    .user-card {
        background: #FFFFFF;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        border: 1px solid #E5E7EB;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }

    .user-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .user-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .user-info h3 {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px 0;
    }

    .user-info p {
        font-size: 14px;
        color: #6B7280;
        margin: 0;
    }

    .kekurangan-list {
        background: #FEF2F2;
        border-left: 4px solid #EF4444;
        padding: 12px 16px;
        border-radius: 6px;
        margin-top: 16px;
    }

    .kekurangan-list h4 {
        font-size: 14px;
        font-weight: 600;
        color: #991B1B;
        margin: 0 0 8px 0;
    }

    .kekurangan-list ul {
        margin: 0;
        padding-left: 20px;
        color: #DC2626;
    }

    .kekurangan-list li {
        margin: 4px 0;
    }

    .btn-kirim {
        padding: 10px 20px;
        background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
        color: #FFFFFF;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-kirim:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 60px 24px;
        background: #FFFFFF;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px 0;
    }

    .empty-state p {
        font-size: 14px;
        color: #6B7280;
        margin: 0;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Notifikasi Kekurangan Syarat</h1>
    <a href="{{ route('admin.kirim_notifikasi') }}" class="btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Kirim Notifikasi Baru
    </a>
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

<div class="info-box">
    <strong>📋 Informasi:</strong> Halaman ini menampilkan daftar pendaftar yang memiliki permohonan magang namun dokumennya belum lengkap. 
    Anda dapat mengirim notifikasi kepada mereka untuk mengingatkan kekurangan dokumen.
</div>

@if(isset($usersKurangSyarat) && $usersKurangSyarat->count() > 0)
    @foreach($usersKurangSyarat as $item)
        <div class="user-card">
            <div class="user-header">
                <div class="user-info">
                    <h3>{{ $item['user']->nama }}</h3>
                    <p>{{ $item['user']->email }}</p>
                    @if($item['permohonan'])
                        <p style="margin-top: 4px; font-size: 13px;">
                            Status: <strong>{{ $item['permohonan']->status }}</strong>
                        </p>
                    @endif
                </div>
                <a href="{{ route('admin.kirim_notifikasi', $item['user']->id) }}" class="btn-kirim">
                    Kirim Notifikasi
                </a>
            </div>

            <div class="kekurangan-list">
                <h4>⚠️ Dokumen yang Belum Lengkap:</h4>
                <ul>
                    @foreach($item['kekurangan'] as $doc)
                        <li>{{ $doc }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
@else
    <div class="empty-state">
        <div class="empty-state-icon">✅</div>
        <h3>Semua Dokumen Lengkap</h3>
        <p>Tidak ada pendaftar dengan dokumen yang belum lengkap saat ini.</p>
    </div>
@endif
@endsection
