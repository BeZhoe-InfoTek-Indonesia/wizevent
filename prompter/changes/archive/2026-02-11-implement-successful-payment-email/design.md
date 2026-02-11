# Design: Order Confirmation & File Generation

## Architecture

### 1. PDF Generation
We will use `barryvdh/laravel-dompdf` to render Blade views into PDF files. This allows us to reuse existing Blade knowledge and maintain consistent styling.

**Storage Path:**
- Invoices: `storage/app/public/orders/{order_id}/invoice.pdf`
- Tickets: `storage/app/public/orders/{order_id}/tickets/{ticket_id}.pdf`

**Access:**
- Files will be stored in the `public` disk.
- Secure, signed URLs (or standard asset URLs if public access is acceptable) will be used in emails. given the nature of the application (tickets), signed URLs or a guarded download route are preferred to prevent unauthorized access. However, for simplicity and typical MVPs, standard obscure paths might suffice initially, but we will aim for signed route downloads if possible.
*Refinement*: To keep it simple and robust, we will use a Controller route `DownloadInvoiceController` and `DownloadTicketController` that checks ownership/authorization before serving the file.

### 2. QR Code Embedding
The `simplesoftwareio/simple-qrcode` library allows generating QR codes as SVG/PNG strings.
- **In PDF**: We will generate the QR code as a base64 string and embed it in the `<img>` tag within the PDF Blade view.
- **In Email**: Users requested "User-specific details" and "QR code for ticket scanning". Embedding multiple QR codes (if multiple tickets) in one email might clutter it.
*Decision*: The email will show a summary and a "Download Tickets" link. For a single ticket, we can show the QR code. For multiple, we might show the first one or just links. The prompt says "provide a QR code...". We will attempt to embed the QR code for each ticket if reasonable (e.g. < 5), or fallback to just the PDF link.

### 3. Trigger Points
The `OrderService` handles status transitions.
- `approvePayment`: Currently triggers email. Will be updated to generate files first.
- `approveManualOrder`: Currently does nothing. Will be updated to match `approvePayment`.

## Fallback Mechanisms
- **Email Failure**: Handled by Laravel's Queue system (retries).
- **File Gen Failure**: If PDF generation fails, the process should throw an exception and rollback the transaction (if synchronous) or fail the job (if async). We will perform generation *synchronously* during the approval process or *asynchronously* via a Job chain.
*Decision*: To ensure the user gets the email *with* links, file generation should happen *before* the email is sent. We will do this synchronously within a Job or the Service if it's fast enough. PDF generation can be slow.
*Better Approach*: Dispatch a Job `ProcessOrderCompletion` which:
    1. Generates Invoice PDF.
    2. Generates Ticket PDFs.
    3. Sends Email.
This prevents the Admin UI from hanging during approval.

## Data Flow
1. Admin clicks "Approve".
2. `OrderService` updates status to `completed`.
3. `OrderService` dispatches `GenerateOrderDocumentsAndNotify` job.
4. Job generates PDFs => saves to storage.
5. Job sends `PaymentVerificationApproved` email.
