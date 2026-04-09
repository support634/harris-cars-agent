#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${APP_DIR:-/opt/harris-cars-agent}"
BRANCH="${BRANCH:-main}"
REPO_URL="${REPO_URL:-https://github.com/support634/harris-cars-agent.git}"

export DEBIAN_FRONTEND=noninteractive

apt-get update
apt-get install -y ca-certificates curl git gnupg lsb-release

if ! command -v docker >/dev/null 2>&1; then
    install -m 0755 -d /etc/apt/keyrings
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /etc/apt/keyrings/docker.gpg
    chmod a+r /etc/apt/keyrings/docker.gpg

    echo \
        "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
        $(. /etc/os-release && echo "$VERSION_CODENAME") stable" \
        > /etc/apt/sources.list.d/docker.list

    apt-get update
    apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
fi

mkdir -p "$(dirname "$APP_DIR")"

if [ ! -d "$APP_DIR/.git" ]; then
    git clone --branch "$BRANCH" "$REPO_URL" "$APP_DIR"
else
    git -C "$APP_DIR" fetch origin "$BRANCH"
    git -C "$APP_DIR" checkout "$BRANCH"
    git -C "$APP_DIR" pull --ff-only origin "$BRANCH"
fi

if [ ! -f "$APP_DIR/.env.production" ] && [ -f "$APP_DIR/.env.production.example" ]; then
    cp "$APP_DIR/.env.production.example" "$APP_DIR/.env.production"
fi

if [ -f "$APP_DIR/scripts/server/deploy.sh" ]; then
    chmod +x "$APP_DIR/scripts/server/deploy.sh"
fi

echo "Bootstrap complete."
echo "Repository: $APP_DIR"
echo "Next: write production secrets into $APP_DIR/.env.production, then run $APP_DIR/scripts/server/deploy.sh"
