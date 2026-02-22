## ADDED Requirements

### Requirement: Banner Management System
The system SHALL provide a management interface for creating, scheduling, and displaying promotional banners across the visitor interface.

#### Scenario: Create banner
- **WHEN** an authenticated user with `cms.create` permission creates a new banner
- **THEN** they SHALL be able to configure:
  - Banner type (hero/section/mobile)
  - Title and subtitle text
  - Banner image (upload or select)
  - Call-to-action button (label and URL)
  - Link target (_self/_blank)
  - Display position (order)
  - Start and end dates for scheduling
- **AND** the banner SHALL be saved with `is_active` set to false by default

#### Scenario: Hero banner type
- **WHEN** creating a banner with type "hero"
- **THEN** the banner SHALL be displayed in the full-width hero section on the home page
- **AND** the image SHALL be responsive (desktop 1920x600, tablet 1024x500, mobile 640x400)
- **AND** the hero banner SHALL support a carousel with auto-rotation

#### Scenario: Section banner type
- **WHEN** creating a banner with type "section"
- **THEN** the banner SHALL be displayed in a section below the hero (e.g., between events and footer)
- **AND** the section banner SHALL support full-width or contained layout
- **AND** multiple section banners SHALL be displayed in a grid

#### Scenario: Mobile banner type
- **WHEN** creating a banner with type "mobile"
- **THEN** the banner SHALL only be displayed on mobile devices (screen width < 768px)
- **AND** the mobile banner SHALL be a fixed bottom bar or full-width banner
- **AND** the mobile banner SHALL have a dismiss/close button

#### Scenario: Banner scheduling
- **WHEN** an admin sets start and end dates for a banner
- **THEN** the banner SHALL only be displayed during the scheduled time window
- **AND** banners SHALL not be displayed before the start date or after the end date
- **AND** a scheduled job SHALL automatically activate/deactivate banners based on dates

#### Scenario: Banner carousel for hero section
- **WHEN** multiple hero banners are active
- **THEN** the hero section SHALL display a carousel with all active banners
- **AND** the carousel SHALL auto-rotate every 5 seconds
- **AND** visitors SHALL be able to manually navigate with arrows or dots
- **AND** the carousel SHALL pause on hover

#### Scenario: Banner click tracking
- **WHEN** a visitor clicks on a banner's call-to-action button
- **THEN** the system SHALL record a click event for that banner
- **AND** the click count SHALL be updated asynchronously (queued job)
- **AND** the admin SHALL be able to view click statistics in the Filament admin panel

#### Scenario: Banner impression tracking
- **WHEN** a banner is displayed to a visitor
- **THEN** the system SHALL record an impression event for that banner
- **AND** the impression SHALL be debounced to avoid counting multiple views from the same session
- **AND** the admin SHALL be able to view impression statistics in the Filament admin panel

#### Scenario: Activate/deactivate banner
- **WHEN** an admin toggles the `is_active` status on a banner
- **THEN** the banner SHALL be immediately shown or hidden from visitors
- **AND** the cache SHALL be invalidated to reflect the change

### Requirement: Banner Image Management
The system SHALL provide tools for managing banner images, including resizing and optimization.

#### Scenario: Upload banner image
- **WHEN** uploading a banner image
- **THEN** the system SHALL validate the file type (JPG, PNG, WEBP)
- **AND** the system SHALL validate the file size (max 5MB)
- **AND** the system SHALL automatically resize the image to fit banner dimensions
- **AND** the resized image SHALL be optimized for web (compressed quality)

#### Scenario: Generate responsive images
- **WHEN** a banner image is uploaded
- **THEN** the system SHALL generate multiple sizes:
  - Desktop: 1920px width (max 600px height)
  - Tablet: 1024px width (max 500px height)
  - Mobile: 640px width (max 400px height)
- **AND** the system SHALL use the srcset attribute to serve the appropriate size
- **AND** this SHALL improve page load performance

#### Scenario: Image cropping tool
- **WHEN** uploading a banner image
- **THEN** the admin SHALL be able to crop the image within the Filament admin
- **AND** the cropping tool SHALL show aspect ratio guidelines
- **AND** the admin SHALL see a preview of the cropped image

### Requirement: Banner Targeting
The system SHALL support banner targeting based on page context and user attributes.

#### Scenario: Page-specific banners
- **WHEN** creating a banner
- **THEN** the admin SHALL be able to select which pages the banner appears on
- **AND** options SHALL include: Home, Events, Event Detail, Checkout, All Pages
- **AND** the banner SHALL only appear on the selected pages

#### Scenario: Event-specific banners
- **WHEN** creating a banner linked to a specific event
- **THEN** the banner SHALL only appear on that event's detail page
- **AND** the banner SHALL inherit the event's SEO metadata if not overridden
- **AND** the banner's CTA button SHALL link to the event's purchase page

#### Scenario: User role targeting
- **WHEN** creating a banner
- **THEN** the admin SHALL be able to target specific user roles (Guest, Visitor, etc.)
- **AND** the banner SHALL only be displayed to users with the targeted role
- **AND** unauthenticated users SHALL see the "Guest" banner

### Requirement: Banner Analytics
The system SHALL provide analytics dashboard for banner performance.

#### Scenario: Click-through rate (CTR)
- **WHEN** viewing banner analytics in the admin panel
- **THEN** the system SHALL calculate and display the click-through rate (CTR)
- **AND** CTR SHALL be calculated as: (clicks / impressions) * 100
- **AND** the CTR SHALL be displayed as a percentage

#### Scenario: Compare banner performance
- **WHEN** viewing the banner list in the admin panel
- **THEN** banners SHALL be sortable by impressions, clicks, and CTR
- **AND** the admin SHALL be able to identify the best-performing banners
- **AND** low-performing banners SHALL be highlighted for review

#### Scenario: Time-based analytics
- **WHEN** viewing banner analytics
- **THEN** the admin SHALL be able to filter by date range
- **AND** the system SHALL display a chart showing impressions and clicks over time
- **AND** the admin SHALL identify peak performance periods

### Requirement: Banner A/B Testing
The system SHALL support A/B testing to optimize banner performance.

#### Scenario: Create A/B test variant
- **WHEN** an admin creates an A/B test for a banner
- **THEN** they SHALL be able to create multiple variants (A, B, C, etc.)
- **AND** each variant SHALL have different images, text, or CTAs
- **AND** the system SHALL randomly assign visitors to see one variant

#### Scenario: Track variant performance
- **WHEN** an A/B test is running
- **THEN** the system SHALL track impressions and clicks for each variant
- **AND** the admin SHALL be able to compare CTR across variants
- **AND** the winning variant SHALL be highlighted with a "Best Performer" badge

#### Scenario: Declare winning variant
- **WHEN** an A/B test has conclusive results
- **THEN** the admin SHALL be able to declare a winning variant
- **AND** the system SHALL automatically activate the winning variant for all visitors
- **AND** other variants SHALL be archived but kept for reference

### Requirement: Banner Caching
The system SHALL cache banner data to improve performance.

#### Scenario: Cache active banners
- **WHEN** banners are requested by visitors
- **THEN** the system SHALL cache the active banner list for 5 minutes
- **AND** the cache SHALL be keyed by page type (home, events, etc.)
- **AND** the cache SHALL be invalidated when any banner is created, updated, or deleted

#### Scenario: Cache banner images
- **WHEN** serving banner images
- **THEN** the system SHALL use browser caching headers (Cache-Control)
- **AND** images SHALL be cached for 1 week
- **AND** image URLs SHALL include version hashes for cache busting
