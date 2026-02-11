# EPIC-008: Visitor Dashboard & Digital Wallet

## Business Value Statement

Provide visitors with a centralized dashboard to manage their tickets, view purchase history, access QR codes offline, and track event timeline, improving user retention and reducing support inquiries.

## Description

Implement visitor dashboard with digital wallet for active tickets, purchase history, offline QR code access via PWA, invoice downloads, and event timeline.

## Source Traceability

| Document | Reference | Section/Page |
|----------|-----------|--------------|
| PRD | US-08 | View digital ticket in dashboard |
| PRD | US-20 | Purchase history |
| PRD | Scope → Visitor Experience | Digital wallet with offline QR access |

## Scope Definition

| In Scope | Out of Scope |
|----------|--------------|
| Dashboard homepage with active tickets | Ticket transfer to other users |
| Digital wallet view with QR codes | Ticket resale marketplace |
| Offline QR access via service worker | Apple Wallet / Google Pay integration |
| Purchase history with order details | Order cancellation by user |
| Invoice PDF download | Receipt customization |
| Event timeline (upcoming, past) | Calendar sync |
| Ticket status badges (valid, used, refunded) | Ticket insurance |

## High-Level Acceptance Criteria

- [ ] Dashboard shows active tickets
- [ ] QR codes accessible offline
- [ ] Purchase history paginated
- [ ] Invoice download functional
- [ ] Event timeline shows upcoming/past
- [ ] Ticket status displayed correctly
- [ ] Mobile-optimized UI
- [ ] Service worker caches QR codes

## Dependencies

- **Prerequisite EPICs:** EPIC-001, EPIC-002, EPIC-007
- **External Dependencies:** Service worker, PDF generation library
- **Technical Prerequisites:** Tickets and orders data

## Complexity Assessment

- **Size:** M (Medium)
- **Technical Complexity:** Medium (PWA, offline)
- **Integration Complexity:** Low
- **Estimated Story Count:** 7-9 stories

## User Stories Covered

- **US-08:** View digital ticket
- **US-20:** Purchase history

## Definition of Done

- [ ] All acceptance criteria met
- [ ] Offline access tested
- [ ] PWA functionality verified
- [ ] Mobile UI tested
- [ ] Code reviewed and approved

---

**EPIC Owner:** Product Owner  
**Estimated Effort:** 2 sprints (4 weeks)  
**Priority:** P0 (Critical Path)
