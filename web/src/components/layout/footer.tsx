export function Footer() {
  return (
    <footer className="mt-10 bg-gray-900 py-10 text-center text-gray-300">
      <div className="mx-auto max-w-7xl px-6">
        <p>&copy; {new Date().getFullYear()} BBKB Yogyakarta. Semua Hak Dilindungi.</p>
        <p className="mt-2 text-sm opacity-75">
          Balai Besar Kerajinan & Batik Yogyakarta
        </p>
      </div>
    </footer>
  );
}
