"use client";

import { useRouter } from "next/navigation";
import { useState } from "react";

export function AjukanForm({
  kuotaId,
  disabled,
}: {
  kuotaId: number;
  disabled?: boolean;
}) {
  const router = useRouter();
  const [loading, setLoading] = useState(false);

  async function handleSubmit() {
    setLoading(true);
    const res = await fetch("/api/permohonan", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ kuotaId }),
    });
    setLoading(false);

    if (res.ok) {
      router.push("/lamaran");
      router.refresh();
    }
  }

  return (
    <button
      type="button"
      disabled={disabled || loading}
      onClick={handleSubmit}
      className="btn-gold"
      style={{ marginTop: 16, width: "auto", padding: "10px 20px", fontSize: 14 }}
    >
      {loading ? "Mengajukan..." : disabled ? "Tidak Tersedia" : "Ajukan Magang"}
    </button>
  );
}
