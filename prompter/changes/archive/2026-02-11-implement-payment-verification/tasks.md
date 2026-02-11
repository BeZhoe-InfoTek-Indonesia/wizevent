# Tasks: Implement Payment Verification

## Phase 1: Database & Models
- [x] Create migration for `orders`, `order_items`, and `tickets` tables
- [x] Create migration for `payment_proofs` table
- [x] Implement `Order`, `OrderItem`, and `Ticket` models with relationships
- [x] Implement `PaymentProof` model
- [x] Add `held_count` or reservation logic to `TicketType` model
- [x] Set up basic policies for Order and PaymentProof

## Phase 2: Order & Ticket Services
- [x] Implement `OrderService` for order creation and state transitions
- [x] Implement `TicketService` for ticket generation upon order completion
- [x] Write unit tests for order status state machine
- [x] Write unit tests for inventory reservation/deduction

## Phase 3: Visitor Portal (Livewire)
- [x] Create `Checkout` Livewire component (selecting quantities for Ticket Types like VIP/Standard)
- [x] Create `PaymentUpload` Livewire component for proof submission
- [x] Implement file validation for payment proofs (JPG/PNG/PDF ≤5MB)
- [x] Add order status view to Visitor Dashboard

## Phase 4: Admin Interface (Filament)
- [x] Create `OrderResource` in Filament
- [x] Implement "Pending Verification" filter/queue in `OrderResource`
- [x] Add Lightbox view for payment proof images in Filament
- [x] Implement Approve/Reject actions with reason modal in Filament
- [x] Add manual order creation form for offline sales in Filament

## Phase 5: Notifications
- [x] Create `OrderCreated` notification (Email)
- [x] Create `PaymentVerificationApproved` notification (Email + Ticket PDF attachment)
- [x] Create `PaymentVerificationRejected` notification (Email + Reason)
- [x] Configure mail queue for these notifications

## Phase 6: Final Integration & QA
- [x] Integrate with existing `FileBucketService` for uploads
- [x] End-to-end testing of the verification workflow
- [x] Verify inventory counts after multiple approval/rejections
- [x] Performance check for ticket generation
