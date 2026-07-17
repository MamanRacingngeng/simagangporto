"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { signOut } from "next-auth/react";

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

export function UserSidebar() {
  const pathname = usePathname();

  return (
    <aside className="sidebar">
      <div className="brand">
        <div style={{ padding: "28px 20px" }}>
          <div style={{ textAlign: "center" }}>
            <div
              style={{
                width: 80,
                height: 80,
                margin: "0 auto 14px",
                borderRadius: "50%",
                background: "linear-gradient(135deg, #2563EB, #1D4ED8)",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                color: "#fff",
                fontWeight: 800,
                fontSize: 24,
              }}
            >
              BBKB
            </div>
            <div
              style={{
                fontSize: 20,
                fontWeight: 800,
                color: "#0C3A6B",
                lineHeight: 1.3,
                marginBottom: 4,
              }}
            >
              Magang Digital
            </div>
            <div style={{ fontSize: 13, color: "#6B7280", fontWeight: 500 }}>
              Balai Besar Standardisasi dan Pelayanan Jasa Kerajinan dan Batik
              Yogyakarta
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
          >
            <span className="nav-icon" aria-hidden="true">
              <NavIcon type={icon} />
            </span>
            <span className="nav-label">{label}</span>
          </Link>
        ))}
      </nav>

      <div style={{ padding: "0 16px 20px" }}>
        <button
          type="button"
          onClick={() => signOut({ callbackUrl: "/" })}
          className="btn-outline"
          style={{ width: "100%" }}
        >
          Keluar
        </button>
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
    <div className="main">
      <div style={{ marginBottom: 24 }}>
        <h1 style={{ fontSize: 28, fontWeight: 700, color: "#111827" }}>{title}</h1>
        {subtitle && (
          <p style={{ marginTop: 8, color: "#6B7280", fontSize: 15 }}>{subtitle}</p>
        )}
      </div>
      {children}
    </div>
  );
}

import { DashboardStyles } from "./dashboard-styles";

export function DashboardLayout({ children }: { children: React.ReactNode }) {
  return (
    <div className="dashboard-page">
      <DashboardStyles />
      <div className="app-root">
        <UserSidebar />
        {children}
      </div>
    </div>
  );
}
