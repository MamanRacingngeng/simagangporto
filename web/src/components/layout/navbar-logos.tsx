/* Logo vektor (SVG) — tajam di semua ukuran layar */

export function NavbarLogos() {
  return (
    <div className="flex items-center gap-3">
      <img
        src="/images/logoBBKB.svg"
        alt="Logo BBKB Yogyakarta"
        width={360}
        height={96}
        className="h-12 w-auto object-contain transition-transform duration-300 hover:scale-105"
        decoding="async"
      />
      <img
        src="/images/logokemenperi.svg"
        alt="Logo Kemenperin"
        width={643}
        height={192}
        className="h-14 w-auto object-contain transition-transform duration-300 hover:scale-105"
        decoding="async"
      />
    </div>
  );
}
