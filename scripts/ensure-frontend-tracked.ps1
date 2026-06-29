# Restaure un .gitignore compatible avec le suivi Git du dossier frontend/.
# Appelé après merge de main dans frontend (main ignore /frontend/ entièrement).

$ErrorActionPreference = "Stop"
$repoRoot = Split-Path -Parent (Split-Path -Parent $MyInvocation.MyCommand.Path)
$gitignorePath = Join-Path $repoRoot ".gitignore"

$lines = Get-Content $gitignorePath | Where-Object {
  $_ -ne "/frontend/" -and $_ -notmatch "^# Frontend versionné"
}

$frontendRules = @(
  "/frontend/node_modules",
  "/frontend/.next",
  "/frontend/.env.local"
)

foreach ($rule in $frontendRules) {
  if ($lines -notcontains $rule) {
    $lines += $rule
  }
}

Set-Content -Path $gitignorePath -Value $lines -Encoding utf8

if (git diff --quiet .gitignore) {
  Write-Host ".gitignore frontend déjà correct."
} else {
  git add .gitignore
  git commit -m "Conserver le suivi Git de frontend/ sur la branche frontend."
  Write-Host ".gitignore corrigé pour suivre frontend/."
}
