@echo off
setlocal enabledelayedexpansion

:: Get current branch
for /f "tokens=*" %%i in ('git branch --show-current') do set CURRENT_BRANCH=%%i

echo 🚀 Preparing deployment push for branch: !CURRENT_BRANCH!...

:: Check git status
git status

:: Ask to continue
set /p CHOICE="Stage all changes and commit? (y/n): "
if /i "!CHOICE!" neq "y" (
    echo ❌ Deployment cancelled.
    exit /b 0
)

git add .

:: Prompt for commit message
set /p COMMIT_MSG="Enter commit message (press Enter for default): "
if "!COMMIT_MSG!"=="" (
    set COMMIT_MSG=Deploy update: %date% %time%
)

echo 💾 Committing changes...
git commit -m "!COMMIT_MSG!"

echo 📤 Pushing to origin/!CURRENT_BRANCH!...
git push origin !CURRENT_BRANCH!

echo ✅ Pushed successfully! GitHub Action deployment has been triggered.
