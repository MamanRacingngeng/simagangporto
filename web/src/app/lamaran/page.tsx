import { requireUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { DashboardLayout, UserShell } from "@/components/layout/user-sidebar";
import { formatDate } from "@/lib/utils";
import { redirect } from "next/navigation";
import { DokumenForm } from "./dokumen-form";

export const dynamic = "force-dynamic";

export default async function LamaranPage() {
  const session = await requireUser();
  if (!session) redirect("/login");

  const userId = Number(session.user.id);
  const [permohonan, dokumen] = await Promise.all([
    prisma.permohonanMagang.findMany({
      where: { userId },
      orderBy: { createdAt: "desc" },
      include: { kuota: { include: { kuota: true } } },
    }),
    prisma.dokumen.findUnique({ where: { userId } }),
  ]);

  return (
    <DashboardLayout>
      <UserShell title="Lamaran Saya" subtitle="Kelola dokumen dan pantau status permohonan.">
        <div className="status-card" style={{ marginBottom: 20 }}>
          <h2 style={{ fontSize: 18, fontWeight: 600, marginBottom: 16 }}>Dokumen Persyaratan</h2>
          <DokumenForm
            initial={{
              cv: dokumen?.cv ?? "",
              suratPengantar: dokumen?.suratPengantar ?? "",
              proposal: dokumen?.proposal ?? "",
            }}
          />
        </div>

        <div className="status-card">
          <h2 style={{ fontSize: 18, fontWeight: 600, marginBottom: 16 }}>Daftar Lamaran</h2>
          {permohonan.length === 0 ? (
            <p style={{ color: "#6B7280" }}>Belum ada lamaran.</p>
          ) : (
            permohonan.map((item) => (
              <div
                key={item.id}
                style={{
                  padding: "16px 0",
                  borderBottom: "1px solid #f3f4f6",
                }}
              >
                <div style={{ display: "flex", justifyContent: "space-between", gap: 12 }}>
                  <div>
                    <p style={{ fontWeight: 600 }}>
                      {item.kuota[0]?.kuota.posisi ?? item.posisiBackup}
                    </p>
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
            ))
          )}
        </div>
      </UserShell>
    </DashboardLayout>
  );
}
