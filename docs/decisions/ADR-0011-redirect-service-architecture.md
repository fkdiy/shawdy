# ADR-0011 – Define redirect service architecture

## Status

Accepted

## Context

Shawdy can currently create short URLs, but does not yet provide redirect
functionality.

Redirects are a core function of a URL shortener and are expected to be
executed significantly more frequently than the creation and management of
short URLs. The redirect path should therefore be performant, provide low
latency, and depend only on components that are necessary to perform a
redirect.

The redirect service must be able to resolve a short code to its corresponding
target URL and redirect the visitor efficiently.

At a later stage, redirects should additionally generate click events for
analytics. Capturing and processing these events should not unnecessarily
affect the latency or availability of the redirect itself.

Existing short URL data is currently persisted in MariaDB. It must be decided
whether the redirect service should access this data directly or use a
separate read model optimized for redirect lookups. If a separate read model
is used, a reliable mechanism for synchronizing it with the authoritative data
source must also be defined.

In addition to technical requirements such as performance, latency,
consistency, and resilience, the decision should take Shawdy's purpose as a
learning and portfolio project into account. New technologies and
architectural concepts may deliberately be introduced when they provide
relevant learning value, but they should still serve a clear and justifiable
purpose within the application.

## Decision

Redirects will be handled by a dedicated service implemented in Go. The service
will have the single responsibility of resolving short codes and returning the
corresponding HTTP redirects.

MariaDB will remain the authoritative source of short URL data. Redis will be
introduced as a read-optimized projection containing the short code to target
URL mappings required by the redirect service. The redirect service will access
Redis directly and will not depend on MariaDB or the Symfony backend for
redirect resolution.

Changes to short URL data will be propagated asynchronously from MariaDB to
Redis. The synchronization will use the Transactional Outbox pattern together
with Symfony Messenger to avoid unreliable dual writes and allow failed
updates to be retried.

Redis will contain only derived data and must be fully reconstructable from
MariaDB. In addition to incremental synchronization, a rebuild mechanism will
be provided to recreate the complete Redis read model from the authoritative
data.

Click events will be captured asynchronously so that analytics processing does
not increase the latency of the redirect path. The concrete persistence and
processing strategy for analytics will be defined separately when analytics
functionality is implemented.

The architecture accepts eventual consistency between MariaDB and Redis. A
newly created or updated short URL may therefore require a short period of time
before the corresponding redirect becomes available.

## Consequences

### Advantages

- The redirect path remains small and independent of the Symfony application
  stack.

- Go provides a lightweight runtime suitable for a small and frequently
  executed network service.

- Redis provides a read model optimized for the short code to target URL
  lookups required by the redirect service.

- Redirect traffic can be deployed and scaled independently from the Symfony
  backend.

- MariaDB remains the authoritative source of application data, while the
  Redis read model can be fully reconstructed if necessary.

- Asynchronous synchronization and click event processing keep additional work
  outside the latency-sensitive redirect path.

- The architecture provides practical experience with Go, service boundaries,
  asynchronous processing, read models, and the Transactional Outbox pattern.

### Disadvantages

- The architecture introduces an additional service, runtime, and programming
  language that must be built, tested, deployed, monitored, and maintained.

- Redis becomes an additional infrastructure dependency required for redirect
  resolution.

- Synchronizing MariaDB and Redis introduces additional complexity and failure
  modes.

- Eventual consistency means that newly created or updated short URLs may not
  be immediately available for redirects.

- A mechanism for rebuilding the Redis read model from MariaDB must be
  implemented and maintained.
  
- For Shawdy's current scale, the additional architectural complexity is not
  required purely to provide redirect functionality.

## Alternatives Considered

### Handle redirects in Symfony

The redirect functionality could be implemented directly in the existing
Symfony application. The short code could be resolved from MariaDB and the
redirect returned by a regular Symfony controller.

This would keep the architecture considerably simpler and avoid introducing a
separate service, Redis read model, synchronization mechanism, and additional
deployment requirements. For Shawdy's current expected traffic, this approach
would likely provide sufficient performance.

This alternative was rejected because redirect requests have a very small and
distinct responsibility and are expected to become the most frequently
executed request path in the application. A dedicated service allows this path
to use a smaller runtime, a specialized read model, and to be deployed and
scaled independently from the rest of the backend.

The dedicated service also provides relevant learning value for Shawdy as a
portfolio project by introducing Go and practical experience with integrating
and operating an independently deployable service. The additional complexity
is therefore accepted deliberately rather than being required by the current
scale of the application.
