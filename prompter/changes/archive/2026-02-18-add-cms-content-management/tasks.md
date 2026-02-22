# Add CMS Content Management System

## Phase 1: Database Setup
- [x] Create migration for `cms_pages` table
- [x] Create migration for `faqs` table
- [x] Create migration for `faq_categories` table
- [x] Create migration for `seo_metadata` table
- [x] Create migration for `banners` table
- [x] Create migration for `promo_countdowns` table
- [x] Create migration for `email_templates` table
- [x] Create migration for `whatsapp_templates` table
- [x] Create migration for `template_versions` table
- [x] Create migration for `payment_banks` table
- [x] Create migration for `payment_instructions` table
- [x] Create models with relationships
- [x] Create model factories and seeders
- [x] Run migrations and seed initial data

## Phase 2: Static Pages CMS
- [x] Create `CmsPageResource` Filament resource
- [x] Implement block-based content editor (text, image, video, HTML)
- [x] Add SEO metadata fields (title, description, OG image)
- [x] Implement page status workflow (draft, published)
- [ ] Create Livewire component for page display (`CmsPageView`)
- [ ] Add visitor route for dynamic pages (`/pages/{slug}`)
- [ ] Implement page caching
- [ ] Add permission policies (`cms.view`, `cms.create`, `cms.edit`, `cms.delete`, `cms.publish`)
- [ ] Create page templates (About, Privacy, Terms, Contact)
- [ ] Test page creation, editing, and publishing

## Phase 3: FAQ Management
- [ ] Create `FaqCategoryResource` Filament resource
- [ ] Create `FaqResource` Filament resource
- [ ] Implement category management with ordering
- [ ] Create Livewire component for FAQ display (`FaqSection`)
- [ ] Implement search and filtering (by category)
- [ ] Implement accordion-style display (one answer open at a time)
- [ ] Add FAQ widget for embedding on other pages
- [ ] Implement view and feedback tracking
- [ ] Add FAQ caching (30-minute cache)
- [ ] Create visitor route (`/faq`)
- [ ] Test FAQ creation, search, and display

## Phase 4: SEO Management
- [ ] Create `SeoMetadataResource` Filament resource
- [ ] Add SEO fields to existing resources (Event, CmsPage)
- [ ] Implement Open Graph tag generation
- [ ] Implement Twitter Card tag generation
- [ ] Add SEO metadata to visitor page layouts
- [ ] Create sitemap generator (`/sitemap.xml`)
- [ ] Create dynamic `robots.txt` route
- [ ] Generate structured data (JSON-LD) for events
- [ ] Test SEO metadata injection and sitemap generation

## Phase 5: Banner Management
- [ ] Create `BannerResource` Filament resource
- [ ] Implement banner types (hero, section, mobile)
- [ ] Add banner scheduling (start/end dates)
- [ ] Implement image upload with resizing (intervention/image)
- [ ] Create Livewire component for hero banner carousel (`HeroBanner`)
- [ ] Create Livewire component for section banners (`SectionBanner`)
- [ ] Create Livewire component for mobile banners (`MobileBanner`)
- [ ] Implement click and impression tracking (queued jobs)
- [ ] Add banner targeting (page-specific, event-specific)
- [ ] Implement banner caching (5-minute cache)
- [ ] Test banner creation, scheduling, and display

## Phase 6: Promo Countdown
- [ ] Create `PromoCountdownResource` Filament resource
- [ ] Create Livewire component for countdown display (`PromoCountdown`)
- [ ] Implement real-time countdown using Alpine.js
- [ ] Add server-side validation for expiration
- [ ] Implement post-expiration behavior (message/redirect)
- [ ] Add display location configuration (home, events, checkout)
- [ ] Integrate with promo code system (auto-apply discounts)
- [ ] Implement countdown analytics (views, dismissals, conversions)
- [ ] Create scheduled job for countdown activation/deactivation
- [ ] Test countdown creation, expiration, and display

