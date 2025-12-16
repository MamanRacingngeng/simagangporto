<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BBKB Yogyakarta - Magang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
      :root { --primary:#0C3A6B; --accent:#F4B400; --dark:#0b1020; --muted:#6b7280; }
      *{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#ffffff;color:#0f172a}
      .container{max-width:1200px;margin:0 auto;padding:0 24px}
      .navbar{position:sticky;top:0;background:#ffffff; color:#0f172a; border-bottom:1px solid #e5e7eb;z-index:10}
      .nav-inner{display:flex;align-items:center;justify-content:space-between;padding:14px 0;padding-left:16px;padding-right:24px}
      .brand{display:flex;align-items:center;gap:8px;margin-left:0}
      .brand img{height:48px}
      .brand img:last-child{height:56px}
      .menu{display:flex;align-items:center;gap:28px}
      .menu a{color:#0f172a;text-decoration:none;font-weight:600;opacity:.8}
      .menu a:hover{opacity:1}
      .btn{display:inline-flex;align-items:center;gap:8px;border-radius:10px;padding:10px 16px;text-decoration:none;font-weight:700}
      .btn-primary{background:var(--accent);color:#0b1020}
      .btn-primary:hover{filter:brightness(.95)}
      .btn-ghost{color:#0f172a;border:1px solid #e5e7eb;background:#fff}
      .btn-ghost:hover{background:#f8fafc}
      .user-profile{display:flex;align-items:center;gap:12px;padding:8px 16px;border-radius:12px;background:var(--accent);color:#0b1020;text-decoration:none;font-weight:600;transition:all 0.3s ease}
      .user-profile:hover{filter:brightness(.95);transform:translateY(-1px);box-shadow:0 4px 12px rgba(0,0,0,.1)}
      .user-profile img{width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid rgba(11,16,32,.1)}
      .user-profile .user-info{display:flex;flex-direction:column;gap:2px}
      .user-profile .user-name{font-size:14px;font-weight:700;line-height:1.2}
      .user-profile .user-email{font-size:12px;opacity:.8;line-height:1.2}
      .user-profile .user-initial{width:40px;height:40px;border-radius:50%;background:rgba(11,16,32,.1);display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;color:#0b1020;border:2px solid rgba(11,16,32,.1)}
      .hero{position:relative;min-height:86vh;display:grid;align-items:center;overflow:hidden}
      .hero-bg{position:absolute;inset:0;background:url('/images/baground.jpg'), url('/images/hero-batik.jpg') center/cover no-repeat;background-size:cover;background-position:center;filter:brightness(.55)}
      .hero-shade{position:absolute;inset:0;background:linear-gradient(180deg,rgba(11,16,32,.2),rgba(11,16,32,.75))}
      .hero-content{position:relative;padding:90px 0;z-index:1}
      .kemen{display:flex;align-items:center;gap:16px;margin-bottom:18px;opacity:.98}
      .kemen img{height:28px;background:#fff;border-radius:6px;padding:4px;box-shadow:0 1px 2px rgba(0,0,0,.12)}
      .title{font-size:clamp(36px,5.4vw,60px);line-height:1.05;font-weight:800;margin:0 0 10px;color:#ffffff;text-shadow:0 2px 12px rgba(2,6,23,.35)}
      .subtitle{font-size:clamp(16px,2.2vw,20px);color:#e5e7eb;margin:0 0 28px;opacity:.95;text-shadow:0 2px 10px rgba(2,6,23,.25)}
      .cta{display:flex;gap:14px;flex-wrap:wrap}
      .section{background:#ffffff;padding:72px 0;border-top:1px solid #e5e7eb}
      .cards{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}
      .card{background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;padding:22px;box-shadow:0 8px 20px rgba(2,6,23,.04)}
      .card h3{margin:8px 0 8px;font-size:18px;color:#0f172a}
      .card p{margin:0;color:#475569}
      @media (max-width:960px){.cards{grid-template-columns:1fr 1fr}}
      @media (max-width:640px){
        .cards{grid-template-columns:1fr}
        .user-profile .user-info{display:none}
        .user-profile{padding:8px 12px}
        .menu{gap:16px}
        .menu a{font-size:14px}
      }
      footer{color:#475569;background:#ffffff;border-top:1px solid #e5e7eb}
      .footer-inner{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:22px 0;flex-wrap:wrap}
      /* Galeri */
      .gallery{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
      .gallery img{width:100%;height:230px;object-fit:cover;border-radius:12px;border:1px solid #e5e7eb}
      @media (max-width:960px){.gallery{grid-template-columns:repeat(2,minmax(0,1fr))}}
      @media (max-width:640px){.gallery{grid-template-columns:1fr}}
      /* Partner */
      .partners{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px;align-items:center}
      .partners .logo{background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;display:flex;align-items:center;justify-content:center;padding:16px;box-shadow:0 6px 16px rgba(2,6,23,.04)}
      .partners .logo img{max-height:44px;max-width:100%}
      @media (max-width:960px){.partners{grid-template-columns:repeat(2,minmax(0,1fr))}}
      /* Testimoni */
      .testi{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}
      .testi .item{background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;padding:18px;box-shadow:0 6px 16px rgba(2,6,23,.04)}
      .testi .by{display:flex;align-items:center;gap:10px;margin-top:10px;color:#111827}
      @media (max-width:960px){.testi{grid-template-columns:1fr 1fr}}
      @media (max-width:640px){.testi{grid-template-columns:1fr}}
    </style>
  </head>
  <body>
    <header class="navbar">
      <div class="container nav-inner">
        <div class="brand">
          <img src="/images/logoBBKB.png" alt="BBKB">
          <img src="/images/logokemenperi.png" alt="Kemenperin">
        </div>
        <nav class="menu">
          <a href="#beranda">Beranda</a>
          <a href="#tentang">Tentang Kami</a>
          <a href="#alur">Alur Pendaftaran</a>
          <a href="#lowongan">Lowongan Magang</a>
          <a href="{{ route('galeri-magang') }}">Galeri Magang</a>
        </nav>
        <div class="menu">
          @auth
            <a href="{{ route('dashboard') }}" class="user-profile">
              @if(auth()->user()->foto_profil)
                <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="{{ auth()->user()->nama }}">
              @elseif(auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->nama }}">
              @else
                <div class="user-initial">
                  {{ strtoupper(substr(auth()->user()->nama ?? 'U', 0, 1)) }}
                </div>
              @endif
              <div class="user-info">
                <span class="user-name">{{ auth()->user()->nama ?? 'User' }}</span>
                <span class="user-email">{{ auth()->user()->email }}</span>
              </div>
            </a>
          @else
            <a href="{{ route('login') }}" class="btn btn-ghost">Login / Daftar</a>
          @endauth
        </div>
      </div>
    </header>

    <main id="beranda" class="hero">
      <div class="hero-bg"></div>
      <div class="hero-shade"></div>
      <div class="container hero-content">
        <h1 class="title">Jelajahi Pengalaman Magang di Dunia Kerajinan & Batik</h1>
        <p class="subtitle">Wujudkan potensimu bersama Balai Besar Kerajinan &amp; Batik Yogyakarta.</p>
        <div class="cta">
          <a class="btn btn-primary" href="{{ auth()->check() ? route('lowongan') : route('login') }}">Lihat Lowongan Magang</a>
          <a class="btn btn-ghost" href="#tentang">Pelajari Program</a>
        </div>
      </div>
    </main>

    <section id="tentang" class="section">
      <div class="container">
        <h2 style="margin:0 0 18px;font-size:28px">Tentang Kami</h2>
        <p style="margin:0 0 24px;color:#9ca3af;max-width:780px">BBKB Yogyakarta mendukung talenta muda untuk belajar langsung di lingkungan kerja nyata. Program magang kami memberi pengalaman praktik terbaik di bidang riset, pengembangan, produksi, dan desain kerajinan & batik.</p>
        <div class="cards">
          <div class="card"><h3>Mentoring Ahli</h3><p>Belajar dari praktisi berpengalaman di industri kerajinan & batik.</p></div>
          <div class="card"><h3>Proyek Nyata</h3><p>Terlibat langsung dalam proyek yang berdampak pada masyarakat.</p></div>
          <div class="card"><h3>Sertifikat Resmi</h3><p>Dapatkan sertifikat penyelesaian dari BBKB Yogyakarta.</p></div>
        </div>
      </div>
    </section>

    <section id="alur" class="section">
      <div class="container">
        <h2 style="margin:0 0 18px;font-size:28px">Alur Pendaftaran</h2>
        <div class="cards">
          <div class="card"><h3>1. Buat Akun</h3><p>Daftar dan lengkapi profil Anda pada portal magang.</p></div>
          <div class="card"><h3>2. Ajukan Lamaran</h3><p>Pilih posisi dan kirimkan berkas yang diminta.</p></div>
          <div class="card"><h3>3. Seleksi & Hasil</h3><p>Tunggu hasil seleksi. Jika diterima, mulai onboarding.</p></div>
        </div>
      </div>
    </section>

    <section id="lowongan" class="section">
      <div class="container" style="display:flex;align-items:center;justify-content:space-between;gap:18px;flex-wrap:wrap">
        <div>
          <h2 style="margin:0 0 12px;font-size:28px">Lowongan Magang</h2>
          <p style="margin:0;color:#9ca3af;max-width:720px">Temukan posisi yang sesuai minatmu dalam bidang Riset, Produksi, Desain, hingga Manajemen Program.</p>
        </div>
        <a class="btn btn-primary" href="{{ auth()->check() ? route('lowongan') : route('login') }}">Lihat Semua Lowongan</a>
      </div>
    </section>

    <section id="galeri" class="section">
      <div class="container">
        <h2 style="margin:0 0 18px;font-size:28px">Galeri Magang</h2>
        <p style="margin:0 0 24px;color:#9ca3af;max-width:780px">Lihat momen-momen berharga dari kegiatan magang di BBKB Yogyakarta. Dokumentasi pengalaman peserta magang dalam berbagai kegiatan dan proyek.</p>
        <div class="gallery">
          <img src="/images/baground.jpg" alt="Kegiatan Magang">
          <img src="/images/hero-batik.jpg" alt="Kegiatan Magang">
          <img src="/images/baground.jpg" alt="Kegiatan Magang">
          <img src="/images/hero-batik.jpg" alt="Kegiatan Magang">
          <img src="/images/baground.jpg" alt="Kegiatan Magang">
          <img src="/images/hero-batik.jpg" alt="Kegiatan Magang">
        </div>
        <div style="margin-top:24px;text-align:center">
          <a class="btn btn-primary" href="{{ route('galeri-magang') }}">Lihat Galeri Magang</a>
        </div>
      </div>
    </section>

    <section id="partner" class="section">
      <div class="container">
        <h2 style="margin:0 0 18px;font-size:28px">Mitra & Partner</h2>
        <div class="partners">
          <div class="logo"><img src="/images/logoBBKB.png" alt="BBKB"></div>
          <div class="logo"><img src="/images/logokemenperi.png" alt="Kemenperin"></div>
          <div class="logo"><img src="/images/logoBBKB.png" alt="BBKB"></div>
          <div class="logo"><img src="/images/logokemenperi.png" alt="Kemenperin"></div>
        </div>
      </div>
    </section>

    <section id="testimoni" class="section">
      <div class="container">
        <h2 style="margin:0 0 18px;font-size:28px">Testimoni Peserta</h2>
        <div class="testi">
          <div class="item">
            “Pengalaman magang yang sangat berkesan. Belajar langsung dari ahli batik!”
            <div class="by">— Putri, Desain Motif</div>
          </div>
          <div class="item">
            “Proyek nyata dan mentoring intensif, sangat membantu pengembangan karier.”
            <div class="by">— Rahman, Riset Material</div>
          </div>
          <div class="item">
            “Lingkungan suportif, fasilitas lengkap, dan budaya kerja yang positif.”
            <div class="by">— Sari, Produksi</div>
          </div>
        </div>
      </div>
    </section>

    <footer>
      <div class="container footer-inner">
        <div>© {{ date('Y') }} BBKB Yogyakarta. Semua hak dilindungi.</div>
        <div style="display:flex;gap:12px">
          <a class="nav-links" href="#tentang" style="color:#9ca3af;text-decoration:none">Tentang</a>
          <a class="nav-links" href="#alur" style="color:#9ca3af;text-decoration:none">Alur</a>
          <a class="nav-links" href="#lowongan" style="color:#9ca3af;text-decoration:none">Lowongan</a>
        </div>
      </div>
    </footer>
  </body>
</html>

