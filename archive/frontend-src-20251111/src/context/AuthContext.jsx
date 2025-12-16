import React, { createContext, useContext, useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import axios from 'axios'

const AuthContext = createContext(null)

export function AuthProvider({ children }){
  const [user, setUser] = useState(null)
  const [token, setToken] = useState(null)
  const [loading, setLoading] = useState(false)
  const navigate = useNavigate()

  useEffect(() => {
    try {
      const raw = localStorage.getItem('app_user')
      const t = localStorage.getItem('app_token')
      if (raw) setUser(JSON.parse(raw))
      if (t) {
        setToken(t)
        axios.defaults.headers.common['Authorization'] = `Bearer ${t}`
      }
    } catch (e) { /* ignore */ }
    // global 401 handler: redirect to login
    const interceptor = axios.interceptors.response.use(
      (r) => r,
      (err) => {
        if (err.response && err.response.status === 401) {
          try { localStorage.removeItem('app_token'); localStorage.removeItem('app_user') } catch(e){}
          if (typeof window !== 'undefined') window.location.href = '/frontend/index.html#/login'
        }
        return Promise.reject(err)
      }
    )

    return () => axios.interceptors.response.eject(interceptor)
  }, [])

  async function login({ email, password }){
    setLoading(true)
    try {
      const res = await axios.post('http://127.0.0.1:8000/api/login', { email, password })
      if (res.data?.success) {
        const tk = res.data.token
        const u = res.data.user
        localStorage.setItem('app_token', tk)
        localStorage.setItem('app_user', JSON.stringify(u))
        axios.defaults.headers.common['Authorization'] = `Bearer ${tk}`
        setToken(tk)
        setUser(u)
        // navigate to dashboard (SPA)
        navigate('/dashboard')
        // also ensure deployed redirect matches requirement
        if (typeof window !== 'undefined') {
          window.location.hash = '#/dashboard'
        }
        return { success: true }
      }
      return { success: false, message: res.data?.message || 'Login gagal' }
    } catch (e) {
      return { success: false, message: e.response?.data?.message || 'Terjadi kesalahan' }
    } finally {
      setLoading(false)
    }
  }

  async function logout(){
    try {
      // attempt to notify server
      await axios.post('http://127.0.0.1:8000/api/logout')
    } catch (e) {
      // ignore
    }
    localStorage.removeItem('app_token')
    localStorage.removeItem('app_user')
    delete axios.defaults.headers.common['Authorization']
    setToken(null)
    setUser(null)
    // redirect to login page (deployed path)
    if (typeof window !== 'undefined') {
      window.location.href = '/frontend/index.html#/login'
    }
    navigate('/login')
  }

  return (
    <AuthContext.Provider value={{ user, token, loading, login, logout }}>
      {children}
    </AuthContext.Provider>
  )
}

export function useAuth(){
  return useContext(AuthContext)
}

export default AuthContext
