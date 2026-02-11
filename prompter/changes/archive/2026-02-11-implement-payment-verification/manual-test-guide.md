# Manual Test Guide: Payment Verification (EPIC-006)

This guide provides step-by-step instructions for manually testing the payment verification workflow, covering both the Visitor experience and Admin operations.

## Prerequisites

1.  **Local Environment**: Ensure the application is running (`npm run dev` and `php artisan serve`).
2.  **Database**: Ensure you have at least one **Event** with **Ticket Types** (Standard/VIP) that have inventory available.
3.  **Roles**:
    *   **Visitor**: Any registered user (or create a new one).
    *   **Admin**: A user with admin access to the Filament dashboard (`/admin`).
4.  **MailTrap/MailHog**: Highly recommended to verify email notifications.

---

## Scenario 1: Standard Checkout & Payment Approval (The Happy Path)

**Goal**: Verify that a visitor can purchase tickets, upload proof, and an admin can approve it, resulting in a completed order and issued tickets.

### Step 1: Visitor - Select Tickets
1.  Navigate to `/events`.
2.  Click on an event to view details.
3.  Click "Book Now" or navigate to `/events/{slug}/checkout`.
4.  Select quantities for different ticket types (e.g., 2 VIP, 1 Standard).
5.  Click **"Review Order"** and then **"Confirm Purchase"**.
6.  **Expected Result**: Redirected to the Order Status page (`/orders/{orderNumber}`) with status `Pending Payment`.

### Step 2: Visitor - Upload Payment Proof
1.  On the Order Status page, click **"Upload Payment Proof"**.
2.  Select a file (JPG, PNG, or PDF, max 5MB).
3.  Click **"Submit Proof"**.
4.  **Expected Result**: Status changes to `Pending Verification`. Notification "Proof submitted successfully" appears.

### Step 3: Admin - Review and Approve
1.  Log in to the Admin Dashboard at `/admin/orders`.
2.  Apply the filter "Status" = `Pending Verification` (usually default).
3.  Locate the new order.
4.  Click the **(...) Action Group** and select **"View Payment Proof"**.
    *   **Verify**: The image/PDF displays correctly in a lightbox/modal.
5.  Click **"Approve Payment"**.
6.  Enter optional approval notes.
7.  **Expected Result**:
    *   Order status changes to `Completed`.
    *   Payment proof status changes to `Approved`.
    *   Inventory for the selected ticket types is deducted.
    *   Visitor receives an email with the Ticket PDFs attached.

---

## Scenario 2: Payment Rejection & Re-upload

**Goal**: Verify that an admin can reject a proof and the visitor can upload a corrected one.

1.  **Visitor**: Create a new order and upload any file as proof (as in Scenario 1, Steps 1-2).
2.  **Admin**: In `/admin/orders`, find the order and select **"Reject Payment"**.
3.  **Admin**: Enter a reason (e.g., "Image is blurry").
4.  **Expected Result**: 
    *   Order status reverts to `Pending Payment` (or remains `Pending Verification` depending on implementation, check `OrderService`).
    *   Visitor receives a rejection email with the reason.
5.  **Visitor**: Refresh the Order Status page.
    *   **Expected Result**: The rejection reason is displayed, and the "Upload Payment Proof" button is visible again.
6.  **Visitor**: Upload a new file.
7.  **Expected Result**: Status returns to `Pending Verification`.

---

## Scenario 3: Admin Manual Order Creation (Offline Sales)

**Goal**: Verify that admins can create orders manually and bypass the verification step.

1.  Log in to Admin Dashboard at `/admin/orders`.
2.  Click **"Create Manual Order"**.
3.  Select a **User**, an **Event**, and add **Ticket Types** with quantities.
4.  Select status: `Completed` (for cash sales) or `Pending Payment`.
5.  Click **"Create"**.
6.  **Expected Result**:
    *   If created as `Completed`, inventory is deducted immediately and tickets are generated.
    *   If created as `Pending Payment`, it follows the standard flow.

---

## Scenario 4: Inventory Reservation & Release

**Goal**: Ensure inventory is reserved upon order creation and released if the order expires or is cancelled.

1.  **Preparation**: Note the current "Available" count for a Ticket Type (e.g., "Standard": 10).
2.  **Visitor**: Start a checkout for 3 "Standard" tickets but **DO NOT** upload proof.
3.  **Check Inventory**: 
    *   **Expected Result**: Available count should be 7 (reserved).
4.  **Action**: Wait for order expiration (or manually trigger expiration logic if possible/configured).
    *   *Alternative*: Cancel the order if a "Cancel" button is available.
5.  **Check Inventory Again**:
    *   **Expected Result**: Available count returns to 10.

---

## Scenario 5: Email Notification Verification

Verify that following emails are sent and contain correct details:

| Phase | Email Trigger | Content to Verify |
| :--- | :--- | :--- |
| **Order Created** | Standard Checkout | Order Number, Summary of items, Payment instructions. |
| **Approval** | Admin Approves | Confirmation message, Attached Ticket PDF(s). |
| **Rejection** | Admin Rejects | Rejection reason, Link to re-upload. |

---

## Technical Edge Cases to Watch For

*   **Concurrency**: Two visitors trying to buy the last ticket simultaneously.
*   **Large Files**: Attempting to upload a >5MB file (should show validation error).
*   **Invalid Formats**: Attempting to upload a `.txt` file (should show validation error).
*   **Access Control**: Ensure visitors cannot view or upload proofs for other people's orders.
