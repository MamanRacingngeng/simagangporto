# SIMAGANG — Next.js (Vercel Ready)

Versi Next.js portal magang BBKB. Tampilan mengikuti Laravel asli (Tailwind CDN + CSS `public/css/`).

## Fitur

- Landing page, tentang kami, galeri
- Registrasi & login (user + admin)
- Dashboard pendaftar: lowongan, lamaran, profil, riwayat
- Panel admin: dashboard, data pendaftar, kuota, jadwal, galeri
- Database via Prisma (SQLite lokal / PostgreSQL production)

## Quick Start (Lokal)

```bash
cd web
cp .env.example .env
# isi DATABASE_URL (Neon PostgreSQL gratis) dan AUTH_SECRET

npm install
npx prisma db push
npm run db:seed
npm run dev
```

> **Catatan:** Production Vercel memakai **PostgreSQL** (Neon/Vercel Postgres). Untuk lokal, buat database gratis di [Neon](https://neon.tech) dan paste connection string ke `DATABASE_URL`.

Buka http://localhost:3000

## Deploy Vercel

Lihat [DEPLOY.md](./DEPLOY.md) — **Root Directory = `web`**

## Laravel (Arsip)

Versi Laravel asli ada di [`../archive/laravel/`](../archive/laravel/). Lihat `README-RESTORE.md` untuk memulihkan.

## Akun Demo (setelah seed)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@simagang.bbkb | admin12345 |
| User | demo@simagang.bbkb | user12345 |
