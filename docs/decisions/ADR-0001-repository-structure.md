# ADR-0001 – Repository Structure

## Status

Superseded by ADR-0012

## Context

Shawdy is intended to be a long-term open-source project. Besides the Symfony application itself, the repository will contain documentation, containerization, CI/CD configuration, and other project-related assets. A clear repository structure is required to keep these responsibilities organized, maintain developer experience, and follow established conventions.

## Decision

The Symfony application remains in the repository root to preserve the standard project structure and ensure compatibility with the Symfony ecosystem and tooling.

The repository is organized according to the current responsibilities of the project. **Top-level directories are introduced only when they represent a distinct responsibility.** Generic grouping directories are intentionally avoided until they provide a clear benefit.

Project documentation and Docker-related files are placed in dedicated top-level directories (`docs/`, `docker/`). Repository-specific configuration follows the conventions of the respective tools, such as `.github/` for GitHub-specific configuration.

## Consequences

### Advantages

- Symfony follows its standard project structure.
- Standard tooling works without additional configuration.
- Repository responsibilities are clearly separated.
- New contributors can navigate the project more easily.
- Project documentation is easier to discover.
- The repository structure can evolve incrementally as new responsibilities emerge.

### Disadvantages

- The repository root may become more crowded as additional project responsibilities are introduced.
- Future project growth may require restructuring if additional responsibilities justify a revised repository structure.

## Alternatives Considered

### Symfony in a subdirectory

Symfony lives in an `/app` subfolder to avoid mixing Symfony folders with deployment and documentation folders. This approach was not chosen because Symfony by default expects to live in the project root and moving it to a subfolder would require additional configuration, deviate from Symfony conventions, reduce tool compatibility, and would unnecessarily diverge from the standard project layout without providing a clear benefit.

### Grouping supporting resources

Documentation and Docker-related folders are bundled in a single `/infrastructure` directory that can be expanded without cluttering the repository root. Not selected because there is currently no requirement for additional responsibilities beside `docs/`, `docker/`, and `.github/`. This decision may be reevaluated if future project growth introduces additional infrastructure responsibilities (see Disadvantages).
