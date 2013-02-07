@echo off
set DIRECTORY=%~dp0
"%PHP_BIN%" %DIRECTORY%\src\index.php %*
