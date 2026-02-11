# Design: Payment Verification Workflow

## Architecture Overview

The payment verification system is built on a robust state machine that ensures data integrity across orders, inventory, and ticketing. It uses a decoupled approach where payment verification is a separate layer between order creation and ticket issuance.

## Data Schema

### Orders Table
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| order_number | string | Unique human-readable ID |
| user_id | foreign_id | Reference to User |
| event_id | foreign_id | Reference to Event |
| status | string | pending_payment, pending_verification, completed, cancelled, expired |
| subtotal | decimal | Total price before discounts/tax |
| discount_amount | decimal | |
| tax_amount | decimal | |
| total_amount | decimal | Final payable amount |
| notes | text | Admin or user notes |
| expires_at | timestamp | For releasing reserved inventory |

### OrderItems Table
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| order_id | foreign_id | Reference to Order |
| ticket_type_id | foreign_id | Reference to TicketType |
| quantity | integer | Number of tickets |
| unit_price | decimal | Price at time of purchase |
| total_price | decimal | quantity * unit_price |

### Tickets Table
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| order_item_id | foreign_id | Reference to OrderItem |
| ticket_type_id | foreign_id | Reference to TicketType |
| ticket_number | string | Unique human-readable ID |
| holder_name | string | Name for the ticket (optional) |
| status | string | active, used, cancelled |
| qr_code_content | text | Encrypted content for QR |

### PaymentProofs Table
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| order_id | foreign_id | Reference to Order |
| file_bucket_id | foreign_id | Reference to FileBucket (polymorphic) |
| status | string | pending, approved, rejected |
| rejection_reason | text | |
| verified_at | timestamp | |
| verified_by | foreign_id | Reference to Admin User |

## Inventory Management Strategy

To prevent overselling during manual payment verification:
1. **Reservation**: When an order is created (`pending_payment`), the requested quantity is "held". We'll add a `held_count` to `TicketType` or calculate available as `quantity - (sold_count + held_count)`.
2. **Commitment**: Upon payment approval (`completed`), `held_count` is decremented and `sold_count` is incremented.
3. **Release**: If the order expires or is cancelled, `held_count` is decremented.

*Note: For MVP, we might simply increment `sold_count` immediately and decrement if cancelled/rejected, but the "held" strategy is more accurate for reporting.*

## Component Design

### OrderService
Handles the business logic for creating orders, calculating totals, and managing status transitions.

### PaymentService
Handles payment proof uploads and the verification logic (Approval/Rejection).

### TicketService
Handles the generation of `Ticket` records based on `Completed` orders.

## Security Considerations
- **File Validation**: Strict MIME type checking (JPG, PNG, PDF) and size limits (5MB).
- **Access Control**: Only the order owner can upload proofs. Only Finance Admins can verify.
- **Data Integrity**: Database transactions for all status transitions affecting inventory.
