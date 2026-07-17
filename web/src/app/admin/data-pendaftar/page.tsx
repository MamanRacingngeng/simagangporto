import { demoAdminPermohonan, PORTFOLIO_NOTICE } from "@/lib/demo-data";
import { AdminLayout, AdminShell } from "@/components/layout/admin-sidebar";
import { formatDate } from "@/lib/utils";
import { StatusActions } from "./status-actions";

export default function DataPendaftarPage() {
  const permohonan = demoAdminPermohonan;

  return (
    <AdminLayout>
      <AdminShell
        title="Data Pendaftar"
        subtitle={`${permohonan.length} permohonan magang`}
      >
        <p className="mb-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-900">{PORTFOLIO_NOTICE}</p>

        <div style={{ display: "flex", flexDirection: "column", gap: 16 }}>
          {permohonan.map((item) => (
            <div key={item.id} className="status-card">
              <div style={{ display: "flex", flexWrap: "wrap", justifyContent: "space-between", gap: 12 }}>
                <div>
                  <h2 style={{ fontSize: 18, fontWeight: 700 }}>{item.user.nama}</h2>
                  <p style={{ fontSize: 14, color: "#6b7280" }}>{item.user.email}</p>
                  <p style={{ marginTop: 8, fontSize: 14, color: "#374151" }}>
                    {item.posisi} · {formatDate(item.tanggalPengajuan)}
                  </p>
                  <p style={{ marginTop: 4, fontSize: 12, color: "#9ca3af" }}>
                    {item.user.universitas ?? "Universitas belum diisi"}
                  </p>
                </div>
                <span
                  style={{
                    background: "#fef3c7",
                    color: "#92400e",
                    padding: "6px 14px",
                    borderRadius: 999,
                    fontSize: 12,
                    fontWeight: 600,
                    height: "fit-content",
                  }}
                >
                  {item.status}
                </span>
              </div>
              <StatusActions id={item.id} status={item.status} />
            </div>
          ))}
        </div>
      </AdminShell>
    </AdminLayout>
  );
}
