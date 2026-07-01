# Synchronise le frontend monorepo vers le dépôt addressimmoFront (déploiement Hostinger).
# Usage : .\scripts\sync-to-addressimmoFront.ps1

$ErrorActionPreference = "Stop"
$repoRoot = Split-Path -Parent (Split-Path -Parent $MyInvocation.MyCommand.Path)
$source = Join-Path $repoRoot "frontend"
$target = Join-Path (Split-Path -Parent $repoRoot) "addressimmoFront"

if (-not (Test-Path $source)) {
  Write-Error "Dossier source introuvable : $source"
}

if (-not (Test-Path $target)) {
  Write-Error "Dépôt cible introuvable : $target"
}

Write-Host "Sync $source -> $target"
robocopy $source $target /E /XD node_modules .next out .git /XF .env.local /NFL /NDL /NJH /NJS | Out-Null
if ($LASTEXITCODE -ge 8) {
  Write-Error "Robocopy a échoué (code $LASTEXITCODE)."
}

Write-Host "OK — frontend synchronisé vers addressimmoFront."
Write-Host "Vérifiez package.json (name: addressimmo-front) et .env.example, puis commit dans addressimmoFront."
