"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { useState } from "react";

export function LoginForm() {
  const router = useRouter();
  const [loading, setLoading] = useState(false);

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setLoading(true);
    await new Promise((resolve) => setTimeout(resolve, 300));
    setLoading(false);
    router.push("/dashboard");
  }

  return (
    <div className="auth-page">
      <div className="auth-card">
        <div className="mb-6 text-center">
          <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 text-2xl font-bold text-gray-900">
            BBKB
          </div>
          <h1 className="text-2xl font-bold text-gray-900">Masuk Pendaftar</h1>
          <p className="mt-2 text-sm text-gray-600">Portal Magang Digital BBKB — Demo Portfolio</p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="mb-1 block text-sm font-medium text-gray-700">Email</label>
            <input name="email" type="email" defaultValue="demo@simagang.bbkb" className="auth-input" />
          </div>
          <div>
            <label className="mb-1 block text-sm font-medium text-gray-700">Password</label>
            <input name="password" type="password" defaultValue="demo12345" className="auth-input" />
          </div>
          <button type="submit" disabled={loading} className="btn-gold">
            {loading ? "Memproses..." : "Masuk Demo"}
          </button>
        </form>

        <p className="mt-6 text-center text-sm text-gray-600">
          Belum punya akun?{" "}
          <Link href="/register" className="font-semibold text-amber-600 hover:underline">
            Daftar
          </Link>
        </p>
        <p className="mt-2 text-center text-sm">
          <Link href="/admin/login" className="text-gray-500 hover:underline">
            Login Admin Demo
          </Link>
        </p>
      </div>
    </div>
  );
}
