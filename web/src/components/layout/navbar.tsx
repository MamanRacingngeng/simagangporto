"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useEffect, useState } from "react";
import { demoUser } from "@/lib/demo-data";
import { NavbarLogos } from "./navbar-logos";

const navLinks = [
  { href: "/#beranda", label: "Beranda" },
  { href: "/tentang-kami", label: "Tentang Kami" },
  { href: "/#alur", label: "Alur" },
  { href: "/#lowongan", label: "Lowongan" },
  { href: "/galeri-magang", label: "Galeri Magang" },
];

export function Navbar() {
  const pathname = usePathname();
  const [open, setOpen] = useState(false);

  useEffect(() => {
    setOpen(false);
  }, [pathname]);

  useEffect(() => {
    document.body.style.overflow = open ? "hidden" : "";
    return () => {
      document.body.style.overflow = "";
    };
  }, [open]);

  return (
    <nav className="site-navbar sticky top-0 z-50 border-b border-gray-200 bg-white shadow-sm">
      <div className="site-navbar-inner mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3 sm:px-6">
        <Link href="/" className="min-w-0 shrink" onClick={() => setOpen(false)}>
          <NavbarLogos />
        </Link>

        <ul className="hidden items-center gap-5 font-medium lg:flex">
          {navLinks.map(({ href, label }) => (
            <li key={href}>
              <Link href={href} className="nav-link text-gray-700">
                {label}
              </Link>
            </li>
          ))}
        </ul>

        <div className="hidden items-center gap-2 sm:flex">
          <Link
            href="/dashboard"
            className="user-profile-btn flex items-center gap-2 rounded-lg px-3 py-2 font-semibold shadow-sm"
          >
            <div className="user-profile-img relative z-10 flex h-9 w-9 items-center justify-center rounded-full border-2 border-gray-200 bg-gray-100 text-sm font-bold text-gray-700 shadow-sm">
              {demoUser.nama.charAt(0)}
            </div>
            <span className="relative z-10 hidden text-sm font-bold text-gray-900 xl:inline">
              Demo
            </span>
          </Link>
          <Link
            href="/admin/dashboard"
            className="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50"
          >
            Admin
          </Link>
        </div>

        <button
          type="button"
          className="mobile-menu-btn lg:hidden"
          aria-label={open ? "Tutup menu" : "Buka menu"}
          aria-expanded={open}
          onClick={() => setOpen((value) => !value)}
        >
          <span className={`mobile-menu-icon ${open ? "open" : ""}`} />
        </button>
      </div>

      <div className={`mobile-nav-overlay lg:hidden ${open ? "open" : ""}`} onClick={() => setOpen(false)} />

      <div className={`mobile-nav-panel lg:hidden ${open ? "open" : ""}`}>
        <ul className="mobile-nav-list">
          {navLinks.map(({ href, label }) => (
            <li key={href}>
              <Link href={href} className="mobile-nav-link" onClick={() => setOpen(false)}>
                {label}
              </Link>
            </li>
          ))}
        </ul>
        <div className="mobile-nav-actions">
          <Link href="/dashboard" className="btn-gold mobile-nav-cta" onClick={() => setOpen(false)}>
            Demo Dashboard
          </Link>
          <Link href="/admin/dashboard" className="mobile-nav-secondary" onClick={() => setOpen(false)}>
            Admin Demo
          </Link>
          <Link href="/login" className="mobile-nav-secondary" onClick={() => setOpen(false)}>
            Login
          </Link>
        </div>
      </div>
    </nav>
  );
}
