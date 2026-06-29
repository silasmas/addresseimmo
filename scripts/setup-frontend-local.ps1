# Restaure le dossier frontend/ depuis la branche `frontend` sans quitter `main`.
# Usage : .\scripts\setup-frontend-local.ps1

$ErrorActionPreference = "Stop"
$repoRoot = Split-Path -Parent (Split-Path -Parent $MyInvocation.MyCommand.Path)
Set-Location $repoRoot

if (-not (git rev-parse --verify frontend 2>$null)) {
  Write-Error "La branche locale 'frontend' est introuvable."
}

Write-Host "Récupération de frontend/ depuis la branche frontend..."
git checkout frontend -- frontend/

Write-Host "OK — frontend/ est prêt pour le dev local (reste ignoré par Git sur main)."
Write-Host "Copiez frontend/.env.local.example vers frontend/.env.local si nécessaire."
