import { requireUser } from "@/lib/auth";
import { prisma } from "@/lib/prisma";
import { DashboardLayout, UserShell } from "@/components/layout/user-sidebar";
import { redirect } from "next/navigation";
import { ProfilForm } from "./profil-form";

export const dynamic = "force-dynamic";

export default async function ProfilPage() {
  const session = await requireUser();
  if (!session) redirect("/login");

  const user = await prisma.user.findUnique({
    where: { id: Number(session.user.id) },
  });

  if (!user) redirect("/login");

  return (
    <DashboardLayout>
      <UserShell title="Profil Saya" subtitle="Lengkapi data diri untuk lamaran magang.">
        <div className="status-card">
          <ProfilForm user={user} />
        </div>
      </UserShell>
    </DashboardLayout>
  );
}
