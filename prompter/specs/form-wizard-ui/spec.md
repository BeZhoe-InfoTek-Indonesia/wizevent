# form-wizard-ui Specification

## Purpose
TBD - created by archiving change refactor-event-form-wizard. Update Purpose after archive.
## Requirements
### Requirement: Basic Information Step
The Event form SHALL provide a "Basic Information" step containing title, slug, category, short description, and detailed description fields.

#### Scenario: Step 1 - Basic Information
- When navigating to the first step "Basic Information"
- Containing:
  - `Title` (Standard TextInput, required)
  - `Slug` (Standard TextInput, disabled/unique)
  - `Category` (Select with existing `categories` relation)
  - `Short Description` (New Textarea/TextInput, optional/required?)
  - `Detailed Description` (Existing `RichEditor` for `description`)

### Requirement: Location & Time Step
The Event form SHALL provide a "Location & Time" step containing city, map, coordinates, and event timing fields.

#### Scenario: Step 2 - Location & Time
- When navigating to the second step "Location & Time"
- Containing:
  - `City` (Select with search/preload)
  - `Map` (Leaflet/MapPicker)
  - `Latitude/Longitude` (TextInputs, synced with map)
  - `Event Start & End Time` (DateTimePickers)

### Requirement: Media & SEO Step
The Event form SHALL provide a "Media & SEO" step containing banner, promo image, and SEO metadata fields.

#### Scenario: Step 3 - Media & SEO
- When navigating to the third step "Media & SEO"
- Containing:
  - `Event Banner` (FileUpload, 2:1 aspect ratio hint, existing `banner` morphological relation)
  - `Promo Image` (New FileUpload, simple image, likely 1:1 or 4:3, saved to `FileBucket` or `promo_image_path`)
  - **SEO Metadata Section (Polymorphic)**:
    -   `Meta Title` (TextInput -> `seoMetadata.title`)
    -   `SEO Description` (Textarea -> `seoMetadata.description`)
    -   `OG Image` (FileUpload -> `seoMetadata.og_image`)
    -   (Filament handles the creation of the `seo_metadata` record via relationship)

### Requirement: Sales Configuration Step
The Event form SHALL provide a "Sales Configuration" step containing sales timing, ticket types, quota, and seating options.

#### Scenario: Step 4 - Sales Configuration
- When navigating to the fourth step "Sales Configuration"
- Containing:
  - `Sales Start/End Time` (DateTimePickers)
  - `Ticket Type` (Repeater, existing logic) contains VIP/Regular options via select/text input
  - `Quota` (Maybe total event capacity? Or sum of ticket types? The prompt asks for "Quota", let's assume `total_capacity` field)
  - `Seating` (Toggle `seating_enabled` if any)

### Requirement: Organizer & Payment Step
The Event form SHALL provide a final "Organizer" step containing organizers, performers, and payment bank information.

#### Scenario: Step 5 - Organizer & Payment
- When navigating to the final step "Organizer"
- Containing:
  - `Organizers` (Select multiple, relationship `organizers`)
  - `Performers/Speakers` (Select multiple, relationship `performers`)
  - **Payment Bank Information (Multiple)**:
    -   `Select::make('paymentBanks')`
    -   `relationship('paymentBanks', 'bank_name')`
    -   `multiple()`
    -   `preload()` (Displays all active banks)

