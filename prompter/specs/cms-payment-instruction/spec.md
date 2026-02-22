# cms-payment-instruction Specification

## Purpose
TBD - created by archiving change add-cms-content-management. Update Purpose after archive.
## Requirements
### Requirement: Payment Bank Manager
The system SHALL provide a management interface for managing bank account information for manual payments.

#### Scenario: Add bank account
- **WHEN** an authenticated user with `cms.create` permission adds a new bank account
- **THEN** they SHALL be able to configure:
  - Bank name (e.g., BCA, Mandiri, BNI)
  - Account number
  - Account holder name
  - Bank logo (upload)
  - QR code image (upload, optional)
  - Is active status
  - Display order
- **AND** the bank account SHALL be saved with `is_active` set to true by default

#### Scenario: Bank account validation
- **WHEN** adding or editing a bank account
- **THEN** the system SHALL validate that:
  - Bank name is not empty
  - Account number contains only digits (10-16 characters)
  - Account holder name is provided
  - Bank logo is uploaded (JPG, PNG, WEBP)
- **AND** validation errors SHALL be displayed in the Filament form

#### Scenario: Display bank accounts at checkout
- **WHEN** a customer reaches the payment selection step
- **THEN** the system SHALL display all active bank accounts
- **AND** each bank SHALL show:
  - Bank logo
  - Bank name
  - Account number (with partial masking for security, e.g., "1234-****-5678")
  - Account holder name
  - QR code (if uploaded)
- **AND** the customer SHALL be able to select one bank for payment

#### Scenario: Reorder bank accounts
- **WHEN** an admin reorders bank accounts using drag-and-drop in Filament
- **THEN** the display order SHALL be saved and reflected in the checkout page
- **AND** the first bank account SHALL be pre-selected by default

#### Scenario: Activate/deactivate bank account
- **WHEN** an admin toggles the `is_active` status on a bank account
- **THEN** the bank account SHALL be immediately shown or hidden from the checkout page
- **AND** deactivating a bank account SHALL not delete it from the database

#### Scenario: Bank account QR code
- **WHEN** a bank account has an uploaded QR code image
- **THEN** the QR code SHALL be displayed prominently at checkout
- **AND** the customer SHALL be able to scan the QR code with their banking app
- **AND** clicking the QR code SHALL open a lightbox with a larger image

### Requirement: Payment Instruction Builder
The system SHALL provide a rich text editor for creating customizable payment instructions.

#### Scenario: Create payment instructions
- **WHEN** an authenticated user with `cms.create` permission creates payment instructions
- **THEN** they SHALL be able to configure:
  - Payment method (bank transfer, e-wallet, etc.)
  - Instruction title
  - Instruction content (rich text)
  - Language (en, id)
  - Is active status
- **AND** the instructions SHALL be displayed to customers after selecting that payment method

#### Scenario: Rich text editor for instructions
- **WHEN** editing payment instruction content
- **THEN** the admin SHALL use a rich text editor with support for:
  - Text formatting (bold, italic, lists)
  - Headings and subheadings
  - Numbered and bulleted lists
  - Links to external resources (e.g., bank website)
  - Images (e.g., step-by-step screenshots)
- **AND** the editor SHALL provide a responsive preview

#### Scenario: Language-specific instructions
- **WHEN** creating payment instructions
- **THEN** the admin SHALL be able to create separate instructions for each supported language
- **AND** customers SHALL see instructions in their selected language
- **AND** if a translation is missing, the default English version SHALL be displayed

#### Scenario: Conditional display logic
- **WHEN** creating payment instructions
- **THEN** the admin SHALL be able to set conditions for displaying instructions:
  - Display only for specific banks
  - Display only for orders above a certain amount
  - Display only for specific events
- **AND** the system SHALL evaluate conditions at checkout and show relevant instructions

#### Scenario: Preview payment instructions
- **WHEN** an admin clicks "Preview" on payment instructions
- **THEN** the system SHALL display the instructions as they would appear to customers
- **AND** the preview SHALL simulate the checkout page layout
- **AND** the preview SHALL be responsive (mobile and desktop views)

### Requirement: Payment Method Templates
The system SHALL provide pre-built templates for common payment methods.

#### Scenario: Bank transfer template
- **WHEN** an admin selects "Bank Transfer" as a template
- **THEN** the system SHALL pre-populate instructions with:
  - Step 1: Select your bank
  - Step 2: Enter the account number
  - Step 3: Transfer the exact amount
  - Step 4: Upload your payment proof
- **AND** the admin SHALL be able to customize the template

#### Scenario: E-wallet template
- **WHEN** an admin selects "E-wallet" as a template
- **THEN** the system SHALL pre-populate instructions with:
  - Step 1: Open your e-wallet app (GoPay, OVO, Dana, etc.)
  - Step 2: Select "Transfer to Bank Account"
  - Step 3: Enter the account number and amount
  - Step 4: Confirm the transfer
- **AND** the admin SHALL be able to customize the template

