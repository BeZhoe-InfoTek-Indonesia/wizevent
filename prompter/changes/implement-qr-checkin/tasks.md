# Tasks: Implement QR Code Check-in System

## Phase 1: Security & Service Enhancement
1.  **Strengthen Encryption**: [x] Update `TicketService::generateQrCode` to explicitly incorporate HMAC-SHA256 if standard `encrypt()` is deemed insufficient, or verify standard `encrypt()` meets requirements. [Requirement: Secure QR Code Generation]
2.  **Add Validation Logic**: [x] Ensure `TicketService::validateQrCode` checks for all failure modes (expired, used, cancelled, invalid sig). [Requirement: Real-time QR Scanning & Validation]
3.  **Unit Tests**: [x] Write tests for encryption/decryption/validation edge cases.

## Phase 2: Visitor Portal Enhancements
4.  **Display QR Codes**: [x] Update the Visitor Dashboard/Ticket view to display the QR code using `simplesoftwareio/simple-qrcode`. [Requirement: QR Code Accessibility]
5.  **Offline Support**: [x] Implement a basic service worker to cache the ticket view and QR assets. [Requirement: QR Code Accessibility]

## Phase 3: Staff Scanner Interface
6.  **Create Scanner Component**: [x] Scaffold a new Livewire component `App\Livewire\Admin\TicketScanner`. [Requirement: Real-time QR Scanning & Validation]
7.  **Implement Camera Integration**: [x] Use Alpine.js and `html5-qrcode` (or similar) to capture scans. [Requirement: Real-time QR Scanning & Validation]
8.  **Add Feedback UI**: [x] Design the Green/Red feedback screens in the scanner UI. [Requirement: Real-time QR Scanning & Validation]
9.  **Manual Entry**: [x] Add a form field for manual ticket number entry to the scanner interface. [Requirement: Manual Fallback]

## Phase 4: Integration & UX
10. **Filament Integration**: [x] Add the Scanner as a custom page or action in the Filament Admin panel.
11. **Check-in Logging**: [x] Ensure `activitylog` records all check-in attempts (success/failure).
12. **Final Validation**: [x] End-to-end testing of scan process on mobile hardware.
