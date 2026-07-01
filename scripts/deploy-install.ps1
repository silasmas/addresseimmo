# Installation AddressImmo en production
#
# 1. Copier .env.example vers .env et configurer BDD + APP_URL + FRONTEND_URL
# 2. php artisan key:generate
# 3. Option A — Assistant web : https://votre-domaine.com/install
# 4. Option B — CLI :
#    php artisan app:install --migrate --seed --email=admin@domaine.com --password="MotDePasseSecurise"
# 5. php artisan storage:link
# 6. Frontend : copier frontend/.env.local.example vers .env.local
#    NEXT_PUBLIC_API_URL=https://votre-domaine.com/api/v1
# 7. npm run build && npm run start (ou déployer sur Vercel)

Write-Host "AddressImmo — guide d'installation"
Write-Host ""
Write-Host "=== BACKEND Laravel (backoffice) ==="
Write-Host "Type Hostinger : PHP / Laravel (PAS Node.js Web App)"
Write-Host "Racine projet  : / (pas frontend/)"
Write-Host "Document root  : public"
Write-Host "Build command  : npm ci && npm run build"
Write-Host "Output dir     : build"
Write-Host ""
Write-Host "=== FRONTEND Next.js (site client) ==="
Write-Host "Type Hostinger : Node.js Web App"
Write-Host "Racine projet  : frontend/"
Write-Host "Build command  : npm ci && npm run build"
Write-Host "Output dir     : .next"
Write-Host "Start command  : npm run start -- -p `$PORT"
Write-Host ""
Write-Host "Web  : /install"
Write-Host "Admin: /back-office  (menu Administration > Déploiement)"
Write-Host "API  : /api/v1/install/status"
Write-Host ""
Write-Host "CLI  : php artisan app:install --migrate --seed --email=... --password=..."
