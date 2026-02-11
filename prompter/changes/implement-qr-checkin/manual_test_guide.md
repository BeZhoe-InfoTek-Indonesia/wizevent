# Manual Test Guide: QR Code Check-in System

This guide provides step-by-step instructions for manually testing the QR code generation, display, and scanning system.

## 1. Preparation
- **Staff Account**: Ensure you have an admin/staff account with permissions to access the Scan Tickets page.
- **Visitor Account**: Ensure you have a visitor account with at least one active ticket.
- **Devices**: 
    - **Device A (Visitor)**: A smartphone or browser to view the ticket.
    - **Device B (Staff)**: A smartphone or camera-equipped device to scan tickets.
- **Environment**: Local environment with `npm run dev` and `php artisan serve` running.

---

## 2. Test Cases

### TC-01: QR Code Generation & Display
**Objective**: Verify that QR codes are generated correctly and visible to the visitor.
1.  Login as a **Visitor**.
2.  Go to **My Tickets** or the **Order Status** page.
3.  View a specific ticket.
4.  **Verification**: 
    - [ ] A QR code is visible on the ticket.
    - [ ] The QR code looks sharp and scannable.
    - [ ] Other ticket details (Number, Type, Holder Name) are correct.

### TC-02: Offline Availability (Service Worker)
**Objective**: Verify that visitors can access their tickets without an active internet connection.
1.  Login as a **Visitor** and view your ticket while online (to allow caching).
2.  Enable **Airplane Mode** or disconnect the internet on the visitor device.
3.  Refresh the ticket page or try to navigate back to it.
4.  **Verification**:
    - [ ] The ticket page and QR code still load correctly from the cache.
    - [ ] No "No Internet" error page is shown for the cached ticket route.

### TC-03: Successful QR Check-in
**Objective**: Verify the standard check-in flow via QR scan.
1.  Login as **Staff/Admin** on Device B.
2.  Navigate to **Scan Tickets** (`/admin/scan-tickets`).
3.  Grant camera permissions if prompted.
4.  Point the camera at the QR code on Device A (Visitor).
5.  **Verification**:
    - [ ] The scanner detects the QR code within 5 seconds.
    - [ ] A **Green** success message is displayed ("Check-in successful!").
    - [ ] The attendee details (Name, Ticket Type) are shown on the screen.

### TC-04: Duplicate Check-in Prevention
**Objective**: Ensure a ticket cannot be used twice.
1.  Use the **same QR code** from TC-03.
2.  Scan it again using the Staff device.
3.  **Verification**:
    - [ ] A **Red** error message is displayed ("Ticket already used").
    - [ ] The check-in timestamp is NOT updated in the database.

### TC-05: Manual Entry Fallback
**Objective**: Verify that staff can check in attendees using the ticket number.
1.  Get a **new active ticket number** from the database or visitor portal.
2.  On the Staff device, locate the **Manual Entry** field in the scanner interface.
3.  Type the ticket number and click **Check In**.
4.  **Verification**:
    - [ ] The system validates the number correctly.
    - [ ] A **Green** success message is displayed.
    - [ ] The ticket status changes to `used`.

### TC-06: Handling Invalid/Tampered QR
**Objective**: Verify security against QR tampering.
1.  (Advanced) Try scanning a random QR code (e.g., from a cereal box or a generated text QR that isn't encrypted).
2.  **Verification**:
    - [ ] A **Red** error message is displayed ("Invalid or tampered QR code").
    - [ ] The system does not crash or provide sensitive technical details.

### TC-07: Cancelled Ticket Validation
**Objective**: Ensure cancelled tickets are rejected.
1.  In the Admin panel, go to **Tickets** and change an active ticket's status to `cancelled`.
2.  Try scanning this ticket's QR code.
3.  **Verification**:
    - [ ] A **Red** error message is displayed ("Ticket has been cancelled").

---

## 3. Post-Test Verification (Audit Trail)

### Activity Log Check
1.  In the Admin panel, navigate to **Activity Logs**.
2.  Filter/Search for "Ticket checked in" or "Failed ticket scan attempt".
3.  **Verification**:
    - [ ] Successful check-ins are logged with the performing staff member's name.
    - [ ] Failed attempts (like TC-04 and TC-06) are logged with the error reason.
    - [ ] Timestamp of the log matches the test time.

## 4. Troubleshooting
- **Camera not opening**: Check browser permissions and ensure no other app is using the camera.
- **QR not scanning**: Ensure sufficient lighting and that the QR code is not partially obscured or too small on the screen.
- **Validation Error**: Check if the APP_KEY has changed (encryption depends on it).

---

## 5. Automated Tests
While this guide focuses on manual testing, you should also run the automated feature tests to ensure core logic integrity:

```bash
php artisan test --filter TicketQrSystemTest
```

This test covers:
- QR code generation security.
- Decryption and payload validation.
- Duplicate check-in prevention at the service level.
- Error handling for invalid signatures.

