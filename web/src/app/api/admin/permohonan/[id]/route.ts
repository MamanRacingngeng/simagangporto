import { NextResponse } from "next/server";
import { auth } from "@/lib/auth";
import { prisma } from "@/lib/prisma";

export async function PATCH(
  request: Request,
  { params }: { params: Promise<{ id: string }> },
) {
  const session = await auth();
  if (!session?.user?.id || session.user.role !== "admin") {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const { id } = await params;
  const body = await request.json();
  const permohonanId = Number(id);

  const permohonan = await prisma.permohonanMagang.findUnique({
    where: { id: permohonanId },
    include: { kuota: { include: { kuota: true } } },
  });

  if (!permohonan) {
    return NextResponse.json({ error: "Not found" }, { status: 404 });
  }

  const oldStatus = permohonan.status;
  const newStatus = body.status as string;

  await prisma.permohonanMagang.update({
    where: { id: permohonanId },
    data: {
      status: newStatus,
      alasanPenolakan: body.alasanPenolakan ?? permohonan.alasanPenolakan,
      catatanRevisi: body.catatanRevisi ?? permohonan.catatanRevisi,
    },
  });

  if (oldStatus !== "Diterima" && newStatus === "Diterima") {
    const kuotaId = permohonan.kuota[0]?.kuotaMagangId;
    if (kuotaId) {
      await prisma.kuotaMagang.update({
        where: { id: kuotaId },
        data: { kuotaTerpakai: { increment: 1 } },
      });
    }
  }

  if (oldStatus === "Diterima" && newStatus !== "Diterima") {
    const kuotaId = permohonan.kuota[0]?.kuotaMagangId;
    if (kuotaId) {
      await prisma.kuotaMagang.update({
        where: { id: kuotaId },
        data: { kuotaTerpakai: { decrement: 1 } },
      });
    }
  }

  if (newStatus === "Revisi" && body.catatanRevisi) {
    await prisma.notifikasi.create({
      data: {
        userId: permohonan.userId,
        permohonanMagangId: permohonanId,
        adminId: Number(session.user.id),
        judul: "Permohonan Perlu Revisi",
        pesan: body.catatanRevisi,
        tipe: "revisi",
      },
    });
  }

  return NextResponse.json({ ok: true });
}
