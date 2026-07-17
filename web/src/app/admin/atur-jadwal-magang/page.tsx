import { requireAdmin } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { AdminLayout, AdminShell } from "@/components/layout/admin-sidebar";
import { formatDate } from "@/lib/utils";
import { redirect } from "next/navigation";
import { JadwalForm } from "./jadwal-form";

export const dynamic = "force-dynamic";

export default async function AturJadwalPage() {
  const session = await requireAdmin();
  if (!session) redirect("/admin/login");

  const jadwal = await prisma.jadwalMagang.findMany({
    orderBy: { tglMulai: "desc" },
  });

  return (
    <AdminLayout>
      <AdminShell title="Atur Jadwal Magang" subtitle="Kelola jadwal mulai dan selesai magang.">
        <div style={{ display: "grid", gap: 32, gridTemplateColumns: "repeat(auto-fit, minmax(300px, 1fr))" }}>
          <div className="status-card">
            <h2 style={{ fontSize: 16, fontWeight: 600, marginBottom: 8 }}>Tambah Jadwal</h2>
            <JadwalForm />
          </div>
          <div style={{ display: "flex", flexDirection: "column", gap: 16 }}>
            {jadwal.map((item) => (
              <div key={item.id} className="status-card">
                <h3 style={{ fontWeight: 600 }}>{item.posisi}</h3>
                <p style={{ fontSize: 14, color: "#6b7280" }}>{item.periode}</p>
                <p style={{ marginTop: 8, fontSize: 14 }}>
                  {formatDate(item.tglMulai)} – {formatDate(item.tglSelesai)}
                </p>
              </div>
            ))}
          </div>
        </div>
      </AdminShell>
    </AdminLayout>
  );
}
