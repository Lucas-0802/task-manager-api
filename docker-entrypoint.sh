set -e

while ! mysqladmin ping -h"mysql" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" --silent; do
    echo 'Aguardando MySQL...'
    sleep 1
done

php artisan migrate --force

exec apache2-foreground
