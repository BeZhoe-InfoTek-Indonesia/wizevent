# Add CMS Content Management System

## Summary
Implement a comprehensive Content Management System (CMS) to enable administrators to manage visitor-facing content without code changes. This includes banner management, FAQ pages, SEO metadata, payment instructions, promotional countdowns, and email/WhatsApp templates.

## Problem
Currently, content management in the Event Ticket Management System requires code changes. Features like promotional banners, FAQ sections, payment instructions, and marketing communications are hardcoded or nonexistent. Content editors cannot update information independently of developers.

## Solution
Introduce a modular CMS system with following components:
1. **Banner Manager** - Promotional banner display management
2. **FAQ & Information** - Dynamic FAQ section with categories
3. **Payment Bank Manager** - Bank account information for manual payments
4. **Payment Instruction Builder** - Customizable payment instructions per payment method
5. **Promo Countdown** - Timed promotional campaigns with countdown timers
6. **Email/WA Templates** - Template management for notifications

## Key Features
1. **Banner Manager**:
   - Multiple banner types (hero, section, mobile)
   - Scheduling (start/end dates)
   - Responsive image management
   - Click tracking and CTAs

2. **FAQ & Information**:
    - FAQ questions and answers
    - Category organization
    - Search and filtering
    - Multi-language support

3. **Payment Bank Manager**:
   - Bank account details
   - QR code uploads
   - Account validation
   - Active/inactive status

5. **Payment Instruction Builder**:
   - Rich text instructions
   - Payment method templates
   - Multi-language support
   - Conditional display logic

6. **Promo Countdown**:
   - Countdown timer configuration
   - Promotional messaging
   - URL redirects on expiration
   - Multi-platform display

## Impact
- **Affected Specs**: All new CMS capabilities (5 new specs)
- **Affected Code**:
   - New models: `Banner`, `Faq`, `PaymentBank`, `PaymentInstruction`, `PromoCountdown`, `EmailTemplate`
   - New Filament Resources: 5 new resources
  - New Livewire Components: Banner display, FAQ widget, Countdown component
  - Updated email system: Template integration

## Implementation Approach
**Phase 1: Core Content (Priority: High)**
- FAQ & Information

**Phase 2: Marketing & Communication (Priority: Medium)**
- Banner Manager
- Promo Countdown
- Email/WA Templates

**Phase 3: Payment Integration (Priority: High)**
- Payment Bank Manager
- Payment Instruction Builder
