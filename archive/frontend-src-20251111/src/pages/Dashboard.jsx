import React, {useState} from 'react'
import Sidebar from '../components/Sidebar'
import Header from '../components/Header'
import StatusCard from '../components/StatusCard'
import QuickActions from '../components/QuickActions'

export default function Dashboard(){
  const [sidebarOpen, setSidebarOpen] = useState(false)
  return (
  <div className="min-h-screen p-4 md:p-4 bg-gradient-to-b from-white to-gray-50">
      {/* layout: on small screens stack, on md+ show sidebar & content side-by-side */}
  <div className="max-w-7xl mx-auto w-full flex flex-col md:flex-row md:items-start md:gap-6">
        {/* Sidebar - on mobile rendered as slide-over */}
        <Sidebar open={sidebarOpen} onClose={() => setSidebarOpen(false)} />

        {/* Main content */}
  <main className="flex-1 md:pl-6">
          <Header onMenuClick={() => setSidebarOpen(true)} />

          <div className="mt-4 flex flex-col gap-4">
            <StatusCard />
            <QuickActions />
          </div>
        </main>
      </div>
    </div>
  )
}
