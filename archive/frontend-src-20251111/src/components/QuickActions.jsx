import React from 'react'
import UploadLaporan from './UploadLaporan'

function ActionCard({title, desc, icon, variant='light', cta, children}){
  const base = 'p-4 rounded-2xl flex-1 flex flex-col gap-3 transition-transform transform hover:scale-[1.02] hover:shadow-md'
  const style = variant === 'dark' ? 'bg-[#111827] text-white' : 'bg-white border border-gray-100'
  return (
    <div className={base + ' ' + style}>
      <div className="text-3xl">{icon}</div>
      <h4 className="text-lg font-semibold">{title}</h4>
      <p className="text-sm text-gray-500">{desc}</p>
      <div className="mt-auto">
        {children ? children : (
          <button className={variant==='dark' ? 'px-4 py-2 rounded-md bg-white text-black':'px-4 py-2 rounded-md bg-blue-500 text-white'}>{cta}</button>
        )}
      </div>
    </div>
  )
}

export default function QuickActions(){
  return (
    <div className="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 items-stretch">
      <ActionCard title="Unggah Laporan Mingguan" desc="Unggah laporan kegiatan mingguan Anda" icon={'📄'} cta={'Unggah Sekarang'} >
        <UploadLaporan />
      </ActionCard>
      <ActionCard title="Lihat Detail Penugasan" desc="Periksa tugas dan instruksi" icon={'🔍'} variant='dark' cta={'Lihat Detail'} />
    </div>
  )
}
