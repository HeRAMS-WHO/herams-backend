#!/bin/sh
touch /run/env.json &&
chown nobody:nobody /run/env.json &&
env &&
jq -s 'env+add' > /run/env.json &&

set -x
# Special fix for WSL, set the xdebug remote host to the WSL host IP.
exec php-fpm -d xdebug.mode=develop,debug -d xdebug.client_host=host.docker.internal --force-stderr --fpm-config /php-fpm.conf
