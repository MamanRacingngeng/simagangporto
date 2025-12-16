import React, {useState} from 'react'
import axios from 'axios'

export default function UploadLaporan(){
  const [file, setFile] = useState(null)
  const [status, setStatus] = useState('')

  function onSubmit(e){
    e.preventDefault()
    if(!file) return setStatus('Pilih file terlebih dahulu')
    const fd = new FormData()
    fd.append('laporan', file)

    setStatus('Mengunggah...')
    axios.post('/api/laporan/upload', fd, {
      headers: {'Content-Type': 'multipart/form-data'}
    }).then(res => {
      setStatus('Berhasil diunggah')
    }).catch(err => {
      setStatus('Gagal mengunggah')
    })
  }

  return (
    <form onSubmit={onSubmit} className="flex flex-col gap-3">
      <input type="file" onChange={e => setFile(e.target.files[0])} />
      <div className="flex items-center gap-3">
        <button type="submit" className="px-4 py-2 rounded-md bg-blue-500 text-white">Unggah</button>
        <div className="text-sm text-gray-500">{status}</div>
      </div>
    </form>
  )
}
