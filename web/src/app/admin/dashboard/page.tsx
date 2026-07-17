import {
  demoAdminMetrics,
  demoAdminPermohonan,
  PORTFOLIO_NOTICE,
} from "@/lib/demo-data";
import { AdminLayout, AdminShell } from "@/components/layout/admin-sidebar";

export default function AdminDashboardPage() {
  const { totalUser, totalPermohonan, diterima, kuota } = demoAdminMetrics;
  const recent = demoAdminPermohonan;

  const metrics = [
    { label: "Total Pendaftar", value: totalUser },
    { label: "Total Lamaran", value: totalPermohonan },
    { label: "Diterima", value: diterima },
    { label: "Kuota Aktif", value: kuota },
  ];

  return (
    <AdminLayout>
      <AdminShell title="Dashboard Admin" subtitle="Ringkasan program magang BBKB.">
        <p className="mb-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-900">{PORTFOLIO_NOTICE}</p>

        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4" style={{ marginBottom: 32 }}>
          {metrics.map((m) => (
            <div key={m.label} className="admin-card">
              <p style={{ fontSize: 14, color: "#6b7280", marginBottom: 8 }}>{m.label}</p>
              <p style={{ fontSize: 36, fontWeight: 800, color: "#0c3a6b", lineHeight: 1 }}>{m.value}</p>
            </div>
          ))}
        </div>

        <div className="admin-card">
          <h2 style={{ fontSize: 18, fontWeight: 600, marginBottom: 16 }}>Lamaran Terbaru</h2>
          <div style={{ overflowX: "auto" }}>
            <table style={{ width: "100%", fontSize: 14, borderCollapse: "collapse" }}>
              <thead>
                <tr style={{ borderBottom: "1px solid #e5e7eb", textAlign: "left" }}>
                  <th style={{ padding: "12px 16px 12px 0", color: "#6b7280", fontWeight: 600 }}>Nama</th>
                  <th style={{ padding: "12px 16px 12px 0", color: "#6b7280", fontWeight: 600 }}>Posisi</th>
                  <th style={{ padding: "12px 0", color: "#6b7280", fontWeight: 600 }}>Status</th>
                </tr>
              </thead>
              <tbody>
                {recent.map((item) => (
                  <tr key={item.id} style={{ borderBottom: "1px solid #f3f4f6" }}>
                    <td style={{ padding: "14px 16px 14px 0", fontWeight: 500 }}>{item.user.nama}</td>
                    <td style={{ padding: "14px 16px 14px 0", color: "#6b7280" }}>{item.posisi}</td>
                    <td style={{ padding: "14px 0" }}>
                      <span
                        style={{
                          background: "#fef3c7",
                          color: "#92400e",
                          padding: "4px 12px",
                          borderRadius: 999,
                          fontSize: 12,
                          fontWeight: 600,
                        }}
                      >
                        {item.status}
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </AdminShell>
    </AdminLayout>
  );
}
