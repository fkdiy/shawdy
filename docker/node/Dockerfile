FROM node:26.7.0-alpine3.24 AS base

WORKDIR /app

RUN npm install --global pnpm@12.4.1


# Development

FROM base AS development

RUN apk add --no-cache su-exec=0.3-r0

COPY docker/node/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]


# Build Nuxt for production

FROM base AS builder

COPY \
    apps/frontend/package.json \
    apps/frontend/pnpm-lock.yaml \
    apps/frontend/pnpm-workspace.yaml \
    ./

RUN pnpm install --frozen-lockfile

COPY apps/frontend/ ./

RUN pnpm build


# Production runtime

FROM node:26.7.0-alpine3.24 AS production

WORKDIR /app

ENV NODE_ENV=production
ENV NITRO_HOST=0.0.0.0
ENV NITRO_PORT=3000

COPY --from=builder --chown=node:node /app/.output/ ./.output

# hadolint ignore=DL3002
USER node

EXPOSE 3000

CMD ["node", ".output/server/index.mjs"]
