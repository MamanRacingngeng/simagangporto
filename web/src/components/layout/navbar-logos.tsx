/* Logo asli Laravel — PNG resmi BBKB & Kemenperin */

export function NavbarLogos() {
  return (
    <div className="navbar-logos flex max-w-[220px] items-center gap-1.5 sm:max-w-none sm:gap-2">
      <img
        src="/images/logoBBKB.png"
        alt="Logo BBKB"
        width={240}
        height={110}
        className="navbar-logo-bbkb h-8 w-auto max-h-8 object-contain sm:h-10 sm:max-h-10 md:h-12 md:max-h-12"
        decoding="async"
      />
      <img
        src="/images/logokemenperi.png"
        alt="Logo Kemenperin"
        width={177}
        height={110}
        className="navbar-logo-kemen h-9 w-auto max-h-9 object-contain sm:h-11 sm:max-h-11 md:h-14 md:max-h-14"
        decoding="async"
      />
    </div>
  );
}
