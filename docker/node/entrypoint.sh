#!/bin/sh

set -e

ensure_owned() {
  directory="$1"

  mkdir -p "$directory"
  chown -R 1000:1000 "$directory"
}

ensure_owned /app/node_modules
ensure_owned /app/.nuxt
ensure_owned /app/.data

su-exec 1000:1000 pnpm install --frozen-lockfile

exec su-exec 1000:1000 "$@"