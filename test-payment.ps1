#Requires -Version 5.1
$ErrorActionPreference = "Stop"

Write-Host "=== Backup .env asli ===" -ForegroundColor Cyan
Copy-Item .env .env.dev.backup -Force

Write-Host "=== Pasang .env.testing ===" -ForegroundColor Cyan
Copy-Item .env.testing .env -Force

Write-Host "=== Clear cache & reset database ===" -ForegroundColor Cyan
php artisan config:clear
php artisan migrate:fresh --seed

Write-Host "=== Matikan server lama (jika ada) ===" -ForegroundColor Cyan
$connections = Get-NetTCPConnection -LocalPort 8000 -State Listen -ErrorAction SilentlyContinue
foreach ($conn in $connections) {
    Stop-Process -Id $conn.OwningProcess -Force -ErrorAction SilentlyContinue
}
Start-Sleep -Seconds 1

Write-Host "=== Jalankan server (background) ===" -ForegroundColor Cyan
$serverJob = Start-Job -ScriptBlock {
    Set-Location $using:PWD
    php artisan serve
}
Start-Sleep -Seconds 3

Write-Host "=== Verifikasi harga produk sebelum test ===" -ForegroundColor Cyan
try {
    $html = Invoke-WebRequest -Uri "http://127.0.0.1:8000/products/cypress-test-chair" -UseBasicParsing
    if ($html.Content -match "Rp\s*500\.000") {
        Write-Host "OK: Harga terverifikasi 500.000" -ForegroundColor Green
    } else {
        Write-Host "PERINGATAN: Harga TIDAK 500.000! Server mungkin baca database salah." -ForegroundColor Red
    }
} catch {
    Write-Host "Gagal cek harga: $_" -ForegroundColor Red
}

Write-Host "=== Jalankan Cypress khusus 06-total-payment.cy.js ===" -ForegroundColor Cyan
$cypressExitCode = 0
try {
    npx cypress run --spec "cypress/e2e/06-total-payment.cy.js"
    $cypressExitCode = $LASTEXITCODE
} finally {
    Write-Host "=== Matikan server ===" -ForegroundColor Cyan
    Stop-Job $serverJob -ErrorAction SilentlyContinue
    Remove-Job $serverJob -Force -ErrorAction SilentlyContinue
    $connections = Get-NetTCPConnection -LocalPort 8000 -State Listen -ErrorAction SilentlyContinue
    foreach ($conn in $connections) {
        Stop-Process -Id $conn.OwningProcess -Force -ErrorAction SilentlyContinue
    }

    Write-Host "=== Kembalikan .env asli ===" -ForegroundColor Cyan
    Copy-Item .env.dev.backup .env -Force
    Remove-Item .env.dev.backup -Force
    php artisan config:clear
}

Write-Host "=== SELESAI ===" -ForegroundColor Green
if ($cypressExitCode -eq 0) {
    Write-Host "TC-08 LOLOS." -ForegroundColor Green
} else {
    Write-Host "TC-08 GAGAL. Cek log/screenshot di atas." -ForegroundColor Yellow
}