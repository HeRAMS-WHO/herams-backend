#!/bin/sh
set -e
touch /run/env.json
chown nobody:nobody /run/env.json
jq -n -s 'env' > /run/env.json
cat /run/env.json | jq
echo "Launching PHP-FPM"
exec php-fpm --force-stderr --fpm-config /php-fpm.conf
