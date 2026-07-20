@echo off
chcp 65001 >nul
title YITMC 测试启动脚本

echo ============================================
echo    YITMC 燕京理工学院 MC 玩家创作协会
echo    官方网站 - 测试环境启动
echo ============================================
echo.

:: 检查 Node.js
where node >nul 2>nul
if %errorlevel% neq 0 (
    echo [错误] 未检测到 Node.js，请先安装 Node.js
    echo 下载地址: https://nodejs.org/
    pause
    exit /b 1
)

:: 显示 Node.js 版本
for /f "tokens=*" %%i in ('node -v') do echo [信息] Node.js 版本: %%i
for /f "tokens=*" %%i in ('npm -v') do echo [信息] npm 版本: %%i
echo.

:: 切换到脚本所在目录
cd /d "%~dp0"

:: 检查 node_modules
if not exist "node_modules\" (
    echo [信息] 正在安装依赖...
    npm install
    if %errorlevel% neq 0 (
        echo [错误] 依赖安装失败
        pause
        exit /b 1
    )
    echo [完成] 依赖安装成功
    echo.
)

:: 启动开发服务器
echo [启动] 正在启动 Vite 开发服务器...
echo [提示] 按 Ctrl+C 停止服务器
echo [提示] 浏览器打开后访问显示的地址（通常为 http://localhost:5173）
echo.

npm run dev

pause
