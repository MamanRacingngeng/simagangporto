import { demoPermohonan, PORTFOLIO_NOTICE } from "@/lib/demo-data";
import { DashboardLayout, UserShell } from "@/components/layout/user-sidebar";
import { formatDate } from "@/lib/utils";

export default function RiwayatLamaranPage() {
  const permohonan = demoPermohonan;

  return (
    <DashboardLayout>
      <UserShell title="Riwayat Lamaran" subtitle="Timeline permohonan magang Anda.">
        <p className="mb-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-900">{PORTFOLIO_NOTICE}</p>

        <div className="status-card">
          {permohonan.map((item, index) => (
            <div
              key={item.id}
              style={{
                display: "flex",
                gap: 16,
                padding: "16px 0",
                borderBottom: index < permohonan.length - 1 ? "1px solid #f3f4f6" : undefined,
              }}
            >
              <div
                style={{
                  width: 36,
                  height: 36,
                  borderRadius: "50%",
                  background: "#2563eb",
                  color: "#fff",
                  display: "flex",
                  alignItems: "center",
                  justifyContent: "center",
                  fontWeight: 700,
                  flexShrink: 0,
                }}
              >
                {index + 1}
              </div>
              <div style={{ flex: 1 }}>
                <div style={{ display: "flex", justifyContent: "space-between", gap: 8 }}>
                  <p style={{ fontWeight: 600 }}>{item.posisi}</p>
                  <span
                    style={{
                      background: "#fef3c7",
                      color: "#92400e",
                      padding: "4px 10px",
                      borderRadius: 999,
                      fontSize: 12,
                      fontWeight: 600,
                    }}
                  >
                    {item.status}
                  </span>
                </div>
                <p style={{ fontSize: 13, color: "#6B7280", marginTop: 4 }}>
                  {formatDate(item.tanggalPengajuan)}
                </p>
              </div>
            </div>
          ))}
        </div>
      </UserShell>
    </DashboardLayout>
  );
}
