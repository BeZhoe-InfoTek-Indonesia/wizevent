# Design: QR Code Check-in System

## Architecture Overview
The QR code system consists of three main parts:
1.  **Generation (Backend)**: Encrypting ticket data and signing it.
2.  **Display (Visitor Portal)**: Rendering the QR code and providing offline access.
3.  **Validation (Staff Portal)**: A mobile-friendly scanner that decrypts and verifies the ticket in real-time.

## Key Components

### 1. TicketService Enhancement
-   **Security**: Use Laravel's `Crypt` facade which uses AES-256-CBC and includes a HMAC for integrity.
-   **Payload**: The QR content will contain a JSON payload with `ticket_id`, `ticket_number`, and a `signature` (though `Crypt` handles this, we can add an extra layer if required by EPIC specifics).
-   **Method**: `generateQrCodeContent(Ticket $ticket)` and `validateQrCodeContent(string $content)`.

### 2. Scanner Interface (Livewire/Alpine.js)
-   **Camera Integration**: Use `html5-qrcode` or simple browser API with Alpine.js to access the camera.
-   **Feedback Loop**:
    -   Scan -> Send to Backend (Livewire) -> Validate -> Return Status.
    -   Frontend shows Green/Red screen overlays based on status.
-   **Performance**: Optimize the Livewire roundtrip to ensure sub-5-second validation.

### 3. Service Worker (Visitor Portal)
-   Cache the visitor's tickets and the QR code images (or the library to generate them client-side) to allow entry even without internet access.

## Data Flow
1.  **Ticket Issued**: `TicketService` creates a record and generates the encrypted QR payload.
2.  **Visitor Accesses Ticket**: Dashboard renders QR code from the payload.
3.  **Staff Scans**: 
    -   Camera captures QR.
    -   Data sent to server.
    -   Server decrypts, checks `status === 'active'`.
    -   Server updates `status = 'used'`, `checked_in_at = now()`.
    -   Success response sent back.

## Trade-offs & Decisions
-   **Online vs. Offline Validation**: The EPIC requires real-time validation to prevent duplicate scans. This necessitates an internet connection for the *staff device*. Offline access is specifically for the *visitor* to show their ticket.
-   **Library Choice**: `simplesoftwareio/simple-qrcode` for generation; `html5-qrcode` (JS) for scanning.
