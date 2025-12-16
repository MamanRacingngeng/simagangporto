import { useEffect, useState } from "react";
import axios from "axios";

export default function Lowongan() {
  const [lowongan, setLowongan] = useState([]);

  useEffect(() => {
    axios
      .get("http://127.0.0.1:8000/api/lowongan")
      .then((res) => setLowongan(res.data))
      .catch(() => setLowongan([]));
  }, []);

  return (
    <div>
      <h2 className="text-2xl font-bold mb-4">Daftar Lowongan Magang</h2>
      <ul className="space-y-3">
        {lowongan.map((item, i) => (
          <li key={i} className="p-4 shadow rounded bg-white">
            <h3 className="font-semibold">{item.nama}</h3>
            <p>{item.deskripsi}</p>
            <button className="mt-2 bg-blue-600 text-white px-3 py-1 rounded">
              Lihat Detail
            </button>
          </li>
        ))}
      </ul>
    </div>
  );
}
