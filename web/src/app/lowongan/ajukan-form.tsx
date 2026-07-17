"use client";

import { useRouter } from "next/navigation";
import { useState } from "react";
import { PORTFOLIO_NOTICE } from "@/lib/demo-data";

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
    await new Promise((resolve) => setTimeout(resolve, 400));
    setLoading(false);
    alert(PORTFOLIO_NOTICE);
    router.push("/lamaran");
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
