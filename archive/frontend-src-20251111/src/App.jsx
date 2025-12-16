import React from 'react'
import { HashRouter as Router, Routes, Route, Link, Navigate } from 'react-router-dom'
import Dashboard from './pages/Dashboard'
import Lowongan from './pages/Lowongan'
import LamaranSaya from './pages/LamaranSaya'
import Profil from './pages/Profil'
import Login from './pages/Login'
import { AuthProvider, useAuth } from './context/AuthContext'

export default function App(){
  return (
    <Router basename="/frontend">
      <AuthProvider>
      <div className="min-h-screen flex">
        {/* Sidebar */}
        <div className="w-64 bg-gray-900 text-white h-screen p-4 hidden md:block">
          <h1 className="text-xl font-bold mb-6">Magang Digital BBKB</h1>
          <nav className="flex flex-col gap-2">
            <Link to="/dashboard" className="hover:bg-blue-600 p-2 rounded">Dashboard</Link>
            <Link to="/lowongan" className="hover:bg-blue-600 p-2 rounded">Lowongan</Link>
            <Link to="/lamaran" className="hover:bg-blue-600 p-2 rounded">Lamaran Saya</Link>
            <Link to="/profil" className="hover:bg-blue-600 p-2 rounded">Profil</Link>
          </nav>
        </div>

        {/* Main content */}
        <div className="flex-1 p-6">
          <Routes>
            <Route path="/" element={<Navigate to="/dashboard" replace />} />
            <Route path="/dashboard" element={<Dashboard />} />
            <Route path="/lowongan" element={<Lowongan />} />
            <Route path="/lamaran" element={<LamaranSaya />} />
            <Route path="/profil" element={<Profil />} />
            <Route path="/login" element={<Login />} />
          </Routes>
        </div>
      </div>
      </AuthProvider>
    </Router>
  )
}
