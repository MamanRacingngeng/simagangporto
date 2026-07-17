import { demoUser, PORTFOLIO_NOTICE } from "@/lib/demo-data";
import { DashboardLayout, UserShell } from "@/components/layout/user-sidebar";
import { ProfilForm } from "./profil-form";

export default function ProfilPage() {
  return (
    <DashboardLayout>
      <UserShell title="Profil Saya" subtitle="Lengkapi data diri untuk lamaran magang.">
        <p className="mb-4 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-900">{PORTFOLIO_NOTICE}</p>
        <div className="status-card">
          <ProfilForm user={demoUser} />
        </div>
      </UserShell>
    </DashboardLayout>
  );
}
