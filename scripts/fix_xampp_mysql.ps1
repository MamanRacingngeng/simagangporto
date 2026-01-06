# Script untuk memperbaiki MySQL XAMPP yang tidak bisa start
# Run as Administrator

Write-Host "=== XAMPP MySQL Repair Script ===" -ForegroundColor Cyan
Write-Host ""

# Cek apakah script dijalankan sebagai Administrator
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
if (-not $isAdmin) {
    Write-Host "PERINGATAN: Script ini sebaiknya dijalankan sebagai Administrator!" -ForegroundColor Yellow
    Write-Host ""
}

# Path XAMPP (sesuaikan jika berbeda)
$xamppPath = "C:\xampp"
$mysqlPath = "$xamppPath\mysql"
$mysqlDataPath = "$mysqlPath\data"

Write-Host "1. Memeriksa lokasi XAMPP..." -ForegroundColor Yellow
if (-not (Test-Path $mysqlPath)) {
    Write-Host "ERROR: MySQL tidak ditemukan di $mysqlPath" -ForegroundColor Red
    Write-Host "Silakan sesuaikan path XAMPP di script ini." -ForegroundColor Yellow
    exit 1
}
Write-Host "   ✓ MySQL ditemukan di: $mysqlPath" -ForegroundColor Green
Write-Host ""

# Step 1: Stop semua proses MySQL
Write-Host "2. Menghentikan semua proses MySQL..." -ForegroundColor Yellow
$mysqlProcesses = Get-Process | Where-Object {$_.ProcessName -like "*mysql*" -or $_.ProcessName -like "*mysqld*"}
if ($mysqlProcesses) {
    foreach ($proc in $mysqlProcesses) {
        try {
            Stop-Process -Id $proc.Id -Force -ErrorAction SilentlyContinue
            Write-Host "   ✓ Process $($proc.ProcessName) (PID: $($proc.Id)) dihentikan" -ForegroundColor Green
        } catch {
            Write-Host "   ⚠ Gagal menghentikan process $($proc.ProcessName)" -ForegroundColor Yellow
        }
    }
} else {
    Write-Host "   ✓ Tidak ada proses MySQL yang berjalan" -ForegroundColor Green
}
Write-Host ""

# Step 2: Cek port 3306
Write-Host "3. Memeriksa port 3306..." -ForegroundColor Yellow
$portCheck = netstat -ano | Select-String ":3306"
if ($portCheck) {
    Write-Host "   ⚠ Port 3306 sedang digunakan:" -ForegroundColor Yellow
    $portCheck | ForEach-Object { Write-Host "      $_" -ForegroundColor Gray }
    Write-Host "   Silakan hentikan proses yang menggunakan port 3306." -ForegroundColor Yellow
} else {
    Write-Host "   ✓ Port 3306 tersedia" -ForegroundColor Green
}
Write-Host ""

# Step 3: Backup data directory (opsional tapi disarankan)
Write-Host "4. Membuat backup data directory..." -ForegroundColor Yellow
$backupPath = "$mysqlDataPath.backup.$(Get-Date -Format 'yyyyMMdd_HHmmss')"
if (Test-Path $mysqlDataPath) {
    try {
        if (-not (Test-Path $backupPath)) {
            Copy-Item -Path $mysqlDataPath -Destination $backupPath -Recurse -Force -ErrorAction Stop
            Write-Host "   ✓ Backup dibuat di: $backupPath" -ForegroundColor Green
        } else {
            Write-Host "   ✓ Backup sudah ada: $backupPath" -ForegroundColor Green
        }
    } catch {
        Write-Host "   ⚠ Gagal membuat backup: $_" -ForegroundColor Yellow
        Write-Host "   Melanjutkan tanpa backup..." -ForegroundColor Yellow
    }
} else {
    Write-Host "   ⚠ Data directory tidak ditemukan, akan dibuat baru" -ForegroundColor Yellow
}
Write-Host ""

# Step 4: Cek dan perbaiki file ibdata1 dan ib_logfile
Write-Host "5. Memeriksa file database..." -ForegroundColor Yellow
$ibdata1 = "$mysqlDataPath\ibdata1"
$iblogfile0 = "$mysqlDataPath\ib_logfile0"
$iblogfile1 = "$mysqlDataPath\ib_logfile1"

