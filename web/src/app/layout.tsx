import type { Metadata, Viewport } from "next";
import Script from "next/script";
import { Providers } from "@/components/providers";

export const metadata: Metadata = {
  title: {
    default: "BBKB Yogyakarta - Magang",
    template: "%s | BBKB Magang",
  },
  description:
    "Portal magang digital Balai Besar Standardisasi dan Pelayanan Jasa Kerajinan dan Batik Yogyakarta.",
};

export const viewport: Viewport = {
  width: "device-width",
  initialScale: 1,
  maximumScale: 5,
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="id" className="h-full">
      <head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link
          rel="preconnect"
          href="https://fonts.gstatic.com"
          crossOrigin="anonymous"
        />
        <link
          href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap"
          rel="stylesheet"
        />
        <link rel="stylesheet" href="/css/laravel-app.css" />
      </head>
      <body className="text-gray-800 antialiased">
        <Script src="https://cdn.tailwindcss.com" strategy="beforeInteractive" />
        <Providers>{children}</Providers>
      </body>
    </html>
  );
}
