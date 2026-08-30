# ADR-0009 – Web Server Reevaluation

## Status

Accepted

## Context

Shawdy's web server was originally selected in ADR-0003, where nginx was chosen over Apache and Caddy based on its mature ecosystem, extensive documentation, resource efficiency, and suitability for the project's containerized environment.

The decision is being re-evaluated after a closer examination of FrankenPHP. At the time of the original decision, Caddy was primarily considered as an alternative web server to nginx or Apache. FrankenPHP provides a different architectural approach by combining a web server with the PHP runtime and supporting PHP applications in worker mode.

This is particularly relevant for Shawdy because its backend is being newly developed using Symfony and API Platform. The backend therefore has no existing legacy architecture that would prevent it from taking advantage of a long-running application server.

## Decision

After re-evaluating the available alternatives, FrankenPHP was selected as Shawdy's web server and application server.

Considering the project's current architecture—a newly developed Symfony and API Platform backend, a containerized environment, and no legacy PHP application that needs to be accommodated—FrankenPHP provides several advantages over the previously selected nginx + PHP-FPM stack.

The Symfony application can run in FrankenPHP's worker mode, allowing the application to remain loaded in memory between requests instead of being initialized from scratch for every request. FrankenPHP also provides built-in HTTP/3 support and automatic SSL/TLS certificate management.

By combining the web server and PHP runtime, FrankenPHP also removes the need for separate nginx and PHP-FPM containers.

## Consequences

### Advantages

- Symfony can run in worker mode and remain loaded in memory, potentially improving request response times by avoiding repeated application initialization.

- Built-in support for HTTP/3.

- Automatic SSL/TLS certificate creation and renewal without requiring additional mechanisms such as a server-side cron job.

- Simplified container architecture by combining the web server and PHP runtime into a single container.

### Disadvantages

- Less mature ecosystem than nginx and PHP-FPM, with less documentation, a smaller community, and less surrounding tooling.

- The existing container structure has to be reworked, and the current nginx configuration, particularly its routing configuration, has to be migrated to a Caddy configuration.

- The long-running worker model introduces a risk of memory leaks or unintended persistent application state that would not persist between requests in a traditional PHP-FPM setup.

## Alternatives Considered

### nginx + PHP-FPM

The existing nginx + PHP-FPM architecture remains a mature and well-established solution with extensive documentation, community support, and tooling.

It was replaced because Shawdy's backend is being developed from scratch using Symfony and API Platform. The absence of a legacy PHP application makes it possible to design the backend specifically for FrankenPHP's worker model and avoids many of the compatibility and state-management concerns that can arise when adapting an existing application.

For Shawdy's current architecture, the advantages of FrankenPHP are therefore considered to outweigh the additional maturity and ecosystem provided by nginx + PHP-FPM.
