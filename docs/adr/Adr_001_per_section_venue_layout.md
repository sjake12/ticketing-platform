# ADR-001: Per-Section Venue Layout Instead of a Flat Global Row/Seat Count

## Status
Accepted

## Context
A venue's `layout` field needs to describe how many seats it has and how they're organized, so the system can auto-generate `Seat` records whenever an event is created at that venue.

The simplest possible schema would be a single global shape:
```json
{ "sections": ["A", "B", "C"], "rows_per_section": 5, "seats_per_row": 10 }
```
This assumes every section in a venue has the same number of rows and the same number of seats per row.

## Decision
`layout` is instead structured per section:
```json
{
  "sections": [
    { "name": "VIP", "rows": 3, "seats_per_row": 8 },
    { "name": "A", "rows": 10, "seats_per_row": 20 },
    { "name": "Balcony", "rows": 5, "seats_per_row": 15 }
  ]
}
```
Each section carries its own `rows` and `seats_per_row`.

## Rationale
Real venues are not uniform. A VIP or floor section is typically small with few rows, while a general admission section can have dozens of rows. A global row/seat count forces every section to the same shape, which is inaccurate for almost any real venue and would need to be redesigned the first time an admin tried to model an actual arena.

The per-section structure also anticipates a near-term need: sections commonly have different pricing (VIP vs. general admission). Storing sections as discrete objects means a `price_multiplier` or `price` field can be added to each section later without a schema migration — it's just a new key in the JSONB blob.

The cost of this decision is a slightly more complex admin form (a repeatable section builder instead of three flat inputs) and slightly more complex validation (`layout.sections.*.rows` instead of a single `rows_per_section` rule). Both were judged worth it for the accuracy and extensibility gained.

## Consequences
- Seat auto-generation logic must loop over `layout.sections` rather than assuming one global row/seat count — see the nested loop in `SeatGeneratorService::generateForEvent()`.
- Admin venue forms need a dynamic "add section" UI rather than three static fields.
- Adding per-section pricing later is a data change only, not a schema migration.
- If a venue's layout is edited after events already exist, past events' seat records are unaffected — the layout is only read at event-creation time (see ADR-002).
