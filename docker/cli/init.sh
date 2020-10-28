#!/bin/sh
wait4ports devdb=tcp://devdb:3306 &&
#/project/protected/yiic cache/warmup &&
exec php -d memory_limit=2g -S 0.0.0.0:8080 -t /project/public
