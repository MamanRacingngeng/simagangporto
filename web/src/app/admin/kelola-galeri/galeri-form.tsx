"use client";

import { useRouter } from "next/navigation";
import { useState } from "react";

export function GaleriForm() {
  const router = useRouter();
  const [loading, setLoading] = useState(false);

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setLoading(true);
    const form = new FormData(e.currentTarget);
    await fetch("/api/admin/galeri", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        judul: form.get("judul"),
        deskripsi: form.get("deskripsi"),
        foto: form.get("foto"),
        urutan: Number(form.get("urutan") || 0),
      }),
    });
    setLoading(false);
    router.refresh();
    e.currentTarget.reset();
  }

  return (
    <form onSubmit={handleSubmit} className="mt-4 space-y-3">
      <input name="judul" required placeholder="Judul" className="auth-input" />
      <textarea name="deskripsi" placeholder="Deskripsi" className="auth-input" rows={3} />
      <input name="foto" placeholder="URL foto" className="auth-input" />
      <input name="urutan" type="number" placeholder="Urutan" className="auth-input" />
      <button type="submit" disabled={loading} className="btn-primary" style={{ width: "auto", marginTop: 8 }}>
        {loading ? "Menyimpan..." : "Simpan Galeri"}
      </button>
    </form>
  );
}
