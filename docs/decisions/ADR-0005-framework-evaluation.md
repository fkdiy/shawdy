# ADR-0003 – Framework Evaluation

## Status

Accepted

## Context

Shawdy requires an application architecture for handling HTTP requests, routing, dependency management, application logic, and supporting development tools. Earlier infrastructure decisions were made with a PHP/Symfony-based application in mind, but the framework choice itself has not yet been formally documented.

## Decision

Symfony was selected as Shawdy's application framework because it satisfies the project's current technical requirements while also supporting one of the project's primary learning objectives: gaining practical experience with a mature, production-proven PHP framework.

## Consequences

### Advantages

- Mature framework with extensive documentation and long-term support.
- Strong adherence to established PHP standards such as PSRs and well-established architectural patterns.
- Provides mature solutions for common web application concerns, including authentication, authorization, validation, and security.
- Provides a standardized application structure that can reduce onboarding effort for new developers who already know Symfony.
- Provides practical experience with a widely used production framework.

### Disadvantages

- The full framework provides many features, some of which are not currently necessary for Shawdy but still add extra complexity.
- Implementing uncommon solutions or features may require working around the framework's conventions and structure.
- Increased onboarding effort for new developers who are new to Symfony.

## Alternatives Considered

### Laravel

Laravel is a mature and widely adopted PHP framework and therefore a valid alternative for Shawdy. However, Symfony is better aligned with the project's learning and portfolio objectives and with the PHP ecosystem already considered in earlier architectural decisions. Laravel also relies more heavily on expressive abstractions and framework conventions, while Shawdy's learning objective favors a more explicit and transparent application architecture.

### Symfony Components without the full framework

Using individual Symfony components without the full framework would provide greater control over the application architecture, but would require significant additional integration and configuration work. Core concerns such as routing, dependency injection, templating, configuration, and application structure would need to be assembled manually. The lack of a standardized full-framework structure would also provide less guidance for maintaining consistency as the project grows. In addition, parts of the Symfony ecosystem, such as MakerBundle and framework-integrated community packages, would be less readily available.

### Plain PHP

Building Shawdy without a framework would provide maximum control and minimal framework overhead, but would require implementing or integrating many common application concerns ourselves. This would significantly increase development effort without providing a corresponding benefit for the current project scope. Security-critical functionality would also have to be designed, implemented, and maintained independently instead of relying on established and maintained framework components. In addition, the project would need to define its own application structure and development conventions, increasing the risk of inconsistency as the number of contributors grows.
