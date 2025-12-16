import React from 'react'
import { motion } from 'framer-motion'
import StatusLamaran from './StatusLamaran'

function StatusIndicator({label, active}){
  return (
    <div className={"flex items-center gap-2 px-3 py-2 rounded-full " + (active? 'bg-blue-50 border border-blue-200':'bg-white/60') }>
      <div className={"w-3 h-3 rounded-full " + (active? 'bg-blue-500':'bg-gray-300')}></div>
      <div className="text-sm text-gray-600">{label}</div>
    </div>
  )
}

export default function StatusCard(){
  return (
    <motion.div initial={{opacity:0, y:10}} animate={{opacity:1, y:0}} transition={{duration:0.4}} className="card overflow-hidden rounded-2xl flex flex-col md:flex-row">
      <div className="flex-1 p-4 md:p-6">
        <StatusLamaran />

        <div className="flex gap-3 mt-4">
          <StatusIndicator label="Diunggah" />
          <StatusIndicator label="Menunggu Proses" active />
          <StatusIndicator label="Tidak Diterima" />
        </div>
      </div>
      <div className="w-full md:w-96 bg-gradient-to-b from-pink-50 to-white flex items-center justify-center p-4">
        <div className="w-full md:w-5/6 h-32 md:h-44 bg-gradient-to-r from-blue-50 to-white rounded-xl shadow-soft-lg"></div>
      </div>
    </motion.div>
  )
}
