# Shawdy

Shawdy is an open-source URL shortener built with Symfony and Docker.

The primary goal of this project is not only to build a production-ready URL shortener, but also to demonstrate professional software engineering practices, including:

- Clean Architecture
- Docker-first development
- Testing
- CI/CD
- Documentation
- Security
- Maintainability

For more information, see the documentation in the `docs/` directory.

## Development

### Prerequisites

- Docker
- Docker Compose

### Start

Start the development environment with:

```bash
docker compose up -d
```

### Access

The application is available at:

[http://localhost:8080](http://localhost:8080)

### Useful commands

Check the status of the containers:

```bash
docker compose ps
```

View container logs:

```bash
docker compose logs
```

Run commands inside the PHP container:

```bash
docker compose exec php <command>
```

For example, to run Composer:

```bash
docker compose exec php composer install
```

Stop the development environment:

```bash
docker compose down
```
