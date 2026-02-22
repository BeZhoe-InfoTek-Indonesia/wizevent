# cms-promo-countdown Specification

## Purpose
TBD - created by archiving change add-cms-content-management. Update Purpose after archive.
## Requirements
### Requirement: Promotional Countdown Timer
The system SHALL provide a countdown timer component for time-limited promotional campaigns.

#### Scenario: Create promo countdown
- **WHEN** an authenticated user with `cms.create` permission creates a new promo countdown
- **THEN** they SHALL be able to configure:
  - Promo title
  - Countdown target date and time
  - Countdown message (e.g., "Sale ends in:")
  - Post-expiration message
  - Redirect URL (where users go after countdown ends)
  - Display location (home, events, checkout, all pages)
  - Banner background image or color
- **AND** the countdown SHALL be saved with `is_active` set to false by default

#### Scenario: Real-time countdown display
- **WHEN** a promo countdown is active
- **THEN** the system SHALL display a countdown timer showing:
  - Days remaining
  - Hours remaining
  - Minutes remaining
  - Seconds remaining
- **AND** the countdown SHALL update every second using Alpine.js or JavaScript
- **AND** the countdown SHALL display in the visitor's local time zone

#### Scenario: Countdown expiration behavior
- **WHEN** the countdown reaches zero (target time)
- **THEN** the system SHALL:
  - Hide the countdown timer
  - Display the post-expiration message (if configured)
  - Automatically redirect users to the configured URL (if set)
- **AND** the countdown SHALL not display again unless reactivated

#### Scenario: Countdown on home page
- **WHEN** a promo countdown is configured to display on the home page
- **THEN** the countdown SHALL appear as a banner bar at the top of the page
- **AND** the banner SHALL be dismissible by users (with a close button)
- **AND** the dismissal SHALL be stored in localStorage to not show again

#### Scenario: Countdown on event listing
- **WHEN** a promo countdown is configured to display on the events listing page
- **THEN** the countdown SHALL appear as a hero banner or section banner
- **AND** the countdown SHALL highlight featured events that are part of the promo
- **AND** events SHALL have a "Flash Sale" badge if they're discounted

#### Scenario: Countdown on checkout page
- **WHEN** a promo countdown is configured to display on the checkout page
- **THEN** the countdown SHALL create urgency during the purchase process
- **AND** the countdown SHALL show the time remaining to claim the discount
- **AND** the countdown SHALL be persistent and not dismissible

#### Scenario: Multiple countdowns
- **WHEN** multiple promo countdowns are active with different display locations
- **THEN** the system SHALL display each countdown in its configured location
- **AND** countdowns SHALL not conflict with each other
- **AND** if multiple countdowns target the same location, only the highest priority one shall display

#### Scenario: Countdown styling
- **WHEN** configuring a promo countdown
- **THEN** the admin SHALL be able to customize:
  - Background color or gradient
  - Text color
  - Font size and weight
  - Border radius and shadow
- **AND** the styling SHALL match the site's branding

### Requirement: Promo Code Integration
The system SHALL integrate countdown timers with promo code systems.

#### Scenario: Auto-apply promo code
- **WHEN** a countdown timer is active and linked to a promo code
- **THEN** the promo code SHALL be automatically applied to the user's cart
- **AND** the discount SHALL be reflected in the order total
- **AND** a message SHALL display: "Promo code FLASH50 has been applied!"

#### Scenario: Promo code validation
- **WHEN** a promo countdown is linked to a promo code
- **THEN** the system SHALL validate that the promo code is:
  - Active (within its valid date range)
  - Not expired
  - Not exceeded its usage limit
- **AND** if validation fails, the countdown SHALL display an "Offer Expired" message

#### Scenario: Countdown-specific promo code
- **WHEN** a promo code is linked to a countdown
- **THEN** the promo code SHALL only be valid during the countdown period
- **AND** after the countdown ends, the promo code SHALL be automatically deactivated
- **AND** the promo code SHALL not be usable after expiration

### Requirement: Countdown Analytics
The system SHALL track countdown performance and user engagement.

#### Scenario: Track countdown views
- **WHEN** a visitor views a page with an active countdown
- **THEN** the system SHALL record a view event for that countdown
- **AND** the view count SHALL be displayed in the Filament admin panel
- **AND** views SHALL be debounced to avoid counting multiple views from the same session

