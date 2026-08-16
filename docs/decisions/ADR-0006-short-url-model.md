# ADR-0006 – Define the Short URL Domain Model

## Status

Accepted

## Context

Shawdy needs a domain model to represent shortened URLs. Before implementing
the core application functionality, we need to establish the minimal set of
concepts and data required to represent a shortened URL.

The initial model should remain focused on the core URL shortening use case
and avoid introducing features that are not yet part of the product scope.

## Decision

A shortened URL is represented by a `ShortUrl` entity with:

- a unique identifier
- the original target URL
- a unique short code

The short code is the public identifier used to resolve a shortened URL.

The initial model does not include:

- user ownership
- expiration dates
- custom aliases
- analytics
- authentication-related data

These features may be introduced through separate architectural decisions
when they become relevant.

## Consequences

### Advantages

- The initial domain model remains small and focused on the core use case.
- The short code has an explicit and persistent identity.
- Additional functionality can be introduced later without complicating the
  initial model unnecessarily.
- The model provides a clear foundation for persistence, URL creation, and
  redirection.

### Disadvantages

- Features such as expiration, custom aliases, and analytics require later
  extensions to the model.
- The short code requires a uniqueness constraint in the database.
- Future requirements may require revisiting parts of the initial model.

## Alternatives Considered

### Store only the target URL and derive the short code

Rejected because the short code is part of the public identity of a shortened
URL and should therefore be explicitly persisted.

### Include all anticipated features in the initial model

Rejected because this would introduce complexity before the requirements for
those features have been established.
