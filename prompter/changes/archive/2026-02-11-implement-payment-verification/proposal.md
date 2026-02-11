# Proposal: Implement Payment Verification (EPIC-006)

## Why
Manual payment methods (bank transfer, e-wallet, cash) require a robust verification workflow to ensure transactions are valid before issuing tickets and deducting inventory. This process currently takes 24-48 hours manually; the goal is to reduce this to under 2 hours through an integrated system.

## What Changes
- Implement `Order`, `OrderItem`, and `Ticket` models and database schema.
- Add `PaymentProof` capability leveraging `FileBucket` for secure uploads.
- Create a Visitor-facing checkout and payment proof upload interface using Livewire.
- Build a Finance Admin verification queue in Filament.
- Implement automated ticket generation and inventory deduction upon payment approval.
- Integrate email notifications for order confirmation, payment approval, and rejection.
- Support manual order creation for offline sales by admins.

## Change ID
`implement-payment-verification`

## Overview
This change implements the end-to-end payment verification workflow as defined in EPIC-006. It bridges the gap between event discovery/selection and ticket issuance by providing a secure way for visitors to submit payment records and for admins to verify them.

## Source
- **EPIC**: EPIC-006: Payment Verification Workflow
- **User Stories**: US-06 (Payment proof upload), US-07 (Payment verification), US-16 (Admin manual payment upload)
- **Priority**: P0 (Critical Path)

## Problem Statement
The system currently lacks the ability to process orders and verify manual payments. Without this, visitors cannot complete their purchase, and event organizers cannot accurately track sales or issue digital tickets.

## Proposed Solution

### 1. Database Schema & Models
- **Orders**: Tracks the overall transaction, status, and pricing.
- **OrderItems**: Detailed breakdown of ticket types and quantities in an order.
- **Tickets**: The final digital asset generated after payment verification (links to `EPIC-007` for QR).
- **PaymentProofs**: Links orders to uploaded proof files (via `FileBucket`) and tracks verification status.

### 2. Visitor Workflow (Livewire)
- **Checkout Flow**: Simple transition from ticket selection to order creation.
- **Payment Submission**: A dedicated view/modal for visitors to upload JPG/PNG/PDF proofs (≤5MB).
- **Status Tracking**: Visibility into order status (Pending Payment, Pending Verification, Completed).

### 3. Finance Admin Workflow (Filament)
- **Verification Queue**: A dedicated table showing orders with "Pending Verification" status.
- **Verification View**: Lightbox view for payment proofs with Approve/Reject actions.
- **Manual Orders**: Ability for admins to create orders for offline/cash sales, bypassing the proof upload.

### 4. Background Processing & Integration
- **Inventory Service**: Atomic deduction of `TicketType` stock upon payment approval.
- **Notification Service**: Queued emails for all status changes.
- **Ticket Service**: Base for ticket generation (QR code generation will be refined in EPIC-007).

## Capabilities Affected

### New Capabilities
1. **order-management** - Managing order lifecycle and items.
2. **payment-verification** - Manual payment proof handling and approval workflow.
3. **ticket-issuance** - Post-payment ticket generation and delivery.

## Design Considerations

### Order Status Transitions
```
[Pending Payment] ──upload_proof──► [Pending Verification] ──approve──► [Completed]
       │                                     │                      │
       └──────────cancel/expire──────────────┴────────reject────────┘
```

### Business Rules
- Order number format: `ORD-{YYYY}{MM}{DD}-{RANDOM_6}`.
- Ticket number format: `TKT-{ORDER_ID}-{RANDOM_8}`.
- Inventory is RESERVED when order is created and DEDUCTED when payment is approved.
- Expired orders release reserved inventory.

## Technical Approach
- **Backend**: Laravel 11 with Eloquent.
- **Frontend**: Livewire 4 for visitor portal, Filament 4 for admin.
- **Storage**: `FileBucket` system for payment proofs.
- **Testing**: Feature tests for state machine and inventory locking.

## Rollout Plan
1. Core Models & Migrations.
2. Order & Ticket Services.
3. Visitor Payment Upload (Livewire).
4. Finance Admin Queue (Filament).
5. Notifications & Final Integration.

## Success Criteria
- [ ] Users can successfully upload payment proofs.
- [ ] Admin can view proofs and approve/reject with reasons.
- [ ] Approved orders correctly deduct inventory.
- [ ] Emails are sent for each stage of the workflow.
- [ ] Admins can create manual orders.
