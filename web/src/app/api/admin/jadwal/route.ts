import { NextResponse } from "next/server";
import { auth } from "@/lib/auth";
import { prisma } from "@/lib/prisma";

export async function POST(request: Request) {
  const session = await auth();
  if (!session?.user?.id || session.user.role !== "admin") {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const body = await request.json();
  await prisma.jadwalMagang.create({
    data: {
      periode: body.periode,
      posisi: body.posisi,
      tglMulai: new Date(body.tglMulai),
      tglSelesai: new Date(body.tglSelesai),
    },
  });

  return NextResponse.json({ ok: true });
}
