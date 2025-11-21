@echo off
REM ============================================
REM Laravel Scheduler - Agua Colegial
REM ============================================
REM
REM Este archivo ejecuta el scheduler de Laravel
REM que a su vez ejecuta las tareas programadas
REM (backups automáticos, limpieza de logs, etc.)
REM
REM IMPORTANTE: Debe ejecutarse cada 1 minuto
REM usando el Programador de Tareas de Windows
REM
REM ============================================

cd C:\xampp\htdocs\agua_colegial
C:\xampp\php\php.exe artisan schedule:run >> NUL 2>&1
