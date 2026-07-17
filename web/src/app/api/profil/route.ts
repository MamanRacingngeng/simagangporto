import { NextResponse } from "next/server";
import { auth } from "@/lib/auth";
import { prisma } from "@/lib/prisma";

export async function PUT(request: Request) {
  const session = await auth();
  if (!session?.user?.id || session.user.role !== "user") {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const body = await request.json();
  const userId = Number(session.user.id);

  await prisma.user.update({
    where: { id: userId },
    data: {
      nama: body.nama,
      namaPanggilan: body.namaPanggilan || null,
      ttl: body.ttl || null,
      domisili: body.domisili || null,
      nim: body.nim || null,
      semester: body.semester ? Number(body.semester) : null,
      ipk: body.ipk ? body.ipk : null,
      program: body.program || null,
      universitas: body.universitas || null,
      softwareTools: body.softwareTools || null,
      portofolio: body.portofolio || null,
      kompetensiUtama: body.kompetensiUtama || null,
    },
  });

  return NextResponse.json({ ok: true });
}
