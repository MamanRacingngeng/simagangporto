<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LamaranController extends Controller
{
    public function status()
    {
        return response()->json([
            'status' => 'Menunggu Proses',
            'pesan' => 'Lamaran Anda sedang ditinjau. Mohon bersabar ya!'
        ]);
    }

    public function uploadLaporan(Request $request)
    {
        $request->validate([
            'laporan' => 'required|file|max:2048', // max in kilobytes (2MB)
        ]);

        $file = $request->file('laporan');
        $filename = Str::random(12) . '_' . preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $file->getClientOriginalName());
        $path = $file->storeAs('laporan', $filename, 'public');

        return response()->json([
            'success' => true,
            'path' => Storage::url($path),
        ]);
    }
}
