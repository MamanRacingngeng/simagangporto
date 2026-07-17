import { NextResponse } from "next/server";
import bcrypt from "bcryptjs";
import { randomBytes } from "crypto";
import { prisma } from "@/lib/prisma";
import { z } from "zod";

const registerSchema = z.object({
  nama: z.string().min(2),
  email: z.string().email(),
  password: z.string().min(8),
});

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const data = registerSchema.parse(body);

    const email = data.email.toLowerCase();
    const existing = await prisma.user.findUnique({ where: { email } });
    if (existing) {
      return NextResponse.json(
        { error: "Email sudah terdaftar." },
        { status: 400 },
      );
    }

    const hashed = await bcrypt.hash(data.password, 12);
    const token = randomBytes(32).toString("hex");

    await prisma.user.create({
      data: {
        nama: data.nama,
        email,
        password: hashed,
        role: "user",
        isActive: true,
        emailVerifiedAt: new Date(),
        emailVerificationToken: token,
      },
    });

    return NextResponse.json({
      message: "Registrasi berhasil. Silakan login.",
    });
  } catch (error) {
    if (error instanceof z.ZodError) {
      return NextResponse.json({ error: "Data tidak valid." }, { status: 400 });
    }
    console.error(error);
    return NextResponse.json(
      { error: "Terjadi kesalahan saat registrasi." },
      { status: 500 },
    );
  }
}
