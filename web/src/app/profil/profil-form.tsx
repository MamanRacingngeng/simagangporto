"use client";

import { useRouter } from "next/navigation";
import { useState } from "react";

type UserProfile = {
  nama: string;
  namaPanggilan: string | null;
  ttl: string | null;
  domisili: string | null;
  nim: string | null;
  semester: number | null;
  ipk: number | null;
  program: string | null;
  universitas: string | null;
  softwareTools: string | null;
  portofolio: string | null;
  kompetensiUtama: string | null;
};

export function ProfilForm({ user }: { user: UserProfile }) {
  const router = useRouter();
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState("");
  const [form, setForm] = useState({
    nama: user.nama,
    namaPanggilan: user.namaPanggilan ?? "",
    ttl: user.ttl ?? "",
    domisili: user.domisili ?? "",
    nim: user.nim ?? "",
    semester: user.semester?.toString() ?? "",
    ipk: user.ipk?.toString() ?? "",
    program: user.program ?? "",
    universitas: user.universitas ?? "",
    softwareTools: user.softwareTools ?? "",
    portofolio: user.portofolio ?? "",
    kompetensiUtama: user.kompetensiUtama ?? "",
  });

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setLoading(true);
    setMessage("");

    const res = await fetch("/api/profil", {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(form),
    });

    setLoading(false);
    setMessage(res.ok ? "Profil berhasil diperbarui." : "Gagal memperbarui profil.");
    if (res.ok) router.refresh();
  }

  const fields = [
    ["nama", "Nama Lengkap"],
    ["namaPanggilan", "Nama Panggilan"],
    ["ttl", "Tempat, Tanggal Lahir"],
    ["domisili", "Domisili"],
    ["nim", "NIM/NIS"],
    ["semester", "Semester"],
    ["ipk", "IPK"],
    ["program", "Program Studi"],
    ["universitas", "Universitas"],
    ["softwareTools", "Software/Tools"],
    ["portofolio", "Portofolio (URL)"],
    ["kompetensiUtama", "Kompetensi Utama"],
  ] as const;

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div className="grid gap-4 sm:grid-cols-2">
        {fields.map(([key, label]) => (
          <div key={key}>
            <label className="mb-1 block text-sm font-medium text-gray-700">{label}</label>
            <input
              value={form[key]}
              onChange={(e) => setForm({ ...form, [key]: e.target.value })}
              className="auth-input"
            />
          </div>
        ))}
      </div>
      {message && <p className="text-sm text-green-700">{message}</p>}
      <button type="submit" disabled={loading} className="btn-primary">
        {loading ? "Menyimpan..." : "Simpan Profil"}
      </button>
    </form>
  );
}
