/* Logo asli Laravel — PNG resmi BBKB & Kemenperin */

export function NavbarLogos() {
  return (
    <div className="flex items-center gap-2">
      <img
        src="/images/logoBBKB.png"
        alt="Logo BBKB"
        width={240}
        height={110}
        className="h-12 max-h-12 w-auto object-contain transition-transform duration-300 hover:scale-110"
        decoding="async"
      />
      <img
        src="/images/logokemenperi.png"
        alt="Logo Kemenperin"
        width={177}
        height={110}
        className="h-14 max-h-14 w-auto object-contain transition-transform duration-300 hover:scale-110"
        decoding="async"
      />
    </div>
  );
}
