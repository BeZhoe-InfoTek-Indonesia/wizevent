## ADDED Requirements

### Requirement: FAQ Management System
The system SHALL provide a content management system for creating, organizing, and displaying Frequently Asked Questions (FAQs) with category support.

#### Scenario: Create FAQ question
- **WHEN** an authenticated user with `cms.create` permission creates a new FAQ
- **THEN** they SHALL be able to enter a question, detailed answer, category, and display order
- **AND** the FAQ SHALL be saved with `is_active` set to true by default

#### Scenario: FAQ categories
- **WHEN** managing FAQs
- **THEN** the admin SHALL be able to create categories to group related questions
- **AND** each FAQ MUST belong to one category
- **AND** categories SHALL have a name, slug, and display order

#### Scenario: Search FAQs
- **WHEN** a visitor searches for FAQs on the FAQ page
- **THEN** the system SHALL search both questions and answers
- **AND** results SHALL be highlighted with the matching text
- **AND** the search SHALL be case-insensitive

#### Scenario: Filter by category
- **WHEN** a visitor selects a category on the FAQ page
- **THEN** only FAQs belonging to that category SHALL be displayed
- **AND** the category filter SHALL update the URL (e.g., `/faq?category=payments`)
- **AND** the category SHALL be highlighted as active

#### Scenario: Accordion-style display
- **WHEN** FAQs are displayed to visitors
- **THEN** they SHALL be shown in an accordion format where only one answer is visible at a time
- **AND** clicking a question SHALL toggle its answer open/closed
- **AND** opening one question SHALL close other questions (exclusive accordion)

#### Scenario: Reorder FAQs
- **WHEN** an admin reorders FAQs within a category using drag-and-drop in Filament
- **THEN** the display order SHALL be saved and reflected in the visitor FAQ page
- **AND** reordering SHALL not affect FAQs in other categories

#### Scenario: Activate/deactivate FAQ
- **WHEN** an admin toggles the `is_active` status on an FAQ
- **THEN** the FAQ SHALL be immediately shown or hidden from visitors
- **AND** deactivating an FAQ SHALL not delete it from the database

### Requirement: Multi-Language FAQ Support
The system SHALL support FAQ content in multiple languages (English and Indonesian).

#### Scenario: Create FAQ for specific language
- **WHEN** an admin creates a new FAQ
- **THEN** they SHALL be able to select the language (en/id)
- **AND** visitors SHALL only see FAQs in their selected locale

#### Scenario: Language fallback
- **WHEN** a visitor views FAQs in a language that has fewer FAQs than the default language
- **THEN** the system SHALL display the default English FAQs as a fallback
- **AND** the system SHALL clearly indicate which FAQs are translated vs. fallback

#### Scenario: Language-specific categories
- **WHEN** creating FAQ categories
- **THEN** the admin SHALL provide translations for the category name in each supported language
- **AND** the category SHALL display in the visitor's selected language

### Requirement: FAQ Widget Integration
The system SHALL provide a reusable FAQ widget that can be embedded on other pages (e.g., event pages, checkout page).

#### Scenario: Embed FAQ widget
- **WHEN** embedding the FAQ widget on a page
- **THEN** the admin SHALL be able to filter by category or tag
- **AND** the widget SHALL display a specified number of FAQs (e.g., top 5)
- **AND** the widget SHALL link to the full FAQ page for more questions

#### Scenario: Dynamic FAQ context
- **WHEN** displaying FAQs on an event page
- **THEN** the system SHALL show FAQs related to that event's category
- **AND** the FAQ widget SHALL automatically filter relevant questions

### Requirement: FAQ Analytics
The system SHALL track FAQ engagement to help administrators understand which questions are most helpful.

#### Scenario: Track FAQ views
- **WHEN** a visitor expands an FAQ to see its answer
- **THEN** the system SHALL record a view for that FAQ
- **AND** the view count SHALL be displayed in the Filament admin panel

#### Scenario: Track FAQ usefulness
- **WHEN** a visitor views an FAQ answer
- **THEN** the system SHALL display "Was this helpful?" buttons (Yes/No)
- **AND** clicking Yes or No SHALL record feedback for that FAQ
- **AND** the feedback counts SHALL be displayed in the admin panel

#### Scenario: Popular FAQs
- **WHEN** viewing the FAQ list in the admin panel
- **THEN** the system SHALL sort FAQs by view count in a "Popular" section
- **AND** the most-viewed FAQs SHALL appear at the top of the list

### Requirement: FAQ Caching
The system SHALL cache FAQ content to improve performance.

#### Scenario: Cache FAQs by category
- **WHEN** FAQs are requested by a visitor
- **THEN** the system SHALL cache the FAQ list for 30 minutes per category
- **AND** the cache SHALL be invalidated when any FAQ is created, updated, or deleted

#### Scenario: Cache search results
- **WHEN** a visitor searches FAQs
- **THEN** the search results SHALL be cached for 15 minutes
- **AND** subsequent searches with the same term SHALL return cached results
