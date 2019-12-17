@ECHO OFF
SETLOCAL DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../froggdev/behat/behat/bin/behat

SET SELENIUM="vendor\froggdev\behat-installer\bin\seleniumIE\selenium.bat"

REM check if can find selenium
IF NOT EXIST %SELENIUM% (
ECHO "Cannot find %SELENIUM%"
PAUSE
EXIT 1
)

REM check if can find behat
IF NOT EXIST %BIN_TARGET% (
ECHO "Cannot find %BIN_TARGET%"
PAUSE
EXIT 1
)

REM Close the selenium window if already exist
Taskkill /FI "WINDOWTITLE eq selenium" > NUL

REM Start selenium in new window
START "selenium" cmd.exe /C %SELENIUM%

REM Start behat scenario
php "%BIN_TARGET%" %*

REM Close the selenium window
Taskkill /FI "WINDOWTITLE eq selenium" > NUL

REM Cleanning if didn't closed properly
Taskkill /IM chromedriver.exe /F /FI "status eq running" > NUL
