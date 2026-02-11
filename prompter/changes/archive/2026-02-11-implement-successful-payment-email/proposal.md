# Proposal: Implement Successful Payment Email with Ticket & Invoice

## Goal
Enhance the order process by sending an automated email notification upon successful payment. This email will provide customers with their tickets, invoice, and QR codes, ensuring a seamless post-purchase experience.

## Changes
### 1. File Generation
- Implement PDF generation for Invoices and Tickets using `barryvdh/laravel-dompdf`.
- Generate QR Code images using `simplesoftwareio/simple-qrcode`.
- Store generated files in the public storage for secure download access.

### 2. Email Notification
- Update the `PaymentVerificationApproved` email to include:
  - Download links for the Invoice PDF.
  - Download links for Ticket PDFs.
  - Embedded QR code for quick scanning.
  - Dynamic user and order details.
- Ensure the email is sent for both "Manual Approval" and "Payment Proof Approval" workflows.

### 3. Queue Handling
- Ensure email sending is queued to prevent blocking the user request.
- Implement basic failure handling (logging).

## Implementation Strategy
1.  **Dependencies**: Install `barryvdh/laravel-dompdf`.
2.  **Services**:
    -   Extend `TicketService` to handle PDF generation.
    -   Create `InvoiceService` (or add to `OrderService`) for Invoice PDF generation.
3.  **Events/Triggers**:
    -   Update `OrderService::approvePayment` and `approveManualOrder` to trigger the file generation and email sending.
4.  **Views**:
    -   Create Blade templates for Invoice PDF and Ticket PDF.
    -   Update Email Blade template.

## Validation
- Verify PDF content (correct order details, layout).
- Verify QR code scannability.
- Verify email delivery and link accessibility.
