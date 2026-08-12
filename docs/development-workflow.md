# Development Workflow

## Overview

The development process follows a consistent workflow from defining a unit of work to integrating the completed changes:

Issue → Branch → Implementation/ADR → Pull Request → CI → Merge

### Issue

A unit of work is defined as a GitHub Issue with a clear scope, goal, and acceptance criteria. The issue provides the basis for the subsequent development work.

### Branch

A dedicated branch is created from `main` for the issue. All changes related to the issue are developed on this branch.

### Implementation / ADR

The work defined by the issue is carried out on the branch. Depending on the nature of the work, this may involve implementing changes or documenting an architectural decision in an ADR.

### Pull Request

Once the work is complete, a Pull Request is created to describe the changes and submit them for review. The Pull Request references the associated issue.

### CI

Automated GitHub Actions checks are run against the Pull Request. Required checks must pass before the changes can be merged.

### Merge

After successful review and completion of the required CI checks, the Pull Request is merged into the target branch. The associated issue is automatically closed through the `Closes #XX` reference.

## GitHub Issues

GitHub Issues are used to define and track individual units of work. Each issue should have a clear scope and a well-defined outcome.

Issues follow a consistent structure:

```md
## Summary

Briefly describe the work to be done and its scope.

## Goal

Describe the intended outcome and clarify what the work should achieve.

## Acceptance Criteria

- [ ] Define the conditions that must be met for the issue to be considered complete.
- [ ] ...
```

`Summary`: The `Summary` section briefly describes the work to be done and its scope.

`Goal`: The `Goal` section describes the intended outcome and clarifies what the work should achieve.

`Acceptance Criteria`: The `Acceptance Criteria` section defines the conditions that must be met for the issue to be considered complete. Criteria should be specific and verifiable where possible.

---

## ADRs

ADRs are used to capture important architectural decisions made by the developers, along with the context, reasoning, and trade-offs behind those decisions.

ADRs follow a consistent structure:

```md
# ADR-XXXX – Decision Title

## Status

Use "Accepted" to mark an approved architectural decision.

## Context

Describe the circumstances, requirements, and constraints that led to the need for a decision.

## Decision

Describe which decision was made and why it was selected.

## Consequences

### Advantages

- Objectively describe the advantages of the decision.
- ...

### Disadvantages

- Objectively describe the possible disadvantages of the decision.
- ...

## Alternatives Considered

### Alternative 1

Evaluate a possible alternative and explain why it was not chosen.

### Alternative 2

...
```

`Status`: The `Status` section indicates the current state of the architectural decision.

`Context`: The `Context` section describes the circumstances, requirements, and constraints that led to the need for a decision. It should provide enough background to understand the problem without relying on external knowledge.

`Decision`: The `Decision` section records the architectural decision that was made and the reasoning behind it.

`Consequences`: The `Consequences` section describes the expected effects of the decision, including its advantages and disadvantages.

`Advantages`: The `Advantages` section describes the benefits and positive consequences of the decision.

`Disadvantages`: The `Disadvantages` section describes the limitations, drawbacks, and potential risks introduced by the decision.

`Alternatives Considered`: The `Alternatives Considered` section documents relevant alternatives that were evaluated and explains why they were not selected.

---

## Pull Requests

Pull Requests are used to describe changes made on a branch, provide additional context, submit those changes for review, and verify that automated checks pass before the changes are merged into the target branch, completing the associated issue.

Pull Requests follow a consistent structure:

```md
## Summary

Briefly describe the purpose of the pull request.

## Changes

- Describe every major change in greater detail.
- ...

## Notes

Add additional context or notes relevant to the pull request.

Closes #XX
```

`Summary`: The `Summary` section briefly describes the purpose of the pull request and provides a high-level overview of the intended change.

`Changes`: The `Changes` section lists the major changes introduced by the pull request and provides enough detail for reviewers to understand what was modified.

`Notes`: The `Notes` section provides additional context, information, or considerations that are relevant to reviewing or understanding the pull request.

The associated issue is referenced using GitHub's closing syntax so that it is automatically closed when the pull request is merged.

---

## When to Create an ADR

An ADR should be created when a decision has a significant and lasting impact on the project's architecture, technology choices, or development structure.

An ADR is generally appropriate when:

- selecting or replacing a major technology or infrastructure component.
- defining how major application components interact.
- making architectural decisions that affect future development.
- choosing between multiple viable approaches with meaningful trade-offs.

An ADR is generally not required for:

- routine implementation decisions within an established architecture.
- minor code or configuration changes.
- bug fixes and maintenance work.
- decisions that are easily reversible and have no significant architectural impact.

When in doubt, the decision should be discussed before creating an ADR.
