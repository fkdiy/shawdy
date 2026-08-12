# ADR-0004 – Database Selection

## Status

Accepted

## Context

Shawdy requires a database to persist URLs, their associated slugs, and future application data such as user accounts. Several mature database systems exist with different strengths and trade-offs.

The selected database should satisfy the current requirements without introducing unnecessary complexity or features that are not yet needed.

## Decision

After evaluating the available alternatives against the project's requirements, MariaDB was selected as Shawdy's database system.

MariaDB satisfies the project's current requirements while providing a mature ecosystem, excellent tooling, broad Doctrine support, and remaining fully open source. Its compatibility with MySQL also allows it to benefit from the extensive ecosystem and documentation built around both projects.

PostgreSQL's more extensive feature set is not required for the project's current scope. Advanced PostgreSQL features can be reconsidered if future project requirements justify their additional capabilities. The application is designed to minimize database-specific dependencies through Doctrine, reducing coupling to a particular database implementation.

## Consequences

### Advantages

- Provides all database functionality required for the current project scope.
- Mature ecosystem with extensive documentation and community support.
- Low resource consumption.
- Well supported by Doctrine and the Symfony ecosystem.

### Disadvantages

- Less extensive feature set than PostgreSQL.
- Advanced PostgreSQL capabilities would require a future migration if they become necessary.

## Alternatives Considered

### MySQL

MySQL is the most mature and most widespread of the candidates that we evaluated. However, we decided against it because MariaDB is a highly compatible alternative that benefits from the same extensive ecosystem and tooling while remaining fully open source.

### PostgreSQL

PostgreSQL is the most feature-rich database that we reviewed, but those additional capabilities do not provide sufficient benefit for the project's current scope.
