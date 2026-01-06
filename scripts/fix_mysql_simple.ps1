# Script Sederhana untuk Memperbaiki MySQL XAMPP
# Jalankan sebagai Administrator

Write-Host "=== Perbaikan MySQL XAMPP ===" -ForegroundColor Cyan
Write-Host ""

# Lokasi XAMPP
$xamppPath = "C:\xampp"
$mysqlDataPath = "$xamppPath\mysql\data"
$mysqlBinPath = "$xamppPath\mysql\bin"

# Step 1: Stop semua proses MySQL
Write-Host "[1/5] Menghentikan proses MySQL..." -ForegroundColor Yellow
Get-Process | Where-Object {$_.ProcessName -match "mysql|mysqld"} | ForEach-Object {
    Stop-Process -Id $_.Id -Force -ErrorAction SilentlyContinue
}
Start-Sleep -Seconds 2
Write-Host "    ✓ Selesai" -ForegroundColor Green
Write-Host ""

# Step 2: Buat backup
Write-Host "[2/5] Membuat backup..." -ForegroundColor Yellow
$backupDir = "$mysqlDataPath.backup_$(Get-Date -Format 'yyyyMMdd_HHmmss')"
if (Test-Path $mysqlDataPath) {
    try {
        New-Item -ItemType Directory -Path $backupDir -Force | Out-Null
        Copy-Item -Path "$mysqlDataPath\*" -Destination $backupDir -Recurse -Force -ErrorAction SilentlyContinue
        Write-Host "    ✓ Backup dibuat: $backupDir" -ForegroundColor Green
    }
    catch {
        Write-Host "    ⚠ Backup tidak lengkap, tapi file penting sudah dicadangkan" -ForegroundColor Yellow
    }
}
Write-Host ""

# Step 3: Hapus file log InnoDB yang corrupt
Write-Host "[3/5] Menghapus file log InnoDB yang mungkin corrupt..." -ForegroundColor Yellow
$filesToRemove = @(
    "$mysqlDataPath\ib_logfile0",
    "$mysqlDataPath\ib_logfile1",
    "$mysqlDataPath\ibtmp1"
)

foreach ($file in $filesToRemove) {
    if (Test-Path $file) {
        try {
            Remove-Item $file -Force -ErrorAction Stop
            Write-Host "    ✓ Dihapus: $(Split-Path $file -Leaf)" -ForegroundColor Green
        }
        catch {
            Write-Host "    ⚠ Gagal menghapus: $(Split-Path $file -Leaf)" -ForegroundColor Yellow
        }
    }
}
Write-Host "    Note: File-file ini akan dibuat ulang otomatis saat MySQL start" -ForegroundColor Gray
Write-Host ""

# Step 4: Perbaiki permission
Write-Host "[4/5] Memperbaiki permission folder..." -ForegroundColor Yellow
$currentUser = [System.Security.Principal.WindowsIdentity]::GetCurrent().Name
try {
    icacls $mysqlDataPath /grant "${currentUser}:F" /T /C 2>&1 | Out-Null
    Write-Host "    ✓ Permission diperbaiki" -ForegroundColor Green
}
catch {
    Write-Host "    ⚠ Gagal memperbaiki permission (tidak masalah jika sudah punya akses)" -ForegroundColor Yellow
}
Write-Host ""

# Step 5: Instruksi
Write-Host "[5/5] Selesai!" -ForegroundColor Green
Write-Host ""
Write-Host "=== Langkah Selanjutnya ===" -ForegroundColor Cyan
Write-Host "1. Buka XAMPP Control Panel" -ForegroundColor White
Write-Host "2. Klik 'Start' pada MySQL" -ForegroundColor White
Write-Host "3. Periksa apakah MySQL berjalan dengan baik" -ForegroundColor White
Write-Host ""
Write-Host "Jika masih error, coba solusi berikut:" -ForegroundColor Yellow
Write-Host ""
Write-Host "SOLUSI A: Reset dengan initialize" -ForegroundColor Cyan
Write-Host "  Buka CMD sebagai Administrator, lalu jalankan:" -ForegroundColor Gray
Write-Host "  cd $mysqlBinPath" -ForegroundColor Gray
Write-Host "  mysqld --initialize-insecure --datadir=$mysqlDataPath" -ForegroundColor Gray
Write-Host ""
Write-Host "SOLUSI B: Cek log error detail" -ForegroundColor Cyan
Write-Host "  Buka file: $mysqlDataPath\mysql_error.log" -ForegroundColor Gray
Write-Host ""
Write-Host "Backup location: $backupDir" -ForegroundColor Cyan
Write-Host ""
