import { useState } from 'react'
import { useAuth } from '../context/AuthContext'

export default function Login(){
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState(null)
  const { login, loading } = useAuth()

  async function submit(e){
    e.preventDefault()
    setError(null)
    if (!email || !password) return setError('Email dan password wajib diisi')
    const res = await login({ email, password })
    if (!res.success) setError(res.message || 'Gagal login')
  }

  return (
    <div className="max-w-md mx-auto bg-white p-6 rounded shadow">
      <h2 className="text-xl font-semibold mb-4">Masuk</h2>
      <form onSubmit={submit} className="space-y-3">
        {error && <div className="text-red-600 text-sm">{error}</div>}
        <div>
          <label className="block text-sm">Email</label>
          <input type="email" value={email} onChange={e=>setEmail(e.target.value)} className="w-full border px-3 py-2 rounded" />
        </div>
        <div>
          <label className="block text-sm">Password</label>
          <input type="password" value={password} onChange={e=>setPassword(e.target.value)} className="w-full border px-3 py-2 rounded" />
        </div>
        <div>
          <button type="submit" className="bg-blue-600 text-white px-4 py-2 rounded flex items-center gap-2">
            {loading ? (<span className="w-4 h-4 border-2 border-white rounded-full animate-spin" />) : null}
            Masuk
          </button>
        </div>
      </form>
    </div>
  )
}
