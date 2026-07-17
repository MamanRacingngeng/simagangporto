import bcrypt from "bcryptjs";
import { PrismaClient } from "@prisma/client";

const prisma = new PrismaClient();

async function main() {
  const adminPassword = await bcrypt.hash("admin12345", 12);
  const userPassword = await bcrypt.hash("user12345", 12);

  const admin = await prisma.user.upsert({
    where: { email: "admin@simagang.bbkb" },
    update: {},
    create: {
      nama: "Administrator BBKB",
      email: "admin@simagang.bbkb",
      password: adminPassword,
      role: "admin",
      isActive: true,
      emailVerifiedAt: new Date(),
    },
  });

  const user = await prisma.user.upsert({
    where: { email: "demo@simagang.bbkb" },
    update: {},
    create: {
      nama: "Demo Pendaftar",
      email: "demo@simagang.bbkb",
      password: userPassword,
      role: "user",
      isActive: true,
      emailVerifiedAt: new Date(),
      universitas: "Universitas Demo",
      program: "Teknik Informatika",
      nim: "123456789",
    },
  });

  await prisma.kuotaMagang.upsert({
    where: {
      periode_posisi: {
        periode: "Semester Genap 2026",
        posisi: "Desain Batik",
      },
    },
    update: {},
    create: {
      periode: "Semester Genap 2026",
      posisi: "Desain Batik",
      deskripsi:
        "Magang desain motif batik dan pengembangan produk kreatif di BBKB Yogyakarta.",
      kuotaMax: 5,
    },
  });

  await prisma.kuotaMagang.upsert({
    where: {
      periode_posisi: {
        periode: "Semester Genap 2026",
        posisi: "Standardisasi Produk",
      },
    },
    update: {},
    create: {
      periode: "Semester Genap 2026",
      posisi: "Standardisasi Produk",
      deskripsi: "Magang di bidang standardisasi dan quality assurance produk kerajinan.",
      kuotaMax: 3,
    },
  });

  await prisma.jadwalMagang.upsert({
    where: {
      periode_posisi: {
        periode: "Semester Genap 2026",
        posisi: "Desain Batik",
      },
    },
    update: {},
    create: {
      periode: "Semester Genap 2026",
      posisi: "Desain Batik",
      tglMulai: new Date("2026-02-01"),
      tglSelesai: new Date("2026-07-31"),
    },
  });

  await prisma.galeriMagang.createMany({
    data: [
      {
        judul: "Kegiatan Magang BBKB",
        deskripsi: "Peserta magang belajar proses batik tulis.",
        foto: "/images/galeri-1.jpg",
        urutan: 1,
      },
      {
        judul: "Workshop Kerajinan",
        deskripsi: "Workshop pengembangan produk kreatif.",
        foto: "/images/galeri-2.jpg",
        urutan: 2,
      },
    ],
  });

  console.log("Seed selesai.");
  console.log("Admin:", admin.email, "/ admin12345");
  console.log("User demo:", user.email, "/ user12345");
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
