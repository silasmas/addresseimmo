# Commit et push des changements frontend vers origin/frontend.
# Usage : .\scripts\push-frontend.ps1 [-Message "description du commit"]

param(
  [string]$Message = ""
)

$ErrorActionPreference = "Stop"
$repoRoot = Split-Path -Parent (Split-Path -Parent $MyInvocation.MyCommand.Path)
Set-Location $repoRoot

if (-not (Test-Path "frontend/package.json")) {
  Write-Host "Dossier frontend/ absent — exécution de setup-frontend-local.ps1..."
  & (Join-Path $repoRoot "scripts\setup-frontend-local.ps1")
}

$stashCreated = $false
$backendDirty = git status --porcelain -- . ":(exclude)frontend"
if ($backendDirty) {
  Write-Host "Changements backend non commités — mise de côté temporaire..."
  git stash push -m "push-frontend-auto-stash" -- . ":(exclude)frontend"
  $stashCreated = $true
}

git checkout frontend
git merge main -m "Sync backend depuis main"
& (Join-Path $repoRoot "scripts\ensure-frontend-tracked.ps1")

$frontendStatus = git status --porcelain -- frontend/
if ($frontendStatus) {
  if (-not $Message) {
    $Message = Read-Host "Message de commit frontend"
  }
  if (-not $Message.Trim()) {
    git checkout main
    if ($stashCreated) { git stash pop }
    Write-Error "Message de commit requis."
  }
  git add frontend/
  git commit -m $Message
}
else {
  Write-Host "Aucun changement frontend à committer."
}

Write-Host "Push vers origin/frontend..."
git push -u origin frontend 2>$null
if ($LASTEXITCODE -ne 0) {
  git push origin frontend
}

git checkout main

if ($stashCreated) {
  Write-Host "Restauration des changements backend en cours..."
  git stash pop
}

Write-Host "OK — frontend poussé sur origin/frontend, de retour sur main."
