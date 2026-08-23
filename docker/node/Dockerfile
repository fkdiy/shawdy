FROM node:26.7.0

RUN npm install -g npm@latest

WORKDIR /app

COPY docker/node/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
