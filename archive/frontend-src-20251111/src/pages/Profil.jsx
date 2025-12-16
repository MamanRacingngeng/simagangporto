import { Link } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'

export default function Profil() {
  const { user, logout } = useAuth()

  if (!user) {
    return (
      <div className="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h2 className="text-2xl font-bold mb-4">Profil Pengguna</h2>
        <p className="mb-3">Anda belum masuk.</p>
        <Link to="/login" className="inline-block bg-blue-600 text-white px-4 py-2 rounded">Masuk</Link>
      </div>
    )
  }

  return (
    <div className="max-w-md mx-auto bg-white p-6 rounded shadow">
      <h2 className="text-2xl font-bold mb-4">Profil Pengguna</h2>
      <p className="mb-1"><strong>Nama:</strong> {user.name}</p>
      <p className="mb-4"><strong>Email:</strong> {user.email}</p>
      <div className="flex gap-2">
        <button onClick={logout} className="bg-red-600 text-white px-4 py-2 rounded">Logout</button>
        <Link to="/" className="bg-gray-200 px-4 py-2 rounded">Kembali</Link>
      </div>
    </div>
  )
}
