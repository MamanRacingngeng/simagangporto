<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Kerja Tersedia</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #10B981;
        }
        .header h1 {
            color: #10B981;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin: 20px 0;
        }
        .success-box {
            background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
            border-left: 4px solid #10B981;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .success-box h2 {
            color: #065F46;
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        .success-box p {
            color: #047857;
            margin: 5px 0;
        }
        .info-box {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .info-box p {
            margin: 5px 0;
            color: #374151;
        }
        .info-box strong {
            color: #111827;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .download-button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transition: all 0.3s ease;
        }
        .download-button:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            color: #6B7280;
            font-size: 12px;
        }
        .note {
            background-color: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .note p {
            margin: 5px 0;
            color: #92400E;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎉 Surat Kerja Tersedia</h1>
        </div>
        
        <div class="content">
            <p>Yth. <strong>{{ $user->nama }}</strong>,</p>
            
            <p>Kami dengan senang hati menginformasikan bahwa <strong>Surat Kerja (SK)</strong> untuk permohonan magang Anda telah tersedia dan dapat diunduh.</p>
            
            <div class="success-box">
                <h2>✅ Status: Diterima</h2>
                <p>Permohonan magang Anda telah disetujui dan Surat Kerja telah diterbitkan oleh instansi.</p>
            </div>
            
            @if($permohonan && $permohonan->kuotaMagang && $permohonan->kuotaMagang->isNotEmpty())
                @php
                    $kuota = $permohonan->kuotaMagang->first();
                    $jadwal = \App\Models\JadwalMagang::where('periode', $kuota->periode)
                        ->where('posisi', $kuota->posisi)
                        ->first();
                @endphp
                <div class="info-box">
                    <p><strong>Divisi/Posisi:</strong> {{ $kuota->posisi }}</p>
                    <p><strong>Periode:</strong> {{ $kuota->periode }}</p>
                    @if($jadwal)
                        <p><strong>Tanggal Mulai:</strong> {{ $jadwal->tgl_mulai->format('d F Y') }}</p>
                        <p><strong>Tanggal Selesai:</strong> {{ $jadwal->tgl_selesai->format('d F Y') }}</p>
                    @endif
                </div>
            @endif
            
            <div class="button-container">
                <a href="{{ $downloadUrl }}" class="download-button">
                    📥 Download Surat Kerja (SK)
                </a>
            </div>
            
            <div class="note">
                <p><strong>📌 Catatan Penting:</strong></p>
                <p>• Surat Kerja ini adalah dokumen resmi dari instansi</p>
                <p>• Simpan file ini dengan baik untuk keperluan administrasi</p>
                <p>• Anda juga dapat mengunduh Surat Kerja melalui dashboard di halaman Status Lamaran atau Panduan Onboarding</p>
            </div>
            
            <p>Jika Anda memiliki pertanyaan atau memerlukan bantuan, silakan hubungi admin melalui sistem atau email.</p>
            
            <p>Terima kasih dan selamat atas diterimanya permohonan magang Anda!</p>
        </div>
        
        <div class="footer">
            <p><strong>SIMAGANG</strong><br>
            Balai Besar Standardisasi dan Pelayanan Jasa Kerajinan dan Batik Yogyakarta</p>
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>

