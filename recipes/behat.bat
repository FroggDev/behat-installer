@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../froggdev/behat/bin/behat
php "%BIN_TARGET%" %*
