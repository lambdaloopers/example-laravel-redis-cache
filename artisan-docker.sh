#!/bin/bash

docker-compose -f docker-compose.yml run php /usr/local/bin/php /var/www/html/artisan $@
