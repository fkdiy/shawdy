# Git Workflow

## Purpose

This document defines the Git workflow used throughout the project. Its purpose is to ensure a consistent development process, document the workflow that maintains a clean project history, and make collaboration predictable for all contributors.

## Branch Strategy

Every change is developed in its own branch. Branches act as isolated workspaces and are merged into `main` only after review.

## Branch Naming

Branch names follow the same type-based naming convention as Conventional Commits, for example docs/git-workflow, feat/visitor-counter, refactor/database-queries.

## Commit Messages

Shawdy uses a simplified subset of the Conventional Commits specification in the format `type: description`, for example `docs: define project philosophy`, `feat: implement a visitor counter for every short URL`, `refactor: speed up database queries`.

## Pull Requests

Every completed branch is integrated into the project through a pull request. Pull requests are the primary mechanism for reviewing and discussing proposed changes before they become part of the project.

- present the proposed changes for review
- document the reasoning behind a change
- provide a discussion space before merging

Each pull request should:

- reference the related issue
- clearly describe the implemented changes
- remain focused on a single task

A pull request should only be merged after all CI workflows triggered by the change have completed successfully.

## Merge Strategy

Shawdy uses a rebase and merge strategy to preserve a linear, easy-to-follow project history while keeping the individual commits that document the development of a feature. Merge commits and squash merges are intentionally avoided to preserve both a linear history and the individual development steps.

## History Rewriting

The commit history of development branches can be freely rewritten before a pull request is submitted for review to create a clean development timeline when they are pulled into the `main` branch. The history of `main` branch itself must not be rewritten.

## Continuous Integration

Shawdy uses GitHub Actions to validate changes before they are merged into `main` and again when relevant changes are pushed to `main`.

The CI setup is split into separate workflows for documentation, the Symfony backend, the Nuxt frontend, and the Go redirect service. Path filters ensure that workflows only run when affected parts of the repository change.

### Documentation

The documentation workflow validates Markdown documentation.

It performs the following checks:

- `markdownlint-cli2` validates Markdown files against the project's Markdown rules.
- `Lychee` checks links in Markdown files for broken or unreachable targets.

### Symfony Backend

The backend workflow runs for relevant changes under `apps/backend`.

It provisions MariaDB and Redis services and performs the following checks:

- installs Composer dependencies
- scans PHP dependencies for known security vulnerabilities
- validates coding standards with PHP CS Fixer
- warms the Symfony container used by static analysis
- validates the Symfony dependency injection container
- performs static analysis with PHPStan
- creates the test database
- validates Doctrine mappings
- prepares the test database schema
- executes the PHPUnit test suite

### Nuxt Frontend

The frontend workflow runs for relevant frontend changes and for changes that affect the complete application stack used by end-to-end tests.

It performs the following checks:

- installs frontend dependencies using `pnpm`
- scans frontend dependencies for known security vulnerabilities
- validates JavaScript and TypeScript code with ESLint
- runs the Nuxt type checker
- executes unit and component tests with Vitest
- verifies that the Nuxt application can be built successfully
- executes end-to-end tests with Playwright

The Playwright job builds and starts the complete application stack using the CI Docker Compose configuration. This includes the Nuxt frontend, Symfony backend, Messenger worker, MariaDB, Redis, Caddy / FrankenPHP, and the Go redirect service.

Before the browser tests run, the workflow verifies that the application and frontend services are reachable. Docker logs and the Playwright report are preserved when required for debugging failed runs.

### Go Redirect Service

The redirector workflow runs for changes under `apps/redirector`.

It provisions Redis and performs the following checks:

- validates formatting with `gofmt`
- performs static analysis with `go vet`
- executes the Go test suite

## Continuous Deployment

Production releases are deployed through a separate GitHub Actions workflow.

Merging changes into `main` does not automatically deploy them. A deployment is triggered explicitly by creating and pushing a semantic version tag such as `v0.1.0`. This allows multiple changes to be integrated into `main` before a new production release is created.

The deployment workflow builds the production container images on a GitHub Actions runner and publishes the versioned images to the GitHub Container Registry. The production server does not build application images itself.

The workflow then connects to the provisioned production server using a dedicated deployment account. The server pulls the images belonging to the release and starts them using the production Docker Compose configuration.

Database migrations and other required deployment operations are executed as part of the deployment process. After the application has been started, health checks verify that the deployed services are available.

Application test suites are not repeated during deployment. Changes have already passed the relevant CI workflows before being merged into `main`. The deployment workflow instead performs checks that are specific to the deployment and fails if the release cannot be deployed successfully.

Production secrets and environment-specific configuration are kept outside the application repository and container images. Persistent production configuration is provisioned separately on the server.

Server provisioning, hardening, and monitoring are maintained independently in the Shawdy infrastructure project and are not part of regular application deployments.

The architectural decisions behind this release process are documented in [ADR-0013 – Define production deployment strategy](decisions/ADR-0013-production-deployment-strategy.md).
