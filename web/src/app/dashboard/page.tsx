import {
  demoNotifikasi,
  demoPermohonan,
  demoUser,
  PORTFOLIO_NOTICE,
} from "@/lib/demo-data";
import {
  DashboardLayout,
  UserShell,
} from "@/components/layout/user-sidebar";
import { formatDate } from "@/lib/utils";
import Link from "next/link";

export default function DashboardPage() {
  const permohonan = demoPermohonan;
  const notifikasi = demoNotifikasi;

  return (
    <DashboardLayout>
      <UserShell
        title={`Selamat datang, ${demoUser.nama}!`}
        subtitle="Pantau lamaran dan notifikasi magang Anda."
      >
        <p className="mb-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-900">{PORTFOLIO_NOTICE}</p>

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
                Lengkap
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
            {permohonan.map((item) => (
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
                  <p style={{ fontWeight: 600 }}>{item.posisi}</p>
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
            ))}
          </div>
        </div>
      </UserShell>
    </DashboardLayout>
  );
}
