@echo off
title YITMC Test Server

echo.
echo ============================================
echo   YITMC - Yanjing Inst. of Tech. MC Club
echo   Dev Server Launcher
echo ============================================
echo.

REM Check Node.js
where node >nul 2>nul
if %errorlevel% neq 0 goto :no_node

echo [OK] Node.js:
node -v
echo [OK] npm:
npm -v
echo.

REM Go to script directory
cd /d "%~dp0"

REM Install dependencies if needed
if exist "node_modules\" goto :start_server

echo [INFO] Installing dependencies...
call npm install
if %errorlevel% neq 0 goto :install_fail
echo [OK] Dependencies installed.
echo.

:start_server
echo [START] Launching Vite dev server...
echo   URL: http://localhost:25565
echo   Press Ctrl+C to stop
echo.
call npm run dev
goto :end

:no_node
echo [ERROR] Node.js not found!
echo Please install Node.js first:
echo https://nodejs.org/
goto :end

:install_fail
echo [ERROR] npm install failed!
goto :end

:end
echo.
pause
