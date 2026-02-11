# EPIC-006: Payment Verification Workflow

## Business Value Statement

Streamline payment verification process for manual payment methods (bank transfer, e-wallet, cash), reducing verification time from 24-48 hours to <2 hours while preventing fraud and ensuring accurate inventory management.

## Description

Implement end-to-end payment verification workflow including visitor payment proof upload, admin verification queue, approval/rejection workflow, automated ticket issuance, inventory deduction, and email notifications.

## Source Traceability

| Document | Reference | Section/Page |
|----------|-----------|--------------|
| PRD | US-06 | Payment proof upload |
| PRD | US-07 | Payment verification by finance admin |
| PRD | US-16 | Admin manual payment upload for offline sales |
| PRD | Success Metrics | Payment Verification Time (<2 hours) |

## Scope Definition

| In Scope | Out of Scope |
|----------|--------------|
| Payment proof upload (JPG/PNG/PDF ≤5MB) | Automated payment gateway integration (Stripe/PayPal) |
| Order creation with "Pending Verification" status | OCR for payment proof data extraction |
| Admin payment verification queue | AI-powered fraud detection |
| Lightbox view for payment proof images | Payment proof annotation tools |
| Approve/reject workflow with reason | Bulk payment approval |
| Inventory deduction on approval | Partial payment support |
| Email notifications (confirmation, approval, rejection) | SMS notifications |
| Admin manual payment upload for offline sales | Refund processing workflow |
| Order status tracking | Payment reconciliation reports |

## High-Level Acceptance Criteria

- [ ] Visitors can upload payment proof during checkout
- [ ] File validation enforces type and size limits
- [ ] Order created with "Pending Verification" status
- [ ] Confirmation email sent with order number
- [ ] Finance admin sees pending payments queue
- [ ] Admin can view payment proof in lightbox
- [ ] Admin can approve with one click
- [ ] Admin can reject with reason text
- [ ] Approval triggers inventory deduction
- [ ] Approval triggers QR code generation (EPIC-007)
- [ ] Approval sends email with tickets
- [ ] Rejection sends email with reason
- [ ] Admin can manually create orders for offline sales
- [ ] Order status visible to visitor in dashboard

## Dependencies

- **Prerequisite EPICs:** EPIC-001, EPIC-002, EPIC-003
- **External Dependencies:**
  - File storage (local or S3)
  - Email queue system
- **Technical Prerequisites:**
  - Orders and tickets tables
  - Email templates

## Complexity Assessment

- **Size:** L (Large)
- **Technical Complexity:** Medium
- **Integration Complexity:** Medium
- **Estimated Story Count:** 9-11 stories

## User Stories Covered

- **US-06:** Payment proof upload
- **US-07:** Payment verification
- **US-16:** Admin manual payment upload

## Definition of Done

- [ ] All acceptance criteria met
- [ ] File upload security tested
- [ ] Inventory locking tested
- [ ] Email delivery tested
- [ ] Admin UI tested
- [ ] Code reviewed and approved

---

**EPIC Owner:** Product Owner  
**Estimated Effort:** 2-3 sprints (4-6 weeks)  
**Priority:** P0 (Critical Path)
