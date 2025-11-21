@echo off
REM ============================================
REM Backup Semanal Manual - Agua Colegial
REM ============================================
REM
REM Este archivo ejecuta un backup manual
REM de la base de datos con compresión.
REM
REM ALTERNATIVA: Si no quieres usar Laravel
REM Scheduler, puedes programar este archivo
REM directamente en el Programador de Tareas
REM de Windows para que se ejecute semanalmente.
REM
REM ============================================

cd C:\xampp\htdocs\agua_colegial
C:\xampp\php\php.exe artisan backup:database --compress --keep-days=90

echo.
echo ============================================
echo Backup completado
echo ============================================
echo Ubicacion: C:\xampp\htdocs\agua_colegial\storage\app\backups
echo.
pause