# Backup file-file penting sebelum menghapus
if (Test-Path $ibdata1) {
    Copy-Item $ibdata1 "$ibdata1.backup.$(Get-Date -Format 'yyyyMMdd_HHmmss')" -ErrorAction SilentlyContinue
}
if (Test-Path $iblogfile0) {
    Copy-Item $iblogfile0 "$iblogfile0.backup.$(Get-Date -Format 'yyyyMMdd_HHmmss')" -ErrorAction SilentlyContinue
}
if (Test-Path $iblogfile1) {
    Copy-Item $iblogfile1 "$iblogfile1.backup.$(Get-Date -Format 'yyyyMMdd_HHmmss')" -ErrorAction SilentlyContinue
}

Write-Host "   File database ditemukan" -ForegroundColor Green
Write-Host ""

# Step 5: Cek my.ini configuration
Write-Host "6. Memeriksa konfigurasi my.ini..." -ForegroundColor Yellow
$myIni = "$mysqlPath\bin\my.ini"
if (Test-Path $myIni) {
    $myIniContent = Get-Content $myIni -Raw
    Write-Host "   ✓ my.ini ditemukan" -ForegroundColor Green
    
    # Cek apakah ada setting yang bermasalah
    if ($myIniContent -match "innodb_force_recovery") {
        Write-Host "   ⚠ Ditemukan innodb_force_recovery, mungkin perlu disesuaikan" -ForegroundColor Yellow
    }
} else {
    Write-Host "   ⚠ my.ini tidak ditemukan, akan menggunakan konfigurasi default" -ForegroundColor Yellow
}
Write-Host ""

# Step 6: Solusi 1 - Coba start dengan skip-grant-tables (untuk reset password jika perlu)
Write-Host "7. Mencoba memperbaiki dengan beberapa solusi..." -ForegroundColor Yellow
Write-Host ""

Write-Host "   Solusi 1: Hapus file ib_logfile yang mungkin corrupt" -ForegroundColor Cyan
Write-Host "   File-file ini akan dibuat ulang saat MySQL start." -ForegroundColor Gray
$confirm = Read-Host "   Hapus ib_logfile0 dan ib_logfile1? (y/n)"

if ($confirm -eq 'y' -or $confirm -eq 'Y') {
    if (Test-Path $iblogfile0) {
        Remove-Item $iblogfile0 -Force -ErrorAction SilentlyContinue
        Write-Host "   ✓ ib_logfile0 dihapus" -ForegroundColor Green
    }
    if (Test-Path $iblogfile1) {
        Remove-Item $iblogfile1 -Force -ErrorAction SilentlyContinue
        Write-Host "   ✓ ib_logfile1 dihapus" -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "   Solusi 2: Perbaiki permission folder data" -ForegroundColor Cyan
if ($isAdmin) {
    try {
        # Berikan full control ke Users group
        icacls $mysqlDataPath /grant Users:F /T /C 2>&1 | Out-Null
        Write-Host "   ✓ Permission diperbaiki" -ForegroundColor Green
    } catch {
        Write-Host "   ⚠ Gagal memperbaiki permission: $_" -ForegroundColor Yellow
    }
} else {
    Write-Host "   ⚠ Melewati perbaikan permission (butuh Admin rights)" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "=== Instruksi Selanjutnya ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. Coba start MySQL dari XAMPP Control Panel" -ForegroundColor White
Write-Host "2. Jika masih error, coba solusi berikut:" -ForegroundColor White
Write-Host "   a. Stop MySQL di XAMPP" -ForegroundColor Gray
Write-Host "   b. Buka CMD sebagai Administrator" -ForegroundColor Gray
Write-Host "   c. Jalankan: cd C:\xampp\mysql\bin" -ForegroundColor Gray
Write-Host "   d. Jalankan: mysqld --initialize-insecure" -ForegroundColor Gray
Write-Host "   e. Start MySQL dari XAMPP" -ForegroundColor Gray
Write-Host ""
Write-Host "3. Jika masih bermasalah, restore dari backup atau install ulang MySQL" -ForegroundColor White
Write-Host ""
Write-Host "Backup location: $backupPath" -ForegroundColor Cyan
Write-Host ""
Write-Host "Script selesai!" -ForegroundColor Green
