<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lamaran Saya - Magang Digital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
                    $user = auth()->user();
                    $permohonanCount = \App\Models\PermohonanMagang::where('user_id', $user->id)->count();
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

            @if($permohonan)
              <!-- Detail Permohonan -->
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
                  
                  @if($permohonan->kuotaMagang->count() > 0)
                    @php
                      $kuota = $permohonan->kuotaMagang->first();
                      $jadwal = $kuota->jadwalMagang;
                    @endphp
                    <div class="info-item">
                      <span class="info-label">Periode Magang</span>
                      <span class="info-value">{{ $kuota->periode }}</span>
                    </div>
                    
                    @if($jadwal)
                      <div class="info-item">
                        <span class="info-label">Tanggal Mulai</span>
                        <span class="info-value">{{ $jadwal->tgl_mulai->format('d F Y') }}</span>
                      </div>
                      
                      <div class="info-item">
                        <span class="info-label">Tanggal Selesai</span>
                        <span class="info-value">{{ $jadwal->tgl_selesai->format('d F Y') }}</span>
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
              </div>
            @endif

            <!-- Draft Dokumen Terunggah (Pengecekan Sebelum Dikirim) -->
            @if(!$permohonan && $dokumen)
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
              
              @if(count($dokumenTerunggah) > 0 && !session('dokumen_baru_diunggah'))
                <div class="document-section" style="background: #EFF6FF; border: 2px solid #3B82F6; margin-bottom: 32px;">
                  <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: #DBEAFE; display: flex; align-items: center; justify-content: center;">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #3B82F6;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </div>
                    <div style="flex: 1;">
                      <h2 style="margin: 0 0 6px; font-size: 20px; font-weight: 700; color: #1E40AF;">Draft Dokumen Terunggah</h2>
                      <p style="margin: 0; font-size: 14px; color: #1E40AF; opacity: 0.9;">
                        Periksa dokumen Anda sebelum mengajukan permohonan. Pastikan semua dokumen sudah lengkap dan benar.
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
                        <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: #F0FDF4; border-radius: 8px; border: 1px solid #10B981;">
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
                            <form action="{{ route('pendaftar.delete_dokumen_field', ['id' => $dokumen->id, 'field' => $doc['field']]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus {{ $doc['nama'] }}? Dokumen ini akan dihapus dan Anda perlu mengunggah ulang.')">
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
                        // Cek apakah ada lowongan aktif yang tersedia
                        $today = now()->toDateString();
                        $lowonganAktif = \App\Models\KuotaMagang::with('jadwalMagang')
                          ->whereColumn('kuota_terpakai', '<', 'kuota_max')
                          ->get()
                          ->filter(function ($kuota) use ($today) {
                            $jadwal = $kuota->jadwalMagang;
                            return $jadwal && $jadwal->tgl_mulai <= $today && $jadwal->tgl_selesai >= $today;
                          })
                          ->first();
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
                <h3 class="upload-form-title">Unggah atau Perbarui Dokumen</h3>
                <p class="upload-form-desc">
                  Unggah dokumen wajib: CV, Surat Pengantar, dan Proposal. Format file: PDF, DOC, atau DOCX (maks. 5MB per file).
                </p>
                
                <form action="{{ route('pendaftar.store_dokumen') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                  @csrf
                  
                  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px; margin-bottom: 24px;">
                    <div class="file-input-wrapper">
                      <input type="file" name="cv" id="cv" accept=".pdf,.doc,.docx" class="file-input" required onchange="handleFileSelect('cv', this)">
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
                      <input type="file" name="surat_pengantar" id="surat_pengantar" accept=".pdf,.doc,.docx" class="file-input" required onchange="handleFileSelect('surat_pengantar', this)">
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
                      <input type="file" name="proposal" id="proposal" accept=".pdf,.doc,.docx" class="file-input" required onchange="handleFileSelect('proposal', this)">
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

            @if(!$permohonan)
              <div class="empty-state">
                <svg class="empty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M14 2v6h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3 class="empty-title">Belum Ada Permohonan</h3>
                <p class="empty-desc">
                  Anda belum mengajukan permohonan magang. Lengkapi dokumen terlebih dahulu, kemudian ajukan permohonan di halaman Lowongan.
                </p>
                <a href="{{ route('lowongan') }}" class="btn-primary">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 21l-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  Lihat Lowongan
                </a>
              </div>
            @endif
          </section>
        </div>
      </main>
    </div>
    
    <script>
      const selectedFiles = {
        cv: null,
        surat_pengantar: null,
        proposal: null
      };
      
      const fileLabels = {
        cv: 'CV (Curriculum Vitae)',
        surat_pengantar: 'Surat Pengantar',
        proposal: 'Proposal'
      };
      
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
          // Validasi ukuran file (5MB = 5 * 1024 * 1024 bytes)
          const maxSize = 5 * 1024 * 1024;
          if (file.size > maxSize) {
            alert(`File ${fileLabels[type]} terlalu besar! Ukuran maksimal adalah 5MB. File Anda: ${formatFileSize(file.size)}`);
            input.value = '';
            selectedFiles[type] = null;
            updatePreview();
            return;
          }
          
          // Validasi tipe file
          const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
          const fileExtension = file.name.split('.').pop().toLowerCase();
          const allowedExtensions = ['pdf', 'doc', 'docx'];
          
          if (!allowedExtensions.includes(fileExtension)) {
            alert(`Format file ${fileLabels[type]} tidak valid! Hanya PDF, DOC, atau DOCX yang diperbolehkan.`);
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
        
        // Cek apakah ada file yang dipilih
        const hasFiles = Object.values(selectedFiles).some(file => file !== null);
        
        if (hasFiles) {
          previewDiv.style.display = 'block';
          previewList.innerHTML = '';
          
          // Tampilkan preview untuk setiap file yang dipilih
          Object.keys(selectedFiles).forEach(type => {
            const file = selectedFiles[type];
            if (file) {
              const fileItem = document.createElement('div');
              fileItem.style.cssText = 'display: flex; align-items: center; justify-content: space-between; padding: 12px; background: #FFFFFF; border-radius: 8px; border: 1px solid #E5E7EB;';
              
              const fileInfo = document.createElement('div');
              fileInfo.style.cssText = 'display: flex; align-items: center; gap: 12px; flex: 1;';
              
              // Icon file
              const icon = document.createElement('div');
              icon.innerHTML = `
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #3B82F6;">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M14 2v6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              `;
              
              // Info file
              const info = document.createElement('div');
              info.style.cssText = 'flex: 1; min-width: 0;';
              
              const fileName = document.createElement('div');
              fileName.style.cssText = 'font-weight: 600; color: #1F2937; font-size: 14px; margin-bottom: 4px; word-break: break-all;';
              fileName.textContent = fileLabels[type];
              
              const fileDetails = document.createElement('div');
              fileDetails.style.cssText = 'font-size: 12px; color: #6B7280; display: flex; align-items: center; gap: 8px;';
              fileDetails.innerHTML = `
                <span>${file.name}</span>
                <span>•</span>
                <span>${formatFileSize(file.size)}</span>
              `;
              
              info.appendChild(fileName);
              info.appendChild(fileDetails);
              
              // Tombol hapus
              const removeBtn = document.createElement('button');
              removeBtn.type = 'button';
              removeBtn.style.cssText = 'padding: 6px; background: #FEE2E2; border: none; border-radius: 6px; cursor: pointer; color: #EF4444; display: flex; align-items: center; justify-content: center; transition: background 0.2s;';
              removeBtn.innerHTML = `
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              `;
              removeBtn.onclick = function() {
                document.getElementById(type).value = '';
                selectedFiles[type] = null;
                updatePreview();
              };
              removeBtn.onmouseover = function() {
                this.style.background = '#FECACA';
              };
              removeBtn.onmouseout = function() {
                this.style.background = '#FEE2E2';
              };
              
              fileInfo.appendChild(icon);
              fileInfo.appendChild(info);
              fileItem.appendChild(fileInfo);
              fileItem.appendChild(removeBtn);
              
              previewList.appendChild(fileItem);
            }
          });
          
          // Update label tombol submit
          const allFilesSelected = Object.values(selectedFiles).every(file => file !== null);
          if (allFilesSelected) {
            submitBtn.innerHTML = `
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Unggah Dokumen (3 file siap)
            `;
          } else {
            const count = Object.values(selectedFiles).filter(file => file !== null).length;
            submitBtn.innerHTML = `
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <polyline points="17 8 12 3 7 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <line x1="12" y1="3" x2="12" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              Unggah Dokumen (${count}/3 file dipilih)
            `;
          }
        } else {
          previewDiv.style.display = 'none';
          submitBtn.innerHTML = `
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <polyline points="17 8 12 3 7 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <line x1="12" y1="3" x2="12" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Unggah Dokumen
          `;
        }
      }
      
      // Validasi sebelum submit
      document.getElementById('uploadForm').addEventListener('submit', function(e) {
        const allFilesSelected = Object.values(selectedFiles).every(file => file !== null);
        if (!allFilesSelected) {
          e.preventDefault();
          alert('Harap pilih semua dokumen (CV, Surat Pengantar, dan Proposal) sebelum mengunggah.');
          return false;
        }
        
        // Validasi ulang ukuran file
        let hasError = false;
        Object.keys(selectedFiles).forEach(type => {
          const file = selectedFiles[type];
          if (file) {
            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
              hasError = true;
              alert(`File ${fileLabels[type]} terlalu besar! Ukuran maksimal adalah 5MB.`);
            }
          }
        });
        
        if (hasError) {
          e.preventDefault();
          return false;
        }
      });
    </script>
  </body>
</html>
