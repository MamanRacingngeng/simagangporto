import {
  demoDokumen,
  demoJadwal,
  demoKuota,
  demoPermohonan,
  PORTFOLIO_NOTICE,
} from "@/lib/demo-data";
import { DashboardLayout, UserShell } from "@/components/layout/user-sidebar";
import { AjukanForm } from "./ajukan-form";

export default function LowonganPage() {
  const kuota = demoKuota;
  const dokumen = demoDokumen;
  const permohonanAktif = demoPermohonan.find((p) => !["Diterima", "Ditolak"].includes(p.status));
  const jadwal = demoJadwal;

  return (
    <DashboardLayout>
      <UserShell title="Lowongan Magang" subtitle="Pilih posisi magang yang tersedia.">
        <p className="mb-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-900">{PORTFOLIO_NOTICE}</p>

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
