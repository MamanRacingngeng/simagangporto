<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Biodata Peserta - Magang Digital</title>
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
      
      .page-title {
        font-size: 32px;
        font-weight: 800;
        color: #0C3A6B;
        margin: 0 0 32px;
        letter-spacing: -0.5px;
        line-height: 1.2;
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
        
        .page-title {
          font-size: 28px;
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
            <h1 class="page-title">Biodata Peserta</h1>
          
          @if(session('success'))
            <div id="notification" style="position:fixed;top:20px;left:50%;transform:translateX(-50%);z-index:9999;padding:16px 24px;background:#10b981;color:#ffffff;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.15);font-weight:500;font-size:15px;min-width:300px;text-align:center;animation:slideDown 0.3s ease-out">
              {{ session('success') }}
            </div>
            <script>
              setTimeout(function() {
                const notification = document.getElementById('notification');
                if (notification) {
                  notification.style.animation = 'slideUp 0.3s ease-out';
                  setTimeout(function() { notification.remove(); }, 300);
                }
              }, 5000);
            </script>
          @endif
          
          <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data" id="profilForm" style="background:#ffffff;border:1px solid #e5e7eb;border-radius:16px;padding:32px;box-shadow:0 1px 3px rgba(0,0,0,0.1)">
            @csrf
            
            <!-- Foto Profil & Info Dasar -->
            <div style="display:flex;gap:32px;margin-bottom:40px;padding-bottom:32px;border-bottom:1px solid #e5e7eb">
              <div style="flex-shrink:0">
                <label for="foto_profil" id="fotoLabel" style="cursor:pointer;display:block">
                  @if($user->foto_profil)
                    <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #e5e7eb">
                  @else
                    <div style="width:120px;height:120px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-size:48px;color:#9ca3af;border:3px solid #e5e7eb">
                      {{ strtoupper(substr($user->nama ?? 'U', 0, 1)) }}
                    </div>
                  @endif
                </label>
                <input type="file" name="foto_profil" id="foto_profil" accept="image/*" style="display:none" disabled onchange="handleFotoChange(event)">
                <p style="margin:8px 0 0;text-align:center;font-size:12px;color:#6b7280" id="fotoHint">Klik untuk ganti foto</p>
              </div>
              
              <div style="flex:1">
                <div style="margin-bottom:16px">
                  <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Nama</label>
                  <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required disabled class="form-input" style="width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:15px;background:#f9fafb">
                  @error('nama') <p style="color:#ef4444;font-size:13px;margin:4px 0 0">{{ $message }}</p> @enderror
                </div>
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                  <div>
                    <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Program</label>
                    <input type="text" name="program" value="{{ old('program', $user->program) }}" placeholder="S1 Informatika" disabled class="form-input" style="width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:15px;background:#f9fafb">
                    @error('program') <p style="color:#ef4444;font-size:13px;margin:4px 0 0">{{ $message }}</p> @enderror
                  </div>
                  
                  <div>
                    <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Universitas</label>
                    <input type="text" name="universitas" value="{{ old('universitas', $user->universitas) }}" placeholder="Universitas Ahmad Dahlan" disabled class="form-input" style="width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:15px;background:#f9fafb">
                    @error('universitas') <p style="color:#ef4444;font-size:13px;margin:4px 0 0">{{ $message }}</p> @enderror
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Informasi Pribadi -->
            <div style="margin-bottom:32px">
              <h2 style="margin:0 0 20px;font-size:20px;font-weight:700;color:#111827">Informasi Pribadi</h2>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
                <div>
                  <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Email</label>
                  <input type="email" name="email" value="{{ old('email', $user->email) }}" required disabled class="form-input" style="width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:15px;background:#f9fafb">
                  @error('email') <p style="color:#ef4444;font-size:13px;margin:4px 0 0">{{ $message }}</p> @enderror
                </div>
                
                <div>
                  <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Nama Panggilan</label>
                  <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan', $user->nama_panggilan) }}" placeholder="Aldi" disabled class="form-input" style="width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:15px;background:#f9fafb">
                  @error('nama_panggilan') <p style="color:#ef4444;font-size:13px;margin:4px 0 0">{{ $message }}</p> @enderror
                </div>
                
                <div>
                  <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">WhatsApp/HP</label>
                  <input type="text" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" placeholder="087749494136" disabled class="form-input" style="width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:15px;background:#f9fafb">
                  @error('no_telepon') <p style="color:#ef4444;font-size:13px;margin:4px 0 0">{{ $message }}</p> @enderror
                </div>
                
                <div>
                  <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">TTL (Tempat, Tanggal Lahir)</label>
                  <input type="text" name="ttl" value="{{ old('ttl', $user->ttl) }}" placeholder="BREBES, 3 January 2008" disabled class="form-input" style="width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:15px;background:#f9fafb">
                  @error('ttl') <p style="color:#ef4444;font-size:13px;margin:4px 0 0">{{ $message }}</p> @enderror
                </div>
                
                <div style="grid-column:1/-1">
                  <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Domisili</label>
                  <input type="text" name="domisili" value="{{ old('domisili', $user->domisili) }}" placeholder="jakarta" disabled class="form-input" style="width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:15px;background:#f9fafb">
                  @error('domisili') <p style="color:#ef4444;font-size:13px;margin:4px 0 0">{{ $message }}</p> @enderror
                </div>
              </div>
            </div>
            
            <!-- Akademik & Skill -->
            <div style="margin-bottom:32px">
              <h2 style="margin:0 0 20px;font-size:20px;font-weight:700;color:#111827">Akademik & Skill</h2>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
                <div>
                  <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">NIM/NIS</label>
                  <input type="text" name="nim" value="{{ old('nim', $user->nim) }}" placeholder="2200018293" disabled class="form-input" style="width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:15px;background:#f9fafb">
                  @error('nim') <p style="color:#ef4444;font-size:13px;margin:4px 0 0">{{ $message }}</p> @enderror
                </div>
                
                <div>
                  <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Semester</label>
                  <input type="number" name="semester" value="{{ old('semester', $user->semester) }}" placeholder="7" min="1" max="14" disabled class="form-input" style="width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:15px;background:#f9fafb">
                  @error('semester') <p style="color:#ef4444;font-size:13px;margin:4px 0 0">{{ $message }}</p> @enderror
                </div>
                
                <div>
                  <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">IPK</label>
                  <input type="number" name="ipk" value="{{ old('ipk', $user->ipk) }}" placeholder="4.00" step="0.01" min="0" max="4" disabled class="form-input" style="width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:15px;background:#f9fafb">
                  @error('ipk') <p style="color:#ef4444;font-size:13px;margin:4px 0 0">{{ $message }}</p> @enderror
                </div>
                
                <div>
                  <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Software/Tools</label>
                  <input type="text" name="software_tools" value="{{ old('software_tools', $user->software_tools) }}" placeholder="Collab" disabled class="form-input" style="width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:15px;background:#f9fafb">
                  @error('software_tools') <p style="color:#ef4444;font-size:13px;margin:4px 0 0">{{ $message }}</p> @enderror
                </div>
                
                <div>
                  <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Portofolio (URL)</label>
                  <input type="url" name="portofolio" value="{{ old('portofolio', $user->portofolio) }}" placeholder="https://portfolio.example.com" disabled class="form-input" style="width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:15px;background:#f9fafb">
                  @error('portofolio') <p style="color:#ef4444;font-size:13px;margin:4px 0 0">{{ $message }}</p> @enderror
                </div>
                
                <div>
                  <label style="display:block;margin-bottom:6px;font-weight:500;color:#374151">Kompetensi Utama</label>
                  <input type="text" name="kompetensi_utama" value="{{ old('kompetensi_utama', $user->kompetensi_utama) }}" placeholder="Data Science" disabled class="form-input" style="width:100%;padding:10px 14px;border:1px solid #e5e7eb;border-radius:8px;font-size:15px;background:#f9fafb">
                  @error('kompetensi_utama') <p style="color:#ef4444;font-size:13px;margin:4px 0 0">{{ $message }}</p> @enderror
                </div>
              </div>
            </div>
            
            <!-- Tombol Action -->
            <div style="display:flex;justify-content:flex-end;gap:12px;padding-top:24px;border-top:1px solid #e5e7eb" id="buttonContainer">
              <button type="button" id="editBtn" onclick="enableEdit()" style="padding:12px 24px;background:#f59e0b;color:#ffffff;border:none;border-radius:8px;font-weight:600;font-size:15px;cursor:pointer;transition:all 0.2s;display:inline-flex;align-items:center;gap:8px">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Edit Data
              </button>
              
              <div id="saveCancelButtons" style="display:none !important;gap:12px;align-items:center">
                <button type="button" id="cancelBtn" onclick="cancelEdit()" style="padding:12px 24px;background:#ffffff;color:#6b7280;border:1px solid #e5e7eb;border-radius:8px;font-weight:600;font-size:15px;cursor:pointer;transition:all 0.2s">
                  Batal
                </button>
                <button type="submit" id="saveBtn" style="padding:12px 24px;background:#10b981;color:#ffffff;border:none;border-radius:8px;font-weight:600;font-size:15px;cursor:pointer;transition:all 0.2s;display:inline-flex;align-items:center;gap:8px">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <polyline points="17 21 17 13 7 13 7 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <polyline points="7 3 7 8 15 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  Simpan
                </button>
              </div>
            </div>
          </form>
          </section>
        </div>
      </main>
    </div>
    <style>
      @keyframes slideDown {
        from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
        to { opacity: 1; transform: translateX(-50%) translateY(0); }
      }
      @keyframes slideUp {
        from { opacity: 1; transform: translateX(-50%) translateY(0); }
        to { opacity: 0; transform: translateX(-50%) translateY(-20px); }
      }
      
    </style>
    <script>
      let originalValues = {};
      
      function enableEdit() {
        // Simpan nilai original untuk cancel
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
          originalValues[input.name] = input.value;
          input.disabled = false;
          input.style.background = '#ffffff';
          input.style.cursor = 'text';
        });
        
        // Simpan foto original
        const fotoInput = document.getElementById('foto_profil');
        originalValues.fotoOriginal = fotoInput ? fotoInput.files.length : 0;
        
        // Enable foto upload
        const fotoLabel = document.getElementById('fotoLabel');
        if (fotoInput) {
          fotoInput.disabled = false;
          fotoLabel.style.cursor = 'pointer';
        }
        document.getElementById('fotoHint').textContent = 'Klik untuk ganti foto';
        
        // Toggle buttons - sembunyikan Edit Data, tampilkan Simpan dan Batal
        document.getElementById('editBtn').style.display = 'none';
        const saveCancelDiv = document.getElementById('saveCancelButtons');
        saveCancelDiv.style.setProperty('display', 'flex', 'important');
        saveCancelDiv.style.alignItems = 'center';
      }
      
      function cancelEdit() {
        // Restore original values
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
          input.value = originalValues[input.name] || input.value;
          input.disabled = true;
          input.style.background = '#f9fafb';
          input.style.cursor = 'not-allowed';
        });
        
        // Restore foto (clear file input)
        const fotoInput = document.getElementById('foto_profil');
        const fotoLabel = document.getElementById('fotoLabel');
        if (fotoInput) {
          fotoInput.disabled = true;
          fotoInput.value = '';
          fotoLabel.style.cursor = 'not-allowed';
          
          // Restore foto preview to original
          @if($user->foto_profil)
            fotoLabel.innerHTML = `<img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #e5e7eb">`;
          @else
            fotoLabel.innerHTML = `<div style="width:120px;height:120px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-size:48px;color:#9ca3af;border:3px solid #e5e7eb">{{ strtoupper(substr($user->nama ?? 'U', 0, 1)) }}</div>`;
          @endif
        }
        document.getElementById('fotoHint').textContent = 'Edit data untuk mengubah foto';
        
        // Toggle buttons - tampilkan Edit Data, sembunyikan Simpan dan Batal
        document.getElementById('editBtn').style.display = 'inline-flex';
        const saveCancelDivCancel = document.getElementById('saveCancelButtons');
        saveCancelDivCancel.style.setProperty('display', 'none', 'important');
      }
      
      function handleFotoChange(event) {
        const file = event.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            const fotoLabel = document.getElementById('fotoLabel');
            fotoLabel.innerHTML = `<img src="${e.target.result}" alt="Foto Profil" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #e5e7eb">`;
          };
          reader.readAsDataURL(file);
        }
      }
      
      // Initialize: ensure all inputs are disabled on load
      document.addEventListener('DOMContentLoaded', function() {
        // Simpan original values
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
          originalValues[input.name] = input.value;
          // Pastikan semua input disabled
          if (!input.disabled) {
            input.disabled = true;
            input.style.background = '#f9fafb';
            input.style.cursor = 'not-allowed';
          }
        });
        
        // Set foto label cursor based on edit mode
        const fotoLabel = document.getElementById('fotoLabel');
        const fotoInput = document.getElementById('foto_profil');
        if (fotoInput) {
          if (fotoInput.disabled) {
            fotoLabel.style.cursor = 'not-allowed';
            document.getElementById('fotoHint').textContent = 'Edit data untuk mengubah foto';
          }
        }
        
        // Pastikan tombol Batal dan Simpan tersembunyi saat pertama kali load
        const saveCancelDiv = document.getElementById('saveCancelButtons');
        const editBtn = document.getElementById('editBtn');
        
        if (saveCancelDiv) {
          saveCancelDiv.style.setProperty('display', 'none', 'important');
        }
        if (editBtn) {
          editBtn.style.display = 'inline-flex';
        }
      });
      
      // Juga pastikan saat window load
      window.addEventListener('load', function() {
        const saveCancelDiv = document.getElementById('saveCancelButtons');
        const editBtn = document.getElementById('editBtn');
        
        if (saveCancelDiv) {
          saveCancelDiv.style.setProperty('display', 'none', 'important');
        }
        if (editBtn) {
          editBtn.style.display = 'inline-flex';
        }
      });
    </script>
  </body>
</html>
