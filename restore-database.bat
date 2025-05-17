@echo off
echo ===================================
echo Secure Database Restoration Tool
echo ===================================
echo.
echo This tool will restore your database from a secure backup.
echo WARNING: This will overwrite your current database!
echo.
echo You will need the password used to create the backup.
echo You will be prompted to enter it securely.
echo.
echo Starting the restoration process...
echo.

php artisan app:restore-database --interactive

echo.
echo Process completed. Press any key to exit.
pause > nul
