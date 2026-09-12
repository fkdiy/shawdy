#!/bin/sh

set -e

if [ "$(stat -c '%u' /app/node_modules)" != "1000" ]; then
  chown -R 1000:1000 /app/node_modules
fi

su-exec 1000:1000 pnpm install --frozen-lockfile

exec su-exec 1000:1000 "$@"