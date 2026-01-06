<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panduan Onboarding - Magang Digital</title>
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
        border-color: #D1D5DB;
      }
      
      .page-title {
        font-size: 32px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 8px 0;
      }
      
      .page-subtitle {
        font-size: 16px;
        color: #6B7280;
        margin: 0 0 32px 0;
      }
      
      .info-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(15, 23, 42, 0.03);
        border: 1px solid #E5E7EB;
        margin-bottom: 24px;
      }
      
      .info-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 2px solid #F3F4F6;
      }
      
      .info-card-title {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin: 0;
      }
      
      .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
      }
      
      .info-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
      }
      
      .info-label {
        font-size: 13px;
        font-weight: 600;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }
      
      .info-value {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
      }
      
      .info-value.empty {
        color: #9CA3AF;
        font-style: italic;
      }
      
      .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
      }
      
      .status-badge.diterima {
        background: #ECFDF5;
        color: #10B981;
      }
      
      .section-divider {
        height: 1px;
        background: #E5E7EB;
        margin: 32px 0;
      }
      
      .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        color: #374151;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
        margin-bottom: 24px;
      }
      
      .btn-back:hover {
        background: #F9FAFB;
        border-color: #D1D5DB;
      }
      
      .highlight-box {
        background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
        border-left: 4px solid #10B981;
        padding: 24px;
        border-radius: 12px;
        margin-bottom: 24px;
      }
      
      .highlight-box h3 {
        font-size: 18px;
        font-weight: 700;
        color: #065F46;
        margin: 0 0 12px 0;
      }
      
      .highlight-box p {
        font-size: 14px;
        color: #047857;
        margin: 0;
        line-height: 1.6;
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
              <span>Halo, {{ $user->nama ?? 'Pengguna' }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="display:inline-block">
              @csrf
              <button type="submit" class="btn-logout">Keluar</button>
            </form>
          </div>
        </div>
        
        <div class="main-content">
          <a href="{{ route('riwayat.lamaran') }}" class="btn-back">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Kembali ke Status Lamaran
          </a>
          
          <h1 class="page-title">Panduan Onboarding</h1>
          <p class="page-subtitle">Informasi lengkap mengenai magang Anda, termasuk detail periode, divisi, dan data diri.</p>
          
          <div class="highlight-box">
            <h3>🎉 Selamat! Anda Diterima</h3>
            <p>Permohonan magang Anda telah disetujui. Berikut adalah informasi lengkap mengenai magang Anda. Pastikan untuk menyimpan informasi ini dan siap untuk memulai perjalanan magang Anda.</p>
          </div>
          
          <!-- Informasi Permohonan -->
          <div class="info-card">
            <div class="info-card-header">
              <h2 class="info-card-title">Informasi Permohonan Magang</h2>
              <span class="status-badge diterima">{{ optional($permohonan)->status }}</span>
            </div>
            
            <div class="info-grid">
              <div class="info-item">
                <span class="info-label">Periode Magang</span>
                <span class="info-value">{{ $kuota ? $kuota->periode : '-' }}</span>
              </div>
              
              <div class="info-item">
                <span class="info-label">Tanggal Pengajuan</span>
                <span class="info-value">
                  {{ $permohonan->tanggal_pengajuan ? $permohonan->tanggal_pengajuan->format('d F Y') : $permohonan->created_at->format('d F Y') }}
                </span>
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
              
              <div class="info-item">
                <span class="info-label">Divisi/Posisi</span>
                <span class="info-value">{{ $kuota ? $kuota->posisi : '-' }}</span>
              </div>
              
              <div class="info-item">
                <span class="info-label">Status</span>
                <span class="info-value">{{ optional($permohonan)->status }}</span>
              </div>
            </div>
          </div>
          
          <!-- Data Diri Lengkap -->
          <div class="info-card">
            <div class="info-card-header">
              <h2 class="info-card-title">Data Diri Lengkap</h2>
            </div>
            
            <div class="info-grid">
              <div class="info-item">
                <span class="info-label">Nama Lengkap</span>
                <span class="info-value">{{ $user->nama ?? '-' }}</span>
              </div>
              
              <div class="info-item">
                <span class="info-label">Nama Panggilan</span>
                <span class="info-value {{ empty($user->nama_panggilan) ? 'empty' : '' }}">
                  {{ $user->nama_panggilan ?? 'Belum diisi' }}
                </span>
              </div>
              
              <div class="info-item">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $user->email ?? '-' }}</span>
              </div>
              
              <div class="info-item">
                <span class="info-label">No. Telepon</span>
                <span class="info-value {{ empty($user->no_telepon) ? 'empty' : '' }}">
                  {{ $user->no_telepon ?? 'Belum diisi' }}
                </span>
              </div>
              
              <div class="info-item">
                <span class="info-label">Tempat, Tanggal Lahir</span>
                <span class="info-value {{ empty($user->ttl) ? 'empty' : '' }}">
                  {{ $user->ttl ?? 'Belum diisi' }}
                </span>
              </div>
              
              <div class="info-item">
                <span class="info-label">Domisili</span>
                <span class="info-value {{ empty($user->domisili) ? 'empty' : '' }}">
                  {{ $user->domisili ?? 'Belum diisi' }}
                </span>
              </div>
              
              <div class="info-item">
                <span class="info-label">NIM</span>
                <span class="info-value {{ empty($user->nim) ? 'empty' : '' }}">
                  {{ $user->nim ?? 'Belum diisi' }}
                </span>
              </div>
              
              <div class="info-item">
                <span class="info-label">Semester</span>
                <span class="info-value {{ empty($user->semester) ? 'empty' : '' }}">
                  {{ $user->semester ?? 'Belum diisi' }}
                </span>
              </div>
              
              <div class="info-item">
                <span class="info-label">IPK</span>
                <span class="info-value {{ empty($user->ipk) ? 'empty' : '' }}">
                  {{ $user->ipk ?? 'Belum diisi' }}
                </span>
              </div>
              
              <div class="info-item">
                <span class="info-label">Program Studi</span>
                <span class="info-value {{ empty($user->program) ? 'empty' : '' }}">
                  {{ $user->program ?? 'Belum diisi' }}
                </span>
              </div>
              
              <div class="info-item">
                <span class="info-label">Universitas</span>
                <span class="info-value {{ empty($user->universitas) ? 'empty' : '' }}">
                  {{ $user->universitas ?? ($user->instansi ?? 'Belum diisi') }}
                </span>
              </div>
              
              <div class="info-item">
                <span class="info-label">Instansi</span>
                <span class="info-value {{ empty($user->instansi) ? 'empty' : '' }}">
                  {{ $user->instansi ?? 'Belum diisi' }}
                </span>
              </div>
              
              @if(!empty($user->software_tools))
                <div class="info-item" style="grid-column: 1 / -1;">
                  <span class="info-label">Software/Tools yang Dikuasai</span>
                  <span class="info-value">{{ $user->software_tools }}</span>
                </div>
              @endif
              
              @if(!empty($user->kompetensi_utama))
                <div class="info-item" style="grid-column: 1 / -1;">
                  <span class="info-label">Kompetensi Utama</span>
                  <span class="info-value">{{ $user->kompetensi_utama }}</span>
                </div>
              @endif
              
              @if(!empty($user->portofolio))
                <div class="info-item" style="grid-column: 1 / -1;">
                  <span class="info-label">Portofolio</span>
                  <span class="info-value">
                    <a href="{{ $user->portofolio }}" target="_blank" style="color: #2563EB; text-decoration: underline;">
                      {{ $user->portofolio }}
                    </a>
                  </span>
                </div>
              @endif
            </div>
          </div>
          
          <!-- Surat Kerja (SK) Download Section -->
          @if(!empty($permohonan->surat_kerja))
            <div class="info-card" style="background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); border: 2px solid #10B981;">
              <div class="info-card-header">
                <h2 class="info-card-title">📄 Surat Kerja (SK)</h2>
              </div>
              
              <div style="padding: 20px; background: #FFFFFF; border-radius: 12px; border: 1px solid #A7F3D0;">
                <p style="margin: 0 0 16px 0; font-size: 14px; color: #374151; line-height: 1.6;">
                  Surat Kerja dari instansi telah tersedia. Email notifikasi juga telah dikirim ke <strong>{{ $user->email }}</strong>. Silakan unduh file berikut untuk keperluan administrasi Anda.
                </p>
                <a href="{{ route('download.sk') }}" style="display: inline-flex; align-items: center; gap: 10px; padding: 14px 28px; background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: #FFFFFF; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 15px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(16, 185, 129, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.3)'">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <polyline points="7 10 12 15 17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="12" y1="15" x2="12" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  Download Surat Kerja (SK)
                </a>
              </div>
            </div>
          @endif
          
          <!-- Informasi Tambahan -->
          <div class="info-card">
            <div class="info-card-header">
              <h2 class="info-card-title">Informasi Tambahan</h2>
            </div>
            
            <div style="padding: 20px; background: #F9FAFB; border-radius: 12px;">
              <p style="margin: 0 0 16px 0; font-size: 14px; color: #374151; line-height: 1.6;">
                <strong>Catatan Penting:</strong>
              </p>
              <ul style="margin: 0; padding-left: 20px; font-size: 14px; color: #6B7280; line-height: 1.8;">
                <li>Pastikan semua data diri Anda sudah lengkap dan benar</li>
                <li>Simpan informasi periode dan jadwal magang Anda</li>
                @if(!empty($permohonan->surat_kerja))
                  <li>Unduh Surat Kerja (SK) di atas jika sudah tersedia</li>
                @endif
                <li>Hubungi admin jika ada perubahan data atau pertanyaan</li>
                <li>Persiapkan diri untuk memulai magang sesuai jadwal yang telah ditentukan</li>
              </ul>
            </div>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>
