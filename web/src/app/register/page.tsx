"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { useState } from "react";

export default function RegisterPage() {
  const router = useRouter();
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setLoading(true);
    setError("");

    const form = new FormData(e.currentTarget);
    const res = await fetch("/api/register", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        nama: form.get("nama"),
        email: form.get("email"),
        password: form.get("password"),
      }),
    });

    const data = await res.json();
    setLoading(false);
    if (!res.ok) {
      setError(data.error ?? "Registrasi gagal.");
      return;
    }
    router.push("/login");
  }

  return (
    <div className="auth-page">
      <div className="auth-card">
        <div className="mb-6 text-center">
          <h1 className="text-2xl font-bold text-gray-900">Daftar Akun</h1>
          <p className="mt-2 text-sm text-gray-600">Buat akun pendaftar magang</p>
        </div>

        {error && (
          <p className="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{error}</p>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="mb-1 block text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input name="nama" required className="auth-input" />
          </div>
          <div>
            <label className="mb-1 block text-sm font-medium text-gray-700">Email</label>
            <input name="email" type="email" required className="auth-input" />
          </div>
          <div>
            <label className="mb-1 block text-sm font-medium text-gray-700">Password (min. 8 karakter)</label>
            <input name="password" type="password" minLength={8} required className="auth-input" />
          </div>
          <button type="submit" disabled={loading} className="btn-gold">
            {loading ? "Memproses..." : "Daftar"}
          </button>
        </form>

        <p className="mt-6 text-center text-sm text-gray-600">
          Sudah punya akun?{" "}
          <Link href="/login" className="font-semibold text-amber-600 hover:underline">
            Masuk
          </Link>
        </p>
      </div>
    </div>
  );
}
