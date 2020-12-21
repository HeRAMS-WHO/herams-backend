#!/bin/sh
cp -r /project/public/. /var/www/html
mkdir -p /var/www/html/assets
chown nobody:nobody /var/www/html/assets
rm -rf /project/public/assets
ln -s /var/www/html/assets /project/public/assets
touch /run/env.json &&
chown nobody:nobody /run/env.json &&
env &&
jq -s 'env+add' > /run/env.json &&
exec php-fpm7 --force-stderr --fpm-config /php-fpm.conf