#!/bin/sh
# Waits for services to come up.
# Usage: wait.sh HOST PORT
TIMEOUT=$1
shift
HOST=$1
shift
PORT=$1
shift
echo -n Waiting for $HOST:$PORT to come up
if timeout $TIMEOUT sh -c 'until nc -z $0 $1; do sleep 0.5; echo -n .; done' $HOST $PORT; then
  exec "$@"
fi