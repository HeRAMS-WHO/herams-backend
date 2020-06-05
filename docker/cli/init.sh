#!/bin/sh
wait4ports devdb=tcp://devdb:3306 &&
#/project/protected/yiic cache/warmup &&
exec php -S 0.0.0.0:8080 -t /project/public
