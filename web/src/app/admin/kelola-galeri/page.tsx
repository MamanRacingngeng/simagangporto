import { demoGaleri, PORTFOLIO_NOTICE } from "@/lib/demo-data";
import { AdminLayout, AdminShell } from "@/components/layout/admin-sidebar";
import { GaleriForm } from "./galeri-form";

export default function KelolaGaleriPage() {
  const galeri = demoGaleri;

  return (
    <AdminLayout>
      <AdminShell title="Kelola Galeri" subtitle="Tambah dan kelola foto galeri magang.">
        <p className="mb-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-900">{PORTFOLIO_NOTICE}</p>

        <div style={{ display: "grid", gap: 32, gridTemplateColumns: "repeat(auto-fit, minmax(300px, 1fr))" }}>
          <div className="status-card">
            <h2 style={{ fontSize: 16, fontWeight: 600, marginBottom: 8 }}>Tambah Galeri</h2>
            <GaleriForm />
          </div>
          <div style={{ display: "flex", flexDirection: "column", gap: 16 }}>
            {galeri.map((item) => (
              <div key={item.id} className="status-card">
                <h3 style={{ fontWeight: 600 }}>{item.judul}</h3>
                <p style={{ fontSize: 14, color: "#6b7280" }}>{item.deskripsi}</p>
              </div>
            ))}
          </div>
        </div>
      </AdminShell>
    </AdminLayout>
  );
}
