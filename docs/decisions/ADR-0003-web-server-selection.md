# ADR-0003 – Web Server Selection

## Status

Superseded by ADR-0009

## Context

Shawdy is a new application with little user interaction in its earliest iterations and no legacy infrastructure or compatibility requirements. The project therefore requires a reliable, mature, and well-documented web server capable of serving the application and handling URL redirections. Several solutions exist with different trade-offs.

The selected web server should satisfy the current requirements without introducing unnecessary complexity or features that are not yet needed.

## Decision

After evaluating the available alternatives against the project's requirements, nginx was selected as Shawdy's web server.

Considering the project's requirements—no legacy dependencies, an established technology stack, a containerized development environment, and a focus on stability and maintainability—nginx provides the most suitable balance of maturity, resource efficiency, documentation, and operational simplicity.

Apache's extensive legacy compatibility was not required for a newly developed application, while Caddy's simplicity did not outweigh the advantages of nginx's mature ecosystem and widespread adoption.

## Consequences

### Advantages

- Mature ecosystem with extensive documentation and long-term support.
- Low resource consumption.
- Well suited for containerized deployments.
- Well supported by Symfony documentation and community resources.

### Disadvantages

- Manual HTTPS configuration compared to Caddy.
- Less suitable for projects relying heavily on `.htaccess`.

## Alternatives Considered

### Apache web server

Apache is the most mature of all web servers that we evaluated. It was not selected because it generally consumes more resources than nginx, is more complex to configure, and provides features such as per-directory configuration through `.htaccess` that are not required for this project.

### Caddy web server

Caddy is a relatively new and easy-to-configure web server. We decided against it because nginx's mature ecosystem and extensive documentation make troubleshooting Docker configuration issues easier. Caddy's simplified configuration is less valuable in a preconfigured containerized environment.
