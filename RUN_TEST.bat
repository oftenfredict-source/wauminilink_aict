@echo off
echo ========================================
echo SSH Connection Test
echo ========================================
echo.
echo Please provide your server details:
echo.
set /p SERVER_HOST="Enter Server Host (e.g., wauminilink.co.tz): "
set /p SERVER_USER="Enter SSH Username: "
set /p SERVER_PORT="Enter SSH Port (usually 22): "
echo.
echo Testing connection...
echo.
powershell -ExecutionPolicy Bypass -File "test-ssh-connection.ps1" -ServerHost "%SERVER_HOST%" -ServerUser "%SERVER_USER%" -ServerPort %SERVER_PORT%
echo.
pause










