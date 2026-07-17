<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Status Permohonan</title>
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
            border-bottom: 3px solid #3B82F6;
        }
        .header h1 {
            color: #3B82F6;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin: 20px 0;
        }
        .status-box {
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid;
        }
        .status-diverifikasi {
            background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);
            border-color: #3B82F6;
        }
        .status-diterima {
            background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);
            border-color: #10B981;
        }
        .status-ditolak {
            background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);
            border-color: #EF4444;
        }
        .status-revisi {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
            border-color: #F59E0B;
        }
        .status-box h2 {
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        .status-diverifikasi h2 { color: #1E40AF; }
        .status-diterima h2 { color: #065F46; }
        .status-ditolak h2 { color: #991B1B; }
        .status-revisi h2 { color: #92400E; }
        .status-box p {
            margin: 5px 0;
        }
        .status-diverifikasi p { color: #1E3A8A; }
        .status-diterima p { color: #047857; }
        .status-ditolak p { color: #7F1D1D; }
        .status-revisi p { color: #78350F; }
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
        .button {
            display: inline-block;
            padding: 14px 32px;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        .button-primary {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        }
        .button-success {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }
        .alasan-box {
            background-color: #FEF2F2;
            border-left: 4px solid #EF4444;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .alasan-box p {
            margin: 5px 0;
            color: #991B1B;
        }
        .revisi-box {
            background-color: #FFFBEB;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .revisi-box p {
            margin: 5px 0;
            color: #92400E;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            color: #6B7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📧 Update Status Permohonan Magang</h1>
        </div>
        
        <div class="content">
            <p>Yth. <strong>{{ $user->nama }}</strong>,</p>
            
            <p>Kami menginformasikan bahwa status permohonan magang Anda telah diperbarui oleh admin.</p>
            
            @if($statusBaru === 'Diverifikasi')
                <div class="status-box status-diverifikasi">
                    <h2>✅ Status: Diverifikasi</h2>
                    <p>Dokumen Anda telah berhasil diverifikasi oleh admin. Permohonan Anda sedang dalam proses peninjauan lebih lanjut untuk menentukan keputusan akhir.</p>
                </div>
            @elseif($statusBaru === 'Diterima')
                <div class="status-box status-diterima">
                    <h2>🎉 Status: Diterima</h2>
                    <p><strong>Selamat!</strong> Permohonan magang Anda telah disetujui. Kami dengan senang hati menginformasikan bahwa Anda telah diterima untuk program magang.</p>
                </div>
            @elseif($statusBaru === 'Ditolak')
                <div class="status-box status-ditolak">
                    <h2>❌ Status: Ditolak</h2>
                    <p>Kami menginformasikan bahwa permohonan magang Anda tidak dapat disetujui pada kesempatan ini.</p>
                </div>
                @if($alasan)
                    <div class="alasan-box">
                        <p><strong>Alasan Penolakan:</strong></p>
                        <p>{{ $alasan }}</p>
                    </div>
                @endif
            @elseif($statusBaru === 'Revisi')
                <div class="status-box status-revisi">
                    <h2>⚠️ Status: Perlu Revisi</h2>
                    <p>Permohonan Anda memerlukan perbaikan. Silakan periksa catatan revisi di bawah ini dan lakukan perbaikan yang diperlukan.</p>
                </div>
                @if($catatanRevisi)
                    <div class="revisi-box">
                        <p><strong>Catatan Revisi:</strong></p>
                        <p>{{ $catatanRevisi }}</p>
                    </div>
                @endif
            @endif
            
            @if($permohonan && $permohonan->kuotaMagang && $permohonan->kuotaMagang->isNotEmpty())
                @php
                    $kuota = $permohonan->kuotaMagang->first();
                    $jadwal = \App\Models\JadwalMagang::where('periode', $kuota->periode)
                        ->where('posisi', $kuota->posisi)
                        ->first();
                @endphp
                <div class="info-box">
                    <p><strong>Detail Permohonan:</strong></p>
                    <p><strong>Divisi/Posisi:</strong> {{ $kuota->posisi }}</p>
                    <p><strong>Periode:</strong> {{ $kuota->periode }}</p>
                    @if($jadwal)
                        <p><strong>Tanggal Mulai:</strong> {{ $jadwal->tgl_mulai->format('d F Y') }}</p>
                        <p><strong>Tanggal Selesai:</strong> {{ $jadwal->tgl_selesai->format('d F Y') }}</p>
                    @endif
                </div>
            @endif
            
            <div class="button-container">
                <a href="{{ route('riwayat.lamaran') }}" class="button button-primary">
                    Lihat Status Lamaran
                </a>
            </div>
            
            @if($statusBaru === 'Diterima')
                <div style="background-color: #ECFDF5; border-left: 4px solid #10B981; padding: 15px; border-radius: 8px; margin: 20px 0;">
                    <p style="margin: 5px 0; color: #065F46;"><strong>Langkah Selanjutnya:</strong></p>
                    <ul style="margin: 10px 0; padding-left: 20px; color: #047857;">
                        <li>Silakan login ke dashboard untuk melihat informasi lebih lanjut</li>
                        <li>Periksa halaman Panduan Onboarding untuk informasi detail</li>
                        <li>Surat Kerja (SK) akan tersedia setelah admin mengunggahnya</li>
                    </ul>
                </div>
            @elseif($statusBaru === 'Revisi')
                <div style="background-color: #FFFBEB; border-left: 4px solid #F59E0B; padding: 15px; border-radius: 8px; margin: 20px 0;">
                    <p style="margin: 5px 0; color: #92400E;"><strong>Langkah Selanjutnya:</strong></p>
                    <ul style="margin: 10px 0; padding-left: 20px; color: #78350F;">
                        <li>Login ke dashboard Anda</li>
                        <li>Perbaiki dokumen sesuai catatan revisi di atas</li>
                        <li>Unggah ulang dokumen yang telah diperbaiki</li>
                        <li>Ajukan kembali permohonan setelah perbaikan selesai</li>
                    </ul>
                </div>
            @endif
            
            <p>Jika Anda memiliki pertanyaan atau memerlukan bantuan, silakan hubungi admin melalui sistem atau email.</p>
        </div>
        
        <div class="footer">
            <p><strong>SIMAGANG</strong><br>
            Balai Besar Standardisasi dan Pelayanan Jasa Kerajinan dan Batik Yogyakarta</p>
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>

