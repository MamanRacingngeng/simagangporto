import axios from "axios";
import { useEffect, useState } from "react";

export default function LamaranSaya() {
  const [lamaran, setLamaran] = useState([]);

  useEffect(() => {
    axios
      .get("http://127.0.0.1:8000/api/lamaran")
      .then((res) => setLamaran(res.data))
      .catch(() => setLamaran([]));
  }, []);

  return (
    <div>
      <h2 className="text-2xl font-bold mb-4">Lamaran Saya</h2>
      {lamaran.length === 0 ? (
        <p>Belum ada lamaran dikirim.</p>
      ) : (
        <ul className="space-y-3">
          {lamaran.map((l, i) => (
            <li key={i} className="p-4 bg-white shadow rounded">
              <h3 className="font-semibold">{l.posisi}</h3>
              <p>Status: {l.status}</p>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
