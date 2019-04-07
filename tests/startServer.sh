#!/bin/bash
#
# run webserver before test
# startServer.sh

BASEDIR=$(dirname "$0")
cd $BASEDIR
php -S localhost:8008 -t webserver webserver/routing.php