# ADR-0013 – Define production deployment strategy

## Status

Accepted

## Context

Shawdy is approaching its first production release and requires a reproducible
process for deploying new application versions to the production server.

The production environment consists of a single VPS running the application
services through Docker Compose. The server itself is provisioned separately
through the `shawdy-infrastructure` repository using Ansible. Infrastructure
provisioning, server configuration, and monitoring are therefore separate
responsibilities from releasing a new version of the Shawdy application.

Changes merged into `main` should not automatically be deployed to production.
Multiple changes may be integrated before a new version is released, and
production deployments should remain an explicit decision.

The existing CI workflows already validate changes before they are merged into
`main`. Repeating the complete application test suites as part of every
deployment would duplicate those checks. The deployment process should instead
focus on building immutable release artifacts, delivering them to the
production environment, applying required production changes, and verifying
that the deployed application is healthy.

Production builds should also not be performed on the application server.
Building images remotely avoids consuming production resources and ensures
that the exact artifacts produced by the release pipeline are the artifacts
that are deployed.

A deployment strategy is therefore required that separates integration,
release creation, artifact distribution, infrastructure provisioning, and
application deployment.

## Decision

Production releases of Shawdy will be deployed through a tag-triggered GitHub
Actions workflow.

Merging changes into `main` does not deploy them automatically. A production
deployment is explicitly initiated by creating and pushing a version tag, for
example `v0.1.0`.

The deployment workflow will build the production container images for the
deployable Shawdy applications on a GitHub Actions runner. Images will be
tagged with the corresponding release version and published to the GitHub
Container Registry.

The production server will not build application images. It will pull the
versioned images from the registry and run them using the production Docker
Compose configuration.

GitHub Actions will connect to the already provisioned production server using
a dedicated SSH deployment account. The deployment process will update the
required deployment configuration, pull the release images, apply required
database migrations, recreate the affected application containers, and verify
the health of the deployed application.

Application tests are not repeated as part of the deployment workflow. They
remain the responsibility of the CI workflows that validate changes before
they are merged into `main`. The deployment workflow may perform
deployment-specific validation and health checks.

Production secrets and environment-specific configuration will not be stored
in the Shawdy application repository or embedded in container images. They
will be provisioned separately on the production server and persist
independently of individual application releases.

Server provisioning remains the responsibility of the separate
`shawdy-infrastructure` repository. Ansible is used to establish and maintain
the required server infrastructure, including the deployment account,
directories, Docker environment, and monitoring infrastructure. Ansible is
not part of the regular Shawdy application deployment process.

## Consequences

### Advantages

- Production deployments are explicit and can be performed independently of
  merges into `main`.

- Version tags provide a clear relationship between a source revision,
  container images, and a production release.

- Production images are built once by the release pipeline and the same
  artifacts are deployed to the server.

- The production server does not need to spend resources building application
  images.

- GitHub Container Registry integrates with the existing GitHub repository and
  GitHub Actions workflows without introducing an additional deployment
  platform.

- Versioned container images provide a foundation for redeploying or rolling
  back to an earlier application release.

- Infrastructure provisioning and application deployment remain separate
  responsibilities.

- The production server does not require the complete application source code
  to build a release.

- The deployment process remains relatively simple and appropriate for a
  single-server Docker Compose environment.

### Disadvantages

- The production server requires authenticated access to the container
  registry.

- GitHub Actions requires SSH access to the production server.

- Deployment credentials and registry credentials must be managed securely.

- Database migrations can make application rollbacks more complicated when a
  release introduces schema changes that are not backward compatible.

- The deployment process depends on GitHub Actions and GitHub Container
  Registry being available when a release is deployed.

- A single-server Docker Compose deployment does not provide the redundancy or
  rolling deployment capabilities of a multi-node orchestration platform.

## Alternatives Considered

### Deploy every merge to `main`

Every successful merge into `main` could automatically trigger a production
deployment.

This would provide a traditional continuous deployment workflow and reduce the
number of manual release steps.

This alternative was rejected because integration into `main` and releasing
to production are intentionally separate decisions for Shawdy. Multiple
changes may be merged before a new production version should be released.

### Trigger deployments manually

Production deployments could be started manually through GitHub Actions using
a workflow dispatch event.

This would provide explicit control over deployments without requiring version
tags.

This alternative was rejected because version tags additionally provide a
persistent and meaningful identifier for each production release and can be
used consistently for source revisions and container image versions.

### Build application images on the production server

The production server could pull the Shawdy repository and build the required
Docker images locally during deployment.

This would avoid the need for a container registry.

This alternative was rejected because production resources should be used to
run the application rather than build it. Building images in the release
pipeline also ensures that the produced artifact can be versioned, distributed,
and deployed without rebuilding it for a specific server.

### Deploy application releases through Ansible

The `shawdy-infrastructure` repository could use Ansible for both server
provisioning and regular application deployments.

This would centralize infrastructure and deployment automation in a single
place.

This alternative was rejected because infrastructure provisioning and
application releases have different lifecycles. The infrastructure should
normally remain unchanged when a new Shawdy version is released. Keeping
Ansible outside the regular deployment path allows application releases to be
performed without running infrastructure automation.

### Use a container orchestration platform

Shawdy could be deployed through an orchestration platform such as Kubernetes
instead of Docker Compose.

This would provide capabilities such as multi-node scheduling, rolling
deployments, service orchestration, and more advanced availability strategies.

This alternative was rejected because Shawdy currently runs on a single VPS
and does not require multi-node orchestration. Introducing an orchestration
platform would add substantial operational complexity without solving a
current project requirement.
