# Deploy SIMAGANG Next.js ke Vercel

## 1. Database (gratis)

Buat database PostgreSQL di salah satu:
- [Neon](https://neon.tech) (recommended)
- Vercel → Storage → Postgres
- Supabase

Salin connection string ke `DATABASE_URL`.

## 2. Environment Variables di Vercel

| Variable | Wajib | Keterangan |
|----------|-------|------------|
| `DATABASE_URL` | Ya | PostgreSQL connection string |
| `AUTH_SECRET` | Ya | Random string (`openssl rand -base64 32`) |
| `AUTH_URL` | Ya | URL production, mis. `https://simagang.vercel.app` |
| `GOOGLE_CLIENT_ID` | Tidak | Google OAuth |
| `GOOGLE_CLIENT_SECRET` | Tidak | Google OAuth |

## 3. Root Directory

Di Vercel Project Settings → **Root Directory**: `web`

## 4. Migrasi & Seed (sekali)

```bash
cd web
cp .env.example .env
# isi DATABASE_URL dan AUTH_SECRET

npx prisma migrate dev --name init
npx prisma db seed
```

## 5. Deploy

Push ke GitHub, connect repo di Vercel, set Root Directory = `web`, deploy.

## Akun Demo (setelah seed)

- **Admin:** admin@simagang.bbkb / admin12345
- **User:** demo@simagang.bbkb / user12345

## Catatan

- Laravel diarsipkan di `../archive/laravel/` — **tidak** dipakai untuk deploy Vercel.
- UI Next.js memakai asset & CSS yang sama dengan Laravel (`public/css/`, `public/images/`).
- Upload dokumen saat ini memakai **URL file** (Google Drive, dll). Untuk upload file penuh, tambahkan Vercel Blob nanti.
