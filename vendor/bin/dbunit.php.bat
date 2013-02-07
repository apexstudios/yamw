@ECHO OFF
SET BIN_TARGET=%~dp0\"../phpunit/dbunit"\dbunit.php
php "%BIN_TARGET%" %*
