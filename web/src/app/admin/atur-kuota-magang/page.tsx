import { demoKuota, PORTFOLIO_NOTICE } from "@/lib/demo-data";
import { AdminLayout, AdminShell } from "@/components/layout/admin-sidebar";
import { KuotaForm } from "./kuota-form";

export default function AturKuotaPage() {
  const kuota = demoKuota;

  return (
    <AdminLayout>
      <AdminShell title="Atur Kuota Magang" subtitle="Kelola kuota posisi magang per periode.">
        <p className="mb-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-900">{PORTFOLIO_NOTICE}</p>

        <div style={{ display: "grid", gap: 32, gridTemplateColumns: "repeat(auto-fit, minmax(300px, 1fr))" }}>
          <div className="status-card">
            <h2 style={{ fontSize: 16, fontWeight: 600, marginBottom: 8 }}>Tambah Kuota</h2>
            <KuotaForm />
          </div>
          <div style={{ display: "flex", flexDirection: "column", gap: 16 }}>
            {kuota.map((item) => (
              <div key={item.id} className="status-card">
                <h3 style={{ fontWeight: 600 }}>{item.posisi}</h3>
                <p style={{ fontSize: 14, color: "#6b7280" }}>{item.periode}</p>
                <p style={{ marginTop: 8, fontSize: 14 }}>
                  Kuota: {item.kuotaTerpakai}/{item.kuotaMax}
                </p>
              </div>
            ))}
          </div>
        </div>
      </AdminShell>
    </AdminLayout>
  );
}
