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

## Merge Strategy

Shawdy uses a rebase and merge strategy to preserve a linear, easy-to-follow project history while keeping the individual commits that document the development of a feature. Merge commits and squash merges are intentionally avoided to preserve both a linear history and the individual development steps.

## History Rewriting

The commit history of development branches can be freely rewritten before a pull request is submitted for review to create a clean development timeline when they are pulled into the `main` branch. The history of `main` branch itself must not be rewritten.

## Continuous Integration

Shawdy uses GitHub Actions to validate changes before they are merged into
`main` and again when relevant changes are pushed to `main`.

The CI setup is split into separate workflows for documentation, the Symfony
backend, the Nuxt frontend, and the Go redirect service. Path filters ensure
that workflows only run when affected parts of the repository change.

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

### Pull Requests

A pull request should only be merged after all CI workflows triggered by the change have completed successfully.

The workflows are intentionally separated by application responsibility so that unrelated checks do not need to run for every change while integration tests can still exercise the complete Shawdy stack where required.
