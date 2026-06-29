# Push backend (main) puis frontend (frontend) en une commande.
# Usage : .\scripts\push-all.ps1 [-BackendMessage "msg"] [-FrontendMessage "msg"]

param(
  [string]$BackendMessage = "",
  [string]$FrontendMessage = ""
)

$ErrorActionPreference = "Stop"
$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path

& (Join-Path $scriptDir "push-backend.ps1") -Message $BackendMessage
& (Join-Path $scriptDir "push-frontend.ps1") -Message $FrontendMessage

Write-Host "OK — backend et frontend poussés sur leurs branches respectives."
