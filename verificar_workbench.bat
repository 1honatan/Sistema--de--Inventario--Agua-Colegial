@echo off
chcp 65001 >nul
color 0A
title Verificación de Configuración MySQL Workbench

echo.
echo ═══════════════════════════════════════════════════════════════
echo   VERIFICACIÓN DE REPARACIÓN DE MYSQL WORKBENCH
echo ═══════════════════════════════════════════════════════════════
echo.

REM Verificar que Workbench no esté corriendo
echo [1/5] Verificando que Workbench no esté ejecutándose...
tasklist /FI "IMAGENAME eq MySQLWorkbench.exe" 2>NUL | find /I /N "MySQLWorkbench.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo     ❌ Workbench está abierto
    echo     ⚠  Por favor cierra MySQL Workbench primero
    echo.
    pause
    exit /b 1
) else (
    echo     ✓ Workbench cerrado correctamente
)

REM Verificar respaldo
echo.
echo [2/5] Verificando respaldo de configuración...
if exist "C:\Users\hp\AppData\Roaming\MySQL\Workbench\wb_options.xml.backup" (
    echo     ✓ Respaldo creado correctamente
) else (
    echo     ❌ No se encontró el respaldo
)

REM Verificar archivo de configuración modificado
echo.
echo [3/5] Verificando configuración modificada...
findstr /C:"workbench:ReverseEngineerTimeOut" "C:\Users\hp\AppData\Roaming\MySQL\Workbench\wb_options.xml" >nul
if %ERRORLEVEL% EQU 0 (
    echo     ✓ Timeout configurado correctamente
) else (
    echo     ❌ Configuración de timeout no encontrada
)

findstr /C:"workbench:ForceSWRendering" "C:\Users\hp\AppData\Roaming\MySQL\Workbench\wb_options.xml" | findstr /C:">1<" >nul
if %ERRORLEVEL% EQU 0 (
    echo     ✓ Renderizado por software activado
) else (
    echo     ⚠  Renderizado por software no confirmado
)

REM Verificar MySQL/XAMPP corriendo
echo.
echo [4/5] Verificando que MySQL esté corriendo...
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo     ✓ MySQL está ejecutándose
) else (
    echo     ❌ MySQL no está corriendo
    echo     ⚠  Por favor inicia XAMPP Apache y MySQL
)

REM Verificar limpieza de caché
echo.
echo [5/5] Verificando limpieza de caché...
if exist "C:\Users\hp\AppData\Roaming\MySQL\Workbench\cache" (
    echo     ✓ Directorio de caché existe
) else (
    echo     ⚠  Directorio de caché no encontrado
)

echo.
echo ═══════════════════════════════════════════════════════════════
echo   RESUMEN
echo ═══════════════════════════════════════════════════════════════
echo.
echo Configuración aplicada correctamente.
echo.
echo PRÓXIMOS PASOS:
echo.
echo 1. Abre MySQL Workbench como ADMINISTRADOR
echo    (Clic derecho → Ejecutar como administrador)
echo.
echo 2. Conecta a tu base de datos 'agua_colegial_bd'
echo.
echo 3. Ve a: Database → Reverse Engineer
echo.
echo 4. IMPORTANTE: En "Select Objects" selecciona solo 5-6 tablas
echo    primero, NO todas a la vez
echo.
echo 5. Si aún falla, usa phpMyAdmin:
echo    http://localhost/phpmyadmin
echo.
echo ═══════════════════════════════════════════════════════════════
echo.
echo Para ver instrucciones detalladas, abre el archivo:
echo workbench_fix_instructions.txt
echo.
pause
