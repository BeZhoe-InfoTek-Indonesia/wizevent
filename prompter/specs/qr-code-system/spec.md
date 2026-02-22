# qr-code-system Specification

## Purpose
TBD - created by archiving change implement-qr-checkin. Update Purpose after archive.
## Requirements
### Requirement: Secure QR Code Generation
The system SHALL generate QR codes containing encrypted and signed ticket information to prevent tampering and counterfeiting.

#### Scenario: Generate encrypted QR payload
- **GIVEN** a valid `Ticket` record
- **WHEN** the system generates the QR content
- **THEN** it MUST use AES-256 encryption
- **AND** it MUST include a cryptographic signature (HMAC-SHA256)
- **AND** the payload MUST include the `ticket_number` and `id`

### Requirement: QR Code Accessibility
The visitor portal SHALL display the QR code for all active tickets and ensure they are accessible offline.

#### Scenario: Display QR Code in Dashboard
- **GIVEN** an authenticated visitor with an active ticket
- **WHEN** they view their ticket in the dashboard
- **THEN** the QR code for that ticket is rendered clearly

#### Scenario: Offline Access via Service Worker
- **GIVEN** a visitor has previously viewed their ticket online
- **WHEN** they are offline and revisit the ticket page
- **THEN** the system MUST display the cached ticket and QR code

### Requirement: Real-time QR Scanning & Validation
The system SHALL provide a mobile-optimized scanner for staff to validate tickets in real-time.

#### Scenario: Successful Check-in
- **GIVEN** a check-in staff is using the mobile scanner
- **WHEN** they scan a valid, active QR code
- **THEN** the system MUST validate the encryption and signature
- **AND** mark the ticket as `used` with the current timestamp
- **AND** display a SUCCESS (Green) feedback screen with ticket holder details

#### Scenario: Duplicate Scan Prevention
- **GIVEN** a ticket that has already been marked as `used`
- **WHEN** the system scans the same QR code again
- **THEN** the system MUST display an ERROR (Red) feedback screen
- **AND** state the reason "Ticket already used"

#### Scenario: Invalid or Tampered QR Code
- **GIVEN** a QR code that cannot be decrypted or has an invalid signature
- **WHEN** the system scans it
- **THEN** the system MUST display an ERROR (Red) feedback screen
- **AND** state the reason "Invalid ticket"

### Requirement: Manual Fallback
The scanner interface SHALL allow manual entry of the ticket number.

#### Scenario: Manual validation
- **GIVEN** a QR code is unscannable (e.g., damaged screen)
- **WHEN** the staff enters the `ticket_number` manually
- **THEN** the system MUST perform the same validation logic as the QR scan

