#!/bin/bash

docker-compose -f provision-compose.yml down --remove-orphans;
docker-compose -f provision-compose.yml up -d &&
docker-compose -f provision-compose.yml logs -f &&
docker-compose -f provision-compose.yml down --remove-orphans;

docker-compose -f docker-compose.yml down --remove-orphans &&
docker-compose -f docker-compose.yml up -d &&
docker-compose -f docker-compose.yml logs -f