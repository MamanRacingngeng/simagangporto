import Link from "next/link";
import Image from "next/image";
import { Navbar } from "@/components/layout/navbar";
import { Footer } from "@/components/layout/footer";
import { demoKuota } from "@/lib/demo-data";

export default function HomePage() {
  const sampleJobs = demoKuota.map((item) => ({
    title: item.posisi,
    description: item.deskripsi ?? "Magang di BBKB Yogyakarta.",
    periode: item.periode,
  }));

  return (
    <>
      <Navbar />
      <main className="fade-in">
        <section
          id="beranda"
          className="hero-batik relative flex w-full flex-col items-center justify-center text-white"
          style={{
            backgroundImage: "url('/images/BagroundDashboard.jpg')",
            backgroundSize: "cover",
            backgroundPosition: "center center",
            minHeight: "100vh",
            margin: 0,
            padding: 0,
          }}
        >
          <div className="absolute inset-0 bg-gradient-to-b from-black/50 via-black/45 to-black/30 backdrop-blur-[0.5px]" />
          <div className="absolute bottom-0 left-0 right-0 h-40 bg-gradient-to-b from-transparent via-white/20 to-white" />

          <div className="relative z-10 mx-auto flex max-w-6xl flex-1 flex-col justify-center px-4 py-24 text-center sm:px-6 sm:py-32">
            <h1 className="animate-fade-in text-2xl font-bold leading-tight text-white drop-shadow-2xl sm:text-3xl md:text-4xl lg:text-5xl">
              Jelajahi Pengalaman Magang di Dunia Kerajinan & Batik
            </h1>
            <p className="mt-6 text-lg font-medium text-white opacity-95 drop-shadow-lg md:text-xl">
              Wujudkan potensi Anda bersama Balai Besar Kerajinan & Batik Yogyakarta.
            </p>
            <div className="mt-8 flex flex-col justify-center gap-3 sm:mt-10 sm:flex-row sm:gap-4">
              <Link
                href="#lowongan"
                className="transform rounded-xl bg-yellow-400 px-6 py-3 text-center text-sm font-semibold text-gray-900 shadow-lg transition hover:scale-105 hover:bg-yellow-500 sm:px-8 sm:py-4 sm:text-base"
              >
                Lihat Lowongan Magang
              </Link>
              <Link
                href="/dashboard"
                className="transform rounded-xl border-2 border-white/50 bg-white/95 px-6 py-3 text-center text-sm font-semibold text-gray-900 shadow-lg transition hover:scale-105 hover:border-white hover:bg-white sm:px-8 sm:py-4 sm:text-base"
              >
                Lihat Demo Sistem
              </Link>
            </div>
          </div>

          <div className="absolute bottom-8 left-1/2 z-10 -translate-x-1/2 animate-bounce">
            <svg className="h-6 w-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
          </div>
        </section>

        <section id="alur" className="relative -mt-24 rounded-t-3xl bg-gray-50 py-20 pt-32 shadow-lg">
          <div className="mx-auto max-w-6xl px-6">
            <h2 className="animate-fade-in mb-10 text-center text-3xl font-bold">Alur Pendaftaran</h2>
            <div className="grid gap-6 md:grid-cols-3">
              {[
                { step: 1, bg: "bg-blue-50", numBg: "bg-blue-600", icon: "/images/buatakun.png", title: "Buat Akun", desc: "Daftar dan lengkapi profil Anda." },
                { step: 2, bg: "bg-green-50", numBg: "bg-green-600", icon: "/images/ajukanlamaran.png", title: "Ajukan Lamaran", desc: "Pilih posisi dan kirim berkas." },
                { step: 3, bg: "bg-yellow-50", numBg: "bg-yellow-500", icon: "/images/seleksidanhasil.png", title: "Seleksi & Hasil", desc: "Tunggu hasil seleksi & onboarding." },
              ].map((item) => (
                <div
                  key={item.step}
                  className={`step-card step-${item.step} transform rounded-xl bg-white p-6 shadow transition-all duration-300 hover:-translate-y-2 hover:shadow-xl`}
                >
                  <div className="mb-4 flex flex-col items-center">
                    <div className={`step-icon-container mb-3 flex h-20 w-20 items-center justify-center rounded-full ${item.bg}`}>
                      <Image src={item.icon} alt={item.title} width={56} height={56} className="step-icon object-contain" />
                    </div>
                    <div className={`step-number flex h-8 w-8 items-center justify-center rounded-full text-sm font-bold text-white ${item.numBg}`}>
                      {item.step}
                    </div>
                  </div>
                  <h3 className="mb-2 text-center text-xl font-bold">{item.title}</h3>
                  <p className="mt-2 text-center text-gray-600">{item.desc}</p>
                </div>
              ))}
            </div>
          </div>
        </section>

        <section id="lowongan" className="relative bg-white py-20">
          <div className="mx-auto max-w-6xl px-6">
            <div className="mb-8 flex flex-col items-center justify-between sm:flex-row">
              <h2 className="mb-4 text-3xl font-bold sm:mb-0">Lowongan Magang</h2>
              <Link href="/lowongan" className="rounded-lg bg-yellow-400 px-4 py-2 transition hover:bg-yellow-500">
                Lihat Semua Lowongan
              </Link>
            </div>
            <div className="grid gap-6 md:grid-cols-3">
              {sampleJobs.map((job) => (
                <div key={job.title} className="rounded-xl bg-white p-6 shadow transition hover:shadow-xl">
                  <h3 className="text-lg font-bold">{job.title}</h3>
                  <p className="mt-2 text-gray-600">{job.description}</p>
                  {job.periode && <p className="mt-2 text-sm text-gray-500">{job.periode}</p>}
                  <Link href="/lowongan" className="mt-4 inline-block text-blue-600 transition hover:text-blue-800">
                    Selengkapnya →
                  </Link>
                </div>
              ))}
            </div>
          </div>
        </section>

        <section id="galeri" className="relative bg-gray-50 py-20">
          <div className="mx-auto max-w-6xl px-6">
            <h2 className="mb-4 text-center text-3xl font-bold">Galeri Magang</h2>
            <p className="mx-auto mb-8 max-w-2xl text-center text-gray-600">
              Lihat momen-momen berharga dari kegiatan magang di BBKB Yogyakarta.
            </p>
            <div className="mb-8 grid grid-cols-2 gap-6 md:grid-cols-4">
              {[
                { emoji: "👥", n: "500+", l: "Peserta Magang" },
                { emoji: "🎓", n: "15+", l: "Program Tersedia" },
                { emoji: "⭐", n: "10+", l: "Tahun Pengalaman" },
                { emoji: "📜", n: "450+", l: "Sertifikat Diterbitkan" },
              ].map(({ emoji, n, l }) => (
                <div
                  key={l}
                  className="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                >
                  <div className="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-yellow-400 to-yellow-500 text-3xl shadow-md">
                    {emoji}
                  </div>
                  <div className="flex-1">
                    <div className="mb-1 text-2xl font-bold text-blue-900">{n}</div>
                    <div className="text-sm font-semibold text-gray-600">{l}</div>
                  </div>
                </div>
              ))}
            </div>
            <div className="mt-8 text-center">
              <Link
                href="/galeri-magang"
                className="inline-flex items-center rounded-lg bg-yellow-400 px-6 py-3 font-semibold text-gray-900 shadow-md transition hover:bg-yellow-500 hover:shadow-lg"
              >
                Lihat Galeri Magang
                <svg className="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                </svg>
              </Link>
            </div>
          </div>
        </section>
      </main>
      <Footer />
    </>
  );
}
