"use client";

import { useRouter } from "next/navigation";
import { useState } from "react";

export function KuotaForm() {
  const router = useRouter();
  const [loading, setLoading] = useState(false);

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setLoading(true);
    const form = new FormData(e.currentTarget);
    await fetch("/api/admin/kuota", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        periode: form.get("periode"),
        posisi: form.get("posisi"),
        deskripsi: form.get("deskripsi"),
        kuotaMax: Number(form.get("kuotaMax")),
      }),
    });
    setLoading(false);
    router.refresh();
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