#### Scenario: Track countdown dismissals
- **WHEN** a visitor dismisses a countdown banner
- **THEN** the system SHALL record a dismissal event for that countdown
- **AND** the dismissal rate SHALL be calculated and displayed in the admin panel
- **AND** high dismissal rates SHALL indicate the countdown is intrusive

#### Scenario: Track countdown conversions
- **WHEN** a visitor completes a purchase while a countdown is active
- **THEN** the system SHALL attribute the conversion to the countdown
- **AND** the conversion count SHALL be displayed in the admin panel
- **AND** the conversion rate SHALL be calculated (conversions / views)

#### Scenario: Compare countdown performance
- **WHEN** viewing the countdown list in the admin panel
- **THEN** countdowns SHALL be sortable by views, dismissals, conversions, and conversion rate
- **AND** the admin SHALL be able to identify the most effective countdowns
- **AND** the system SHALL suggest the best performing countdown settings

### Requirement: Countdown Scheduling
The system SHALL support scheduled activation and deactivation of countdown timers.

#### Scenario: Schedule countdown activation
- **WHEN** an admin sets a future start date for a countdown
- **THEN** the countdown SHALL not be displayed until the start date is reached
- **AND** a scheduled job SHALL automatically activate the countdown at the specified time

#### Scenario: Schedule countdown deactivation
- **WHEN** an admin sets an end date for a countdown
- **THEN** the countdown SHALL automatically deactivate at the specified time
- **AND** the post-expiration behavior SHALL be triggered (message/redirect)
- **AND** the countdown SHALL move to an "Expired" status in the admin panel

#### Scenario: Recurring countdowns
- **WHEN** an admin creates a recurring countdown (e.g., weekly flash sale)
- **THEN** the system SHALL automatically activate and deactivate the countdown on a schedule
- **AND** the schedule SHALL support daily, weekly, or monthly recurrence
- **AND** each recurrence SHALL be tracked separately in analytics

### Requirement: Countdown Mobile Optimization
The system SHALL provide an optimized countdown experience for mobile devices.

#### Scenario: Mobile countdown banner
- **WHEN** a countdown is displayed on a mobile device
- **THEN** the countdown SHALL appear as a compact banner at the top of the screen
- **AND** the countdown SHALL display days, hours, minutes (hide seconds to save space)
- **AND** the countdown SHALL be dismissible with a tap

#### Scenario: Mobile countdown styling
- **WHEN** viewing a countdown on a mobile device
- **THEN** the font size SHALL be smaller and optimized for readability
- **AND** the countdown SHALL use touch-friendly targets (minimum 44px)
- **AND** the countdown SHALL not obstruct important content

#### Scenario: Push notification on countdown expiration
- **WHEN** a user has subscribed to push notifications and a countdown expires
- **THEN** the system SHALL send a push notification with the post-expiration message
- **AND** tapping the notification SHALL redirect to the configured URL
- **AND** this SHALL encourage users to return before missing the offer

### Requirement: Countdown Localization
The system SHALL support countdown timers in multiple languages.

#### Scenario: Localized countdown labels
- **WHEN** a visitor views a countdown in a specific language
- **THEN** the countdown labels SHALL be displayed in that language
- **AND** time units SHALL be localized (e.g., "days" -> "hari" in Indonesian)
- **AND** the countdown message SHALL use the correct language

#### Scenario: Localized promo code messages
- **WHEN** a promo countdown links to a promo code
- **THEN** the promo code application message SHALL be in the visitor's selected language
- **AND** the message SHALL be translated and culturally appropriate

### Requirement: Countdown Caching
The system SHALL cache countdown data to improve performance.

#### Scenario: Cache countdown configuration
- **WHEN** countdown timers are requested by visitors
- **THEN** the system SHALL cache the countdown configuration for 1 minute
- **AND** the cache SHALL include the target time, message, and styling
- **AND** the cache SHALL be invalidated when any countdown is created, updated, or deleted

#### Scenario: Server-side countdown validation
- **WHEN** a countdown is displayed
- **THEN** the server SHALL validate the target time before serving the countdown
- **AND** if the countdown has expired on the server, it SHALL not be sent to the client
- **AND** this SHALL prevent time-based exploits (e.g., manipulating client-side time)

