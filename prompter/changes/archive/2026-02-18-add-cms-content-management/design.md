# CMS Content Management System Design

## Overview
A modular CMS system built on Laravel 11 and Filament v4.7, enabling content editors to manage visitor-facing content independently. The system follows a pattern of Filament Resources for admin management and Livewire components for visitor display.

## Architecture

### Component Structure
```
CMS System
├── Content Pages
│   ├── Static Pages (About, Privacy, Terms)
│   ├── Landing Pages
│   └── FAQ System
├── Navigation
│   └── Two-level Menu Builder
├── Marketing
│   ├── Banner Manager
│   └── Promo Countdown
├── Payment Instructions
│   ├── Bank Accounts
│   └── Instruction Templates
├── Communication
│   ├── Email Templates
│   └── WhatsApp Templates
└── SEO
    └── Metadata Management
```

### Database Schema Considerations

**Banners Table**:
- `id`, `title`, `type` (hero/section/mobile), `image_path`
- `link_url`, `link_target` (_self/_blank)
- `position`, `is_active`, `start_date`, `end_date`
- `click_count`, `impression_count`
- Indexes: `is_active`, `start_date`, `end_date`

**Faqs Table**:
- `id`, `category_id`, `question`, `answer`, `order`, `is_active`
- Indexes: `category_id`, `is_active`, `order`

**FaqCategories Table**:
- `id`, `name`, `slug`, `order`, `is_active`

**PaymentBanks Table**:
- `id`, `bank_name`, `account_number`, `account_holder`
- `qr_code_path`, `logo_path`, `is_active`, `order`
- Indexes: `is_active`, `order`

**PaymentInstructions Table**:
- `id`, `payment_method` (transfer/bank/ewallet), `content` (rich text)
- `is_active`, `locale` (for i18n)

**PromoCountdowns Table**:
- `id`, `title`, `message`, `target_date`, `url`
- `is_active`, `display_location` (home/events/all)

**EmailTemplates Table**:
- `id`, `key`, `name`, `subject`, `html_content`, `text_content`
- `variables` (JSON), `locale`, `is_default`

**CmsPages Table**:
- `id`, `title`, `slug`, `content` (JSON blocks), `status`
- `seo_title`, `seo_description`, `og_image`
- `published_at`, `created_by`, `updated_by`

**NavigationItems Table**:
- `id`, `parent_id`, `label`, `url`, `icon`
- `order`, `is_active`, `target` (_self/_blank)
- Indexes: `parent_id`, `order`, `is_active`

**SeoMetadata Table**:
- `id`, `page_type`, `page_id`, `title`, `description`
- `keywords`, `og_image`, `canonical_url`
- Indexes: `page_type`, `page_id`

## Data Flow

### Content Publishing Flow
1. **Draft Creation**: Admin creates content in Filament Resource
2. **Preview**: Admin reviews content with preview mode
3. **Publishing**: Set status to published, publish_at timestamp set
4. **Cache Invalidation**: Clear related cache keys
5. **Visitor Display**: Livewire components fetch from database with eager loading

### Navigation Rendering Flow
1. **Fetch**: Retrieve all active navigation items ordered by parent_id and order
2. **Build Tree**: Recursively build two-level tree structure
3. **Render**: Livewire component renders navigation in visitor interface
4. **Cache**: Cache navigation for 15 minutes to reduce queries

### Banner Display Flow
1. **Query**: Fetch active banners matching current date/time
2. **Filter**: Filter by type (hero/section) and device (mobile/desktop)
3. **Track**: Log impression (async job to avoid blocking)
4. **Render**: Display banner with CTA button
5. **Click**: Log click event on button click

## UI/UX Considerations

### Filament Admin Interface
- **Modals**: Use slideover for quick edits, modal for detailed forms
- **Live Preview**: Show preview pane for banners and landing pages
- **Drag-and-Drop**: Reorder navigation items, banners, FAQs
- **Rich Text Editor**: TinyMCE or CKEditor for content editing
- **Image Uploads**: Cropping tool for banners and OG images
- **Bulk Actions**: Activate/deactivate multiple items at once

