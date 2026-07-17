import { Navbar } from "@/components/layout/navbar";
import { Footer } from "@/components/layout/footer";

export default function TentangKamiPage() {
  return (
    <>
      <Navbar />
      <main>
        <section
          className="relative flex min-h-[70vh] items-center justify-center py-32 text-white md:py-40"
          style={{
            backgroundImage: "url('/images/baground2.jpg')",
            backgroundSize: "cover",
            backgroundPosition: "center",
          }}
        >
          <div className="absolute inset-0 bg-gradient-to-b from-black/50 via-black/45 to-black/40 backdrop-blur-[0.5px]" />
          <div className="relative mx-auto w-full max-w-6xl px-6 text-center">
            <div className="mb-8 flex justify-center">
              <div className="flex h-28 w-28 items-center justify-center overflow-hidden rounded-full bg-white p-2 shadow-lg">
                <img src="/images/profilebbkb.png" alt="Logo BBKB" width={100} height={100} className="h-full w-full object-contain" />
              </div>
            </div>
            <h1 className="mb-4 text-2xl font-bold drop-shadow-lg md:text-3xl lg:text-4xl">Tentang Kami</h1>
            <p className="mx-auto max-w-3xl text-base opacity-95 drop-shadow-md md:text-lg">
              Balai Besar Kerajinan dan Batik (BBKB) Yogyakarta
            </p>
          </div>
        </section>

        <section className="bg-white py-20">
          <div className="mx-auto grid max-w-6xl items-center gap-12 px-6 md:grid-cols-2">
            <div>
              <h2 className="mb-6 text-3xl font-bold text-gray-800">Profil BBKB Yogyakarta</h2>
              <div className="space-y-4 leading-relaxed text-gray-700">
                <p>
                  Balai Besar Kerajinan dan Batik (BBKB) Yogyakarta merupakan unit pelaksana teknis di bawah Kementerian Perindustrian RI yang berfokus pada penelitian, pengembangan, pelayanan teknis, serta peningkatan kompetensi di bidang kerajinan dan batik.
                </p>
                <p>
                  BBKB Yogyakarta mendukung talenta muda untuk belajar secara langsung melalui program magang, penelitian, dan pengembangan produk, guna berkontribusi pada industri kreatif nasional.
                </p>
              </div>
            </div>
            <div className="flex justify-center">
              <div className="w-full max-w-md rounded-2xl bg-gray-50 p-8 shadow-lg">
                <div className="flex flex-col items-center text-center">
                  <img src="/images/profilebbkb.png" alt="BBKB" width={96} height={96} className="mb-6 object-contain" />
                  <h3 className="text-lg font-bold text-gray-800">Balai Besar Kerajinan Dan Batik Yogyakarta</h3>
                  <p className="mt-2 text-sm text-gray-600">Kementerian Perindustrian</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section className="bg-gray-50 py-20">
          <div className="mx-auto max-w-6xl px-6">
            <h2 className="mb-10 text-center text-3xl font-bold">Mengapa Memilih BBKB Yogyakarta?</h2>
            <div className="grid gap-8 md:grid-cols-3">
              {[
                { color: "from-blue-50 to-blue-100", title: "Mentoring Ahli", desc: "Belajar langsung dari praktisi berpengalaman di industri kerajinan & batik." },
                { color: "from-green-50 to-green-100", title: "Proyek Nyata", desc: "Terlibat langsung dalam proyek penelitian dan pengembangan yang berdampak." },
                { color: "from-yellow-50 to-yellow-100", title: "Sertifikat Resmi", desc: "Dapatkan sertifikat penyelesaian resmi dari BBKB Yogyakarta." },
              ].map((item) => (
                <div key={item.title} className={`rounded-2xl bg-gradient-to-br ${item.color} p-8 shadow-lg`}>
                  <h3 className="mb-4 text-xl font-bold text-gray-800">{item.title}</h3>
                  <p className="leading-relaxed text-gray-700">{item.desc}</p>
                </div>
              ))}
            </div>
          </div>
        </section>
      </main>
      <Footer />
    </>
  );
}
