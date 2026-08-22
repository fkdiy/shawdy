# ADR-0008 – Define Frontend Architecture

## Status

Accepted

## Context

To determine how development of Shawdy should continue, for example with an
API-based approach or by rendering templates directly within controllers,
we need to define Shawdy's frontend architecture.

## Decision

Shawdy will use a separate JavaScript-based frontend built with Vue.js,
communicating with the Symfony backend through an HTTP API.

A JavaScript-based frontend framework is well suited for Shawdy's intended
single-page application architecture and provides a clear separation between
the frontend and backend responsibilities.

API Platform was selected as the API framework because it provides an
established foundation for building HTTP APIs within the Symfony ecosystem,
including functionality for routing, serialization, validation, and API
documentation.

Using API Platform also allows us to gain practical experience with a
dedicated API framework that can be applied to future projects.

## Consequences

### Advantages

- Vue.js is a mature and widely adopted frontend framework.
- Compared to other established frameworks such as React and Angular, Vue.js
  provides a relatively low entry barrier while still providing concepts and
  experience that can be transferred to other modern frontend frameworks.
- Vue.js integrates well with Symfony, making it a suitable choice for the
  planned architecture.
- Using Vue.js allows us to gain practical experience with a dedicated
  frontend framework and broaden our professional skill set beyond the
  Symfony backend ecosystem.
- A dedicated frontend framework provides broader experience with modern
  frontend development than relying on Symfony-native tools alone.

### Disadvantages

- Introducing a separate frontend framework increases the overall learning
  curve and implementation complexity.
- The frontend requires its own automated testing strategy in addition to
  the existing backend tests.
- The CI pipeline needs additional tooling and checks for the frontend.
- The frontend and backend become separate applications that need to be
  developed, maintained, and deployed together.
- Communication between the frontend and backend introduces an additional
  API boundary that needs to be designed, tested, and maintained.

## Alternatives Considered

### React or Angular

Rejected because their additional learning curve would be disproportionate
to the overall scope of Shawdy. The project already covers several areas
including Docker, Symfony, Doctrine, API development, automated testing, CI,
and later CD and deployment. Choosing a frontend framework with a
significantly higher entry barrier would add complexity that is not
justified by the project's remaining scope.

Angular also provides less integration with the Symfony ecosystem compared
to Vue.js.

### Server-side rendering with Twig and Stimulus

Rejected because it would provide less opportunity to gain experience with a
dedicated frontend ecosystem and would keep the frontend more closely tied to
Symfony. A separate Vue.js frontend provides experience that is more
transferable to applications using different backend technologies.
