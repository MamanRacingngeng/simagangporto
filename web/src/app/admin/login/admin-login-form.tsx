"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { useState } from "react";

export function AdminLoginForm() {
  const router = useRouter();
  const [loading, setLoading] = useState(false);

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setLoading(true);
    await new Promise((resolve) => setTimeout(resolve, 300));
    setLoading(false);
    router.push("/admin/dashboard");
  }

  return (
    <div className="auth-page">
      <div className="auth-card">
        <div className="mb-6 text-center">
          <h1 className="text-2xl font-bold text-gray-900">Login Admin</h1>
          <p className="mt-2 text-sm text-gray-600">Panel administrasi SIMAGANG — Demo Portfolio</p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="mb-1 block text-sm font-medium text-gray-700">Email Admin</label>
            <input name="email" type="email" defaultValue="admin@simagang.bbkb" className="auth-input" />
          </div>
          <div>
            <label className="mb-1 block text-sm font-medium text-gray-700">Password</label>
            <input name="password" type="password" defaultValue="admin12345" className="auth-input" />
          </div>
          <button type="submit" disabled={loading} className="btn-gold">
            {loading ? "Memproses..." : "Masuk Admin Demo"}
          </button>
        </form>

        <p className="mt-6 text-center text-sm">
          <Link href="/login" className="text-gray-500 hover:underline">
            ← Kembali ke login pendaftar
          </Link>
        </p>
      </div>
    </div>
  );
}
