# Script untuk memverifikasi tidak ada secrets yang tertinggal di kode

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Verifikasi Tidak Ada Secrets di Kode" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Secrets yang perlu dicek
$secrets = @(
    @{pattern="621317498890-4ohnt5kuac8jilnvhlvmsodjge833psq"; name="Google OAuth Client ID"},
    @{pattern="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"; name="Google OAuth Client Secret"},
    @{pattern="621317498890-4ohnt5kuac8jilnvhlvmsodjge833psq.apps.googleusercontent.com"; name="Google OAuth Client ID (full)"}
)

$foundSecrets = $false

# Exclude directories yang tidak perlu dicek
$excludeDirs = @(
    "vendor",
    "node_modules",
    ".git",
    "storage",
    "bootstrap/cache"
)

# Get all PHP files
$phpFiles = Get-ChildItem -Path . -Filter "*.php" -Recurse | Where-Object {
    $relativePath = $_.FullName.Replace((Get-Location).Path + "\", "").Replace("\", "/")
    $shouldExclude = $false
    foreach ($exclude in $excludeDirs) {
        if ($relativePath -like "$exclude/*" -or $relativePath -eq $exclude) {
            $shouldExclude = $true
            break
        }
    }
    return -not $shouldExclude
}

Write-Host "Memindai $($phpFiles.Count) file PHP..." -ForegroundColor Cyan
Write-Host ""

foreach ($secret in $secrets) {
    Write-Host "Mencari: $($secret.name)" -ForegroundColor Yellow
    
    $found = $false
    foreach ($file in $phpFiles) {
        $content = Get-Content $file.FullName -Raw -ErrorAction SilentlyContinue
        if ($content -and $content -match [regex]::Escape($secret.pattern)) {
            $found = $true
            $foundSecrets = $true
            $relativePath = $file.FullName.Replace((Get-Location).Path + "\", "").Replace("\", "/")
            Write-Host "  ❌ DITEMUKAN di: $relativePath" -ForegroundColor Red
            
            # Show context (2 lines before and after)
            $lines = Get-Content $file.FullName
            for ($i = 0; $i -lt $lines.Length; $i++) {
                if ($lines[$i] -match [regex]::Escape($secret.pattern)) {
                    $start = [Math]::Max(0, $i - 2)
                    $end = [Math]::Min($lines.Length - 1, $i + 2)
                    Write-Host "    Konteks:" -ForegroundColor Gray
                    for ($j = $start; $j -le $end; $j++) {
                        $marker = if ($j -eq $i) { ">>> " } else { "    " }
                        Write-Host "$marker$($j + 1): $($lines[$j])" -ForegroundColor $(if ($j -eq $i) { "Red" } else { "Gray" })
                    }
                    break
                }
            }
        }
    }
    
    if (-not $found) {
        Write-Host "  ✅ Tidak ditemukan" -ForegroundColor Green
    }
    Write-Host ""
}

Write-Host "========================================" -ForegroundColor Cyan
if ($foundSecrets) {
    Write-Host "❌ MASALAH DITEMUKAN!" -ForegroundColor Red
    Write-Host "Silakan hapus secrets yang ditemukan di atas sebelum commit." -ForegroundColor Red
    exit 1
} else {
    Write-Host "✅ SEMUA BERSIH!" -ForegroundColor Green
    Write-Host "Tidak ada secrets yang ditemukan di kode." -ForegroundColor Green
    exit 0
}

