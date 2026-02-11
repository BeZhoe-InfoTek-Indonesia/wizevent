# payment-verification Specification

## Purpose
TBD - created by archiving change implement-payment-verification. Update Purpose after archive.
## Requirements
### Requirement: Payment Proof Submission
The system SHALL allow visitors to upload a single file (JPG, PNG, PDF) as proof of payment for an order.

#### Scenario: Valid proof upload
- **GIVEN** an order in `pending_payment` status
- **WHEN** the user uploads a 2MB JPG file as payment proof
- **THEN** the file is stored in the `file_buckets` system
- **AND** the order status is updated to `pending_verification`

### Requirement: Admin Verification Workflow
The system SHALL allow Finance Admins to view pending payment proofs and either approve or reject the payment.

#### Scenario: Payment approval
- **GIVEN** an order in `pending_verification` status with a valid proof
- **WHEN** the admin clicks "Approve"
- **THEN** the order status is updated to `completed`
- **AND** ticket issuance is triggered

#### Scenario: Payment rejection
- **GIVEN** an order in `pending_verification` status with an invalid proof
- **WHEN** the admin clicks "Reject" and provides a reason "Image blurry"
- **THEN** the order status reverts to `pending_payment`
- **AND** the user is notified with the reason "Image blurry"

