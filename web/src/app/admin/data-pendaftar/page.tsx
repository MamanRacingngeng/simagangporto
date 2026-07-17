import { requireAdmin } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { AdminLayout, AdminShell } from "@/components/layout/admin-sidebar";
import { formatDate } from "@/lib/utils";
import { redirect } from "next/navigation";
import { StatusActions } from "./status-actions";

export const dynamic = "force-dynamic";

export default async function DataPendaftarPage() {
  const session = await requireAdmin();
  if (!session) redirect("/admin/login");

  const permohonan = await prisma.permohonanMagang.findMany({
    orderBy: { createdAt: "desc" },
    include: { user: true, dokumen: true, kuota: { include: { kuota: true } } },
  });

  return (
    <AdminLayout>
      <AdminShell
        title="Data Pendaftar"
        subtitle={`${permohonan.length} permohonan magang`}
      >
        <div style={{ display: "flex", flexDirection: "column", gap: 16 }}>
          {permohonan.map((item) => (
            <div key={item.id} className="status-card">
              <div style={{ display: "flex", flexWrap: "wrap", justifyContent: "space-between", gap: 12 }}>
                <div>
                  <h2 style={{ fontSize: 18, fontWeight: 700 }}>{item.user.nama}</h2>
                  <p style={{ fontSize: 14, color: "#6b7280" }}>{item.user.email}</p>
                  <p style={{ marginTop: 8, fontSize: 14, color: "#374151" }}>
                    {item.kuota[0]?.kuota.posisi ?? item.posisiBackup} ·{" "}
                    {formatDate(item.tanggalPengajuan)}
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
