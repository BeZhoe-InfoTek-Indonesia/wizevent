# cms-templates Specification

## Purpose
TBD - created by archiving change add-cms-content-management. Update Purpose after archive.
## Requirements
### Requirement: Email Template Management
The system SHALL provide a management interface for creating and editing email notification templates.

#### Scenario: Create email template
- **WHEN** an authenticated user with `cms.create` permission creates a new email template
- **THEN** they SHALL be able to configure:
  - Template key (unique identifier, e.g., `order_created`, `payment_approved`)
  - Template name (human-readable, e.g., "Order Confirmation")
  - Email subject line
  - HTML content (rich text editor)
  - Plain text content
  - Available variables (e.g., `{{customer_name}}`, `{{order_number}}`)
- **AND** the template SHALL be saved with `is_active` set to true by default

#### Scenario: Template variables
- **WHEN** editing an email template
- **THEN** the system SHALL provide a sidebar showing all available variables
- **AND** variables SHALL be grouped by category (Order, Event, Customer, etc.)
- **AND** clicking a variable SHALL insert it into the content at the cursor position
- **AND** variables SHALL be rendered with actual data when the email is sent

#### Scenario: Rich text editor
- **WHEN** editing email template content
- **THEN** the admin SHALL use a rich text editor with support for:
  - Text formatting (bold, italic, underline, headings)
  - Lists (ordered and unordered)
  - Links and buttons
  - Images (upload or URL)
  - Tables
  - HTML code view
- **AND** the editor SHALL provide a responsive preview

#### Scenario: Preview email template
- **WHEN** an admin clicks "Preview" on an email template
- **THEN** the system SHALL display the email as it would appear in the recipient's inbox
- **AND** the preview SHALL substitute sample data for variables
- **AND** the preview SHALL be displayed in an iframe to simulate an email client

#### Scenario: Set default template
- **WHEN** an admin marks a template as `is_default`
- **THEN** that template SHALL be used for sending emails unless overridden
- **AND** only one template per key SHALL be marked as default
- **AND** marking a new template as default SHALL automatically unmark the previous default

#### Scenario: Email template localization
- **WHEN** creating an email template
- **THEN** the admin SHALL be able to create versions for multiple languages (en, id)
- **AND** the system SHALL send the email in the recipient's preferred language
- **AND** if a translation is missing, the default English version SHALL be used

### Requirement: WhatsApp Template Management
The system SHALL provide a management interface for WhatsApp message templates.

#### Scenario: Create WhatsApp template
- **WHEN** an authenticated user with `cms.create` permission creates a new WhatsApp template
- **THEN** they SHALL be able to configure:
  - Template key (unique identifier)
  - Template name
  - Message content (plain text with emoji support)
  - Available variables (e.g., `{{customer_name}}`, `{{order_number}}`)
  - WhatsApp Business API template ID (for external approval)
- **AND** the template SHALL be saved with `is_active` set to true by default

#### Scenario: WhatsApp message limits
- **WHEN** creating a WhatsApp template
- **THEN** the system SHALL enforce WhatsApp's character and formatting limits
- **AND** the system SHALL display a character count (1024 character limit)
- **AND** the system SHALL warn if the message exceeds one segment (multiple messages)

#### Scenario: WhatsApp template variables
- **WHEN** editing a WhatsApp template
- **THEN** the system SHALL show available variables compatible with WhatsApp
- **AND** variables SHALL be inserted using double braces: `{{variable_name}}`
- **AND** the system SHALL validate that all used variables are defined

#### Scenario: WhatsApp template preview
- **WHEN** an admin clicks "Preview" on a WhatsApp template
- **THEN** the system SHALL display the message as it would appear in a WhatsApp chat
- **AND** the preview SHALL substitute sample data for variables
- **AND** the preview SHALL match WhatsApp's styling (green bubbles, timestamp, etc.)

### Requirement: Template Versioning
The system SHALL track version history for email and WhatsApp templates.

#### Scenario: Auto-save version on edit
- **WHEN** an admin saves changes to a template
- **THEN** the system SHALL automatically create a version snapshot of the previous content
- **AND** the version SHALL include: content, subject, created_at, created_by
- **AND** the system SHALL store up to 50 versions per template

#### Scenario: View version history
- **WHEN** an admin views a template in the Filament admin
- **THEN** they SHALL be able to see a list of all versions
- **AND** each version SHALL show the creator, date, and a diff summary
- **AND** the admin SHALL be able to compare two versions side-by-side

#### Scenario: Restore previous version
- **WHEN** an admin restores a previous version
- **THEN** the system SHALL restore the content from that version
- **AND** a new version SHALL be created for the restoration
- **AND** the system SHALL prompt for confirmation before restoring

### Requirement: Template Testing
The system SHALL allow administrators to test templates before sending them to customers.

#### Scenario: Send test email
- **WHEN** an admin clicks "Send Test" on an email template
- **THEN** the system SHALL open a modal to enter a test email address
- **AND** the system SHALL send the email with sample data
- **AND** the test email SHALL be marked as "TEST" in the subject line
- **AND** the admin SHALL receive feedback on successful delivery

