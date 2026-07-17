import { Navbar } from "@/components/layout/navbar";
import { Footer } from "@/components/layout/footer";
import { prisma } from "@/lib/prisma";
import { tryQuery } from "@/lib/db";
import Image from "next/image";

export const dynamic = "force-dynamic";

export default async function GaleriMagangPage() {
  const galeri = await tryQuery(
    () =>
      prisma.galeriMagang.findMany({
        where: { aktif: true },
        orderBy: { urutan: "asc" },
      }),
    [],
  );

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

          {galeri.length === 0 ? (
            <div className="rounded-2xl border border-dashed border-gray-200 bg-white p-16 text-center">
              <div className="mb-4 text-6xl opacity-50">📷</div>
              <h3 className="text-xl font-bold text-gray-900">Belum Ada Foto Galeri</h3>
              <p className="mt-2 text-gray-600">Foto galeri akan ditampilkan di sini.</p>
            </div>
          ) : (
            <div className="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
              {galeri.map((item) => (
                <figure key={item.id} className="overflow-hidden rounded-2xl bg-white shadow-lg ring-1 ring-gray-100">
                  {item.foto ? (
                    <Image
                      src={item.foto}
                      alt={item.judul}
                      width={400}
                      height={260}
                      className="h-[260px] w-full object-cover"
                      unoptimized
                    />
                  ) : (
                    <div className="flex h-[260px] items-center justify-center bg-gradient-to-br from-yellow-100 to-yellow-200 text-5xl">
                      📷
                    </div>
                  )}
                  <figcaption className="p-6">
                    <h2 className="text-lg font-bold text-gray-900">{item.judul}</h2>
                    {item.deskripsi && (
                      <p className="mt-2 text-sm leading-relaxed text-gray-600">{item.deskripsi}</p>
                    )}
                  </figcaption>
                </figure>
              ))}
            </div>
          )}
        </div>
      </main>
      <Footer />
    </>
  );
}
