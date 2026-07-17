"use client";

import { useRouter } from "next/navigation";
import { useState } from "react";

type DokumenState = {
  cv: string;
  suratPengantar: string;
  proposal: string;
};

export function DokumenForm({ initial }: { initial: DokumenState }) {
  const router = useRouter();
  const [form, setForm] = useState(initial);
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState("");

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setLoading(true);
    setMessage("");

    const res = await fetch("/api/dokumen", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(form),
    });

    setLoading(false);
    setMessage(res.ok ? "Dokumen berhasil disimpan." : "Gagal menyimpan dokumen.");
    if (res.ok) router.refresh();
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      {(
        [
          ["cv", "CV (URL)"],
          ["suratPengantar", "Surat Pengantar (URL)"],
          ["proposal", "Proposal (URL)"],
        ] as const
      ).map(([key, label]) => (
        <div key={key}>
          <label className="mb-1 block text-sm font-medium text-gray-700">{label}</label>
          <input
            value={form[key]}
            onChange={(e) => setForm({ ...form, [key]: e.target.value })}
            placeholder="https://..."
            className="auth-input"
          />
        </div>
      ))}
      <p className="text-xs text-gray-500">Masukkan link Google Drive atau URL file dokumen.</p>
      {message && <p className="text-sm text-green-700">{message}</p>}
      <button type="submit" disabled={loading} className="btn-primary">
        {loading ? "Menyimpan..." : "Simpan Dokumen"}
      </button>
    </form>
  );
}
