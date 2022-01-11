#!/bin/sh
touch /run/env.json &&
chown nobody:nobody /run/env.json &&
env &&
jq -s 'env+add' > /run/env.json &&
exec php-fpm -d xdebug.mode=develop,debug --force-stderr --fpm-config /php-fpm.conf
