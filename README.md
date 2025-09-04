Limpar o Cache

# 1) Gerar migrations necessárias (idempotente)
./vendor/bin/sail artisan cache:table
./vendor/bin/sail artisan session:table
./vendor/bin/sail artisan queue:table

# 2) Aplicar migrations
./vendor/bin/sail artisan migrate

# 3) Limpar e recompilar caches do Laravel
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan route:clear
./vendor/bin/sail artisan view:clear
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:cache
