<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PermohonanMagang;
use App\Models\Dokumen;
use App\Models\KuotaMagang;
use App\Models\JadwalMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Helpers\CacheHelper;

class PendaftarController extends Controller
{
    // Mengisi Data Diri
    public function isiDataDiri()
    {
        $user = Auth::user();
        return view('pendaftar.isi_data_diri', compact('user'));
    }

    public function updateDataDiri(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255', // Sesuai ERD: nama bukan name
            'no_telepon' => 'nullable|string',
            'instansi' => 'nullable|string', // Sesuai ERD: instansi (bukan universitas/jurusan terpisah)
        ]);

        $user = Auth::user();
        $user->update($request->only([
            'nama', // Sesuai ERD: nama bukan name
            'no_telepon',
            'instansi', // Sesuai ERD: instansi
        ]));
        
        // Clear user cache
        CacheHelper::clearUserCache($user->id);

        return back()->with('success', 'Data diri berhasil diperbarui.');
    }

    // Unggah Dokumen
    public function unggahDokumen()
    {
        $user = Auth::user();
        // OPTIMASI: Gunakan select spesifik dan eager loading untuk menghindari N+1
        $dokumen = Dokumen::where('user_id', $user->id)
            ->select('id', 'user_id', 'cv', 'surat_pengantar', 'proposal', 'tanggal_upload')
            ->get();
        $permohonan = PermohonanMagang::where('user_id', $user->id)
            ->select('id', 'user_id', 'status', 'dokumen_id')
            ->first();

        return view('pendaftar.unggah_dokumen', compact('dokumen', 'permohonan'));
    }

    /**
     * Activity Diagram - Pendaftaran Magang
     * User mengunggah dokumen (CV, Surat Pengantar, Proposal)
     */
    public function storeDokumen(Request $request)
    {
        try {
            // Activity Diagram: User mengunggah dokumen (CV, Surat Pengantar, Proposal)
            // Terima file secara parsial: user boleh mengunggah satu atau beberapa file
            $request->validate([
                'cv' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'surat_pengantar' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'proposal' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            ], [
                '*.mimes' => 'File harus berformat PDF, DOC, atau DOCX.',
                '*.max' => 'Ukuran file maksimal 5MB.',
            ]);

            $user = Auth::user();
        
        // Ambil atau buat record dokumen untuk user ini
        $dokumen = Dokumen::where('user_id', $user->id)->first();
        if (!$dokumen) {
            $dokumen = new Dokumen();
            $dokumen->user_id = $user->id;
        }

        $updated = false;

        // Upload CV jika ada
        if ($request->hasFile('cv')) {
            if ($dokumen->cv && Storage::disk('public')->exists($dokumen->cv)) {
                Storage::disk('public')->delete($dokumen->cv);
            }
            $file = $request->file('cv');
            $filename = 'cv_' . time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('dokumen', $filename, 'public');
            $dokumen->cv = $path;
            $updated = true;
        }

        // Upload Surat Pengantar jika ada
        if ($request->hasFile('surat_pengantar')) {
            if ($dokumen->surat_pengantar && Storage::disk('public')->exists($dokumen->surat_pengantar)) {
                Storage::disk('public')->delete($dokumen->surat_pengantar);
            }
            $file = $request->file('surat_pengantar');
            $filename = 'surat_pengantar_' . time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('dokumen', $filename, 'public');
            $dokumen->surat_pengantar = $path;
            $updated = true;
        }

        // Upload Proposal jika ada
        if ($request->hasFile('proposal')) {
            if ($dokumen->proposal && Storage::disk('public')->exists($dokumen->proposal)) {
                Storage::disk('public')->delete($dokumen->proposal);
            }
            $file = $request->file('proposal');
            $filename = 'proposal_' . time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('dokumen', $filename, 'public');
            $dokumen->proposal = $path;
            $updated = true;
        }

        if ($updated) {
            $dokumen->tanggal_upload = now()->toDateString();
            $dokumen->save();
        } else {
            return back()->withErrors(['error' => 'Tidak ada file yang dipilih untuk diunggah.'])->withInput();
        }
        
        // Cek apakah sudah ada permohonan yang terhubung dengan dokumen ini
        $permohonanAda = PermohonanMagang::where('user_id', $user->id)
            ->where('dokumen_id', $dokumen->id)
            ->exists();
        
        // Cek apakah ada permohonan dengan status "Revisi" - jika ada, ubah status menjadi "Diajukan"
        $permohonanRevisi = PermohonanMagang::where('user_id', $user->id)
            ->where('status', 'Revisi')
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($permohonanRevisi) {
            // Jika user mengunggah dokumen setelah mendapat revisi, ubah status menjadi "Diajukan"
            $permohonanRevisi->update([
                'status' => 'Diajukan',
                'catatan_revisi' => null, // Hapus catatan revisi karena sudah diperbaiki
            ]);
            
            // Clear cache untuk memastikan perubahan langsung terlihat
            CacheHelper::clearUserCache($user->id);
        }
        
        if (!$permohonanAda) {
            // Jika belum ada permohonan, cek apakah ada permohonan tanpa dokumen yang bisa dihubungkan
            $permohonanTanpaDokumen = PermohonanMagang::where('user_id', $user->id)
                ->whereNull('dokumen_id')
                ->first();
            
            if ($permohonanTanpaDokumen) {
                // Hubungkan dokumen ke permohonan yang sudah ada
                $permohonanTanpaDokumen->update(['dokumen_id' => $dokumen->id]);
            }
        }

            // Clear cache when document is uploaded (before response)
            CacheHelper::clearUserCache($user->id);
            \Illuminate\Support\Facades\Cache::forget("lamaran_user_{$user->id}");
            \Illuminate\Support\Facades\Cache::forget("dashboard_user_{$user->id}");
            \Illuminate\Support\Facades\Cache::forget("riwayat_user_{$user->id}");
            
            // Refresh dokumen dari database untuk memastikan data terbaru
            $dokumen->refresh();
            
            // Balasan berbeda untuk AJAX vs form biasa
            if ($request->ajax() || $request->wantsJson()) {
                // Hitung dokumen yang terunggah
                $dokumenTerunggah = [];
                if (!empty($dokumen->cv)) {
                    $dokumenTerunggah[] = 'cv';
                }
                if (!empty($dokumen->surat_pengantar)) {
                    $dokumenTerunggah[] = 'surat_pengantar';
                }
                if (!empty($dokumen->proposal)) {
                    $dokumenTerunggah[] = 'proposal';
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Dokumen berhasil diunggah.',
                    'dokumen' => [
                        'cv' => $dokumen->cv,
                        'surat_pengantar' => $dokumen->surat_pengantar,
                        'proposal' => $dokumen->proposal,
                        'tanggal_upload' => $dokumen->tanggal_upload ? $dokumen->tanggal_upload->format('d F Y, H:i') : null,
                    ],
                    'dokumen_terunggah' => $dokumenTerunggah,
                    'total_terunggah' => count($dokumenTerunggah),
                    'lengkap' => count($dokumenTerunggah) === 3
                ]);
            }

            return redirect()->route('lamaran')
                ->with('success', 'Dokumen berhasil diunggah.')
                ->with('dokumen_baru_diunggah', true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing dokumen: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat mengunggah dokumen. Pastikan file tidak corrupt dan ukurannya tidak melebihi 5MB per file. Silakan coba lagi.'
            ])->withInput();
        }
    }

    public function deleteDokumen($id)
    {
        $dokumen = Dokumen::where('user_id', Auth::id())->findOrFail($id);
        
        // Hapus file jika ada
        if ($dokumen->cv && Storage::disk('public')->exists($dokumen->cv)) {
            Storage::disk('public')->delete($dokumen->cv);
        }
        if ($dokumen->surat_pengantar && Storage::disk('public')->exists($dokumen->surat_pengantar)) {
            Storage::disk('public')->delete($dokumen->surat_pengantar);
        }
        if ($dokumen->proposal && Storage::disk('public')->exists($dokumen->proposal)) {
            Storage::disk('public')->delete($dokumen->proposal);
        }

        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    // Hapus dokumen per field (CV, Surat Pengantar, atau Proposal)
    public function deleteDokumenField(Request $request, $id, $field)
    {
        try {
            $dokumen = Dokumen::where('user_id', Auth::id())->findOrFail($id);
            
            // Validasi field yang diizinkan
            $allowedFields = ['cv', 'surat_pengantar', 'proposal'];
            if (!in_array($field, $allowedFields)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Field dokumen tidak valid.'], 400);
                }
                return back()->withErrors(['error' => 'Field dokumen tidak valid.']);
            }
            
            // Hapus file dari storage jika ada
            if ($dokumen->$field && Storage::disk('public')->exists($dokumen->$field)) {
                Storage::disk('public')->delete($dokumen->$field);
            }
            
            // Update field menjadi null
            $dokumen->update([$field => null]);
            
            // Refresh dokumen dari database untuk memastikan data terbaru
            $dokumen->refresh();
            
            // Jika semua dokumen sudah dihapus, hapus record dokumen
            if (empty($dokumen->cv) && empty($dokumen->surat_pengantar) && empty($dokumen->proposal)) {
                $dokumen->delete();
                
                // Clear cache
                CacheHelper::clearUserCache(Auth::id());
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => true, 'message' => 'Semua dokumen telah dihapus.']);
                }
                return redirect()->route('lamaran')
                    ->with('success', 'Semua dokumen telah dihapus.');
            }
            
            $fieldNames = [
                'cv' => 'CV (Curriculum Vitae)',
                'surat_pengantar' => 'Surat Pengantar',
                'proposal' => 'Proposal'
            ];
            
            // Clear cache
            CacheHelper::clearUserCache(Auth::id());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => $fieldNames[$field] . ' berhasil dihapus. Anda dapat mengunggah ulang dokumen ini.',
                    'remaining_docs' => [
                        'cv' => !empty($dokumen->cv),
                        'surat_pengantar' => !empty($dokumen->surat_pengantar),
                        'proposal' => !empty($dokumen->proposal)
                    ]
                ]);
            }

            return redirect()->route('lamaran')
                ->with('success', $fieldNames[$field] . ' berhasil dihapus. Anda dapat mengunggah ulang dokumen ini.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Dokumen not found: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Dokumen tidak ditemukan.'], 404);
            }
            return back()->withErrors(['error' => 'Dokumen tidak ditemukan.']);
        } catch (\Exception $e) {
            Log::error('Error deleting dokumen field: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus dokumen. Silakan coba lagi.'], 500);
            }
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menghapus dokumen. Silakan coba lagi.'
            ]);
        }
    }

    // Ajukan Permohonan Magang
    public function ajukanPermohonanMagang()
    {
        $user = Auth::user();
        // OPTIMASI: Hanya ambil kolom yang diperlukan dan gunakan select spesifik
        $kuota = KuotaMagang::select('id', 'periode', 'posisi', 'kuota_max', 'kuota_terpakai')->get();
        $jadwal = JadwalMagang::select('id', 'periode', 'posisi', 'tgl_mulai', 'tgl_selesai')->get();
        $permohonan = PermohonanMagang::where('user_id', $user->id)
            ->select('id', 'user_id', 'status', 'dokumen_id')
            ->first();
        $dokumen = Dokumen::where('user_id', $user->id)
            ->select('id', 'user_id', 'cv', 'surat_pengantar', 'proposal')
            ->first();

        return view('pendaftar.ajukan_permohonan_magang', compact('kuota', 'jadwal', 'permohonan', 'dokumen'));
    }

    /**
     * Activity Diagram - Pendaftaran Magang
     * User mengirim permohonan magang
     * Decision: Dokumen valid?
     *   - Ya: Sistem menyimpan permohonan → Status = "Diajukan"
     *   - Tidak: Sistem meminta user memperbaiki dokumen
     */
    public function storePermohonanMagang(Request $request)
    {
        $user = Auth::user();

        // Cek apakah user bisa mendaftar (satu akun hanya 1 divisi, kecuali masa berlaku habis dan ditolak)
        $cekDaftar = PermohonanMagang::cekBisaDaftar($user->id);
        
        if (!$cekDaftar['bisa_daftar']) {
            return back()->withErrors(['error' => $cekDaftar['alasan']])->withInput();
        }

        // Cek apakah user sudah mengunggah dokumen
        $dokumen = Dokumen::where('user_id', $user->id)->first();
        
        if (!$dokumen) {
            return back()->withErrors(['error' => 'Silakan unggah dokumen terlebih dahulu (CV, Surat Pengantar, Proposal).'])
                ->withInput();
        }

        // Decision: Dokumen valid?
        // Validasi dokumen lengkap (CV, Surat Pengantar, Proposal)
        $dokumenLengkap = true;
        $pesanError = [];

        if (empty($dokumen->cv)) {
            $dokumenLengkap = false;
            $pesanError[] = 'CV belum diunggah.';
        }

        if (empty($dokumen->surat_pengantar)) {
            $dokumenLengkap = false;
            $pesanError[] = 'Surat Pengantar belum diunggah.';
        }

        if (empty($dokumen->proposal)) {
            $dokumenLengkap = false;
            $pesanError[] = 'Proposal belum diunggah.';
        }

        // Decision: Dokumen valid? → Tidak
        if (!$dokumenLengkap) {
            // Sistem meminta user memperbaiki dokumen
            return back()->withErrors(['error' => 'Dokumen tidak lengkap. ' . implode(' ', $pesanError)])
                ->withInput();
        }

        // Decision: Dokumen valid? → Ya
        // Sistem menyimpan permohonan
        PermohonanMagang::create([
            'user_id' => $user->id,
            'dokumen_id' => $dokumen->id,
            'tanggal_pengajuan' => now()->toDateString(),
            'status' => 'Diajukan', // Status = "Diajukan"
        ]);
        
        // Cache will be cleared by Observer, but clear user cache too
        CacheHelper::clearUserCache($user->id);

        return redirect()->route('pendaftar.status_permohonan')
            ->with('success', 'Permohonan magang berhasil diajukan. Status: Diajukan');
    }

    // Lihat Status Permohonan
    public function lihatStatusPermohonan()
    {
        $user = Auth::user();
        $permohonan = PermohonanMagang::where('user_id', $user->id)
            ->with(['dokumen'])
            ->first();

        return view('pendaftar.status_permohonan', compact('permohonan'));
    }
}
