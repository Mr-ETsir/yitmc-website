# YITMC 测试启动脚本 (PowerShell - Windows 11)
# 如果无法运行，请在 PowerShell 中执行: Set-ExecutionPolicy -Scope CurrentUser RemoteSigned

$ErrorActionPreference = "Stop"
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "   YITMC 燕京理工学院 MC 玩家创作协会" -ForegroundColor Yellow
Write-Host "   官方网站 - 测试环境启动" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# 切换到脚本所在目录
Set-Location $PSScriptRoot

# 检查 Node.js
try {
    $nodeVersion = node -v 2>&1
    $npmVersion = npm -v 2>&1
    Write-Host "[信息] Node.js 版本: $nodeVersion" -ForegroundColor Green
    Write-Host "[信息] npm 版本: $npmVersion" -ForegroundColor Green
} catch {
    Write-Host "[错误] 未检测到 Node.js，请先安装 Node.js" -ForegroundColor Red
    Write-Host "下载地址: https://nodejs.org/" -ForegroundColor Yellow
    Read-Host "按 Enter 键退出"
    exit 1
}

Write-Host ""

# 检查并安装依赖
if (-not (Test-Path "node_modules")) {
    Write-Host "[信息] 正在安装依赖..." -ForegroundColor Yellow
    npm install
    if ($LASTEXITCODE -ne 0) {
        Write-Host "[错误] 依赖安装失败" -ForegroundColor Red
        Read-Host "按 Enter 键退出"
        exit 1
    }
    Write-Host "[完成] 依赖安装成功" -ForegroundColor Green
    Write-Host ""
}

# 启动开发服务器
Write-Host "[启动] 正在启动 Vite 开发服务器..." -ForegroundColor Cyan
Write-Host "[提示] 按 Ctrl+C 停止服务器" -ForegroundColor Yellow
Write-Host "[提示] 服务器地址: http://localhost:25565" -ForegroundColor Yellow
Write-Host ""

npm run dev
