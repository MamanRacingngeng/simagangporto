"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { signIn } from "next-auth/react";
import { useState } from "react";

export function LoginForm() {
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
      role: "user",
      redirect: false,
    });

    setLoading(false);
    if (result?.error) {
      setError("Email atau password salah, atau akun belum aktif.");
      return;
    }
    router.push("/dashboard");
    router.refresh();
  }

  return (
    <div className="auth-page">
      <div className="auth-card">
        <div className="mb-6 text-center">
          <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-yellow-500 text-2xl font-bold text-gray-900">
            BBKB
          </div>
          <h1 className="text-2xl font-bold text-gray-900">Masuk Pendaftar</h1>
          <p className="mt-2 text-sm text-gray-600">Portal Magang Digital BBKB</p>
        </div>

        {error && (
          <p className="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{error}</p>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="mb-1 block text-sm font-medium text-gray-700">Email</label>
            <input name="email" type="email" required className="auth-input" />
          </div>
          <div>
            <label className="mb-1 block text-sm font-medium text-gray-700">Password</label>
            <input name="password" type="password" required className="auth-input" />
          </div>
          <button type="submit" disabled={loading} className="btn-gold">
            {loading ? "Memproses..." : "Masuk"}
          </button>
        </form>

        <button
          type="button"
          onClick={() => signIn("google", { callbackUrl: "/dashboard" })}
          className="mt-4 w-full rounded-xl border border-gray-200 py-3 text-sm font-medium hover:bg-gray-50"
        >
          Masuk dengan Google
        </button>

        <p className="mt-6 text-center text-sm text-gray-600">
          Belum punya akun?{" "}
          <Link href="/register" className="font-semibold text-amber-600 hover:underline">
            Daftar
          </Link>
        </p>
        <p className="mt-2 text-center text-sm">
          <Link href="/admin/login" className="text-gray-500 hover:underline">
            Login Admin
          </Link>
        </p>
      </div>
    </div>
  );
}
