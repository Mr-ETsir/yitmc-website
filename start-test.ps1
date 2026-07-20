# YITMC Dev Server Launcher (Windows 11)
# Double-click to run, or right-click -> "Run with PowerShell"

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  YITMC - Yanjing Inst. of Tech. MC Club" -ForegroundColor Yellow
Write-Host "  Dev Server Launcher" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# Go to script directory
Set-Location $PSScriptRoot

# Check Node.js
try {
    $nodeVer = & node -v 2>&1
    $npmVer = & npm -v 2>&1
    Write-Host "[OK] Node.js: $nodeVer" -ForegroundColor Green
    Write-Host "[OK] npm:     $npmVer" -ForegroundColor Green
} catch {
    Write-Host "[ERROR] Node.js not found!" -ForegroundColor Red
    Write-Host "Please install Node.js: https://nodejs.org/" -ForegroundColor Yellow
    Write-Host ""
    Read-Host "Press Enter to exit"
    exit 1
}

Write-Host ""

# Install dependencies if needed
if (-not (Test-Path "node_modules")) {
    Write-Host "[INFO] Installing dependencies..." -ForegroundColor Yellow
    & npm install
    if ($LASTEXITCODE -ne 0) {
        Write-Host "[ERROR] npm install failed!" -ForegroundColor Red
        Read-Host "Press Enter to exit"
        exit 1
    }
    Write-Host "[OK] Dependencies installed." -ForegroundColor Green
    Write-Host ""
}

# Launch dev server
Write-Host "[START] Launching Vite dev server..." -ForegroundColor Cyan
Write-Host "  URL: http://localhost:25565" -ForegroundColor Yellow
Write-Host "  Press Ctrl+C to stop" -ForegroundColor Yellow
Write-Host ""

& npm run dev

# Keep window open after server stops
Write-Host ""
Read-Host "Server stopped. Press Enter to exit"
