export const demoUser = {
  id: 1,
  nama: "Ahmad Pratama",
  namaPanggilan: "Ahmad",
  email: "demo@simagang.bbkb",
  ttl: "Yogyakarta, 12 Maret 2002",
  domisili: "Yogyakarta",
  nim: "2021001234",
  semester: 6,
  ipk: 3.65,
  program: "Teknik Industri",
  universitas: "Universitas Gadjah Mada",
  softwareTools: "Adobe Illustrator, CorelDRAW, Figma",
  portofolio: "https://drive.google.com/demo-portfolio",
  kompetensiUtama: "Desain batik, riset material tekstil",
};

export const demoKuota = [
  {
    id: 1,
    periode: "Januari – Juni 2026",
    posisi: "Magang Desain Batik",
    deskripsi: "Pelajari teknik desain batik modern dan tradisional di BBKB Yogyakarta.",
    kuotaMax: 10,
    kuotaTerpakai: 4,
    createdAt: new Date("2026-01-10"),
  },
  {
    id: 2,
    periode: "Januari – Juni 2026",
    posisi: "Magang Pemasaran Digital",
    deskripsi: "Kembangkan skill pemasaran digital untuk produk kerajinan dan batik.",
    kuotaMax: 8,
    kuotaTerpakai: 3,
    createdAt: new Date("2026-01-10"),
  },
  {
    id: 3,
    periode: "Juli – Desember 2026",
    posisi: "Magang Produksi Kerajinan",
    deskripsi: "Terlibat langsung dalam proses produksi dan quality control kerajinan.",
    kuotaMax: 12,
    kuotaTerpakai: 2,
    createdAt: new Date("2026-02-01"),
  },
];

export const demoJadwal = [
  {
    id: 1,
    periode: "Januari – Juni 2026",
    posisi: "Magang Desain Batik",
    tglMulai: new Date("2026-01-15"),
    tglSelesai: new Date("2026-06-30"),
  },
  {
    id: 2,
    periode: "Januari – Juni 2026",
    posisi: "Magang Pemasaran Digital",
    tglMulai: new Date("2026-02-01"),
    tglSelesai: new Date("2026-07-15"),
  },
  {
    id: 3,
    periode: "Juli – Desember 2026",
    posisi: "Magang Produksi Kerajinan",
    tglMulai: new Date("2026-07-01"),
    tglSelesai: new Date("2026-12-20"),
  },
];

export const demoDokumen = {
  cv: "https://drive.google.com/file/demo-cv",
  suratPengantar: "https://drive.google.com/file/demo-surat",
  proposal: "https://drive.google.com/file/demo-proposal",
};

export const demoPermohonan = [
  {
    id: 1,
    status: "Diverifikasi",
    tanggalPengajuan: new Date("2026-02-10"),
    posisiBackup: null as string | null,
    posisi: "Magang Desain Batik",
  },
  {
    id: 2,
    status: "Diajukan",
    tanggalPengajuan: new Date("2026-01-20"),
    posisiBackup: "Magang Pemasaran Digital",
    posisi: "Magang Pemasaran Digital",
  },
];

export const demoNotifikasi = [
  { id: 1, pesan: "Dokumen lamaran Anda sedang diverifikasi.", dibaca: false },
  { id: 2, pesan: "Lengkapi profil universitas untuk melanjutkan.", dibaca: false },
];

export const demoGaleri = [
  {
    id: 1,
    judul: "Workshop Desain Batik",
    deskripsi: "Peserta magang belajar teknik motif batik kontemporer.",
    foto: null as string | null,
    urutan: 1,
    aktif: true,
  },
  {
    id: 2,
    judul: "Studi Lapangan Industri",
    deskripsi: "Kunjungan ke UMKM kerajinan batik di Yogyakarta.",
    foto: null as string | null,
    urutan: 2,
    aktif: true,
  },
  {
    id: 3,
    judul: "Presentasi Hasil Magang",
    deskripsi: "Peserta mempresentasikan proyek akhir magang di BBKB.",
    foto: null as string | null,
    urutan: 3,
    aktif: true,
  },
];

export const demoAdminPermohonan = [
  {
    id: 1,
    status: "Diverifikasi",
    tanggalPengajuan: new Date("2026-02-10"),
    posisiBackup: null as string | null,
    user: {
      nama: "Ahmad Pratama",
      email: "ahmad@email.com",
      universitas: "Universitas Gadjah Mada",
    },
    posisi: "Magang Desain Batik",
  },
  {
    id: 2,
    status: "Diajukan",
    tanggalPengajuan: new Date("2026-02-08"),
    posisiBackup: "Magang Pemasaran Digital",
    user: {
      nama: "Siti Rahmawati",
      email: "siti@email.com",
      universitas: "Universitas Negeri Yogyakarta",
    },
    posisi: "Magang Pemasaran Digital",
  },
  {
    id: 3,
    status: "Diterima",
    tanggalPengajuan: new Date("2026-01-15"),
    posisiBackup: null as string | null,
    user: {
      nama: "Budi Santoso",
      email: "budi@email.com",
      universitas: "Institut Seni Indonesia",
    },
    posisi: "Magang Produksi Kerajinan",
  },
];

export const demoAdminMetrics = {
  totalUser: 128,
  totalPermohonan: 86,
  diterima: 34,
  kuota: 3,
};

export const PORTFOLIO_NOTICE =
  "Mode portfolio — tampilan demo untuk menunjukkan sistem yang dibuat.";
