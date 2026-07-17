"use client";

import { useRouter } from "next/navigation";
import { useState } from "react";

export function StatusActions({ id, status }: { id: number; status: string }) {
  const router = useRouter();
  const [loading, setLoading] = useState(false);

  async function updateStatus(newStatus: string, extra?: Record<string, string>) {
    setLoading(true);
    await fetch(`/api/admin/permohonan/${id}`, {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ status: newStatus, ...extra }),
    });
    setLoading(false);
    router.refresh();
  }

  return (
    <div className="mt-4 flex flex-wrap gap-2">
      {status === "Diajukan" && (
        <button
          type="button"
          disabled={loading}
          onClick={() => updateStatus("Diverifikasi")}
          className="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white"
        >
          Verifikasi
        </button>
      )}
      {["Diverifikasi", "Diajukan"].includes(status) && (
        <>
          <button
            type="button"
            disabled={loading}
            onClick={() => updateStatus("Diterima")}
            className="rounded-lg bg-green-600 px-3 py-1.5 text-xs font-semibold text-white"
          >
            Terima
          </button>
          <button
            type="button"
            disabled={loading}
            onClick={() => {
              const alasan = prompt("Alasan penolakan:");
              if (alasan) updateStatus("Ditolak", { alasanPenolakan: alasan });
            }}
            className="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white"
          >
            Tolak
          </button>
          <button
            type="button"
            disabled={loading}
            onClick={() => {
              const catatan = prompt("Catatan revisi:");
              if (catatan) updateStatus("Revisi", { catatanRevisi: catatan });
            }}
            className="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white"
          >
            Minta Revisi
          </button>
        </>
      )}
    </div>
  );
}
