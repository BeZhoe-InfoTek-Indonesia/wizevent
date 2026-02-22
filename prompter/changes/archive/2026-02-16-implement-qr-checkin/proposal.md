# Proposal: Implement QR Code System for Check-in

## Problem Statement
The system needs a secure and efficient way to validate tickets at venue entry points. Currently, while there is some basic QR code generation and validation logic in `TicketService`, there is no dedicated scanner interface for check-in staff, and the security requirements (HMAC-SHA256) and performance goals (<5 sec) need to be formally implemented and validated.

## Proposed Changes
1.  **Enhance QR Security**: Update `TicketService` to ensure QR code content is generated using AES-256 encryption with HMAC-SHA256 signatures for tamper-proofing.
2.  **Visitor Dashboard Integration**: Ensure QR codes are easily accessible in the visitor portal, including offline support via a service worker.
3.  **Mobile Scanner Interface**: Create a sub-second mobile-optimized scanner interface for check-in staff using HTML5 camera API.
4.  **Real-time Validation**: Implement the backend logic to handle scan results, prevent duplicate entries, and provide instant visual feedback (Green for Success, Red for Error).
5.  **Manual Entry Fallback**: Provide a way for staff to manually enter ticket numbers if the QR code is unscannable.

## Impact
-   **Security**: Prevents fraudulent tickets through cryptographic verification.
-   **Efficiency**: Reduces check-in time significantly.
-   **User Experience**: Provides a smooth entry process for attendees and easy-to-use tools for staff.

## Spec Deltas
-   `prompter/changes/implement-qr-checkin/specs/qr-code-system/spec.md`: New specification for the QR check-in system.
