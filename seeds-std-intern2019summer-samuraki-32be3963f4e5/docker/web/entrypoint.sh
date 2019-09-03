#!/bin/bash -x

service syslog-ng start
service postfix start
docker-php-entrypoint apache2-foreground
