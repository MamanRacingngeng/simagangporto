import { requireAdmin } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { AdminLayout, AdminShell } from "@/components/layout/admin-sidebar";
import { redirect } from "next/navigation";
import { GaleriForm } from "./galeri-form";

export const dynamic = "force-dynamic";

export default async function KelolaGaleriPage() {
  const session = await requireAdmin();
  if (!session) redirect("/admin/login");

  const galeri = await prisma.galeriMagang.findMany({
    orderBy: { urutan: "asc" },
  });

  return (
    <AdminLayout>
      <AdminShell title="Kelola Galeri" subtitle="Tambah dan kelola foto galeri magang.">
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