### Visitor Interface
- **Banners**: Auto-rotating carousel for hero banners
- **FAQ**: Accordion-style with search and category filters
- **Countdown**: Real-time countdown using Alpine.js
- **Navigation**: Responsive with mobile bottom bar
- **Loading States**: Skeleton screens during content fetch

## Performance Considerations

### Caching Strategy
1. **Navigation**: Cache for 15 minutes, invalidate on CRUD operations
2. **FAQs**: Cache for 30 minutes, invalidate on updates
3. **Banners**: Cache for 5 minutes, more frequent due to scheduling
4. **SEO Metadata**: Cache per page for 1 hour
5. **Pages**: Cache published pages with ETag for conditional requests

### Database Optimization
1. **Indexing**: Add composite indexes for frequently queried fields
2. **Eager Loading**: Prevent N+1 queries in banner and FAQ display
3. **Pagination**: For admin tables (Navigation, FAQs, Banners)
4. **Soft Deletes**: All CMS tables use soft deletes for recovery

### Asset Optimization
1. **Image Resizing**: Use intervention/image for responsive banner images
2. **Lazy Loading**: Load banners below fold on scroll
3. **CDN**: Upload images to S3-compatible storage in production

## Security Considerations

### Input Validation
1. **XSS Prevention**: Sanitize all user-generated content
2. **SQL Injection**: Use Eloquent with parameter binding
3. **File Uploads**: Validate MIME types, file sizes, and dimensions
4. **CSRF Protection**: All forms use Laravel CSRF tokens

### Access Control
1. **Permissions**:
   - `cms.view` - View CMS content
   - `cms.create` - Create new content
   - `cms.edit` - Edit existing content
   - `cms.delete` - Delete content
   - `cms.publish` - Publish/unpublish content
2. **Role Assignment**: Super Admin and Event Manager have full access

### Audit Logging
1. **Activity Log**: Track all CMS CRUD operations
2. **Version History**: Track page revisions with diff capability
3. **Soft Deletes**: Prevent accidental data loss

## Internationalization (i18n)

### Multi-Language Support
1. **Content Pages**: Translatable fields (title, content)
2. **FAQs**: Language-specific questions/answers
3. **Email Templates**: Separate templates per locale
4. **Payment Instructions**: Different instructions per language
5. **SEO Metadata**: Localized meta tags

### Implementation Approach
- Use Laravel's translation system for static labels
- Store translatable content in separate tables or JSON columns
- Fallback to English (`en`) if translation missing

## Integration Points

### Existing Systems
1. **Event Management**: Link banners to specific events
2. **Payment Verification**: Display payment instructions based on selected bank
3. **Email System**: Use email templates for all notifications
4. **SEO System**: Auto-inject meta tags in visitor pages

### External Integrations
1. **WhatsApp**: Use WhatsApp Business API for template messages
2. **Analytics**: Integrate with Google Analytics for banner tracking
3. **CDN**: Use S3-compatible storage for images

## Migration Plan

### Phase 1: Database Setup
1. Create all CMS migrations
2. Run seeders for default content
3. Create model relationships

### Phase 2: Admin Interface
1. Create Filament Resources
2. Implement form validation
3. Add permissions and policies

### Phase 3: Visitor Interface
1. Create Livewire components
2. Implement caching layer
3. Add preview functionality

### Phase 4: Integration
1. Link to existing features
2. Update email system to use templates
3. Add SEO meta tags to visitor pages

## Risks & Mitigations

### Performance Risks
- **Risk**: Too many banners/FAQs causing slow page load
- **Mitigation**: Implement pagination, lazy loading, and aggressive caching

### Content Quality Risks
- **Risk**: Poor quality content affects user experience
- **Mitigation**: Implement approval workflow, content guidelines

### Security Risks
- **Risk**: XSS attacks through rich text content
- **Mitigation**: Sanitize HTML, use allowlist for tags

### Migration Risks
- **Risk**: Breaking existing navigation/hardcoded content
- **Mitigation**: Keep old content as fallback, gradual migration

## Open Questions
1. Should we implement a full page builder or simpler block-based content?
2. Do we need approval workflow for content publishing?
3. Should banners be linked to specific events or globally applicable?
4. Do we need A/B testing capability for banners?
5. Should email templates support conditional logic?
