"use client";

import { useRouter } from "next/navigation";
import { useState } from "react";

export function JadwalForm() {
  const router = useRouter();
  const [loading, setLoading] = useState(false);

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setLoading(true);
    const form = new FormData(e.currentTarget);
    await fetch("/api/admin/jadwal", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        periode: form.get("periode"),
        posisi: form.get("posisi"),
        tglMulai: form.get("tglMulai"),
        tglSelesai: form.get("tglSelesai"),
      }),
    });
    setLoading(false);
    router.refresh();
    e.currentTarget.reset();
  }

  return (
    <form onSubmit={handleSubmit} className="mt-4 space-y-3">
      <input name="periode" required placeholder="Periode" className="auth-input" />
      <input name="posisi" required placeholder="Posisi" className="auth-input" />
      <input name="tglMulai" required type="date" className="auth-input" />
      <input name="tglSelesai" required type="date" className="auth-input" />
      <button type="submit" disabled={loading} className="btn-primary" style={{ width: "auto", marginTop: 8 }}>
        {loading ? "Menyimpan..." : "Simpan Jadwal"}
      </button>
    </form>
  );
}
