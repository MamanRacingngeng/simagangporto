import React, {useState, useRef, useEffect} from 'react'
import { useAuth } from '../context/AuthContext'
import { Link } from 'react-router-dom'

export default function Header({onMenuClick}){
  const [open, setOpen] = useState(false)
  const { user, logout } = useAuth()
  const ref = useRef()

  useEffect(()=>{
    function onDoc(e){
      if (ref.current && !ref.current.contains(e.target)) setOpen(false)
    }
    document.addEventListener('click', onDoc)
    return ()=> document.removeEventListener('click', onDoc)
  },[])

  return (
  <header className="flex items-center justify-between mb-4">
      <div className="flex items-center gap-3">
        {/* mobile menu button */}
        <button onClick={onMenuClick} className="md:hidden p-2 rounded-md hover:bg-gray-100 transition">☰</button>
        <h2 className="hidden md:block text-lg font-semibold">Dashboard</h2>
      </div>

      <div className="flex items-center gap-4">
        <button className="p-2 rounded-md hover:bg-gray-100 transition">🔔</button>

        <div className="relative" ref={ref}>
          <button onClick={()=>setOpen(o=>!o)} className="flex items-center gap-2 p-2 rounded hover:bg-gray-100">
            <div className="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">👤</div>
            <div className="hidden sm:block text-sm text-gray-600">{user ? `Halo, ${user.name}` : 'Masuk'}</div>
          </button>

          {open && (
            <div className="absolute right-0 mt-2 w-44 bg-white border rounded shadow p-2 z-50">
              {user ? (
                <>
                  <div className="text-sm">{user.name}</div>
                  <div className="text-xs text-gray-500 mb-2">{user.email}</div>
                  <button onClick={logout} className="w-full text-left px-2 py-1 rounded hover:bg-gray-100">Logout</button>
                  <Link to="/profil" className="block px-2 py-1 rounded hover:bg-gray-100">Lihat Profil</Link>
                </>
              ) : (
                <Link to="/login" className="block px-2 py-1 rounded hover:bg-gray-100">Masuk</Link>
              )}
            </div>
          )}
        </div>
      </div>
    </header>
  )
}
