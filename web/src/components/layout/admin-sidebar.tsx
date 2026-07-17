"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useEffect, useState } from "react";
import { DashboardStyles } from "./dashboard-styles";

const links = [
  { href: "/admin/dashboard", label: "Dashboard" },
  { href: "/admin/data-pendaftar", label: "Data Pendaftar" },
  { href: "/admin/atur-kuota-magang", label: "Kuota Magang" },
  { href: "/admin/atur-jadwal-magang", label: "Jadwal Magang" },
  { href: "/admin/kelola-galeri", label: "Kelola Galeri" },
];

export function AdminSidebar({
  open,
  onNavigate,
}: {
  open: boolean;
  onNavigate: () => void;
}) {
  const pathname = usePathname();

  return (
    <aside className={`admin-sidebar ${open ? "open" : ""}`}>
      <div className="brand">
        <div className="brand-title">Panel Admin</div>
        <div className="brand-subtitle">SIMAGANG BBKB Yogyakarta</div>
      </div>

      <nav className="nav">
        {links.map(({ href, label }) => (
          <Link
            key={href}
            href={href}
            className={`nav-item ${pathname === href ? "active" : ""}`}
            onClick={onNavigate}
          >
            {label}
          </Link>
        ))}
      </nav>

      <div style={{ padding: "16px" }}>
        <Link
          href="/"
          className="btn-outline"
          style={{ width: "100%", display: "block", textAlign: "center" }}
          onClick={onNavigate}
        >
          Kembali ke Beranda
        </Link>
      </div>
    </aside>
  );
}

export function AdminShell({
  children,
  title,
  subtitle,
}: {
  children: React.ReactNode;
  title: string;
  subtitle?: string;
}) {
  return (
    <>
      <h1 style={{ fontSize: "clamp(1.35rem, 4vw, 1.75rem)", fontWeight: 700, color: "#0f172a", marginBottom: 8 }}>
        {title}
      </h1>
      {subtitle && (
        <p style={{ color: "#6B7280", marginBottom: 24, fontSize: 15 }}>{subtitle}</p>
      )}
      {children}
    </>
  );
}

export function AdminLayout({ children }: { children: React.ReactNode }) {
  const pathname = usePathname();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  useEffect(() => {
    setSidebarOpen(false);
  }, [pathname]);

  return (
    <div className="dashboard-page">
      <DashboardStyles />
      <div
        className={`sidebar-backdrop ${sidebarOpen ? "open" : ""}`}
        onClick={() => setSidebarOpen(false)}
        aria-hidden={!sidebarOpen}
      />
      <div className="admin-wrapper">
        <AdminSidebar open={sidebarOpen} onNavigate={() => setSidebarOpen(false)} />
        <div className="admin-main">
          <button
            type="button"
            className="sidebar-toggle"
            aria-label="Buka menu admin"
            onClick={() => setSidebarOpen(true)}
          >
            ☰ Menu Admin
          </button>
          {children}
        </div>
      </div>
    </div>
  );
}
