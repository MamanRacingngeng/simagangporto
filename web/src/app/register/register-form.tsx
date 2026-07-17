"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { useState } from "react";
import { PORTFOLIO_NOTICE } from "@/lib/demo-data";

export function RegisterForm() {
  const router = useRouter();
  const [loading, setLoading] = useState(false);

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setLoading(true);
    await new Promise((resolve) => setTimeout(resolve, 300));
    setLoading(false);
    alert(PORTFOLIO_NOTICE);
    router.push("/login");
  }

  return (
    <div className="auth-page">
      <div className="auth-card">
        <div className="mb-6 text-center">
          <h1 className="text-2xl font-bold text-gray-900">Daftar Akun</h1>
          <p className="mt-2 text-sm text-gray-600">Demo portfolio — formulir pendaftaran</p>
        </div>

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
            {loading ? "Memproses..." : "Daftar Demo"}
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
