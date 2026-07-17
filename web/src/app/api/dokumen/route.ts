import { NextResponse } from "next/server";
import { auth } from "@/lib/auth";
import { prisma } from "@/lib/prisma";

export async function POST(request: Request) {
  const session = await auth();
  if (!session?.user?.id || session.user.role !== "user") {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const userId = Number(session.user.id);
  const body = await request.json();

  await prisma.dokumen.upsert({
    where: { userId },
    create: {
      userId,
      cv: body.cv || null,
      suratPengantar: body.suratPengantar || null,
      proposal: body.proposal || null,
      tanggalUpload: new Date(),
    },
    update: {
      cv: body.cv || null,
      suratPengantar: body.suratPengantar || null,
      proposal: body.proposal || null,
      tanggalUpload: new Date(),
    },
  });

  return NextResponse.json({ ok: true });
}
