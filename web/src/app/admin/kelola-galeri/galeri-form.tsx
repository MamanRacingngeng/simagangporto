"use client";

import { useState } from "react";
import { PORTFOLIO_NOTICE } from "@/lib/demo-data";

export function GaleriForm() {
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
      <input name="judul" required placeholder="Judul" className="auth-input" />
      <textarea name="deskripsi" placeholder="Deskripsi" className="auth-input" rows={3} />
      <input name="foto" placeholder="URL foto" className="auth-input" />
      <input name="urutan" type="number" placeholder="Urutan" className="auth-input" />
      {message && <p className="text-sm text-amber-800">{message}</p>}
      <button type="submit" disabled={loading} className="btn-primary" style={{ width: "auto", marginTop: 8 }}>
        {loading ? "Menyimpan..." : "Simpan Galeri"}
      </button>
    </form>
  );
}
