<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lamaran Saya - Magang Digital</title>
    {{-- Resource Hints untuk performa lebih cepat --}}
    <link rel="dns-prefetch" href="{{ url('/') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Font loading optimasi - non-blocking --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"></noscript>
    {{-- CSS dengan preload untuk faster rendering --}}
    <link rel="preload" href="{{ asset('css/dashboard.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/dashboard.css') }}"></noscript>
    {{-- JS dengan defer untuk non-blocking --}}
    <script src="{{ asset('js/dashboard.js') }}" defer></script>
    <style>
      * { 
        box-sizing: border-box; 
        margin: 0; 
        padding: 0; 
      }
      
      body { 
        margin: 0; 
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        font-size: 14px;
        line-height: 1.5;
        color: #1F2937;
      }
      
      .dashboard-body {
        background: #F9FAFB;
        min-height: 100vh;
      }
      
      .dashboard-wrap {
        display: flex;
        min-height: 100vh;
      }
      
      .main {
        flex: 1;
        padding: 0;
        background: transparent;
        overflow-x: hidden;
        display: flex;
        flex-direction: column;
      }
      
      .main-content {
        flex: 1;
        max-width: 1400px;
        width: 100%;
        margin: 0 auto;
        padding: 40px 48px 60px;
        overflow-y: auto;
      }
      
      .topbar {
        background: #FFFFFF;
        border-bottom: 1px solid #E5E7EB;
        padding: 16px 48px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        width: 100%;
        flex-shrink: 0;
      }
      
      .topbar-right {
        display: flex;
        align-items: center;
        gap: 12px;
      }
      
      .user-greeting {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        background: #F9FAFB;
        border-radius: 10px;
        font-weight: 500;
        font-size: 14px;
        color: #374151;
      }
      
      .btn-logout {
        padding: 10px 18px;
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        color: #6B7280;
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
      }
      
      .btn-logout:hover {
        background: #F3F4F6;
        color: #1F2937;
        border-color: #D1D5DB;
      }

      .content {
        animation: fadeIn 0.4s ease-out;
      }
      
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
      }

      .page-title {
        font-size: 40px;
        font-weight: 800;
        color: #0C3A6B;
        margin: 0 0 32px;
        line-height: 1.2;
        letter-spacing: -0.5px;
      }

      .info-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #F3F4F6;
        margin-bottom: 32px;
      }

      .info-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #F3F4F6;
      }

      .info-card-title {
        font-size: 20px;
        font-weight: 700;
        color: #1F2937;
        margin: 0;
      }

      .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
      }

      .status-badge.diajukan {
        background: #EFF6FF;
        color: #2563EB;
      }

      .status-badge.diverifikasi {
        background: #FEF3C7;
        color: #F59E0B;
      }

      .status-badge.diterima {
        background: #D1FAE5;
        color: #10B981;
      }

      .status-badge.ditolak {
        background: #FEE2E2;
        color: #EF4444;
      }

      .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
      }

      .info-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
      }

      .info-label {
        font-size: 13px;
        color: #6B7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
      }

      .info-value {
        font-size: 16px;
        color: #1F2937;
        font-weight: 600;
      }

      .document-section {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #F3F4F6;
        margin-bottom: 32px;
      }

      .document-section-title {
        font-size: 20px;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 24px;
      }

      .document-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
      }

      .document-item {
        background: #F9FAFB;
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        transition: all 0.2s ease;
      }

      .document-item.uploaded {
        border-color: #10B981;
        background: #F0FDF4;
      }

      .document-item.missing {
        border-color: #EF4444;
        background: #FEF2F2;
      }

      .document-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
      }

      .document-item.uploaded .document-icon {
        background: #D1FAE5;
      }

      .document-item.missing .document-icon {
        background: #FEE2E2;
      }

      .document-info {
        flex: 1;
        min-width: 0;
      }

      .document-name {
        font-size: 16px;
        font-weight: 600;
        color: #1F2937;
        margin: 0 0 6px;
      }

      .document-status {
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
      }

      .document-item.uploaded .document-status {
        color: #10B981;
      }

      .document-item.missing .document-status {
        color: #EF4444;
      }

      .upload-form {
        background: #F9FAFB;
        border: 2px dashed #D1D5DB;
        border-radius: 12px;
        padding: 32px;
        text-align: center;
      }

      .upload-form-title {
        font-size: 18px;
        font-weight: 600;
        color: #1F2937;
        margin: 0 0 12px;
      }

      .upload-form-desc {
        font-size: 14px;
        color: #6B7280;
        margin: 0 0 24px;
      }

      .file-input-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 16px;
      }

      .file-input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
      }

      .file-input-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: #3B82F6;
        color: #FFFFFF;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
      }

      .file-input-label:hover {
        background: #2563EB;
        transform: translateY(-1px);
      }

      .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        background: #10B981;
        color: #FFFFFF;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 16px;
      }

      .btn-submit:hover:not(:disabled) {
        background: #059669;
        transform: translateY(-1px);
      }

      .btn-submit:disabled {
        background: #9CA3AF;
        cursor: not-allowed;
        opacity: 0.6;
      }

      .empty-state {
        text-align: center;
        padding: 60px 40px;
        background: #FFFFFF;
        border-radius: 16px;
        border: 1px solid #F3F4F6;
      }

      .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 24px;
        opacity: 0.25;
        color: #9CA3AF;
      }

      .empty-title {
        font-size: 22px;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 12px;
      }

      .empty-desc {
        font-size: 15px;
        color: #6B7280;
        margin: 0 0 24px;
      }

      .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        background: #3B82F6;
        color: #FFFFFF;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
        border: none;
        cursor: pointer;
      }
      
      .btn-primary:hover {
        background: #2563EB;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
      }

      .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
      }

      .alert-error {
        background: #FEE2E2;
        border-left: 4px solid #EF4444;
        color: #991B1B;
      }

      .alert-success {
        background: #D1FAE5;
        border-left: 4px solid #10B981;
        color: #065F46;
      }

      @media (max-width: 1024px) {
        .main-content {
          padding: 32px 32px 48px;
        }
        
        .topbar {
          padding: 16px 32px;
        }
      }
      
      @media (max-width: 768px) {
        .main-content {
          padding: 24px 20px 40px;
        }
        
        .topbar {
          padding: 12px 20px;
        }

        .info-grid {
          grid-template-columns: 1fr;
        }

        .document-grid {
          grid-template-columns: 1fr;
        }
      }
    </style>
  </head>
  <body class="dashboard-body">
    <div class="dashboard-wrap">
      @include('partials.sidebar')
      <main class="main">
        <div class="topbar">
          <div class="topbar-right">
            <div class="user-greeting">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>Halo, {{ auth()->user()->nama ?? 'Pengguna' }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display:inline-block">
              @csrf
              <button type="submit" class="btn-logout">Keluar</button>
            </form>
          </div>
        </div>
        
        <div class="main-content">
          <section class="content">
            <h1 class="page-title">Lamaran Saya</h1>

            @if(session('success'))
              <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div style="flex: 1;">
                  <div>{{ session('success') }}</div>
                  @php
                    // Data sudah di-pass dari controller, tidak perlu query lagi
                    $user = auth()->user();
                    $permohonanCount = isset($permohonan) && $permohonan ? 1 : 0;
                  @endphp
                  @if($permohonanCount > 0)
                    <div style="margin-top: 12px;">
                      <a href="{{ route('riwayat.lamaran') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: #FFFFFF; color: #10B981; border: 2px solid #10B981; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.2s;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                          <polyline points="12 6 12 12 16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Lihat Status Lamaran
                      </a>
                    </div>
                  @endif
                </div>
              </div>
              @php
                // Set flag untuk menyembunyikan draft setelah dokumen baru diunggah
                session()->flash('dokumen_baru_diunggah', true);
              @endphp
            @endif

            @if(session('error') || $errors->any())
              <div class="alert alert-error">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                  <path d="M12 8v4M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <div>
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
              </div>
            @endif

            @if($permohonan && in_array($permohonan->status, ['Diajukan', 'Diverifikasi', 'Revisi']))
              <!-- Detail Permohonan Aktif -->
              <div class="info-card">
                <div class="info-card-header">
                  <h2 class="info-card-title">Detail Permohonan</h2>
                  <span class="status-badge {{ strtolower($permohonan->status) }}">
                    {{ $permohonan->status }}
                  </span>
                </div>
                
                <div class="info-grid">
                  <div class="info-item">
                    <span class="info-label">Tanggal Pengajuan</span>
                    <span class="info-value">
                      {{ $permohonan->tanggal_pengajuan ? $permohonan->tanggal_pengajuan->format('d F Y') : $permohonan->created_at->format('d F Y') }}
                    </span>
                  </div>
                  
                  @php
                    // Gunakan backup data jika kuota sudah dihapus
                    $periode = null;
                    $tglMulai = null;
                    $tglSelesai = null;
                    
                    if ($permohonan->kuotaMagang->count() > 0) {
                      $kuota = $permohonan->kuotaMagang->first();
                      $periode = $kuota->periode ?? null;
                      $jadwal = $kuota->jadwalMagang ?? null;
                      if ($jadwal) {
                        $tglMulai = $jadwal->tgl_mulai ?? null;
                        $tglSelesai = $jadwal->tgl_selesai ?? null;
                      }
                    } else {
                      // Gunakan backup data jika kuota sudah dihapus
                      $periode = $permohonan->periode_backup ?? null;
                      $tglMulai = $permohonan->tgl_mulai_backup ?? null;
                      $tglSelesai = $permohonan->tgl_selesai_backup ?? null;
                    }
                  @endphp
                  
                  @if($periode)
                    <div class="info-item">
                      <span class="info-label">Periode Magang</span>
                      <span class="info-value">{{ $periode }}</span>
                    </div>
                    
                    @if($tglMulai)
                      <div class="info-item">
                        <span class="info-label">Tanggal Mulai</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($tglMulai)->format('d F Y') }}</span>
                      </div>
                    @endif
                    
                    @if($tglSelesai)
                      <div class="info-item">
                        <span class="info-label">Tanggal Selesai</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($tglSelesai)->format('d F Y') }}</span>
                      </div>
                    @endif
                  @endif
                  
                  <div class="info-item">
                    <span class="info-label">Instansi</span>
                    <span class="info-value">
                      {{ auth()->user()->instansi ?? 'Balai Besar Standardisasi dan Pelayanan Jasa Kerajinan dan Batik Yogyakarta' }}
                    </span>
                  </div>
                </div>

                @if($permohonan->status === 'Ditolak')
                  <div style="margin-top: 20px; padding: 16px; background: #FEE2E2; border-radius: 8px; border-left: 4px solid #EF4444;">
                    <p style="margin: 0; color: #991B1B; font-weight: 500;">
                      Permohonan Anda ditolak. Silakan lengkapi atau perbaiki dokumen Anda dan ajukan kembali.
                    </p>
                    @if($permohonan->alasan_penolakan)
                      <div style="margin-top: 12px; padding: 12px; background: #FFFFFF; border-radius: 6px;">
                        <p style="margin: 0 0 6px; font-size: 13px; font-weight: 600; color: #991B1B;">
                          Alasan Penolakan:
                        </p>
                        <p style="margin: 0; font-size: 13px; color: #7F1D1D; line-height: 1.6; white-space: pre-line;">
                          {{ $permohonan->alasan_penolakan }}
                        </p>
                      </div>
                    @endif
                  </div>
                @endif
                  @if($permohonan->status === 'Revisi')
                    <div style="margin-top: 20px; padding: 16px; background: #FEF7ED; border-radius: 8px; border-left: 4px solid #F59E0B;">
                      <p style="margin: 0; color: #92400E; font-weight: 600;">
                        Permohonan Anda membutuhkan revisi. Silakan perbaiki dokumen sesuai instruksi di bawah dan unggah ulang.
                      </p>
                      @if($permohonan->catatan_revisi)
                        <div style="margin-top: 12px; padding: 12px; background: #FFFFFF; border-radius: 6px;">
                          <p style="margin: 0 0 6px; font-size: 13px; font-weight: 600; color: #92400E;">
                            Instruksi Revisi:
                          </p>
                          <p style="margin: 0; font-size: 13px; color: #7A4B16; line-height: 1.6; white-space: pre-line;">
                            {{ $permohonan->catatan_revisi }}
                          </p>
                        </div>
                      @endif
                    </div>
                  @endif
              </div>
            @endif

            <!-- Dokumen Terunggah (Ditampilkan setelah upload) -->
            @if($dokumen)
              @php
                // Cek dokumen yang sudah terunggah
                $dokumenTerunggah = [];
                if (!empty($dokumen->cv)) {
                  $dokumenTerunggah[] = ['nama' => 'CV (Curriculum Vitae)', 'field' => 'cv', 'path' => $dokumen->cv];
                }
                if (!empty($dokumen->surat_pengantar)) {
                  $dokumenTerunggah[] = ['nama' => 'Surat Pengantar', 'field' => 'surat_pengantar', 'path' => $dokumen->surat_pengantar];
                }
                if (!empty($dokumen->proposal)) {
                  $dokumenTerunggah[] = ['nama' => 'Proposal', 'field' => 'proposal', 'path' => $dokumen->proposal];
                }
                $dokumenLengkap = count($dokumenTerunggah) === 3;
              @endphp
              
              @if(count($dokumenTerunggah) > 0)
                <div class="document-section" style="background: #EFF6FF; border: 2px solid #3B82F6; margin-bottom: 32px;">
                  <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: #DBEAFE; display: flex; align-items: center; justify-content: center;">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #3B82F6;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </div>
                    <div style="flex: 1;">
                      <h2 style="margin: 0 0 6px; font-size: 20px; font-weight: 700; color: #1E40AF;">
                        @if(isset($permohonan) && $permohonan)
                          Dokumen Terunggah
                        @else
                          Draft Dokumen Terunggah
                        @endif
                      </h2>
                      <p style="margin: 0; font-size: 14px; color: #1E40AF; opacity: 0.9;">
                        @if(isset($permohonan) && $permohonan)
                          Dokumen yang telah Anda unggah untuk permohonan ini.
                        @else
                          Periksa dokumen Anda sebelum mengajukan permohonan. Pastikan semua dokumen sudah lengkap dan benar.
                        @endif
                      </p>
                    </div>
                    @if($dokumenLengkap)
                      <span style="padding: 6px 12px; background: #D1FAE5; color: #065F46; border-radius: 8px; font-size: 12px; font-weight: 600;">
                        ✓ Lengkap
                      </span>
                    @else
                      <span style="padding: 6px 12px; background: #FEF3C7; color: #92400E; border-radius: 8px; font-size: 12px; font-weight: 600;">
                        ⚠ Belum Lengkap ({{ count($dokumenTerunggah) }}/3)
                      </span>
                    @endif
                  </div>
                  
                  <div style="background: #FFFFFF; border-radius: 12px; padding: 20px;">
                    <h3 style="margin: 0 0 16px; font-size: 16px; font-weight: 600; color: #1F2937;">Dokumen yang Terunggah</h3>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                      @foreach($dokumenTerunggah as $doc)
                        <div class="uploaded-doc-item" data-doc-field="{{ $doc['field'] }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; background: #F0FDF4; border-radius: 8px; border: 1px solid #10B981;">
                          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 6L9 17l-5-5" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg>
                          <span style="flex: 1; font-size: 14px; font-weight: 500; color: #1F2937;">{{ $doc['nama'] }}</span>
                          <span style="font-size: 12px; color: #10B981; font-weight: 600; margin-right: 8px;">Terunggah</span>
                          @php
                            $fileExists = \Storage::disk('public')->exists($doc['path']);
                          @endphp
                          <div style="display: flex; gap: 8px; align-items: center;">
                            @if($fileExists)
                              <a href="{{ asset('storage/' . $doc['path']) }}" target="_blank" style="padding: 6px 12px; background: #3B82F6; color: #FFFFFF; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; transition: background 0.2s; display: inline-flex; align-items: center; gap: 4px;" onmouseover="this.style.background='#2563EB'" onmouseout="this.style.background='#3B82F6'">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                  <polyline points="15 3 21 3 21 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                  <line x1="10" y1="14" x2="21" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Lihat
                              </a>
                            @endif
                                  <form action="{{ route('pendaftar.delete_dokumen_field', ['id' => $dokumen->id, 'field' => $doc['field']]) }}" method="POST" style="display: inline;" class="ajax-delete" data-confirm="Yakin ingin menghapus {{ $doc['nama'] }}? Dokumen ini akan dihapus dan Anda perlu mengunggah ulang.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 6px 12px; background: #EF4444; color: #FFFFFF; border: none; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 4px;" onmouseover="this.style.background='#DC2626'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#EF4444'; this.style.transform='translateY(0)'">
                                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                      </svg>
                                      Hapus
                                    </button>
                                  </form>
                          </div>
                        </div>
                      @endforeach
                    </div>
                    
                    @if($dokumen->tanggal_upload)
                      <p style="margin: 16px 0 0 0; font-size: 13px; color: #6B7280; text-align: right;">
                        Diunggah pada {{ $dokumen->tanggal_upload->format('d F Y, H:i') }}
                      </p>
                    @endif
                    
                    @if($dokumenLengkap)
                      @php
                        // OPTIMASI: Gunakan cache untuk menghindari query setiap kali
                        $today = now()->toDateString();
                        $lowonganCacheKey = "lamaran_lowongan_{$today}";
                        $lowonganAktif = \Illuminate\Support\Facades\Cache::remember($lowonganCacheKey, 300, function () use ($today) {
                          return \App\Models\KuotaMagang::with('jadwalMagang')
                          ->whereColumn('kuota_terpakai', '<', 'kuota_max')
                          ->get()
                          ->filter(function ($kuota) use ($today) {
                            $jadwal = $kuota->jadwalMagang;
                            return $jadwal && $jadwal->tgl_mulai <= $today && $jadwal->tgl_selesai >= $today;
                          })
                          ->first();
                        });
                      @endphp
                      
                      <div style="margin-top: 20px; padding: 16px; background: #D1FAE5; border-radius: 8px; border-left: 4px solid #10B981;">
                        <p style="margin: 0 0 12px; font-size: 14px; color: #065F46; font-weight: 600;">
                          ✓ Semua dokumen sudah lengkap! Anda siap mengajukan permohonan.
                        </p>
                        @if($lowonganAktif)
                          <form action="{{ route('pendaftar.store_permohonan') }}" method="POST" style="display: inline-block;">
                            @csrf
                            <input type="hidden" name="dokumen_id" value="{{ $dokumen->id }}">
                            <input type="hidden" name="kuota_id" value="{{ $lowonganAktif->id }}">
                            <button type="submit" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #10B981; color: #FFFFFF; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; transition: all 0.2s; cursor: pointer;" onmouseover="this.style.background='#059669'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#10B981'; this.style.transform='translateY(0)'">
                              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <polyline points="7 10 12 15 17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                              </svg>
                              Unggah Permohonan Magang
                            </button>
                          </form>
                          <p style="margin: 12px 0 0 0; font-size: 12px; color: #059669; font-weight: 500;">
                            ℹ️ Permohonan akan langsung diajukan ke lowongan aktif: <strong>{{ $lowonganAktif->periode }}</strong>
                          </p>
                        @else
                          <a href="{{ route('lowongan') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #10B981; color: #FFFFFF; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.2s;" onmouseover="this.style.background='#059669'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#10B981'; this.style.transform='translateY(0)'">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                              <polyline points="7 10 12 15 17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                              <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Unggah Permohonan Magang
                          </a>
                          <p style="margin: 12px 0 0 0; font-size: 12px; color: #059669; font-weight: 500;">
                            ℹ️ Pilih lowongan terlebih dahulu untuk mengajukan permohonan
                          </p>
                        @endif
                      </div>
                    @else
                      <div style="margin-top: 20px; padding: 16px; background: #FEF3C7; border-radius: 8px; border-left: 4px solid #F59E0B;">
                        <p style="margin: 0; font-size: 14px; color: #92400E; font-weight: 500;">
                          ⚠️ Masih ada dokumen yang belum terunggah. Lengkapi semua dokumen (CV, Surat Pengantar, dan Proposal) sebelum mengajukan permohonan.
                        </p>
                      </div>
                    @endif
                  </div>
                </div>
              @endif
            @endif

            <!-- Area Unggah Dokumen -->
            <div class="document-section">
              <h2 class="document-section-title">
                @if(!$permohonan && $dokumen && count($dokumenTerunggah ?? []) > 0)
                  Perbarui Dokumen
                @else
                  Unggah Dokumen
                @endif
              </h2>
              
              <!-- Form Upload Dokumen -->
              <div class="upload-form">
                <h3 class="upload-form-title">
                  @if(isset($permohonan) && $permohonan->status === 'Revisi')
                    🔄 Perbaiki dan Unggah Ulang Dokumen
                  @else
                    Unggah atau Perbarui Dokumen
                  @endif
                </h3>
                <p class="upload-form-desc">
                  @if(isset($permohonan) && $permohonan->status === 'Revisi')
                    <strong style="color: #F59E0B;">Status: REVISI</strong> — Silakan perbaiki dokumen sesuai instruksi di atas dan unggah ulang. Setelah dokumen diperbaiki dan diunggah ulang, status akan otomatis kembali menjadi "Diajukan" untuk verifikasi ulang.
                  @else
                    Unggah dokumen wajib: CV, Surat Pengantar, dan Proposal. Format file: PDF, DOC, atau DOCX (maks. 5MB per file).
                  @endif
                </p>
                
                <form action="{{ route('pendaftar.store_dokumen') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                  @csrf
                  
                  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 24px;">
                    <div class="file-input-wrapper">
                      <input type="file" name="cv" id="cv" accept=".pdf,.doc,.docx" class="file-input" onchange="handleFileSelect('cv', this)">
                      <label for="cv" class="file-input-label">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          <polyline points="17 8 12 3 7 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          <line x1="12" y1="3" x2="12" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Pilih CV
                      </label>
                    </div>
                    
                    <div class="file-input-wrapper">
                      <input type="file" name="surat_pengantar" id="surat_pengantar" accept=".pdf,.doc,.docx" class="file-input" onchange="handleFileSelect('surat_pengantar', this)">
                      <label for="surat_pengantar" class="file-input-label">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          <polyline points="17 8 12 3 7 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          <line x1="12" y1="3" x2="12" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Pilih Surat Pengantar
                      </label>
                    </div>
                    
                    <div class="file-input-wrapper">
                      <input type="file" name="proposal" id="proposal" accept=".pdf,.doc,.docx" class="file-input" onchange="handleFileSelect('proposal', this)">
                      <label for="proposal" class="file-input-label">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          <polyline points="17 8 12 3 7 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          <line x1="12" y1="3" x2="12" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Pilih Proposal
                      </label>
                    </div>
                  </div>
                  
                  <!-- Preview Draft Dokumen -->
                  <div id="filePreview" style="display: none; background: #F0F9FF; border: 2px solid #3B82F6; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                    <h4 style="margin: 0 0 16px; font-size: 16px; font-weight: 600; color: #1E40AF; display: flex; align-items: center; gap: 8px;">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                      Draft Dokumen yang Akan Diunggah
                    </h4>
                    <div id="filePreviewList" style="display: flex; flex-direction: column; gap: 12px;">
                      <!-- Preview items akan ditambahkan oleh JavaScript -->
                    </div>
                  </div>
                  
                  <button type="submit" class="btn-submit" id="submitBtn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <polyline points="17 8 12 3 7 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      <line x1="12" y1="3" x2="12" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Unggah Dokumen
                  </button>
                </form>
              </div>
            </div>

            @if(!$permohonan || !in_array($permohonan->status ?? '', ['Diajukan', 'Diverifikasi', 'Revisi']))
              <div class="empty-state">
                <svg class="empty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M14 2v6h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3 class="empty-title">Belum Ada Permohonan Aktif</h3>
                <p class="empty-desc">
                  Anda belum mengajukan permohonan magang. Lengkapi dokumen terlebih dahulu, kemudian ajukan permohonan di halaman Lowongan.
                </p>
                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                  <a href="{{ route('lowongan') }}" class="btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M21 21l-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Lihat Lowongan
                  </a>
                </div>
              </div>
            @endif
          </section>
        </div>
      </main>
    </div>
    
    <style>
      /* Simple toast/snackbar */
      .toast-container {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 8px;
        pointer-events: none;
      }
      .toast {
        min-width: 240px;
        max-width: 360px;
        background: #111827;
        color: #fff;
        padding: 12px 16px;
        border-radius: 8px;
        box-shadow: 0 6px 18px rgba(15,23,42,0.2);
        opacity: 0;
        transform: translateY(8px);
        transition: all 220ms ease;
        pointer-events: auto;
        font-size: 14px;
      }
      .toast.show {
        opacity: 1;
        transform: translateY(0);
      }
      .toast.success { background: #10B981; color: #04240f; }
      .toast.error { background: #EF4444; color: #fff; }
    </style>

    <div class="toast-container" id="toastContainer" aria-live="polite" aria-atomic="true"></div>

    <script>
      // Toast helper
      window.showToast = function(message, type = 'success', timeout = 3500) {
        const container = document.getElementById('toastContainer');
        if (!container) {
          console.log(message);
          return;
        }
        const toast = document.createElement('div');
        toast.className = 'toast ' + (type === 'error' ? 'error' : 'success');
        toast.textContent = message;
        container.appendChild(toast);
        void toast.offsetWidth;
        toast.classList.add('show');
        setTimeout(() => {
          toast.classList.remove('show');
          setTimeout(() => toast.remove(), 300);
        }, timeout);
      };

      const selectedFiles = { cv: null, surat_pengantar: null, proposal: null };
      const fileLabels = { cv: 'CV (Curriculum Vitae)', surat_pengantar: 'Surat Pengantar', proposal: 'Proposal' };

      function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
      }

      function handleFileSelect(type, input) {
        const file = input.files[0];
        if (file) {
          const maxSize = 5 * 1024 * 1024;
          if (file.size > maxSize) {
            showToast(`File ${fileLabels[type]} terlalu besar! Ukuran maksimal adalah 5MB. File Anda: ${formatFileSize(file.size)}`, 'error');
            input.value = '';
            selectedFiles[type] = null;
            updatePreview();
            return;
          }
          const fileExtension = file.name.split('.').pop().toLowerCase();
          const allowedExtensions = ['pdf', 'doc', 'docx'];
          if (!allowedExtensions.includes(fileExtension)) {
            showToast(`Format file ${fileLabels[type]} tidak valid! Hanya PDF, DOC, atau DOCX yang diperbolehkan.`, 'error');
            input.value = '';
            selectedFiles[type] = null;
            updatePreview();
            return;
          }
          selectedFiles[type] = file;
          updatePreview();
        } else {
          selectedFiles[type] = null;
          updatePreview();
        }
      }

      function updatePreview() {
        const previewDiv = document.getElementById('filePreview');
        const previewList = document.getElementById('filePreviewList');
        const submitBtn = document.getElementById('submitBtn');
        const hasFiles = Object.values(selectedFiles).some(file => file !== null);
        if (hasFiles) {
          previewDiv.style.display = 'block';
          previewList.innerHTML = '';
          Object.keys(selectedFiles).forEach(type => {
            const file = selectedFiles[type];
            if (file) {
              const fileItem = document.createElement('div');
              fileItem.style.cssText = 'display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #FFFFFF; border-radius: 8px; border: 1px solid #E5E7EB;';
              const fileInfo = document.createElement('div');
              fileInfo.style.cssText = 'display: flex; align-items: center; gap: 12px; flex: 1;';
              const icon = document.createElement('div');
              icon.innerHTML = `\n                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #3B82F6;">\n                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n                  <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n                </svg>\n              `;
              const info = document.createElement('div');
              info.style.cssText = 'flex: 1; min-width: 0;';
              const fileName = document.createElement('div');
              fileName.style.cssText = 'font-weight: 600; color: #1F2937; font-size: 14px; margin-bottom: 4px; word-break: break-all;';
              fileName.textContent = fileLabels[type];
              const fileDetails = document.createElement('div');
              fileDetails.style.cssText = 'font-size: 12px; color: #6B7280; display: flex; align-items: center; gap: 8px;';
              fileDetails.innerHTML = `\n                <span>${file.name}</span>\n                <span>•</span>\n                <span>${formatFileSize(file.size)}</span>\n              `;
              info.appendChild(fileName);
              info.appendChild(fileDetails);
              const removeBtn = document.createElement('button');
              removeBtn.type = 'button';
              removeBtn.style.cssText = 'padding: 6px; background: #FEE2E2; border: none; border-radius: 6px; cursor: pointer; color: #EF4444; display: flex; align-items: center; justify-content: center; transition: background 0.2s;';
              removeBtn.innerHTML = `\n                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">\n                  <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n                </svg>\n              `;
              removeBtn.onclick = function() { document.getElementById(type).value = ''; selectedFiles[type] = null; updatePreview(); };
              removeBtn.onmouseover = function() { this.style.background = '#FECACA'; };
              removeBtn.onmouseout = function() { this.style.background = '#FEE2E2'; };
              fileInfo.appendChild(icon);
              fileInfo.appendChild(info);
              fileItem.appendChild(fileInfo);
              fileItem.appendChild(removeBtn);
              previewList.appendChild(fileItem);
            }
          });
          const allFilesSelected = Object.values(selectedFiles).every(file => file !== null);
          if (allFilesSelected) {
            submitBtn.innerHTML = `\n              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">\n                <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n              </svg>\n              Unggah Dokumen (3 file siap)\n            `;
          } else {
            const count = Object.values(selectedFiles).filter(file => file !== null).length;
            submitBtn.innerHTML = `\n              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">\n                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n                <polyline points="17 8 12 3 7 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n                <line x1="12" y1="3" x2="12" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n              </svg>\n              Unggah Dokumen (${count}/3 file dipilih)\n            `;
          }
        } else {
          previewDiv.style.display = 'none';
          submitBtn.innerHTML = `\n            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">\n              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n              <polyline points="17 8 12 3 7 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n              <line x1="12" y1="3" x2="12" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n            </svg>\n            Unggah Dokumen\n          `;
        }
      }

      // Upload handler (AJAX)
      (function() {
        const form = document.getElementById('uploadForm');
        if (!form) return;
        
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          const hasFiles = Object.values(selectedFiles).some(f => f !== null);
          if (!hasFiles) {
            showToast('Pilih setidaknya satu dokumen untuk diunggah.', 'error');
            return;
          }
          for (const type of Object.keys(selectedFiles)) {
            const file = selectedFiles[type];
            if (file && file.size > 5 * 1024 * 1024) {
              showToast(`File ${fileLabels[type]} terlalu besar! Ukuran maksimal 5MB.`, 'error');
              return;
            }
          }
          
          // Disable submit button and show loading
          const submitBtn = document.getElementById('submitBtn');
          const originalBtnText = submitBtn.innerHTML;
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="animation: spin 1s linear infinite;"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg> Mengunggah...';
          
          const url = form.action;
          const formData = new FormData();
          const tokenEl = form.querySelector('input[name="_token"]');
          const csrfToken = tokenEl ? tokenEl.value : null;
          if (selectedFiles.cv) formData.append('cv', selectedFiles.cv);
          if (selectedFiles.surat_pengantar) formData.append('surat_pengantar', selectedFiles.surat_pengantar);
          if (selectedFiles.proposal) formData.append('proposal', selectedFiles.proposal);

          fetch(url, {
            method: 'POST',
            headers: Object.assign({ 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            credentials: 'same-origin',
            body: formData,
          }).then(r => {
            if (!r.ok) {
              return r.json().then(data => ({ success: false, message: data.message || 'Terjadi kesalahan saat mengunggah dokumen.' }));
            }
            return r.json();
          }).then(data => {
            if (data && data.success) {
              // Reset file inputs and clear selected files
              ['cv', 'surat_pengantar', 'proposal'].forEach(type => {
                const input = document.getElementById(type);
                if (input) {
                  // Create a new input element to completely reset it
                  const newInput = input.cloneNode(true);
                  input.parentNode.replaceChild(newInput, input);
                  // Re-attach event listener
                  newInput.addEventListener('change', function() {
                    handleFileSelect(type, this);
                  });
                  selectedFiles[type] = null;
                }
              });
              
              // Clear preview
              updatePreview();
              
              // Reload only the document section with cache busting
              const cacheBuster = '?t=' + Date.now();
              fetch(window.location.href + cacheBuster, { 
                credentials: 'same-origin', 
                headers: { 
                  'X-Requested-With': 'XMLHttpRequest', 
                  'Accept': 'text/html',
                  'Cache-Control': 'no-cache'
                } 
              })
                .then(resp => resp.text())
                .then(html => {
                  const parser = new DOMParser();
                  const doc = parser.parseFromString(html, 'text/html');
                  
                  // Find the main content area
                  const newContent = doc.querySelector('.content') || doc.querySelector('.main-content');
                  const oldContent = document.querySelector('.content') || document.querySelector('.main-content');
                  
                  if (newContent && oldContent) {
                    // Find the document section (draft dokumen terunggah) - look for the blue box
                    const newDocSection = Array.from(newContent.querySelectorAll('.document-section')).find(section => {
                      return section.style.backgroundColor === 'rgb(239, 246, 255)' || 
                             section.getAttribute('style')?.includes('EFF6FF') ||
                             section.getAttribute('style')?.includes('#EFF6FF');
                    });
                    
                    const oldDocSection = Array.from(oldContent.querySelectorAll('.document-section')).find(section => {
                      return section.style.backgroundColor === 'rgb(239, 246, 255)' || 
                             section.getAttribute('style')?.includes('EFF6FF') ||
                             section.getAttribute('style')?.includes('#EFF6FF');
                    });
                    
                    if (newDocSection && oldDocSection) {
                      // Replace the entire document section with animation
                      oldDocSection.style.opacity = '0';
                      oldDocSection.style.transform = 'translateY(-10px)';
                      setTimeout(() => {
                        oldDocSection.outerHTML = newDocSection.outerHTML;
                        // Fade in the new section
                        const insertedSection = document.querySelector('.document-section[style*="EFF6FF"]');
                        if (insertedSection) {
                          insertedSection.style.transition = 'opacity 0.3s, transform 0.3s';
                          insertedSection.style.opacity = '0';
                          setTimeout(() => {
                            insertedSection.style.opacity = '1';
                            insertedSection.style.transform = 'translateY(0)';
                          }, 10);
                        }
                      }, 200);
                    } else if (newDocSection && !oldDocSection) {
                      // Section doesn't exist yet, insert it before the upload form
                      const uploadSection = oldContent.querySelector('.document-section:not([style*="EFF6FF"])');
                      if (uploadSection) {
                        newDocSection.style.opacity = '0';
                        uploadSection.parentNode.insertBefore(newDocSection, uploadSection);
                        setTimeout(() => {
                          newDocSection.style.transition = 'opacity 0.3s, transform 0.3s';
                          newDocSection.style.opacity = '1';
                          newDocSection.style.transform = 'translateY(0)';
                        }, 10);
                      }
                    } else {
                      // Fallback: update the entire content area
                      oldContent.innerHTML = newContent.innerHTML;
                    }
                  }
                  
                  showToast(data.message || 'Dokumen berhasil diunggah.', 'success');
                  
                  // Re-enable submit button
                  submitBtn.disabled = false;
                  submitBtn.innerHTML = originalBtnText;
                })
                .catch(err => {
                  console.error('Error reloading document section:', err);
                  showToast('Dokumen berhasil diunggah, tetapi terjadi kesalahan saat memperbarui tampilan. Silakan refresh halaman.', 'success');
                  submitBtn.disabled = false;
                  submitBtn.innerHTML = originalBtnText;
                });
            } else {
              showToast((data && data.message) || 'Terjadi kesalahan saat mengunggah dokumen.', 'error');
              submitBtn.disabled = false;
              submitBtn.innerHTML = originalBtnText;
            }
          }).catch(err => {
            console.error('Upload error:', err);
            showToast('Terjadi kesalahan jaringan saat mengunggah dokumen. Silakan coba lagi.', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
          });
        });
      })();
      
      // Add CSS for spinner animation
      if (!document.querySelector('style[data-spinner]')) {
        const style = document.createElement('style');
        style.setAttribute('data-spinner', 'true');
        style.textContent = '@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }';
        document.head.appendChild(style);
      }

      // AJAX delete per-field
      document.addEventListener('DOMContentLoaded', function() {
        // Use event delegation for dynamically added forms
        document.addEventListener('submit', function(e) {
          const form = e.target.closest('form.ajax-delete');
          if (!form) return;
          
          e.preventDefault();
          const confirmMsg = form.getAttribute('data-confirm') || 'Yakin ingin menghapus dokumen ini?';
          if (!confirm(confirmMsg)) return;
          
          const url = form.action;
          const tokenEl = form.querySelector('input[name="_token"]');
          const csrfToken = tokenEl ? tokenEl.value : null;
          const container = form.closest('.uploaded-doc-item');
          const docSection = container ? container.closest('.document-section') : null;
          const docListContainer = container ? container.parentElement : null;

          // Show loading state
          if (container) {
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';
          }

          fetch(url, {
            method: 'DELETE',
            headers: Object.assign({ 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            credentials: 'same-origin',
          }).then(r => {
            if (r.status === 200 || r.status === 204) return r.json().catch(() => ({ success: true }));
            if (!r.ok) {
              return r.json().then(data => ({ success: false, message: data.message || 'Terjadi kesalahan' }));
            }
            return r.json();
          }).then(data => {
            if (data && data.success) {
              // Remove the deleted document item from DOM
              if (container) {
                container.style.transition = 'opacity 0.3s, transform 0.3s, height 0.3s, margin 0.3s';
                container.style.opacity = '0';
                container.style.transform = 'translateX(-20px)';
                container.style.height = container.offsetHeight + 'px';
                
                setTimeout(() => {
                  container.style.height = '0';
                  container.style.margin = '0';
                  container.style.padding = '0';
                  
                  setTimeout(() => {
                    container.remove();
                    
                    // Update the "Belum Lengkap" badge if needed
                    if (docListContainer) {
                      const remainingDocs = docListContainer.querySelectorAll('.uploaded-doc-item').length;
                      const badge = docSection ? docSection.querySelector('span[style*="FEF3C7"]') : null;
                      if (badge && remainingDocs < 3) {
                        badge.textContent = `⚠ Belum Lengkap (${remainingDocs}/3)`;
                      }
                      
                      // If no documents left, hide the section or show empty state
                      if (remainingDocs === 0) {
                        const docSectionParent = docSection ? docSection.closest('.document-section') : null;
                        if (docSectionParent) {
                          const emptyState = docSectionParent.querySelector('.empty-state');
                          if (!emptyState) {
                            const emptyDiv = document.createElement('div');
                            emptyDiv.className = 'empty-state';
                            emptyDiv.style.cssText = 'text-align: center; padding: 24px; color: #6B7280;';
                            emptyDiv.innerHTML = '<p>Tidak ada dokumen yang terunggah.</p>';
                            docListContainer.appendChild(emptyDiv);
                          }
                        }
                      }
                    }
                  }, 150);
                }, 300);
              }
              
              showToast((data && data.message) || 'Dokumen berhasil dihapus.', 'success');
            } else {
              // Restore container state on error
              if (container) {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
              }
              showToast((data && data.message) || 'Terjadi kesalahan saat menghapus dokumen.', 'error');
            }
          }).catch(err => {
            console.error('Delete error:', err);
            // Restore container state on error
            if (container) {
              container.style.opacity = '1';
              container.style.pointerEvents = 'auto';
            }
            showToast('Terjadi kesalahan jaringan saat menghapus dokumen. Silakan coba lagi.', 'error');
          });
        });
      });
    </script>

    {{-- Include PJAX / navigation enhancements for smoother page switches --}}
    @include('partials.nav_enhance')
  </body>
</html>
