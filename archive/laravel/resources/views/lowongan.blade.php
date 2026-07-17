<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lowongan - Magang Digital</title>
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
        margin: 0 0 12px;
        line-height: 1.2;
        letter-spacing: -0.5px;
      }

      .page-subtitle {
        font-size: 16px;
        color: #6B7280;
        margin: 0 0 32px;
      }

      .lowongan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
      }

      .lowongan-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border: 1px solid #F3F4F6;
        transition: all 0.3s ease;
      }

      .lowongan-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-color: #E5E7EB;
      }

      .lowongan-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #F3F4F6;
      }

      .periode-title {
        font-size: 20px;
        font-weight: 700;
        color: #1F2937;
        margin: 0;
      }

      .kuota-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
      }

      .kuota-badge.available {
        background: #D1FAE5;
        color: #065F46;
      }

      .kuota-badge.full {
        background: #FEE2E2;
        color: #991B1B;
      }

      .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #F9FAFB;
      }

      .info-row:last-child {
        border-bottom: none;
      }

      .info-label {
        font-size: 14px;
        color: #6B7280;
        font-weight: 500;
      }

      .info-value {
        font-size: 14px;
        color: #1F2937;
        font-weight: 600;
      }

      .info-value.kuota-hijau {
        color: #10B981;
      }

      .info-value.kuota-merah {
        color: #EF4444;
      }

      .btn-ajukan {
        width: 100%;
        padding: 12px 24px;
        background: #3B82F6;
        color: #FFFFFF;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 20px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
      }

      .btn-ajukan:hover:not(:disabled) {
        background: #2563EB;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
      }

      .btn-ajukan:disabled {
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
        font-size: 20px;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 12px;
      }

      .empty-desc {
        font-size: 15px;
        color: #6B7280;
        margin: 0;
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
            <h1 class="page-title">Lowongan Magang</h1>
            <p class="page-subtitle">Pilih periode magang yang sesuai untuk Anda. Pastikan untuk melengkapi dokumen sebelum mengajukan permohonan.</p>

            @if(isset($kuotaMagang) && $kuotaMagang && $kuotaMagang->count() > 0)
              <div class="lowongan-grid">
                @foreach($kuotaMagang as $kuota)
                  @php
                    // OPTIMASI: Null safety untuk menghindari error
                    $jadwal = isset($kuota->jadwalMagang) ? $kuota->jadwalMagang : null;
                    $sisaKuota = isset($kuota->sisa_kuota) ? $kuota->sisa_kuota : 0;
                    $kuotaTersedia = isset($kuota->kuota_tersedia) ? $kuota->kuota_tersedia : false;
                    $today = now()->toDateString();
                    $isAkanDatang = $jadwal && isset($jadwal->tgl_mulai) && \Carbon\Carbon::parse($jadwal->tgl_mulai)->gt($today);
                    $isAktif = $jadwal && isset($jadwal->tgl_mulai) && isset($jadwal->tgl_selesai) 
                        && \Carbon\Carbon::parse($jadwal->tgl_mulai)->lte($today) 
                        && \Carbon\Carbon::parse($jadwal->tgl_selesai)->gte($today);
                  @endphp
                  <div class="lowongan-card">
                    <div class="lowongan-card-header">
                      <h3 class="periode-title">Periode {{ $kuota->periode ?? '-' }}</h3>
                      <div style="display: flex; gap: 8px; align-items: center;">
                        @if($isAkanDatang)
                          <span class="kuota-badge" style="background: #FEF3C7; color: #92400E;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                              <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                              <polyline points="12 6 12 12 16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Akan Datang
                          </span>
                        @endif
                        <span class="kuota-badge {{ $kuotaTersedia ? 'available' : 'full' }}">
                          @if($kuotaTersedia)
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                              <path d="M12 8v4M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Tersedia
                          @else
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M10 10l4 4m0-4l-4 4m6-6a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Penuh
                          @endif
                        </span>
                      </div>
                    </div>

                    @if(isset($kuota->posisi) && $kuota->posisi)
                      <div class="info-row" style="background: #F0F9FF; padding: 12px; border-radius: 8px; margin-bottom: 12px;">
                        <span class="info-label" style="color: #1E40AF; font-weight: 700;">Posisi/Departemen</span>
                        <span class="info-value" style="color: #1E40AF; font-weight: 700;">{{ $kuota->posisi }}</span>
                      </div>
                    @endif

                    <div class="info-row">
                      <span class="info-label">Tanggal Pelaksanaan</span>
                      <span class="info-value">
                        @if($jadwal && isset($jadwal->tgl_mulai) && isset($jadwal->tgl_selesai))
                          {{ \Carbon\Carbon::parse($jadwal->tgl_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($jadwal->tgl_selesai)->format('d/m/Y') }}
                          @if($isAkanDatang && isset($jadwal->tgl_mulai))
                            <span style="display: block; font-size: 12px; color: #92400E; margin-top: 4px; font-weight: 500;">
                              ⏰ Dimulai pada {{ \Carbon\Carbon::parse($jadwal->tgl_mulai)->format('d F Y') }}
                            </span>
                          @endif
                        @else
                          -
                        @endif
                      </span>
                    </div>

                    <div class="info-row">
                      <span class="info-label">Sisa Kuota</span>
                      <span class="info-value {{ ($sisaKuota ?? 0) > 0 ? 'kuota-hijau' : 'kuota-merah' }}" style="font-weight: 700; font-size: 16px;">
                        {{ $sisaKuota ?? 0 }} dari {{ $kuota->kuota_max ?? 0 }} tersedia
                      </span>
                    </div>

                    @if($kuotaTersedia)
                      @php
                        // Gunakan $dokumenLengkap dari controller yang sudah di-cache
                        // Tidak perlu query lagi di view untuk performa lebih cepat
                      @endphp
                      @if(isset($memilikiPermohonanAktif) && $memilikiPermohonanAktif)
                        <button class="btn-ajukan" disabled style="background: #9CA3AF; cursor: not-allowed;">
                          Anda Sudah Memiliki Permohonan Aktif
                        </button>
                        <p style="margin: 12px 0 0 0; font-size: 13px; color: #6B7280; text-align: center; padding: 8px; background: #F3F4F6; border-radius: 6px;">
                          ⚠️ @if(isset($cekDaftar) && !empty($cekDaftar['alasan'])){{ $cekDaftar['alasan'] }}@else Anda sudah memiliki permohonan yang sedang diproses. Satu akun hanya dapat mendaftar untuk 1 divisi lowongan magang. Cek status di menu Status Lamaran.@endif
                        </p>
                      @elseif($dokumenLengkap && isset($dokumen) && $dokumen && isset($dokumen->id))
                        <form action="{{ route('pendaftar.store_permohonan') }}" method="POST" style="margin-top: 20px;">
                          @csrf
                          <input type="hidden" name="dokumen_id" value="{{ $dokumen->id ?? '' }}">
                          <input type="hidden" name="kuota_id" value="{{ $kuota->id ?? '' }}">
                          <button type="submit" class="btn-ajukan">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                              <polyline points="7 10 12 15 17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                              <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Ajukan Permohonan
                          </button>
                        </form>
                      @else
                        <button class="btn-ajukan" data-action="lengkapi-dokumen" data-url="{{ route('lamaran') }}" style="background: #F59E0B;">
                          Lengkapi Dokumen Dulu
                        </button>
                        <p style="margin: 12px 0 0 0; font-size: 13px; color: #92400E; text-align: center; padding: 8px; background: #FEF3C7; border-radius: 6px;">
                          ⚠️ Pastikan dokumen lengkap sebelum mengajukan
                        </p>
                      @endif
                    @else
                      <button class="btn-ajukan" disabled>
                        Kuota Penuh
                      </button>
                    @endif
                  </div>
                @endforeach
              </div>
            @else
              <div class="empty-state">
                <svg class="empty-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" stroke-width="1.5"/>
                  <path d="M16 7V6a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3 class="empty-title">Tidak Ada Lowongan Tersedia</h3>
                <p class="empty-desc">
                  Saat ini belum ada periode magang yang dibuka. Silakan cek kembali nanti untuk informasi terbaru.
                </p>
              </div>
            @endif
          </section>
        </div>
      </main>
    </div>
    <script>
      // Handle tombol lengkapi dokumen
      document.addEventListener('DOMContentLoaded', function() {
        const btnLengkapiDokumen = document.querySelector('[data-action="lengkapi-dokumen"]');
        if (btnLengkapiDokumen) {
          btnLengkapiDokumen.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            alert('Pastikan Anda sudah melengkapi dan mengunggah semua dokumen wajib (CV, Surat Pengantar, Proposal) di menu Lamaran Saya sebelum mengajukan permohonan.');
            window.location.href = url;
          });
        }
      });
    </script>
  </body>
</html>
