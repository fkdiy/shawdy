# ADR-0002 – Containerization Strategy

## Status

Accepted

## Context

Shawdy is intended to be portable and provide a reproducible, consistent development environment across different operating systems and deployment targets with minimal dependency on the host machine.

The project should also minimize onboarding effort by allowing new contributors to start developing with as little local setup as possible.

## Decision

The web server, PHP runtime, and the database will run in their own separate containers that can be started together as a consistent development environment.

Communication between services follows their responsibilities. The web server communicates with the PHP runtime, which in turn communicates with the database. Services do not communicate directly unless required by their responsibilities.

Development tools that require a PHP runtime (such as Composer, PHPUnit, and PHPStan) share the PHP container. Introducing a dedicated tooling container would currently add complexity without providing sufficient architectural benefit.

## Consequences

### Advantages

- The development environment behaves consistently across operating systems.
- New developers can start the complete development environment with minimal local setup.
- Runtime components can be maintained and updated independently without affecting unrelated services because responsibilities are clearly separated.
- The development environment is isolated from host-specific software versions.

### Disadvantages

- Slightly higher resource requirements and operational overhead.
- Developers need a basic understanding of containerized workflows.

## Alternatives Considered

### All services run directly on the server

Containerization is discarded entirely and all components run directly on the host machine. This approach was not chosen because two hosts are rarely identical. Different web server, database, and PHP runtime versions can cause various errors and unexpected behavior of the app. Reproducing the expected development environment requires additional manual setup and increases onboarding effort.

### All services run in the same container

All runtime components and developer tools run in a single container. This idea was discarded because it would not provide a clear separation of runtime responsibilities and it would be impossible to switch out or update individual components without rebuilding the entire runtime image even if other components are unaffected. Although this approach slightly reduces resource usage, it still requires developers to understand the same containerized workflow while giving up the architectural benefits of separating runtime responsibilities.

### Developer tools run in their own tool container

Developer tools (such as Composer, PHPUnit, and PHPStan) reside in their own container. This option was not chosen because they require the same PHP runtime and a dedicated tooling container would currently introduce additional complexity without sufficient architectural benefit. As more developer tools are added, this decision might change.
