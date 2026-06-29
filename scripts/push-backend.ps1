# Commit et push des changements backend vers origin/main.
# Usage : .\scripts\push-backend.ps1 [-Message "description du commit"]

param(
  [string]$Message = ""
)

$ErrorActionPreference = "Stop"
$repoRoot = Split-Path -Parent (Split-Path -Parent $MyInvocation.MyCommand.Path)
Set-Location $repoRoot

$currentBranch = git rev-parse --abbrev-ref HEAD
if ($currentBranch -ne "main") {
  Write-Error "Exécutez ce script depuis la branche main (actuelle : $currentBranch)."
}

$status = git status --porcelain -- . ":(exclude)frontend"
if ($status) {
  if (-not $Message) {
    $Message = Read-Host "Message de commit backend"
  }
  if (-not $Message.Trim()) {
    Write-Error "Message de commit requis."
  }
  git add -A
  git reset -- frontend/ 2>$null
  git commit -m $Message
}
else {
  Write-Host "Aucun changement backend à committer."
}

Write-Host "Push vers origin/main..."
git push origin main

Write-Host "Synchronisation de la branche frontend avec main..."
git checkout frontend
git merge main -m "Sync backend depuis main"
git checkout main

Write-Host "OK — backend poussé sur main, branche frontend mise à jour localement."
