docke#!/bin/sh
set -e
cp -r /project/public/. /var/www/html
mkdir -p /var/www/html/assets
chown nobody:nobody /var/www/html/assets
rm -rf /project/public/assets
ln -s /var/www/html/assets /project/public/assets
touch /run/env.json
chown nobody:nobody /run/env.json
env
jq -s 'env+add' > /run/env.json

# Check if we need to seed a database with SQL files.
if [ -d "/database-seed" ]; then
  echo "Seeding database";
  cp -r /database/. /database-seed
fi


exec php-fpm --force-stderr --fpm-config /php-fpm.conf
