@ECHO off
SELENIUM="vendor\froggdev\behat-installer\bin\seleniumIE\selenium.bat"

REM check if can find selenium
IF NOT EXIST %SELENIUM% (
ECHO "Cannot find %SELENIUM%"
PAUSE
EXIT 1
)

REM Start selenium if exist
START "Selenium" cmd /c %SELENIUM%



