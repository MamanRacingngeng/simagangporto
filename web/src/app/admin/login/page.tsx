"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { signIn } from "next-auth/react";
import { useState } from "react";

export default function AdminLoginPage() {
  const router = useRouter();
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setLoading(true);
    setError("");

    const form = new FormData(e.currentTarget);
    const result = await signIn("credentials", {
      email: String(form.get("email")),
      password: String(form.get("password")),
      role: "admin",
      redirect: false,
    });

    setLoading(false);
    if (result?.error) {
      setError("Kredensial admin tidak valid.");
      return;
    }
    router.push("/admin/dashboard");
    router.refresh();
  }

  return (
    <div className="auth-page">
      <div className="auth-card">
        <div className="mb-6 text-center">
          <h1 className="text-2xl font-bold text-gray-900">Login Admin</h1>
          <p className="mt-2 text-sm text-gray-600">Panel administrasi SIMAGANG</p>
        </div>

        {error && (
          <p className="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{error}</p>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="mb-1 block text-sm font-medium text-gray-700">Email Admin</label>
            <input name="email" type="email" required className="auth-input" />
          </div>
          <div>
            <label className="mb-1 block text-sm font-medium text-gray-700">Password</label>
            <input name="password" type="password" required className="auth-input" />
          </div>
          <button type="submit" disabled={loading} className="btn-gold">
            {loading ? "Memproses..." : "Masuk Admin"}
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
