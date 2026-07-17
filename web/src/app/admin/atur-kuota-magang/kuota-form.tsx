"use client";

import { useState } from "react";
import { PORTFOLIO_NOTICE } from "@/lib/demo-data";

export function KuotaForm() {
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
      {[
        ["periode", "Periode", "Semester Genap 2026"],
        ["posisi", "Posisi/Divisi", "Desain Batik"],
        ["deskripsi", "Deskripsi", "Magang desain motif batik"],
        ["kuotaMax", "Kuota Maksimum", "5"],
      ].map(([name, label, placeholder]) => (
        <div key={name}>
          <label className="text-sm font-medium">{label}</label>
          <input
            name={name}
            required
            placeholder={placeholder}
            type={name === "kuotaMax" ? "number" : "text"}
            className="auth-input"
          />
        </div>
      ))}
      {message && <p className="text-sm text-amber-800">{message}</p>}
      <button
        type="submit"
        disabled={loading}
        className="btn-primary"
        style={{ width: "auto", marginTop: 8 }}
      >
        {loading ? "Menyimpan..." : "Simpan Kuota"}
      </button>
    </form>
  );
}