## Phase 7: Email/WA Templates
- [ ] Create `EmailTemplateResource` Filament resource
- [ ] Create `WhatsappTemplateResource` Filament resource
- [ ] Implement rich text editor (TinyMCE or CKEditor)
- [ ] Add template variable sidebar with insertion
- [ ] Implement template preview functionality
- [ ] Create template versioning system (auto-save on edit)
- [ ] Add test email/WA message functionality
- [ ] Define template variables (Order, Event, Payment, System)
- [ ] Integrate with existing email system
- [ ] Add localization support (multi-language templates)
- [ ] Track template analytics (opens, clicks)
- [ ] Update existing notification mailables to use templates
- [ ] Test template creation, preview, and sending

## Phase 8: Payment Instructions
- [ ] Create `PaymentBankResource` Filament resource
- [ ] Create `PaymentInstructionResource` Filament resource
- [ ] Implement bank account management with validation
- [ ] Add QR code upload support
- [ ] Implement rich text editor for instructions
- [ ] Create payment method templates (Bank Transfer, E-wallet, QRIS)
- [ ] Add conditional display logic (specific banks, amounts, events)
- [ ] Update checkout page to display bank accounts and instructions
- [ ] Implement "Copy to clipboard" for account numbers
- [ ] Add instruction preview functionality
- [ ] Integrate with payment verification workflow
- [ ] Track payment analytics (method selection, success rate)
- [ ] Add payment instruction caching
- [ ] Test payment bank management and instruction display at checkout

## Phase 9: Permissions & Security
- [ ] Create CMS permissions (`cms.view`, `cms.create`, `cms.edit`, `cms.delete`, `cms.publish`)
- [ ] Assign permissions to roles (Super Admin, Event Manager)
- [ ] Create policies for all CMS resources
- [ ] Add activity logging for all CMS CRUD operations
- [ ] Implement file upload validation (MIME types, sizes, dimensions)
- [ ] Add XSS protection for rich text content
- [ ] Test role-based access control

## Phase 10: Internationalization
- [ ] Create translation files for CMS admin interface (`lang/en/cms.php`, `lang/id/cms.php`)
- [ ] Add translatable fields support for CmsPages, FAQs, EmailTemplates, PaymentInstructions
- [ ] Implement language fallback (English as default)
- [ ] Add hreflang tags for SEO
- [ ] Create language-specific sitemaps
- [ ] Test multi-language functionality

## Phase 11: Performance Optimization
- [ ] Implement page caching strategy
- [ ] Implement FAQ caching strategy
- [ ] Implement banner caching strategy
- [ ] Implement SEO metadata caching
- [ ] Add eager loading for all resource queries
- [ ] Optimize image sizes (responsive images, WebP)
- [ ] Implement lazy loading for banners and images
- [ ] Run performance tests and optimize slow queries

## Phase 12: Testing & Validation
- [ ] Write unit tests for all CMS models
- [ ] Write feature tests for CMS resources (CRUD operations)
- [ ] Write feature tests for visitor pages (page display, navigation, FAQ, banners)
- [ ] Write feature tests for payment instructions at checkout
- [ ] Write feature tests for template sending (email/WA)
- [ ] Run PHPUnit test suite and fix failing tests
- [ ] Run PHPStan static analysis and fix issues
- [ ] Test mobile responsiveness across all CMS features
- [ ] Perform end-to-end testing with real scenarios

## Phase 13: Documentation
- [ ] Update AGENTS.md with CMS capabilities
- [ ] Create user guide for CMS features
- [ ] Document template variables reference
- [ ] Document SEO best practices for content editors
- [ ] Create screenshots and walkthrough videos
- [ ] Update project documentation

## Post-Implementation
- [ ] Deploy to staging environment
- [ ] Perform smoke testing on staging
- [ ] Gather user feedback and iterate
- [ ] Deploy to production
- [ ] Monitor analytics and performance metrics
- [ ] Create backup and rollback plan
