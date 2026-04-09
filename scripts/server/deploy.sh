#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${APP_DIR:-/opt/harris-cars-agent}"
BRANCH="${BRANCH:-main}"

cd "$APP_DIR"

if [ ! -f .env.production ]; then
    echo ".env.production is missing in $APP_DIR" >&2
    exit 1
fi

git fetch origin "$BRANCH"
git checkout "$BRANCH"
git pull --ff-only origin "$BRANCH"

docker compose -f docker-compose.prod.yml --env-file .env.production build --pull
docker compose -f docker-compose.prod.yml --env-file .env.production up -d --remove-orphans

docker compose -f docker-compose.prod.yml --env-file .env.production ps
