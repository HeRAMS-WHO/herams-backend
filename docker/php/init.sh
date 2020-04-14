#!/bin/sh
wait-for-it 30 devdb 3306 &&
#/project/protected/yiic cache/warmup &&
exec php -S 0.0.0.0:8080 -t /project/public
