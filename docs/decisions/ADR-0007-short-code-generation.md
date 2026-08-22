# ADR-0007 – Define Short Code Generation

## Status

Accepted

## Context

In order to create short codes to represent shortened URLs, we need to
define their format and how they are generated in a unique way that
doesn't cause collisions, even if thousands of short codes are stored
in the database.

## Decision

Short codes are generated from the auto-incrementing database ID of the
corresponding `ShortUrl` entity.

The database ID provides uniqueness by design, as every persisted entity
receives a unique identifier. Using the ID as the short code directly,
however, would make short codes predictable. Sequential IDs would allow
other short URLs to be easily guessed and could make it possible for
automated clients to systematically crawl a large portion of the
application.

To prevent this, the database ID is transformed using a deterministic
mathematical obfuscation function before it is encoded as a short code.

The obfuscation function must provide a one-to-one mapping between database
IDs and obfuscated values. This ensures that the resulting values remain
unique in exactly the same way as the original database IDs, while making
the sequence of generated short codes non-obvious.

The resulting obfuscated value is encoded using Base62 to produce the
public short code.

No database lookup or retry mechanism is required to detect collisions
during short code generation.

## Consequences

### Advantages

- Short codes generated from unique database IDs are always unique as well.
- No collision handling is needed.
- Without collision checks, short code generation is very fast.

### Disadvantages

- The obfuscation does not provide cryptographic security and does not
  prevent short codes from being discovered through other means.
- Short code generation depends on the `ShortUrl` database ID being
  assigned before the short code can be generated.
- The obfuscation algorithm introduces additional implementation
  complexity compared to generating random strings.

## Alternatives Considered

### Generate random strings and check for collisions

Rejected because collision handling would be required. As more short codes
are stored, the probability of generating an already existing code increases,
which can require additional generation attempts and database lookups.
