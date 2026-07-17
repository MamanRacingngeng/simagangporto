import { NextResponse } from "next/server";
import { auth } from "@/lib/auth";
import { prisma } from "@/lib/prisma";

export async function POST(request: Request) {
  const session = await auth();
  if (!session?.user?.id || session.user.role !== "user") {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const userId = Number(session.user.id);
  const { kuotaId } = await request.json();

  const [dokumen, kuota, existing] = await Promise.all([
    prisma.dokumen.findUnique({ where: { userId } }),
    prisma.kuotaMagang.findUnique({ where: { id: Number(kuotaId) } }),
    prisma.permohonanMagang.findFirst({
      where: {
        userId,
        status: { notIn: ["Diterima", "Ditolak"] },
      },
    }),
  ]);

  if (!dokumen?.cv) {
    return NextResponse.json(
      { error: "Unggah CV terlebih dahulu." },
      { status: 400 },
    );
  }

  if (!kuota || kuota.kuotaTerpakai >= kuota.kuotaMax) {
    return NextResponse.json({ error: "Kuota penuh." }, { status: 400 });
  }

  if (existing) {
    return NextResponse.json(
      { error: "Anda masih memiliki lamaran aktif." },
      { status: 400 },
    );
  }

  const permohonan = await prisma.permohonanMagang.create({
    data: {
      userId,
      dokumenId: dokumen.id,
      tanggalPengajuan: new Date(),
      status: "Diajukan",
      periodeBackup: kuota.periode,
      posisiBackup: kuota.posisi,
      kuota: {
        create: { kuotaMagangId: kuota.id },
      },
    },
  });

  return NextResponse.json({ id: permohonan.id });
}
