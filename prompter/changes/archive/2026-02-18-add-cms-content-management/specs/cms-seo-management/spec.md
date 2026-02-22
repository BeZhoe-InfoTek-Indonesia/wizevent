## ADDED Requirements

### Requirement: SEO Metadata Management
The system SHALL provide a management interface for configuring SEO metadata for pages and content types.

#### Scenario: Configure page-level SEO
- **WHEN** creating or editing a page (static page, event, or custom page)
- **THEN** the admin SHALL be able to configure:
  - SEO Title (up to 60 characters)
  - Meta Description (up to 160 characters)
  - Meta Keywords (comma-separated)
  - Canonical URL
- **AND** these metadata SHALL be injected into the page's `<head>` section

#### Scenario: Open Graph tags
- **WHEN** configuring SEO metadata for a page
- **THEN** the admin SHALL be able to upload or select an Open Graph (OG) image
- **AND** the system SHALL generate the following OG tags:
  - `og:title`
  - `og:description`
  - `og:image`
  - `og:url`
  - `og:type` (website/article)
  - `og:site_name`
- **AND** the OG image SHALL be automatically resized to 1200x630px if larger

#### Scenario: Twitter Card tags
- **WHEN** configuring SEO metadata for a page
- **THEN** the system SHALL generate Twitter Card tags compatible with Twitter's summary cards:
  - `twitter:card` (summary_large_image)
  - `twitter:title`
  - `twitter:description`
  - `twitter:image`

#### Scenario: Dynamic meta tags for events
- **WHEN** an event page is rendered
- **THEN** the system SHALL auto-generate SEO meta tags from event data:
  - Title: `{Event Name} - {Venue Name} | TicketRed`
  - Description: `{Event Date} at {Location}. {First 150 chars of description}`
  - OG Image: Event banner image
- **AND** the admin SHALL be able to override these auto-generated values

#### Scenario: Noindex/nofollow control
- **WHEN** creating a page that should not be indexed by search engines
- **THEN** the admin SHALL be able to enable `noindex` and `nofollow` meta tags
- **AND** the system SHALL add `<meta name="robots" content="noindex, nofollow">` to the page head

#### Scenario: Structured data (schema.org)
- **WHEN** configuring an event page
- **THEN** the system SHALL automatically generate JSON-LD structured data for:
  - Event schema (Event)
  - Organization schema (Organization)
  - BreadcrumbList schema (Breadcrumbs)
- **AND** the structured data SHALL pass Google's Rich Results Test

### Requirement: Sitemap Generation
The system SHALL automatically generate XML sitemaps for search engine indexing.

#### Scenario: Generate sitemap
- **WHEN** the sitemap URL (`/sitemap.xml`) is requested
- **THEN** the system SHALL generate an XML sitemap including:
  - Static pages (About, Privacy, Terms, etc.)
  - All published events
  - All published content pages
  - Category pages (events by category)
- **AND** each URL SHALL include `<lastmod>` timestamp and `<changefreq>` (daily/weekly/monthly)
- **AND** the sitemap SHALL be cached for 1 hour

#### Scenario: Dynamic sitemap entries
- **WHEN** a new event is published or an existing event is updated
- **THEN** the sitemap cache SHALL be invalidated
- **AND** the next sitemap request SHALL include the new/updated event

#### Scenario: Sitemap index for large sites
- **WHEN** the sitemap contains more than 50,000 URLs
- **THEN** the system SHALL generate a sitemap index file
- **AND** the index SHALL link to multiple sitemap files (sitemaps 1, 2, 3, etc.)
- **AND** each sitemap file SHALL contain up to 50,000 URLs

### Requirement: Robots.txt Management
The system SHALL provide a dynamic `robots.txt` file that can be configured through the admin panel.

#### Scenario: Default robots.txt
- **WHEN** no custom configuration is provided
- **THEN** the system SHALL serve a default `robots.txt` allowing all bots
- **AND** the file SHALL include the sitemap URL

#### Scenario: Custom robots.txt rules
- **WHEN** an admin configures custom robots.txt rules in the settings
- **THEN** the system SHALL serve those rules instead of the default
- **AND** the admin SHALL be able to:
  - Block specific bots (User-agent)
  - Disallow specific paths (Disallow)
  - Allow specific paths (Allow)
  - Set crawl-delay (Crawl-delay)

### Requirement: SEO Analytics Integration
The system SHALL integrate with Google Analytics for tracking page performance.

#### Scenario: Google Analytics tracking
- **WHEN** a visitor views any page
- **THEN** the system SHALL inject the Google Analytics tracking script
- **AND** the tracking code SHALL be configurable in settings
- **AND** the system SHALL support GA4 (Google Analytics 4)

#### Scenario: Page view events
- **WHEN** a page is viewed
- **THEN** the system SHALL send a page_view event to Google Analytics
- **AND** the event SHALL include:
  - Page title
  - Page location (URL)
  - Page referrer
  - Custom dimensions (user role, locale, etc.)

### Requirement: URL Structure Management
The system SHALL support SEO-friendly URL structures for all content types.

#### Scenario: Clean URLs
- **WHEN** creating any content (page, event, etc.)
- **THEN** the system SHALL automatically generate a URL-friendly slug from the title
- **AND** the slug SHALL be lowercase, alphanumeric, with hyphens for spaces
- **AND** special characters SHALL be removed

#### Scenario: Slug uniqueness validation
- **WHEN** creating or editing content with a slug that already exists
- **THEN** the system SHALL validate and prevent duplicate slugs
- **AND** the admin SHALL see an error message: "This slug is already in use"
- **AND** the admin SHALL be prompted to choose a unique slug

#### Scenario: Redirect old URLs
- **WHEN** a slug is changed for existing content
- **THEN** the system SHALL create a 301 redirect from the old slug to the new slug
- **AND** visitors and search engines SHALL be automatically redirected
- **AND** the redirect SHALL preserve SEO value

### Requirement: Multi-Language SEO
The system SHALL support SEO metadata in multiple languages.

#### Scenario: Language-specific meta tags
- **WHEN** configuring SEO metadata for a page
- **THEN** the admin SHALL be able to enter metadata for each supported language
- **AND** the system SHALL inject the metadata in the visitor's selected language
- **AND** hreflang tags SHALL be included for alternate language versions

#### Scenario: Language-specific sitemaps
- **WHEN** generating sitemaps
- **THEN** the system SHALL generate separate sitemaps for each language
- **AND** each sitemap SHALL contain only URLs for that language
- **AND** the sitemap index SHALL reference all language-specific sitemaps
