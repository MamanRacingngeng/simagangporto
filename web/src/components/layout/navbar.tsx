import Link from "next/link";
import { getSessionSafe } from "@/lib/auth";
import { NavbarLogos } from "./navbar-logos";

export async function Navbar() {
  const session = await getSessionSafe();

  return (
    <nav className="sticky top-0 z-50 border-b border-gray-200 bg-white shadow-sm">
      <div
        className="mx-auto flex max-w-7xl items-center justify-between py-4"
        style={{ paddingLeft: 16, paddingRight: 24 }}
      >
        <Link href="/" className="flex items-center gap-2">
          <NavbarLogos />
        </Link>

        <ul className="hidden items-center gap-6 font-medium md:flex">
          <li><Link href="/#beranda" className="nav-link text-gray-700">Beranda</Link></li>
          <li><Link href="/tentang-kami" className="nav-link text-gray-700">Tentang Kami</Link></li>
          <li><Link href="/#alur" className="nav-link text-gray-700">Alur</Link></li>
          <li><Link href="/#lowongan" className="nav-link text-gray-700">Lowongan</Link></li>
          <li><Link href="/galeri-magang" className="nav-link text-gray-700">Galeri Magang</Link></li>
        </ul>

        {session?.user ? (
          <Link
            href={session.user.role === "admin" ? "/admin/dashboard" : "/dashboard"}
            className="user-profile-btn flex items-center gap-3 rounded-lg px-4 py-2 font-semibold shadow-sm"
          >
            <div className="user-profile-img relative z-10 flex h-10 w-10 items-center justify-center rounded-full border-2 border-gray-200 bg-gray-100 text-sm font-bold text-gray-700 shadow-sm">
              {session.user.nama?.charAt(0).toUpperCase() ?? "U"}
            </div>
            <div className="relative z-10 hidden flex-col items-start md:flex">
              <span className="text-sm font-bold leading-tight text-gray-900">{session.user.nama}</span>
              <span className="text-xs font-medium leading-tight text-gray-600">{session.user.email}</span>
            </div>
          </Link>
        ) : (
          <Link href="/login" className="user-profile-btn rounded-lg px-4 py-2 font-semibold shadow-sm">
            <span className="relative z-10 text-gray-900">Login / Daftar</span>
          </Link>
        )}
      </div>
    </nav>
  );
}
