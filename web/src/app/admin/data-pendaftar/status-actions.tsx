"use client";

import { useState } from "react";
import { PORTFOLIO_NOTICE } from "@/lib/demo-data";

export function StatusActions({ id, status }: { id: number; status: string }) {
  const [loading, setLoading] = useState(false);

  async function demoAction(label: string) {
    setLoading(true);
    await new Promise((resolve) => setTimeout(resolve, 300));
    setLoading(false);
    alert(`${label} — ${PORTFOLIO_NOTICE}`);
  }

  return (
    <div className="mt-4 flex flex-wrap gap-2">
      {status === "Diajukan" && (
        <button
          type="button"
          disabled={loading}
          onClick={() => demoAction("Verifikasi")}
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
            onClick={() => demoAction("Terima")}
            className="rounded-lg bg-green-600 px-3 py-1.5 text-xs font-semibold text-white"
          >
            Terima
          </button>
          <button
            type="button"
            disabled={loading}
            onClick={() => demoAction("Tolak")}
            className="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white"
          >
            Tolak
          </button>
          <button
            type="button"
            disabled={loading}
            onClick={() => demoAction("Minta Revisi")}
            className="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white"
          >
            Minta Revisi
          </button>
        </>
      )}
    </div>
  );
}
