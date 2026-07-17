import { requireUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import {
  DashboardLayout,
  UserShell,
} from "@/components/layout/user-sidebar";
import { formatDate } from "@/lib/utils";
import Link from "next/link";
import { redirect } from "next/navigation";

export const dynamic = "force-dynamic";

export default async function DashboardPage() {
  const session = await requireUser();
  if (!session) redirect("/login");

  const userId = Number(session.user.id);
  const [user, permohonan, notifikasi] = await Promise.all([
    prisma.user.findUnique({ where: { id: userId } }),
    prisma.permohonanMagang.findMany({
      where: { userId },
      orderBy: { createdAt: "desc" },
      take: 3,
      include: { kuota: { include: { kuota: true } } },
    }),
    prisma.notifikasi.findMany({
      where: { userId, dibaca: false },
      orderBy: { createdAt: "desc" },
      take: 5,
    }),
  ]);

  return (
    <DashboardLayout>
      <UserShell
        title={`Selamat datang, ${user?.nama ?? session.user.nama}!`}
        subtitle="Pantau lamaran dan notifikasi magang Anda."
      >
        <div className="grid gap-4 sm:grid-cols-3" style={{ marginBottom: 24 }}>
          <div className="status-card" style={{ marginBottom: 0, padding: 24 }}>
            <div>
              <div className="status-title">Lamaran Aktif</div>
              <div className="status-big">{permohonan.length}</div>
            </div>
          </div>
          <div className="status-card" style={{ marginBottom: 0, padding: 24 }}>
            <div>
              <div className="status-title">Notifikasi Baru</div>
              <div className="status-big">{notifikasi.length}</div>
            </div>
          </div>
          <div className="status-card" style={{ marginBottom: 0, padding: 24 }}>
            <div>
              <div className="status-title">Profil</div>
              <div className="status-big" style={{ fontSize: 18 }}>
                {user?.universitas ? "Lengkap" : "Perlu diisi"}
              </div>
            </div>
          </div>
        </div>

        <div className="status-card">
          <div style={{ width: "100%" }}>
            <div style={{ display: "flex", justifyContent: "space-between", marginBottom: 16 }}>
              <h2 style={{ fontSize: 18, fontWeight: 600 }}>Lamaran Terbaru</h2>
              <Link href="/lamaran" style={{ color: "#2563eb", fontSize: 14 }}>
                Lihat semua →
              </Link>
            </div>
            {permohonan.length === 0 ? (
              <p style={{ color: "#6B7280" }}>
                Belum ada lamaran.{" "}
                <Link href="/lowongan" style={{ color: "#2563eb" }}>
                  Ajukan magang
                </Link>
              </p>
            ) : (
              permohonan.map((item) => (
                <div
                  key={item.id}
                  style={{
                    display: "flex",
                    justifyContent: "space-between",
                    padding: "12px 0",
                    borderBottom: "1px solid #f3f4f6",
                  }}
                >
                  <div>
                    <p style={{ fontWeight: 600 }}>
                      {item.kuota[0]?.kuota.posisi ?? item.posisiBackup ?? "Magang"}
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
                    }}
                  >
                    {item.status}
                  </span>
                </div>
              ))
            )}
          </div>
        </div>
      </UserShell>
    </DashboardLayout>
  );
}
