FROM node:26.7.0-alpine3.24 AS base

WORKDIR /app


# Devlopment

FROM base AS development

COPY docker/node/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]


# Build Nuxt for production

FROM base AS builder

COPY frontend/package.json frontend/package-lock.json ./

RUN npm ci

COPY frontend/ ./

RUN npm run build


# Run the compiled Nuxt/Nitro application

FROM base AS production

ENV NODE_ENV=production
ENV NITRO_HOST=0.0.0.0
ENV NITRO_PORT=3000

COPY --from=builder --chown=node:node /app/.output/ ./.output

USER 1000:1000

EXPOSE 3000

CMD ["node", ".output/server/index.mjs"]