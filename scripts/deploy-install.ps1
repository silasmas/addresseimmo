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
Write-Host "Web  : /install"
Write-Host "Admin: /admin  (menu Administration > Déploiement)"
Write-Host "API  : /api/v1/install/status"
Write-Host ""
Write-Host "CLI  : php artisan app:install --migrate --seed --email=... --password=..."
