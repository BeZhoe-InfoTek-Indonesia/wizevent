# Tasks: Implement Successful Payment Email

1.  **Install Dependencies**
    -   [x] Install `barryvdh/laravel-dompdf`.
    -   [x] Publish configuration if necessary.

2.  **Scaffold Views**
    -   [x] Create `resources/views/pdfs/invoice.blade.php`.
    -   [x] Create `resources/views/pdfs/ticket.blade.php`.
    -   [x] Update `resources/views/emails/payment-verification-approved.blade.php` to include download links and QR code logic.

3.  **Implement File Generation Logic**
    -   [x] Create `App\Services\InvoiceService` with `generateInvoicePdf(Order $order)` method.
    -   [x] Update `App\Services\TicketService` with `generateTicketPdf(Ticket $ticket)` method.
    -   [x] Implement QR code base64 generation helper.

4.  **Implement Download Routes**
    -   [x] Create `OrderController` methods: `downloadInvoice(Order $order)` and `downloadTicket(Ticket $ticket)`.
    -   [x] Add routes in `web.php` middleware group (auth).

5.  **Refactor Order Completion Logic**
    -   [x] Create `App\Jobs\ProcessOrderCompletion` job.
    -   [x] Move email sending logic from `OrderService` to this Job.
    -   [x] Add PDF generation calls to this Job.
    -   [x] Update `OrderService::approvePayment` and `approveManualOrder` to dispatch this Job.

6.  **Testing & Validation**
    -   [x] Test Manual Approval flow.
    -   [x] Test Payment Proof Approval flow.
    -   [x] Verify PDF layout and data.
    -   [x] Check email content and links.
