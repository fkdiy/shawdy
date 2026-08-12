# Git Workflow

## Purpose

This document defines the Git workflow used throughout the project. Its purpose is to ensure a consistent development process, document the workflow that maintains a clean project history, and make collaboration predictable for all contributors.

## Branch Strategy

Every change is developed in its own branch. Branches act as isolated workspaces and are merged into `main` only after review.

## Branch Naming

Branch names follow the same type-based naming convention as Conventional Commits, for example docs/git-workflow, feat/visitor-counter, refactor/database-queries.

## Commit Messages

Shawdy uses a simplified subset of the Conventional Commits specification in the format <type>: <description>, for example docs: define project philosophy, feat: implement a visitor counter for every short URL, refactor: speed up database queries.

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

## Documentation CI

Shawdy uses GitHub Actions to automatically validate its Markdown documentation.

The documentation workflow runs on every pull request and whenever changes are pushed to `main`.

It performs the following checks:

- `markdownlint-cli2` validates Markdown files against the project's Markdown rules.
- `Lychee` checks links in Markdown files for broken or unreachable targets.

A failed documentation check causes the workflow to fail and must be resolved before the pull request can be merged.