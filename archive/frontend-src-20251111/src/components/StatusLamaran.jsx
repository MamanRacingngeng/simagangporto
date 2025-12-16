import React, {useEffect, useState} from 'react'
import axios from 'axios'

export default function StatusLamaran(){
  const [data, setData] = useState({status: 'Loading...', pesan: ''})
  const [loading, setLoading] = useState(true)
  useEffect(() => {
    axios.get('/api/lamaran/status')
      .then(res => setData(res.data))
      .catch(err => setData({status: 'Gagal memuat status', pesan: ''}))
      .finally(() => setLoading(false))
  }, [])

  return (
    <div>
      <div className="text-sm text-gray-500">Status Lamaran Anda</div>
      <h3 className="text-3xl font-bold text-blue-600 mt-2">{data.status}</h3>
      {data.pesan && <p className="text-sm text-gray-500 mt-3">{data.pesan}</p>}
      {loading && <p className="text-sm text-gray-400 mt-2">Memuat...</p>}
    </div>
  )
}
