"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useEffect, useState } from "react";
import { DashboardStyles } from "./dashboard-styles";

const links = [
  { href: "/dashboard", label: "Dashboard", icon: "home" },
  { href: "/lowongan", label: "Lowongan", icon: "briefcase" },
  { href: "/lamaran", label: "Lamaran Saya", icon: "file" },
  { href: "/riwayat-lamaran", label: "Status Lamaran", icon: "history" },
  { href: "/profil", label: "Profil", icon: "user" },
];

function NavIcon({ type }: { type: string }) {
  const props = {
    width: 16,
    height: 16,
    viewBox: "0 0 24 24",
    fill: "none" as const,
    xmlns: "http://www.w3.org/2000/svg",
  };
  if (type === "home")
    return (
      <svg {...props}>
        <path d="M3 11.5L12 4l9 7.5" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
        <path d="M5 21V12h14v9" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
      </svg>
    );
  if (type === "briefcase")
    return (
      <svg {...props}>
        <rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" strokeWidth="1.5" />
        <path d="M16 7V6a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v1" stroke="currentColor" strokeWidth="1.5" />
      </svg>
    );
  if (type === "file")
    return (
      <svg {...props}>
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" strokeWidth="1.5" />
        <path d="M14 2v6h6" stroke="currentColor" strokeWidth="1.5" />
      </svg>
    );
  if (type === "history")
    return (
      <svg {...props}>
        <circle cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="1.5" />
        <polyline points="12 6 12 12 16 14" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
      </svg>
    );
  return (
    <svg {...props}>
      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" strokeWidth="1.5" />
      <circle cx="12" cy="7" r="4" stroke="currentColor" strokeWidth="1.5" />
    </svg>
  );
}

export function UserSidebar({
  open,
  onNavigate,
}: {
  open: boolean;
  onNavigate: () => void;
}) {
  const pathname = usePathname();

  return (
    <aside className={`sidebar ${open ? "open" : ""}`}>
      <div className="brand">
        <div style={{ padding: "12px 8px 20px" }}>
          <div style={{ textAlign: "center" }}>
            <img
              src="/images/logoBBKB.png"
              alt="Logo BBKB"
              width={200}
              height={92}
              style={{ height: 48, width: "auto", margin: "0 auto 12px", display: "block", objectFit: "contain" }}
            />
            <div style={{ fontSize: 18, fontWeight: 800, color: "#0C3A6B", lineHeight: 1.3, marginBottom: 4 }}>
              Magang Digital
            </div>
            <div style={{ fontSize: 12, color: "#6B7280", fontWeight: 500, lineHeight: 1.4 }}>
              BBKB Yogyakarta
            </div>
          </div>
        </div>
      </div>

      <nav className="nav" aria-label="Main navigation">
        {links.map(({ href, label, icon }) => (
          <Link
            key={href}
            href={href}
            className={`nav-item ${pathname === href ? "active" : ""}`}
            onClick={onNavigate}
          >
            <span className="nav-icon" aria-hidden="true">
              <NavIcon type={icon} />
            </span>
            <span className="nav-label">{label}</span>
          </Link>
        ))}
      </nav>

      <div style={{ padding: "0 8px 12px" }}>
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

export function UserShell({
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
      <div style={{ marginBottom: 24 }}>
        <h1 style={{ fontSize: "clamp(1.35rem, 4vw, 1.75rem)", fontWeight: 700, color: "#111827" }}>{title}</h1>
        {subtitle && (
          <p style={{ marginTop: 8, color: "#6B7280", fontSize: 15 }}>{subtitle}</p>
        )}
      </div>
      {children}
    </>
  );
}

export function DashboardLayout({ children }: { children: React.ReactNode }) {
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
      <div className="app-root">
        <UserSidebar open={sidebarOpen} onNavigate={() => setSidebarOpen(false)} />
        <div className="main">
          <button
            type="button"
            className="sidebar-toggle"
            aria-label="Buka menu navigasi"
            onClick={() => setSidebarOpen(true)}
          >
            ☰ Menu
          </button>
          {children}
        </div>
      </div>
    </div>
  );
}
