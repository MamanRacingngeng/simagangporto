import { requireUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { DashboardLayout, UserShell } from "@/components/layout/user-sidebar";
import { redirect } from "next/navigation";
import { AjukanForm } from "./ajukan-form";

export const dynamic = "force-dynamic";

export default async function LowonganPage() {
  const session = await requireUser();
  if (!session) redirect("/login");

  const userId = Number(session.user.id);
  const [kuota, dokumen, permohonanAktif, jadwal] = await Promise.all([
    prisma.kuotaMagang.findMany({ orderBy: { createdAt: "desc" } }),
    prisma.dokumen.findUnique({ where: { userId } }),
    prisma.permohonanMagang.findFirst({
      where: { userId, status: { notIn: ["Diterima", "Ditolak"] } },
    }),
    prisma.jadwalMagang.findMany(),
  ]);

  return (
    <DashboardLayout>
      <UserShell title="Lowongan Magang" subtitle="Pilih posisi magang yang tersedia.">
        {!dokumen?.cv && (
          <div
            className="status-card"
            style={{ background: "#fffbeb", borderLeft: "4px solid #fbbf24", marginBottom: 20 }}
          >
            <p style={{ fontSize: 14, color: "#92400e" }}>
              Lengkapi dokumen di halaman{" "}
              <a href="/lamaran" style={{ fontWeight: 600, color: "#b45309" }}>
                Lamaran
              </a>{" "}
              sebelum mengajukan.
            </p>
          </div>
        )}

        <div style={{ display: "grid", gap: 20, gridTemplateColumns: "repeat(auto-fill, minmax(300px, 1fr))" }}>
          {kuota.map((item) => {
            const schedule = jadwal.find(
              (j) => j.periode === item.periode && j.posisi === item.posisi,
            );
            const tersedia = item.kuotaTerpakai < item.kuotaMax;
            return (
              <div key={item.id} className="job-card rounded-xl bg-white p-6 shadow">
                <p style={{ fontSize: 13, color: "#d97706", fontWeight: 600 }}>{item.periode}</p>
                <h2 style={{ fontSize: 20, fontWeight: 700, marginTop: 8 }}>{item.posisi}</h2>
                <p style={{ marginTop: 12, fontSize: 14, color: "#6B7280" }}>
                  {item.deskripsi ?? "Program magang BBKB Yogyakarta."}
                </p>
                <p style={{ marginTop: 12, fontSize: 13 }}>
                  Kuota: {item.kuotaTerpakai}/{item.kuotaMax}
                </p>
                {schedule && (
                  <p style={{ marginTop: 8, fontSize: 12, color: "#9CA3AF" }}>
                    {schedule.tglMulai.toLocaleDateString("id-ID")} –{" "}
                    {schedule.tglSelesai.toLocaleDateString("id-ID")}
                  </p>
                )}
                <AjukanForm
                  kuotaId={item.id}
                  disabled={!tersedia || !!permohonanAktif || !dokumen?.cv}
                />
              </div>
            );
          })}
        </div>
      </UserShell>
    </DashboardLayout>
  );
}
