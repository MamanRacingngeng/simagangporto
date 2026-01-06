<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    /**
     * Clear all user-related cache
     */
    public static function clearUserCache($userId)
    {
        $patterns = [
            "dashboard_user_{$userId}",
            "lamaran_user_{$userId}",
            "riwayat_user_{$userId}",
            "notifikasi_user_{$userId}",
            "dokumen_user_{$userId}",
            "dokumen_user_{$userId}_full",
            "dokumen_lengkap_user_{$userId}",
            "cek_daftar_user_{$userId}",
            "status_lamaran_user_{$userId}",
        ];
        
        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
        }
    }
    
    /**
     * Clear all admin-related cache
     */
    public static function clearAdminCache()
    {
        $patterns = [
            'admin_dashboard_*',
            'admin_activities',
            'admin_line_chart_data',
            'ada_permohonan_baru',
            'jadwal_aktif_*',
            'kuota_aktif_*',
        ];
        
        // Clear specific cache keys
        Cache::forget('admin_activities');
        Cache::forget('admin_line_chart_data');
        Cache::forget('ada_permohonan_baru');
        
        // Clear date-based cache
        $today = now()->toDateString();
        Cache::forget("jadwal_aktif_{$today}");
        Cache::forget("kuota_aktif_{$today}");
    }
    
    /**
     * Clear lowongan cache
     */
    public static function clearLowonganCache()
    {
        $today = now()->toDateString();
        Cache::forget("lowongan_list_{$today}");
        Cache::forget("lowongan_tersedia_{$today}");
    }
    
    /**
     * Clear all permohonan-related cache
     */
    public static function clearPermohonanCache($userId = null)
    {
        if ($userId) {
            self::clearUserCache($userId);
        } else {
            // Clear all user caches (expensive, use sparingly)
            Cache::flush();
        }
        
        self::clearAdminCache();
        self::clearLowonganCache();
        Cache::forget('total_kuota_max');
        Cache::forget('galeri_magang_aktif');
        
        // Clear sidebar dan view caches
        $today = now()->toDateString();
        Cache::forget("sidebar_lowongan_{$today}");
        Cache::forget("riwayat_lowongan_{$today}");
        Cache::forget("lamaran_lowongan_{$today}");
    }
}

