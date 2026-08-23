# Build the Vue.js frontend
FROM node:26.7.0-alpine3.24 AS frontend

WORKDIR /app

# Copy package files first so npm dependencies can be cached independently
COPY frontend/package*.json ./

# Install the exact dependencies defined in package-lock.json
RUN npm ci

# Copy the frontend source code
COPY frontend/ ./

# Build the production-ready Vue.js application
RUN npm run build


# Production web server
# The final image contains only Nginx and the compiled frontend,
# not Node.js, npm, or the frontend source code.
FROM nginx:1.31.3-alpine3.24 AS production

# Provide the Nginx configuration template.
# The official Nginx image generates the final configuration at container startup
# using the environment variables defined in Docker Compose.
COPY docker/nginx/templates/default.conf.template /etc/nginx/templates/default.conf.template

# Copy only the compiled frontend from the build stage into the final image.
COPY --from=frontend /app/dist /usr/share/nginx/html
