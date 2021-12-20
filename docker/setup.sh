#!/bin/bash

docker/start.sh
cd docker
docker-compose exec php docker/internal_setup.sh