#### Scenario: Send test WhatsApp message
- **WHEN** an admin clicks "Send Test" on a WhatsApp template
- **THEN** the system SHALL open a modal to enter a test phone number
- **AND** the system SHALL send the WhatsApp message with sample data
- **AND** the system SHALL display delivery status (sent, delivered, failed)

#### Scenario: Test data configuration
- **WHEN** sending a test email or WhatsApp message
- **THEN** the admin SHALL be able to customize the sample data for variables
- **AND** this SHALL allow testing with realistic data (e.g., specific customer names)
- **AND** the test data SHALL be saved for future tests

### Requirement: Template Variables
The system SHALL provide a comprehensive set of variables for dynamic content.

#### Scenario: Order variables
- **WHEN** using an order-related template (e.g., `order_created`, `payment_approved`)
- **THEN** the following variables SHALL be available:
  - `{{customer_name}}` - Customer's full name
  - `{{customer_email}}` - Customer's email address
  - `{{order_number}}` - Unique order identifier
  - `{{order_total}}` - Total amount with currency
  - `{{order_items}}` - List of purchased tickets
  - `{{order_date}}` - Order creation date
  - `{{event_name}}` - Event title
  - `{{event_date}}` - Event date and time
  - `{{event_location}}` - Event venue and address
  - `{{ticket_qr_codes}}` - QR codes for tickets (if applicable)

#### Scenario: Event variables
- **WHEN** using an event-related template (e.g., `event_published`, `event_updated`)
- **THEN** the following variables SHALL be available:
  - `{{event_name}}` - Event title (already updated)
  - `{{event_description}}` - Event description
  - `{{event_date}}` - Event date and time
  - `{{event_location}}` - Event venue
  - `{{event_url}}` - URL to event page
  - `{{event_image}}` - Event banner image URL
  - `{{ticket_types}}` - List of available ticket types and prices

#### Scenario: Payment variables
- **WHEN** using a payment-related template (e.g., `payment_rejected`, `payment_approved`)
- **THEN** the following variables SHALL be available:
  - `{{payment_amount}}` - Payment amount
  - `{{payment_method}}` - Payment method (bank transfer, e-wallet, etc.)
  - `{{payment_date}}` - Payment date and time
  - `{{bank_name}}` - Bank name (for manual payment)
  - `{{account_number}}` - Bank account number
  - `{{rejection_reason}}` - Reason for rejection (if applicable)

#### Scenario: System variables
- **WHEN** using any template
- **THEN** the following system variables SHALL always be available:
  - `{{company_name}}` - Platform name (e.g., "{{ config('app.name') }}")
  - `{{company_email}}` - Support email address
  - `{{company_phone}}` - Support phone number
  - `{{current_year}}` - Current year
  - `{{unsubscribe_url}}` - Unsubscribe link (for marketing emails)
  - `{{terms_url}}` - Terms of Service URL
  - `{{privacy_url}}` - Privacy Policy URL

### Requirement: Template Analytics
The system SHALL track template performance and delivery metrics.

#### Scenario: Track email opens
- **WHEN** a recipient opens an email
- **THEN** the system SHALL record an open event for that template
- **AND** the open count SHALL be displayed in the Filament admin panel
- **AND** open tracking SHALL use a transparent 1x1 pixel image

#### Scenario: Track email clicks
- **WHEN** a recipient clicks a link in an email
- **THEN** the system SHALL record a click event for that template
- **AND** the system SHALL redirect the user to the original URL
- **AND** the click count SHALL be displayed in the admin panel

#### Scenario: Calculate open rate and click-through rate
- **WHEN** viewing template analytics
- **THEN** the system SHALL calculate:
  - Open rate: (opens / sent) * 100
  - Click-through rate (CTR): (clicks / opens) * 100
- **AND** these metrics SHALL help identify effective templates

#### Scenario: Track WhatsApp delivery
- **WHEN** a WhatsApp message is sent
- **THEN** the system SHALL track the delivery status via the WhatsApp Business API
- **AND** statuses SHALL include: sent, delivered, read, failed
- **AND** the delivery counts SHALL be displayed in the admin panel

### Requirement: Template Categories
The system SHALL organize templates into categories for easier management.

#### Scenario: Template categories
- **WHEN** viewing the template list in the Filament admin
- **THEN** templates SHALL be organized into categories:
  - Order Notifications (order_created, payment_approved, etc.)
  - Event Notifications (event_published, event_updated, etc.)
  - Marketing (promotions, newsletters)
  - System (password reset, email verification)
- **AND** the admin SHALL be able to filter by category

#### Scenario: Category-based permissions
- **WHEN** assigning template permissions
- **THEN** the system SHALL support category-based permissions
- **AND** roles SHALL be granted access to specific categories (e.g., Marketing role can only edit Marketing templates)
- **AND** unauthorized categories SHALL be hidden from the template list

### Requirement: Template Caching
The system SHALL cache compiled templates to improve performance.

#### Scenario: Cache compiled templates
- **WHEN** a template is sent for the first time
- **THEN** the system SHALL compile the template with variable substitution
- **AND** the compiled template SHALL be cached for 1 hour
- **AND** subsequent sends SHALL use the cached version
- **AND** the cache SHALL be invalidated when the template is updated

