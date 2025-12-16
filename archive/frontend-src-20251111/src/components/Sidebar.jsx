import React from 'react'

import { Link } from 'react-router-dom'

function NavItem({icon, children, to, active}){
  return (
    <Link to={to} className={"flex items-center gap-3 px-4 py-3 rounded-lg transition-all " + (active? 'bg-blue-100 text-blue-600':'text-gray-300 hover:bg-white/5') }>
      <span className="text-lg">{icon}</span>
      <span className="text-sm font-medium">{children}</span>
    </Link>
  )
}

export default function Sidebar({open, onClose}){
  // open: boolean (mobile drawer)
  return (
    <>
      {/* overlay for mobile when open */}
      <div className={(open ? 'fixed inset-0 z-30 bg-black/40 md:hidden' : 'hidden') } onClick={onClose} />

      <aside className={"fixed inset-y-0 left-0 z-40 w-64 p-4 bg-[#0f1720] text-white rounded-r-2xl shadow-soft-lg flex flex-col transform transition-transform duration-300 md:relative md:translate-x-0 md:rounded-2xl " + (open ? 'translate-x-0' : '-translate-x-full md:translate-x-0') }>
      <div className="flex items-center justify-between mb-4">
        <div className="text-lg font-semibold">Magang Digital BBKB</div>
        <button onClick={onClose} className="md:hidden p-2 rounded-md hover:bg-white/5">✕</button>
      </div>
      <nav className="flex flex-col gap-2">
  <NavItem icon={'📊'} to={'/dashboard'} active>Dashboard</NavItem>
        <NavItem icon={'📝'} to={'/lowongan'}>Lowongan</NavItem>
        <NavItem icon={'📂'} to={'/lamaran'}>Lamaran Saya</NavItem>
        <NavItem icon={'👤'} to={'/profil'}>Profil</NavItem>
      </nav>

      <div className="mt-auto bg-gradient-to-b from-[#111827] to-[#0b1220] p-3 rounded-xl mt-4 flex items-center justify-between">
        <div>
          <div className="text-sm font-semibold">Lowongan Magang Dibuka!</div>
          <div className="text-xs text-gray-300">Ayo kirim lamaran sekarang</div>
        </div>
        <button className="ml-4 bg-white text-black px-3 py-1 rounded-md text-sm">Lihat Detail</button>
      </div>
    </aside>
    </>
  )
}
