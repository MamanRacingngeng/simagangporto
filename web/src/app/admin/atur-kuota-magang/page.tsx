import { requireAdmin } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { AdminLayout, AdminShell } from "@/components/layout/admin-sidebar";
import { redirect } from "next/navigation";
import { KuotaForm } from "./kuota-form";

export const dynamic = "force-dynamic";

export default async function AturKuotaPage() {
  const session = await requireAdmin();
  if (!session) redirect("/admin/login");

  const kuota = await prisma.kuotaMagang.findMany({
    orderBy: { createdAt: "desc" },
  });

  return (
    <AdminLayout>
      <AdminShell title="Atur Kuota Magang" subtitle="Kelola kuota posisi magang per periode.">
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
