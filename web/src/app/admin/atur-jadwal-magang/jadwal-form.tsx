"use client";

import { useState } from "react";
import { PORTFOLIO_NOTICE } from "@/lib/demo-data";

export function JadwalForm() {
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState("");

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setLoading(true);
    await new Promise((resolve) => setTimeout(resolve, 400));
    setLoading(false);
    setMessage(PORTFOLIO_NOTICE);
    e.currentTarget.reset();
  }

  return (
    <form onSubmit={handleSubmit} className="mt-4 space-y-3">
      <input name="periode" required placeholder="Periode" className="auth-input" />
      <input name="posisi" required placeholder="Posisi" className="auth-input" />
      <input name="tglMulai" required type="date" className="auth-input" />
      <input name="tglSelesai" required type="date" className="auth-input" />
      {message && <p className="text-sm text-amber-800">{message}</p>}
      <button type="submit" disabled={loading} className="btn-primary" style={{ width: "auto", marginTop: 8 }}>
        {loading ? "Menyimpan..." : "Simpan Jadwal"}
      </button>
    </form>
  );
}
