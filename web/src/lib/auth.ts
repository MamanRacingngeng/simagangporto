import { redirect } from "next/navigation";
import NextAuth from "next-auth";
import Credentials from "next-auth/providers/credentials";
import Google from "next-auth/providers/google";
import bcrypt from "bcryptjs";
import { prisma } from "@/lib/prisma";

export const { handlers, auth, signIn, signOut } = NextAuth({
  trustHost: true,
  session: { strategy: "jwt" },
  pages: {
    signIn: "/login",
  },
  providers: [
    Google({
      clientId: process.env.GOOGLE_CLIENT_ID,
      clientSecret: process.env.GOOGLE_CLIENT_SECRET,
      allowDangerousEmailAccountLinking: true,
    }),
    Credentials({
      name: "credentials",
      credentials: {
        email: { label: "Email", type: "email" },
        password: { label: "Password", type: "password" },
        role: { label: "Role", type: "text" },
      },
      async authorize(credentials) {
        if (!credentials?.email || !credentials?.password) return null;

        const email = String(credentials.email).toLowerCase();
        const password = String(credentials.password);
        const expectedRole = credentials.role
          ? String(credentials.role)
          : "user";

        const user = await prisma.user.findUnique({ where: { email } });
        if (!user || !user.password) return null;
        if (user.role !== expectedRole) return null;
        if (!user.isActive) return null;

        const valid = await bcrypt.compare(password, user.password);
        if (!valid) return null;

        return {
          id: String(user.id),
          email: user.email,
          name: user.nama,
          nama: user.nama,
          role: user.role,
        };
      },
    }),
  ],
  callbacks: {
    async signIn({ user, account, profile }) {
      if (account?.provider !== "google") return true;

      const email = user.email?.toLowerCase();
      if (!email) return false;

      const existing = await prisma.user.findUnique({ where: { email } });
      if (existing) {
        if (existing.role === "admin") return true;
        if (!existing.isActive) {
          await prisma.user.update({
            where: { id: existing.id },
            data: {
              isActive: true,
              emailVerifiedAt: new Date(),
              googleId: account.providerAccountId,
              avatar: user.image ?? existing.avatar,
            },
          });
        }
        return true;
      }

      await prisma.user.create({
        data: {
          nama: user.name ?? profile?.name ?? email.split("@")[0],
          email,
          role: "user",
          isActive: true,
          emailVerifiedAt: new Date(),
          googleId: account.providerAccountId,
          avatar: user.image,
        },
      });

      return true;
    },
    async jwt({ token, user, account }) {
      if (user?.email) {
        const dbUser = await prisma.user.findUnique({
          where: { email: user.email.toLowerCase() },
        });
        if (dbUser) {
          token.id = String(dbUser.id);
          token.role = dbUser.role;
          token.nama = dbUser.nama;
        }
      } else if (token.email) {
        const dbUser = await prisma.user.findUnique({
          where: { email: token.email.toLowerCase() },
        });
        if (dbUser) {
          token.id = String(dbUser.id);
          token.role = dbUser.role;
          token.nama = dbUser.nama;
        }
      }

      if (account?.provider === "google" && !token.role) {
        token.role = "user";
      }

      return token;
    },
    async session({ session, token }) {
      if (session.user) {
        session.user.id = token.id as string;
        session.user.role = token.role as "user" | "admin";
        session.user.nama = token.nama as string;
      }
      return session;
    },
  },
});

export async function getSessionSafe() {
  try {
    return await auth();
  } catch (error) {
    console.error("[auth]", error);
    return null;
  }
}

export async function requireUser() {
  const session = await getSessionSafe();
  if (!session?.user?.id) redirect("/login");
  if (session.user.role === "admin") redirect("/admin/dashboard");
  return session;
}

export async function requireAdmin() {
  const session = await getSessionSafe();
  if (!session?.user?.id) redirect("/admin/login");
  if (session.user.role !== "admin") redirect("/dashboard");
  return session;
}

export async function redirectIfAuthenticated() {
  const session = await getSessionSafe();
  if (!session?.user?.id) return;
  if (session.user.role === "admin") redirect("/admin/dashboard");
  redirect("/dashboard");
}