#### Scenario: QRIS template
- **WHEN** an admin selects "QRIS" as a template
- **THEN** the system SHALL pre-populate instructions with:
  - Step 1: Scan the QR code with your mobile banking or e-wallet app
  - Step 2: Enter the exact amount
  - Step 3: Confirm the payment
  - Step 4: Upload your payment proof
- **AND** the admin SHALL be able to customize the template

### Requirement: Payment Instruction Display
The system SHALL display clear, user-friendly payment instructions at checkout.

#### Scenario: Instructions modal
- **WHEN** a customer selects a payment method at checkout
- **THEN** the system SHALL display the instructions in a modal or expandable section
- **AND** the modal SHALL include:
  - Step-by-step instructions
  - Bank account details
  - QR code (if applicable)
  - Copy-to-clipboard button for account number
  - "I have completed the payment" button
- **AND** the modal SHALL be dismissible but re-accessible

#### Scenario: Copy account number
- **WHEN** a customer clicks the "Copy" button next to an account number
- **THEN** the system SHALL copy the account number to the clipboard
- **AND** a toast notification SHALL display: "Account number copied!"
- **AND** the copy button SHALL show a "Copied!" state temporarily

#### Scenario: Instructional images
- **WHEN** payment instructions include images (e.g., screenshots)
- **THEN** the images SHALL be displayed in a gallery or carousel
- **AND** clicking an image SHALL open a lightbox with a larger view
- **AND** the images SHALL be optimized for mobile (responsive)

#### Scenario: Video instructions
- **WHEN** payment instructions include a YouTube or Vimeo video URL
- **THEN** the system SHALL embed the video player in the instructions
- **AND** the video SHALL be autoplay-muted to respect browser policies
- **AND** the video SHALL be responsive

### Requirement: Payment Verification Integration
The system SHALL integrate payment instructions with the payment verification workflow.

#### Scenario: Link instructions to payment proof upload
- **WHEN** a customer has completed payment (after viewing instructions)
- **THEN** the system SHALL display a "Upload Payment Proof" button
- **AND** clicking the button SHALL open the file upload modal
- **AND** the system SHALL guide the customer to upload a screenshot or photo of their transfer

#### Scenario: Payment proof requirements
- **WHEN** uploading payment proof
- **THEN** the system SHALL display requirements based on the selected payment method:
  - For bank transfer: Screenshot of transfer confirmation with account number, amount, and date visible
  - For QRIS: Screenshot of the payment confirmation screen
- **AND** the system SHALL validate that the uploaded image meets these requirements

#### Scenario: Instruction-based rejection reasons
- **WHEN** a finance admin rejects a payment proof
- **THEN** they SHALL be able to select rejection reasons based on the payment instructions:
  - "Account number does not match"
  - "Transfer amount is incorrect"
  - "Proof is unclear or incomplete"
  - "Transfer date is outside the allowed window"
- **AND** these reasons SHALL be sent to the customer in a rejection email

### Requirement: Payment Analytics
The system SHALL track payment method usage and instruction effectiveness.

#### Scenario: Track payment method selection
- **WHEN** a customer selects a payment method at checkout
- **THEN** the system SHALL record the selection for analytics
- **AND** the admin SHALL be able to view which payment methods are most popular
- **AND** the system SHALL display a chart showing payment method distribution

#### Scenario: Track instruction completion
- **WHEN** a customer clicks "I have completed the payment" after viewing instructions
- **THEN** the system SHALL record this event
- **AND** the admin SHALL be able to see how many customers viewed instructions vs. completed payment
- **AND** low completion rates SHALL indicate unclear instructions

#### Scenario: Track payment success rate by method
- **WHEN** payments are verified (approved or rejected)
- **THEN** the system SHALL track the success rate for each payment method
- **AND** the admin SHALL be able to see which payment methods have the highest approval rate
- **AND** methods with low approval rates SHALL be flagged for review

### Requirement: Multi-Currency Support
The system SHALL support bank accounts and instructions in multiple currencies.

#### Scenario: Multi-currency bank accounts
- **WHEN** adding a bank account
- **THEN** the admin SHALL be able to specify the currency (IDR, USD, EUR, etc.)
- **AND** the system SHALL display the account number and currency at checkout
- **AND** customers SHALL only see bank accounts that match their order currency

#### Scenario: Currency-specific instructions
- **WHEN** creating payment instructions
- **THEN** the admin SHALL be able to specify the applicable currency
- **AND** customers SHALL see instructions relevant to their order currency
- **AND** the system SHALL automatically convert amounts if displaying in a different currency

### Requirement: Payment Instruction Caching
The system SHALL cache payment instructions to improve performance.

#### Scenario: Cache instructions by payment method
- **WHEN** payment instructions are requested at checkout
- **THEN** the system SHALL cache the instructions for 15 minutes per payment method
- **AND** the cache SHALL be keyed by payment method and language
- **AND** the cache SHALL be invalidated when instructions are created, updated, or deleted

#### Scenario: Cache bank accounts
- **WHEN** bank accounts are requested at checkout
- **THEN** the system SHALL cache the active bank account list for 10 minutes
- **AND** the cache SHALL include only active accounts
- **AND** the cache SHALL be invalidated when a bank account is created, updated, or deleted

