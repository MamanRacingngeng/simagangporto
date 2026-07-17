import { auth } from "@/lib/auth";
import { NextResponse } from "next/server";

export default auth((req) => {
  const { pathname } = req.nextUrl;
  const isLoggedIn = !!req.auth;
  const role = req.auth?.user?.role;

  const isAdminRoute =
    pathname.startsWith("/admin") && pathname !== "/admin/login";
  const isUserRoute =
    pathname.startsWith("/dashboard") ||
    pathname.startsWith("/lowongan") ||
    pathname.startsWith("/lamaran") ||
    pathname.startsWith("/profil") ||
    pathname.startsWith("/riwayat-lamaran") ||
    pathname.startsWith("/panduan-onboarding") ||
    pathname.startsWith("/laporan");

  const isAuthRoute =
    pathname === "/login" ||
    pathname === "/register" ||
    pathname === "/admin/login";

  if (isAdminRoute) {
    if (!isLoggedIn) {
      return NextResponse.redirect(new URL("/admin/login", req.url));
    }
    if (role !== "admin") {
      return NextResponse.redirect(new URL("/dashboard", req.url));
    }
  }

  if (isUserRoute) {
    if (!isLoggedIn) {
      return NextResponse.redirect(new URL("/login", req.url));
    }
    if (role === "admin") {
      return NextResponse.redirect(new URL("/admin/dashboard", req.url));
    }
  }

  if (isAuthRoute && isLoggedIn) {
    if (role === "admin") {
      return NextResponse.redirect(new URL("/admin/dashboard", req.url));
    }
    return NextResponse.redirect(new URL("/dashboard", req.url));
  }

  return NextResponse.next();
});

export const config = {
  matcher: [
    "/dashboard/:path*",
    "/lowongan/:path*",
    "/lamaran/:path*",
    "/profil/:path*",
    "/riwayat-lamaran/:path*",
    "/panduan-onboarding/:path*",
    "/laporan/:path*",
    "/admin/:path*",
    "/login",
    "/register",
  ],
};
