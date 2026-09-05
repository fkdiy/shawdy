# ADR-0012 – Reevaluate repository structure

## Status

Accepted

## Context

When Shawdy was initially structured, it was primarily intended to be a Symfony
application. Keeping the Symfony application directly in the repository root
therefore followed established Symfony conventions and allowed standard tooling
to work without additional configuration.

As Shawdy's scope and learning objectives have evolved, the repository now
contains multiple independently structured application components. Symfony
with API Platform provides the backend, Nuxt provides the frontend, and a
dedicated redirect service implemented in Go will be introduced.

The repository root therefore no longer represents a single Symfony
application. Keeping Symfony at the root would implicitly treat the backend as
the primary application while the frontend and redirect service are organized
as subordinate components, even though they represent separate application
boundaries with their own runtimes, dependencies, tooling, and conventions.

The increasing number of responsibilities has also made the repository root
more difficult to navigate. Some of the advantages originally established by
ADR-0001, such as clearly separated responsibilities and easy navigation, are
therefore becoming less effective as additional application components are
introduced.

ADR-0001 explicitly anticipated this development by stating that future project
growth may require restructuring when additional responsibilities justify a
revised repository structure. Shawdy has now reached this point.

At the same time, containerization reduces the importance of keeping Symfony
in the repository root. Each application can be located in its own repository
directory while that directory is mounted as the application root inside its
respective container. Symfony, Nuxt, and the redirect service can therefore
retain their conventional project structures and tooling without requiring
them to share the repository root.

The repository structure should consequently be reevaluated to represent the
current application boundaries while preserving established conventions within
each individual application.

## Decision

Shawdy will use a monorepo structure in which independently deployable
applications are grouped under a top-level `apps/` directory.

The Symfony/API Platform backend, Nuxt frontend, and Go redirect service will
be located in separate application directories:

```text
apps/
├── backend/
├── frontend/
└── redirector/
```

Each application directory acts as the project root for its respective
technology and retains the established conventions and tooling of that
ecosystem.

Symfony will therefore move from the repository root to `apps/backend/`.
The Nuxt application will reside in `apps/frontend/`, and the redirect service
will reside in `apps/redirector/`.

Containerization will preserve the expected application roots by using the
respective application directory as the working directory or mounted project
root inside each container. Moving an application below `apps/` therefore does
not require its internal project structure to deviate from framework
conventions.

Repository-wide responsibilities remain at the repository root. Documentation
will remain under `docs/`, GitHub-specific configuration under `.github/`, and
Docker-specific configuration under `docker/`. Root-level files such as the
Docker Compose configuration, Makefile, and README remain responsible for
orchestrating and documenting the repository as a whole.

Additional grouping directories such as `packages/` or `infra/` will only be
introduced when distinct shared-code or infrastructure responsibilities
actually require them.

This decision supersedes ADR-0001.

## Consequences

### Advantages

- The repository becomes easier to navigate by grouping independently
  deployable applications under a common `apps/` directory.

- The Symfony backend, Nuxt frontend, and redirect service are represented as
  equal application components instead of implicitly treating Symfony as the
  primary application.

- Each application can retain the conventional project structure and tooling
  of its respective ecosystem.

- Repository-wide responsibilities remain clearly separated from individual
  application code.

- The structure provides a clearer foundation for adding future applications
  without further cluttering the repository root.

### Disadvantages

- Existing application files must be moved to their new locations.

- Docker Compose configuration, Docker build contexts, volume mounts, CI
  workflows, and other repository-wide paths must be updated accordingly.

- Existing local workflows and documentation that reference the current paths
  may require adjustments.

## Alternatives Considered

### Keep Symfony in the repository root

The existing structure could be retained with Symfony remaining in the
repository root while the frontend and additional services are placed in
separate subdirectories.

This would avoid restructuring the existing backend and require fewer changes
to the current Docker and tooling configuration.

This alternative was rejected because the repository no longer represents a
single Symfony application. Keeping Symfony in the root would implicitly treat
the backend as the primary application while other independently deployable
components are organized around it. As additional application responsibilities
are introduced, this structure would also continue to increase the amount of
unrelated content in the repository root.

### Use separate repositories for each application

The Symfony backend, Nuxt frontend, and redirect service could each be
maintained in their own repository.

This would provide strong isolation between applications and allow each
component to have completely independent versioning, release processes,
permissions, and repository-level tooling.

This alternative was rejected because Shawdy is currently developed and
operated as a single product. The applications share the same local
development environment, container orchestration, architectural documentation,
and overall delivery process.

Using multiple repositories would introduce additional coordination overhead
for changes affecting more than one application, require separate repository
and CI/CD management, and make it harder to review changes that span
application boundaries.

At Shawdy's current size and team structure, these costs would not provide a
sufficient benefit over a structured monorepo.
