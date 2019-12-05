@echo off
set /p msg=Commit message:

git add .

git commit -m "%msg%"

git push

git tag -d v1.0.0

git push origin :refs/tags/v1.0.0

git tag -a v1.0.0 -m "version 1.0.0"

git push origin v1.0.0