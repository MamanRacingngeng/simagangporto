import { NextResponse } from "next/server";
import { auth } from "@/lib/auth";
import { prisma } from "@/lib/prisma";

export async function POST(request: Request) {
  const session = await auth();
  if (!session?.user?.id || session.user.role !== "admin") {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const body = await request.json();
  await prisma.galeriMagang.create({
    data: {
      judul: body.judul,
      deskripsi: body.deskripsi,
      foto: body.foto ?? "",
      urutan: Number(body.urutan ?? 0),
      aktif: true,
    },
  });

  return NextResponse.json({ ok: true });
}
