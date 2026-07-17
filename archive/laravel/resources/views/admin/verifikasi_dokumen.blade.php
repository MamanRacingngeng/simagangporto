@extends('layouts.admin')

@section('title', 'Verifikasi Dokumen - SIMAGANG')

@section('content')
<h1 style="margin:0 0 24px;font-size:28px;font-weight:700">Verifikasi Dokumen</h1>

@if(session('success'))
    <div style="padding:16px;background:#d1fae5;border-left:4px solid #10b981;border-radius:8px;margin-bottom:24px;color:#065f46">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="padding:16px;background:#fee2e2;border-left:4px solid #ef4444;border-radius:8px;margin-bottom:24px;color:#991b1b">
        {{ session('error') }}
    </div>
@endif

<p style="margin:0 0 24px;color:#6b7280">Daftar permohonan dengan status "Diajukan" yang perlu diverifikasi.</p>

@if(isset($permohonan) && $permohonan->count() > 0)
    <div class="admin-card">
        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse">
                <thead>
                    <tr style="border-bottom:2px solid #e5e7eb">
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Nama Pendaftar</th>
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Email</th>
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Tanggal Pengajuan</th>
                        <th style="padding:12px;text-align:left;font-weight:600;color:#6b7280;font-size:13px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permohonan as $p)
                        <tr style="border-bottom:1px solid #f3f4f6">
                            <td style="padding:12px;color:#1f2937;font-weight:500">{{ $p->user->nama ?? '-' }}</td>
                            <td style="padding:12px;color:#6b7280">{{ $p->user->email ?? '-' }}</td>
                            <td style="padding:12px;color:#6b7280">{{ $p->tanggal_pengajuan ? \Carbon\Carbon::parse($p->tanggal_pengajuan)->format('d/m/Y') : $p->created_at->format('d/m/Y') }}</td>
                            <td style="padding:12px">
                                <a href="{{ route('admin.detail_pendaftar', $p->id) }}" style="padding:6px 12px;background:#2563eb;color:#fff;text-decoration:none;border-radius:6px;font-size:13px;font-weight:600">Verifikasi</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(isset($permohonan) && $permohonan->hasPages())
            <div style="margin-top:24px;display:flex;justify-content:center">
                {{ $permohonan->links() }}
            </div>
        @endif
    </div>
@else
    <div class="admin-card" style="text-align:center;padding:48px">
        <p style="margin:0;color:#6b7280;font-size:16px">Tidak ada permohonan yang perlu diverifikasi.</p>
    </div>
@endif
@endsection

