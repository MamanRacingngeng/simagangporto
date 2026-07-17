import { Navbar } from "@/components/layout/navbar";
import { Footer } from "@/components/layout/footer";
import { demoGaleri } from "@/lib/demo-data";

export default function GaleriMagangPage() {
  return (
    <>
      <Navbar />
      <main className="py-16">
        <div className="mx-auto max-w-6xl px-6">
          <div className="mb-12 text-center">
            <h1 className="text-3xl font-bold text-gray-900 md:text-4xl">Galeri Magang</h1>
            <p className="mt-4 text-gray-600">
              Dokumentasi kegiatan dan momen magang di BBKB Yogyakarta.
            </p>
          </div>

          <div className="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            {demoGaleri.map((item) => (
              <figure key={item.id} className="overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-gray-100">
                <div className="flex h-[260px] items-center justify-center bg-gradient-to-br from-yellow-100 to-yellow-200 text-5xl">
                  📷
                </div>
                <figcaption className="p-6">
                  <h2 className="text-lg font-bold text-gray-900">{item.judul}</h2>
                  {item.deskripsi && (
                    <p className="mt-2 text-sm leading-relaxed text-gray-600">{item.deskripsi}</p>
                  )}
                </figcaption>
              </figure>
            ))}
          </div>
        </div>
      </main>
      <Footer />
    </>
  );
}
