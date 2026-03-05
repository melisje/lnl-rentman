#!/bin/bash

# git pull

# composer install
# npm install
# npm run build

# composer dump-autoload
# php artisan migrate
# php artisan db:seed --class=RentmanSeeder


php artisan queue:restart

---

#!/bin/bash
set -e # Stop het script direct als er iets fout gaat

echo "🚀 Start deployment..."

# 1. Zet de applicatie in onderhoudsmodus
# php artisan down || true

# 2. Haal de laatste code op
# git pull origin main
git pull

# 3. PHP Dependencies (geoptimaliseerd voor productie)
composer install --no-dev --optimize-autoloader

# 4. JS Dependencies & Build
# TIP: 'npm ci' is sneller en betrouwbaarder op servers dan 'npm install'
npm ci
npm run build

# 5. Database acties
php artisan migrate --force # --force is nodig in productie
# Wees voorzichtig met de seeder in een live omgeving!
php artisan db:seed --class=RentmanSeeder --force

# 6. Optimalisatie (Maakt je app veel sneller)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Queue & Cache herstarten
php artisan queue:restart

# 8. Weer online zetten
# php artisan up

echo "✅ Deployment succesvol afgerond!"