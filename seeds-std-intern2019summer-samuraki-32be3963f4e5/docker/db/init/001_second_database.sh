#!/bin/sh

echo "CREATE DATABASE IF NOT EXISTS \`${MYSQL_DATABASE}-test\` ;" | "${mysql[@]}"
echo 'FLUSH PRIVILEGES ;' | "${mysql[@]}"