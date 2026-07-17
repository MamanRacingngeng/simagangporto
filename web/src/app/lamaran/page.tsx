import { demoDokumen, demoPermohonan, PORTFOLIO_NOTICE } from "@/lib/demo-data";
import { DashboardLayout, UserShell } from "@/components/layout/user-sidebar";
import { formatDate } from "@/lib/utils";
import { DokumenForm } from "./dokumen-form";

export default function LamaranPage() {
  const permohonan = demoPermohonan;
  const dokumen = demoDokumen;

  return (
    <DashboardLayout>
      <UserShell title="Lamaran Saya" subtitle="Kelola dokumen dan pantau status permohonan.">
        <p className="mb-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-900">{PORTFOLIO_NOTICE}</p>

        <div className="status-card" style={{ marginBottom: 20 }}>
          <h2 style={{ fontSize: 18, fontWeight: 600, marginBottom: 16 }}>Dokumen Persyaratan</h2>
          <DokumenForm initial={dokumen} />
        </div>

        <div className="status-card">
          <h2 style={{ fontSize: 18, fontWeight: 600, marginBottom: 16 }}>Daftar Lamaran</h2>
          {permohonan.map((item) => (
            <div
              key={item.id}
              style={{
                padding: "16px 0",
                borderBottom: "1px solid #f3f4f6",
              }}
            >
              <div style={{ display: "flex", justifyContent: "space-between", gap: 12 }}>
                <div>
                  <p style={{ fontWeight: 600 }}>{item.posisi}</p>
                  <p style={{ fontSize: 13, color: "#6B7280" }}>
                    {formatDate(item.tanggalPengajuan)}
                  </p>
                </div>
                <span
                  style={{
                    background: "#fef3c7",
                    color: "#92400e",
                    padding: "4px 12px",
                    borderRadius: 999,
                    fontSize: 12,
                    fontWeight: 600,
                    height: "fit-content",
                  }}
                >
                  {item.status}
                </span>
              </div>
            </div>
          ))}
        </div>
      </UserShell>
    </DashboardLayout>
  );
}
