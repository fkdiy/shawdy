# ADR-0010 – Evaluate Nuxt for Server-Side Rendering

## Status

Accepted

## Context

Shawdy currently uses a separate Vue.js frontend that communicates with the
Symfony backend through an HTTP API.

The frontend is currently implemented as a client-side rendered single-page
application in development and is served as static files directly by
FrankenPHP in production.

While this architecture provides some advantages, server-side rendering would
also provide additional benefits for the application.

We therefore need to determine whether the existing Vue.js application should
be extended with a framework providing server-side rendering capabilities.

## Decision

Shawdy will use Nuxt as the frontend framework and will migrate the existing
Vue.js frontend to Nuxt.

Nuxt was selected because it builds upon Vue.js and therefore allows the
existing frontend technology and knowledge to be retained while providing
server-side rendering and the associated application infrastructure.

The Symfony backend will remain responsible for the HTTP API and application
backend, while Nuxt will be responsible for rendering the frontend.

The frontend and backend will therefore be separate applications in both
development and production and will communicate through the existing HTTP API,
making the architecture of both environments more consistent.

Nuxt will initially be introduced to provide server-side rendering for
Shawdy's frontend. Existing frontend functionality should be migrated without
unnecessary architectural changes.

## Consequences

### Advantages

- Nuxt provides server-side rendering while retaining Vue.js as the frontend
  technology.

- The existing Vue.js knowledge and components can be reused during the
  migration.

- Nuxt provides an established application structure and conventions for
  routing, rendering, and frontend application development.

- The separation between the Nuxt frontend and Symfony backend is maintained
  in development and production.

- The Symfony backend remains independent of the frontend rendering
  implementation and continues to expose its functionality through the
  HTTP API.

- Using Nuxt provides additional practical experience with server-side
  rendering and modern Vue.js application architecture.

- Development and production use the same fundamental frontend architecture,
  reducing differences between the two environments.

### Disadvantages

- Introducing Nuxt increases the complexity of the frontend architecture.

- The frontend now requires a Node.js runtime in addition to the Symfony
  backend.

- Server-side rendering introduces additional considerations compared to
  client-side rendering, particularly regarding code that depends on browser
  APIs or client-only state.

- The existing Vue.js frontend needs to be migrated to the Nuxt application
  structure.

- The CI and CD pipelines require additional tooling and steps for building,
  testing, and deploying the Nuxt application.

- Production no longer consists solely of static frontend files served
  directly by FrankenPHP, requiring an additional frontend server process.

## Alternatives Considered

### Continue with just Vue.js

Rejected because it would retain the current client-side rendering model in
development and static file serving through FrankenPHP in production.

This would maintain a difference between the development and production
frontend architectures and provide less practical experience with
server-side rendering and modern universal JavaScript applications.

### Use another Vue.js-compatible SSR framework

Rejected because Nuxt provides an established and comprehensive application
framework for Vue.js server-side rendering.

Using Nuxt also allows the project to retain Vue.js while expanding the
existing frontend architecture rather than introducing a fundamentally
different frontend technology.
