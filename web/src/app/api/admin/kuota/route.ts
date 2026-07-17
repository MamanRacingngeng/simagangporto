import { NextResponse } from "next/server";
import { auth } from "@/lib/auth";
import { prisma } from "@/lib/prisma";

export async function POST(request: Request) {
  const session = await auth();
  if (!session?.user?.id || session.user.role !== "admin") {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const body = await request.json();
  await prisma.kuotaMagang.create({
    data: {
      periode: body.periode,
      posisi: body.posisi,
      deskripsi: body.deskripsi,
      kuotaMax: Number(body.kuotaMax),
    },
  });

  return NextResponse.json({ ok: true });
}
