"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";

const links = [
  { href: "/admin/dashboard", label: "Dashboard" },
  { href: "/admin/data-pendaftar", label: "Data Pendaftar" },
  { href: "/admin/atur-kuota-magang", label: "Kuota Magang" },
  { href: "/admin/atur-jadwal-magang", label: "Jadwal Magang" },
  { href: "/admin/kelola-galeri", label: "Kelola Galeri" },
];

export function AdminSidebar() {
  const pathname = usePathname();

  return (
    <aside className="admin-sidebar">
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
          >
            {label}
          </Link>
        ))}
      </nav>

      <div style={{ padding: "16px" }}>
        <Link href="/" className="btn-outline" style={{ width: "100%", display: "block", textAlign: "center" }}>
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
    <div className="admin-main">
      <h1 style={{ fontSize: 28, fontWeight: 700, color: "#0f172a", marginBottom: 8 }}>
        {title}
      </h1>
      {subtitle && (
        <p style={{ color: "#6B7280", marginBottom: 24, fontSize: 15 }}>{subtitle}</p>
      )}
      {children}
    </div>
  );
}

import { DashboardStyles } from "./dashboard-styles";

export function AdminLayout({ children }: { children: React.ReactNode }) {
  return (
    <div className="dashboard-page">
      <DashboardStyles />
      <div className="admin-wrapper">
        <AdminSidebar />
        {children}
      </div>
    </div>
  );
}
