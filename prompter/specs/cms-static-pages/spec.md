# cms-static-pages Specification

## Purpose
TBD - created by archiving change add-cms-content-management. Update Purpose after archive.
## Requirements
### Requirement: Static Page Management
The system SHALL provide a content management system for creating and managing static pages (About Us, Privacy Policy, Terms of Service, Contact, etc.) through the Filament admin panel.

#### Scenario: Create new static page
- **WHEN** an authenticated user with `cms.create` permission accesses the CmsPage resource in Filament
- **THEN** they SHALL be able to create a new page with title, slug, content blocks, and SEO metadata
- **AND** the page SHALL be saved in draft status by default

#### Scenario: Publish static page
- **WHEN** an admin publishes a static page
- **THEN** the system SHALL set the status to `published` and record the `published_at` timestamp
- **AND** the page SHALL be accessible to visitors via its slug URL

#### Scenario: Edit static page with version control
- **WHEN** an admin edits an existing static page
- **THEN** the system SHALL create a version snapshot of the previous content
- **AND** the admin SHALL be able to view version history and restore previous versions

#### Scenario: SEO metadata for static page
- **WHEN** creating or editing a static page
- **THEN** the admin SHALL be able to configure SEO title, description, keywords, and Open Graph image
- **AND** these metadata SHALL be injected into the visitor page's `<head>` section

#### Scenario: Block-based content editing
- **WHEN** editing a static page's content
- **THEN** the admin SHALL use a block-based editor with support for:
  - Text/Heading blocks
  - Image blocks with captions
  - Video embed blocks
  - HTML code blocks
  - Divider/Spacer blocks

#### Scenario: Preview static page
- **WHEN** an admin clicks "Preview" on a static page
- **THEN** the system SHALL display the page in a modal window as it would appear to visitors
- **AND** the preview SHALL work for both draft and published versions

### Requirement: Page Publishing Workflow
The system SHALL support a simple publishing workflow with draft and published states for static pages.

#### Scenario: Draft pages not accessible to visitors
- **WHEN** a static page has status `draft`
- **THEN** visitors SHALL NOT be able to access the page
- **AND** attempts to access SHALL return a 404 error

#### Scenario: Published pages accessible to visitors
- **WHEN** a static page has status `published` and `published_at` is in the past
- **THEN** visitors SHALL be able to access the page at `/pages/{slug}`
- **AND** the page SHALL display all content blocks and SEO metadata

#### Scenario: Schedule publication
- **WHEN** an admin sets a future `published_at` date on a draft page and publishes it
- **THEN** the page SHALL not be visible until the `published_at` date is reached
- **AND** a scheduled job SHALL automatically publish the page at the specified time

### Requirement: Page Template System
The system SHALL provide page templates for common static page types.

#### Scenario: Use predefined template
- **WHEN** creating a new static page
- **THEN** the admin SHALL be able to select from predefined templates (About, Privacy Policy, Terms of Service, Contact)
- **AND** the selected template SHALL pre-populate the page with appropriate content blocks and structure

#### Scenario: Custom template creation
- **WHEN** an admin frequently creates similar pages
- **THEN** they SHALL be able to save a page as a custom template
- **AND** this template SHALL appear in the template dropdown for future page creation

