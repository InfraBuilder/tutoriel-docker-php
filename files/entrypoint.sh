#!/bin/bash

echo "Serveur : $(hostname) - Lancé le $(date "+%Y:%m:%d %H:%M:%S")" > /var/www/html/info.txt

echo "Starting server with params: $@"
exec "$@"
