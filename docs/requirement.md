# Enhanced Prompt: Event Ticket Management System Development Specification

## Project Overview
Develop a comprehensive event ticket management system using Laravel 11.31 framework with a dual-interface architecture: traditional Blade templates for administrative functions and Laravel Livewire for dynamic user interactions. The system must handle complete event lifecycle management, from creation and ticketing to attendee validation and reporting.

---

## Technical Stack Requirements

### Core Framework
- **Backend**: Laravel 11.31
- **Admin Interface**: Laravel Blade templating engine
- **User Interface**: Laravel Livewire 3.x
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Authentication**: Laravel Breeze + Laravel Sanctum + Google OAuth 2.0
- **Queue System**: Redis (recommended) or database-backed queues
- **Cache**: Redis (recommended) or file/database-based caching
- **Search**: database search

### Additional Libraries
- **QR Code Generation**: `simplesoftwareio/simple-qrcode` or `bacon/bacon-qr-code`
- **QR Code Scanning**: JavaScript-based solution (Html5-QRCode or jsQR)
- **Email Service**: Laravel Mail with SMTP or service provider (Mailgun, SendGrid, Amazon SES)
- **Social Authentication**: `laravel/socialite` package for Google OAuth
- **Role & Permission Management**: `spatie/laravel-permission` for flexible authorization
- **Activity Logging**: `spatie/laravel-activitylog` for comprehensive audit trails
- **Log Viewer**: `opcodesio/log-viewer` for real-time log monitoring
- **File Storage**: Laravel Storage with S3 or local filesystem support
- **Image Processing**: `intervention/image` for photo uploads and optimization

---

## Module 1: Role & Permission Management (Spatie Laravel Permission)

### 1.1 Role Definition & Hierarchy

#### Predefined Roles
**Super Admin Role:**
- Full system access and control
- User management capabilities
- Role and permission assignment
- System configuration access
- Global settings management
- Audit log access
- Log viewer access
- QR code scanning and validation

**Event Manager Role:**
- Create, edit, and delete events
- Manage ticket types and pricing
- Configure venue seating layouts
- Access event analytics and reports
- Manage event notifications
- View attendee information
- QR code scanning for own events

**Finance Admin Role:**
- Payment verification and processing
- Financial reporting access
- Refund processing capabilities
- Revenue analytics
- Transaction audit trails

**Check-in Staff Role:**
- QR code scanning and validation
- Manual check-in override
- View attendee lists
- Real-time entry monitoring
- Problem resolution access

**Visitor/Attendee Role:**
- Event browsing and ticket purchasing
- Event search functionality
- Seat selection for events
- Profile management
- Ticket history access
- Rate testimonials
- Love/favorite events
- Receive notifications
- View QR codes on purchased tickets

#### Permission Structure
**Event Permissions:**
- `events.view` - View event listings
- `events.create` - Create new events
- `events.edit` - Modify existing events
- `events.delete` - Delete events
- `events.publish` - Publish/unpublish events
- `events.analytics` - Access event analytics
- `events.manage-seating` - Configure seating layouts

**Ticket Permissions:**
- `tickets.view` - View ticket information
- `tickets.create` - Create ticket types
- `tickets.edit` - Modify ticket configurations
- `tickets.validate` - Validate/scan QR codes at check-in
- `tickets.manual-checkin` - Manual check-in override
- `tickets.view-qr` - View QR codes on tickets (visitor access)

**User Permissions:**
- `users.view` - View user profiles
- `users.edit` - Edit user information
- `users.delete` - Delete user accounts
- `users.assign-roles` - Assign roles to users
- `users.manage-permissions` - Manage user permissions

**Financial Permissions:**
- `finance.view-reports` - Access financial reports
- `finance.verify-payments` - Verify payment proofs
- `finance.process-refunds` - Process refunds
- `finance.manage-pricing` - Manage event pricing

**System Permissions:**
- `system.view-logs` - Access log viewer
- `system.view-activity` - View activity logs
- `system.manage-settings` - Manage system settings
- `system.manage-notifications` - Manage system notifications

### 1.2 Dynamic Role Management

#### Admin Interface for Role Management
**Role CRUD Operations:**
- Create custom roles with specific permission sets
- Edit existing role permissions
- Deactivate roles (soft delete to maintain audit trail)
- Role duplication for similar permission sets

**Permission Assignment Interface:**
- Granular permission assignment per role
- Bulk permission operations
- Permission inheritance visualization
- Real-time permission preview

**User-Role Management:**
- Assign multiple roles to users
- Role-based access control dashboard
- User permission audit interface
- Role assignment history tracking

#### Security Features
- **Guard Separation**: Different authentication guards for admin and visitor interfaces
- **Permission Caching**: Optimized permission checking with cache layer
- **Audit Logging**: Complete audit trail for role and permission changes (Spatie Activity Log)
- **Permission Middleware**: Route-level permission enforcement
- **Dynamic Permission Checking**: Runtime permission validation in UI components

---

## Module 2: Admin Panel (Blade Templates)

### 2.1 Event Management System

#### Event Creation & Publishing
**Required Fields:**
- Event title (string, max 255 characters, required)
- Event description (rich text editor, required)
- Event date and time (datetime picker with timezone support)
- Event location (address with optional Google Maps integration)
- Event banner image (upload functionality with validation: max 5MB, formats: JPG, PNG, WebP)
- Event status (draft/published/cancelled)
- Seating enabled toggle (boolean)
- Event categories/tags (for search functionality)

**Seating Configuration (when enabled):**
- Venue layout builder (sections, rows, seats)
- Seat categories (VIP, Premium, Standard, etc.)
- Seat pricing per category
- Visual seat map designer
- Seat availability management
- Seat hold/reserve functionality

**Ticket Configuration:**
- Total ticket inventory (integer, min: 1)
- Multiple ticket type support with the following attributes per type:
  - Type name (e.g., "VIP", "Standard", "Early Bird")
  - Base price (decimal, 2 decimal places)
  - Quantity allocated per type
  - Seat category mapping (for seated events)
  - Discount configuration:
    - Discount percentage or fixed amount
    - Discount start/end dates
    - Promo code support (optional)
    - Discount applicability rules

**Functional Requirements:**
- CRUD operations (Create, Read, Update, Delete) for all events
- Bulk actions (publish multiple events, duplicate events)
- Event preview before publishing
- Image optimization and thumbnail generation for banners
- Validation rules for all input fields with clear error messages

#### Notification System (Admin Management)
**Capabilities:**
- Create and send notifications to all users or specific segments
- Notification types:
  - Event announcements
  - Event updates/changes
  - Ticket confirmation
  - Payment status updates
  - System announcements
  - Promotional notifications
- Customizable notification templates
- Schedule notifications for future delivery
- Support for multiple channels:
  - In-app notifications (real-time)
  - Email notifications
  - SMS notifications (integration-ready)
  - Push notifications (web push)
  
**Implementation Details:**
- Queue-based notification delivery to prevent timeout issues
- Configurable notification preferences per user
- Admin dashboard to track notification delivery status
- Failed notification retry mechanism
- Unsubscribe functionality with user preference management
- Notification analytics (open rates, click rates)

#### Manual Ticket Management

**Payment Proof Workflow:**
- Admin interface to review payment proof submissions received via WhatsApp or other channels
- Upload and attach payment proof images/documents to orders
- Order statuses: Pending, Verified, Rejected, Refunded
- Approval workflow:
  1. Admin reviews payment proof
  2. Validates payment details (amount, reference number, date)
  3. Approves or rejects with reason
  4. Automatically deducts ticket inventory upon approval
  5. Sends confirmation notification to user (in-app + email)

**Inventory Management:**
- Real-time ticket availability tracking per event and ticket type
- Seat availability tracking (for seated events)
- Manual inventory adjustment interface with audit logging
- Inventory reservation system (temporary hold during checkout process)
- Low stock alerts for administrators
- Prevent overselling with database-level constraints

#### Reporting & Analytics Dashboard

**Sales Reports:**
- Date range filtering (custom ranges, presets: today, this week, this month, custom)
- Revenue breakdown by:
  - Event
  - Ticket type
  - Seat category
  - Time period (daily, weekly, monthly)
  - Payment method
- Export functionality (CSV, Excel, PDF formats)
- Visual charts and graphs (revenue trends, ticket sales over time)

**Visitor Tracking:**
- Total attendee count per event
- Check-in status tracking (registered, checked-in, no-show)
- Real-time check-in monitoring dashboard
- Attendee demographics (if collected)
- Repeat attendee identification
- Seat occupancy visualization

**Testimonial Management:**
- Interface to collect, moderate, and publish visitor testimonials
- Star rating system (1-5 stars)
- Text feedback with character limits
- Photo uploads from attendees (optional)
- Approval workflow before public display
- Display testimonials on event pages or homepage
- **Testimonial rating analytics** (view how visitors rate testimonials)
- Most helpful testimonials ranking

**Event Love/Favorite Analytics:**
- Track "loved" counts per event
- Popular events dashboard
- Trending events based on love/favorite activity
- User engagement metrics

### 2.2 QR Code Management

**QR Code Generation:**
- QR codes generated for each ticket upon payment approval
- QR codes visible to ticket holders on their digital tickets
- QR code encoding:
  - Ticket ID (encrypted)
  - Event ID
  - User ID (hashed)
  - Seat assignment (if applicable)
  - Digital signature for tampering prevention
  - Timestamp of generation

**Visitor QR Code Display:**
- Full-screen QR code view for easy scanning at venue
- Brightness boost option for better scanning
- Offline access to QR codes
- Download QR code as image
- Share ticket with QR code

**QR Code Scanning Interface (Check-in Staff):**
- Accessible to authorized staff (Super Admin, Event Manager, Check-in Staff)
- Camera access for mobile and desktop devices
- Real-time video feed display
- Instant validation feedback (visual and audio cues)
- Manual ticket number entry fallback option

**Validation Process:**
1. Staff scans QR code using authorized device
2. System extracts and decrypts encoded data
3. Verifies digital signature
4. Queries database for ticket information
5. Validates:
   - Ticket exists and is valid
   - Ticket matches current event
   - Seat assignment correct (for seated events)
   - Not already checked-in
   - Within allowed entry time window
6. Displays result:
   - **Success**: Attendee name, ticket type, seat location (green indicator)
   - **Failure**: Error reason, entry denied (red indicator)
7. Logs entry attempt with timestamp and result

**Admin Scanning Dashboard:**
- Multiple concurrent scanning stations
- Real-time entry statistics
- Seat occupancy visualization
- Problem ticket flagging and resolution
- Manual override capability with reason logging

### 2.3 Activity Log Viewer (Spatie Activity Log)

**Features:**
- Complete audit trail for all system actions
- User action tracking (login, logout, CRUD operations)
- Model change history (before/after values)
- Filterable by:
  - User
  - Action type
  - Date range
  - Model type
- Export activity logs
- Activity log retention policies

### 2.4 System Log Viewer (Opcodes Log Viewer)

**Features:**
- Real-time log monitoring
- Log level filtering (debug, info, warning, error, critical)
- Log search functionality
- Log file rotation management
- Downloadable log files
- Access restricted to Super Admin role

---

## Module 3: User Interface (Laravel Livewire)

### 3.1 Authentication System (Laravel Breeze)

#### Authentication Features
**User Registration:**
- Email/password registration with validation
- Email verification workflow
- Strong password requirements
- CAPTCHA integration (optional)

**User Login:**
- Email/password login
- Remember me functionality
- Login throttling (rate limiting)
- Two-factor authentication (optional)

**Password Management:**
- Forgot password flow
- Password reset via email
- Password confirmation for sensitive actions

**Social Authentication (Google OAuth):**
- Laravel Socialite configuration for Google provider
- OAuth callback handling and user creation/matching
- Secure token storage and refresh mechanism
- Fallback to traditional email/password authentication
- Account linking (connect Google account to existing email account)

**Session Management:**
- Secure session handling with CSRF protection
- Session timeout configuration
- Multi-device login support
- Session revocation capability

### 3.2 Event Search & Discovery

#### Search Functionality
**Search Features:**
- **Global Search Bar**: Prominently displayed in header
- **Full-Text Search**: Search across event titles, descriptions, locations
- **Category Filtering**: Filter by event categories/tags
- **Date Filtering**: Filter by date range
- **Location Filtering**: Filter by city/venue
- **Price Filtering**: Filter by price range
- **Availability Filtering**: Show only events with available tickets

**Search Implementation:**
- Laravel Scout integration for fast search
- Meilisearch or Algolia backend (recommended)
- Database fallback for simpler deployments
- Search suggestions/autocomplete
- Recent searches history
- Popular searches display

**Search Results:**
- Card-based event listings
- Sorting options (date, price, popularity, relevance)
- Pagination with infinite scroll option
- Quick view modal for event details
- Save to favorites directly from results

### 3.3 Comprehensive Visitor Dashboard

#### Dashboard Overview & Navigation
**Main Dashboard Layout:**
- **Header Section**: User profile summary with quick actions
- **Search Bar**: Quick access to event search
- **Navigation Tabs**: Structured access to all dashboard sections
  - Overview (default view)
  - My Tickets
  - Purchase History
  - Loved Events
  - Notifications
  - Profile Settings
- **Quick Action Panel**: Fast access to common tasks
- **Notification Center**: Real-time alerts and updates

**Dashboard Metrics Cards:**
- **Upcoming Events**: Count and next event countdown
- **Total Tickets**: Lifetime ticket purchase count
- **Events Attended**: Historical attendance tracking
- **Loved Events**: Saved events count
- **Unread Notifications**: Notification badge count
- **Account Status**: Membership level and benefits

#### Active Ticket Summary
**Upcoming Events Display:**
- **Event Cards**: Visual cards showing upcoming events with:
  - Event banner thumbnail
  - Event name, date, and venue
  - Ticket type and seat information
  - Seat location (section, row, seat number)
  - Time until event (countdown timer)
  - Directions/Maps integration

**Ticket Management Features:**
- **Digital Ticket View**: Complete ticket with QR code display
- **QR Code Full-Screen**: Optimized display for venue scanning
- **Ticket Information Display**: All ticket details visible
- **Seat Information Display**: Visual seat location indicator
- **Ticket Transfer**: Send tickets to friends/family
- **Add to Google Calendar**: One-click add event to Google Calendar
  - Event title, date, time, and location auto-filled
  - Event description with ticket details
  - Set reminders (1 day before, 2 hours before)
  - Include venue address with Google Maps link
  - Sync updates if event details change
- **Calendar Export Options**:
  - Google Calendar (direct integration)
  - Apple Calendar (.ics file)
- **Offline Access**: Cached ticket and QR code for offline use

### 3.4 Loved Events System (New Feature)

#### Love/Favorite Functionality
**Features:**
- **Heart Icon**: Click to love/unlove an event
- **Love Counter**: Display total loves for each event
- **Loved Events List**: Dedicated section in dashboard
- **Love Notifications**: Optional alerts for loved event updates
- **Love-Based Recommendations**: Suggest similar events

**Implementation:**
- Optimistic UI updates (instant feedback)
- Real-time love count updates via WebSocket
- Persist loves across sessions
- Love history tracking

**Loved Events Dashboard:**
- Grid/list view of all loved events
- Filter by upcoming/past events
- Sort by date loved, event date, popularity
- Quick actions (remove love, buy tickets, share)

### 3.5 Social Sharing Features (New Feature)

#### Event Sharing
**Share Buttons:**
- **Facebook**: Share event with preview image and description
- **Twitter/X**: Tweet event with hashtags and link
- **WhatsApp**: Send event link with message
- **Telegram**: Share to contacts or groups
- **Copy Link**: Copy shareable URL to clipboard
- **Email**: Share via email with pre-filled subject and body

**Share Locations:**
- Share button on event detail pages
- Share from event cards in listings
- Share from loved events dashboard
- Share from purchased tickets

**Deep Links:**
- Unique shareable URLs for each event
- UTM tracking parameters for analytics
- Short URL generation option
- QR code for event link (separate from ticket QR)
 
**User Experience:**
- Native share dialog on mobile (Web Share API)
- Share modal on desktop
- Success confirmation toast
- Share history in user profile

### 3.6 Notification Center (New Feature)

#### In-App Notifications
**Notification Types:**
- **Event Updates**: Changes to events user has tickets for
- **New Events**: Events matching user preferences
- **Ticket Confirmations**: Payment and ticket status
- **Loved Event Alerts**: Updates for loved events
- **System Announcements**: Platform updates
- **Promotional**: Special offers and discounts

**Notification Interface:**
- **Bell Icon**: Notification indicator in header
- **Badge Count**: Unread notification count
- **Dropdown Panel**: Quick view of recent notifications
- **Full Notification Page**: Complete notification history
- **Read/Unread Status**: Visual distinction
- **Mark All as Read**: Bulk action

**Notification Actions:**
- Click to navigate to relevant content
- Dismiss individual notifications
- Archive notifications
- Notification preferences management

**Real-Time Delivery:**
- WebSocket integration for instant notifications
- Browser push notifications (with permission)
- Email notifications (configurable)
- Notification grouping (similar notifications)

### 3.7 Testimonial Rating System (New Feature)

#### Rate Testimonials
**Features:**
- **Helpful/Not Helpful**: Binary rating for testimonials
- **Like Counter**: Display helpful count
- **Rating Influence**: Higher-rated testimonials shown first
- **One Vote Per User**: Prevent duplicate ratings

**Implementation:**
- Rate testimonials on event pages
- Visual feedback on rating action
- Sorted by helpfulness score
- Most helpful testimonials highlighted

### 3.8 Seat Selection System

#### Interactive Seat Map
**Display Features:**
- **Visual Venue Layout**: Interactive SVG-based seat map
- **Section Navigation**: Click to zoom into sections
- **Seat Status Indicators**:
  - Available (green)
  - Selected (blue)
  - Reserved/Held (yellow)
  - Sold (gray)
  - Unavailable (red)
- **Seat Details Tooltip**: Price, category, view rating on hover
- **Legend**: Clear color-coding explanation

**Selection Features:**
- **Click-to-Select**: Single click to select/deselect seats
- **Multi-Seat Selection**: Select multiple seats for group bookings
- **Best Available**: Auto-suggest best available seats based on criteria
- **Accessibility Filter**: Filter for wheelchair-accessible seats
- **Price Filter**: Filter seats by price range
- **View Filter**: Filter by view quality rating

**User Experience:**
- **Real-Time Availability**: Live seat status updates via WebSocket
- **Seat Lock Timer**: 10-minute hold on selected seats during checkout
- **Selection Summary**: Floating panel showing selected seats and total
- **Mobile Optimization**: Pinch-to-zoom, touch-friendly selection
- **Undo Selection**: Easy removal of selected seats

#### Seat Selection Workflow
1. User selects event and ticket quantity
2. If event has seating enabled, redirect to seat selection page
3. User views interactive venue map
4. User selects desired seats (respecting quantity limits)
5. Selected seats are temporarily held (10 minutes)
6. User proceeds to checkout with seat assignments
7. On payment approval, seats are permanently assigned to tickets
8. Seat assignments and QR code displayed on digital tickets

### 3.9 Comprehensive Profile Settings (New Feature)

#### Profile Settings Page
**Account Information:**
- **Profile Photo**: Upload, crop, and manage profile picture
- **Full Name**: Editable display name
- **Email Address**: View/change with verification required
- **Phone Number**: Optional, with verification
- **Date of Birth**: Optional, for age-restricted events
- **Bio**: Short personal description

**Address Management:**
- **Primary Address**: For billing and communication
- **Multiple Addresses**: Save multiple addresses
- **Default Address**: Set default for checkout
- **Address Validation**: Format validation

**Notification Preferences:**
- **Email Notifications**:
  - Event updates (on/off)
  - Promotional emails (on/off)
  - Loved event alerts (on/off)
  - Weekly digest (on/off)
- **In-App Notifications**:
  - All notifications (on/off)
  - Sound alerts (on/off)
  - Desktop notifications (on/off)
- **SMS Notifications**: Phone number required
  - Event reminders (on/off)
  - Urgent updates (on/off)

**Privacy Settings:**
- **Profile Visibility**: Public/Private
- **Show Activity**: Allow others to see activity
- **Data Sharing**: Third-party data sharing consent
- **Download Data**: GDPR data export
- **Delete Account**: Account deletion request

**Preferences:**
- **Language**: Interface language selection
- **Timezone**: User timezone
- **Currency**: Preferred currency display
- **Theme**: Light/Dark/Auto mode
- **Accessibility**: Font size, high contrast, reduced motion

**Security Settings:**
- **Change Password**: Current + new password
- **Two-Factor Authentication**: Enable/disable 2FA
- **Active Sessions**: View and revoke sessions
- **Login History**: Recent login activity
- **Connected Accounts**: Manage social logins (Google)

**Event Preferences:**
- **Favorite Categories**: Select preferred event types
- **Seating Preferences**: Preferred seat locations
- **Accessibility Needs**: Wheelchair, hearing assistance, etc.
- **Companion Preferences**: Usually attend alone/with others

### 3.10 Ticket Event History
**Comprehensive Event Timeline:**
- **Chronological Listing**: All events attended sorted by date
- **Event Status Indicators**:
  - Completed (attended)
  - No-show (purchased but didn't attend)
  - Cancelled (refunded)
  - Transferred (sent to another user)

**Event History Details:**
- **Event Information**: Full event details and descriptions
- **Seat Information**: Historical seat assignments
- **Attendance Verification**: Check-in status (admin-verified)
- **Photo Memories**: Upload and view event photos
- **Event Ratings**: Rate and review attended events
- **Social Sharing**: Share event memories on social platforms
- **Receipts & Downloads**: Access purchase receipts and tax documents

### 3.11 Purchase History & Financial Tracking
**Transaction Management:**
- **Purchase Timeline**: Complete chronological order history
- **Order Status Tracking**: Real-time status updates
  - Pending payment verification
  - Payment confirmed
  - Tickets issued
  - Event completed
  - Refund processed

**Financial Analytics:**
- **Spending Overview**: Monthly/yearly spending on events
- **Savings Tracker**: Discounts and promo codes used
- **Budget Management**: Set spending limits and alerts
- **Tax Documentation**: Annual spending summaries for tax purposes
- **Payment Method Management**: Saved payment methods and preferences

**Order Details:**
- **Receipt Generation**: Detailed receipts with QR codes and seat info
- **Refund Requests**: Submit and track refund requests 

### 3.12 Mobile-Optimized Features
**Touch-Friendly Interface:**
- **Swipe Navigation**: Intuitive gesture controls
- **Pull-to-Refresh**: Natural data synchronization
- **Haptic Feedback**: Tactile responses for interactions
- **Pinch-to-Zoom**: For seat map navigation

**Offline Capabilities:**
- **Offline Ticket Access**: Critical ticket information cached
- **Sync Indicators**: Clear status of data synchronization
- **Progressive Loading**: Graceful degradation for slow connections
- **Background Sync**: Automatic data updates when connection restored

## General Technical Requirements

### 4.1 Code Quality & Maintainability

**Code Standards:**
- Follow PSR-12 coding standards for PHP
- Use Laravel best practices and conventions
- Implement Repository pattern for data access (optional but recommended)
- Service layer for business logic separation
- Dependency injection for testability
- Meaningful variable and function naming
- Comprehensive inline documentation and PHPDoc blocks

**Testing Requirements:**
- Unit tests for critical business logic (PHPUnit)
- Feature tests for API endpoints and workflows
- Browser tests for critical user flows (Laravel Dusk)
- Minimum 70% code coverage target
- Automated testing in CI/CD pipeline

### 4.2 Security Implementation

**Authentication Security:**
- Password hashing using bcrypt (Laravel default)
- CSRF protection on all forms
- XSS prevention (Laravel Blade auto-escaping)
- SQL injection prevention (Eloquent ORM)
- Rate limiting on authentication endpoints
- Two-factor authentication (2FA) option for admin accounts

**QR Code Security:**
- Encryption of sensitive data within QR codes (AES-256)
- HMAC signature verification
- Prevention of QR code tampering
- Secure storage of QR code generation keys
- Audit logging for all QR code validations
- QR scanning restricted by permission (`tickets.scan-qr`)

**Data Protection:**
- GDPR compliance considerations
- Personal data encryption at rest (sensitive fields)
- Secure file upload validation
- Protection against mass assignment vulnerabilities
- Regular security dependency updates

### 4.3 User Interface Design

**Mobile-First Responsive Design Requirements:**

All UX/UI designs **MUST** be fully responsive and optimized for mobile devices. This is a critical requirement driven by:

1. **Mobile User Dominance:**
   - Over 60% of web traffic now comes from mobile devices
   - Event attendees primarily access tickets and check-in features via smartphones
   - QR code scanning is inherently a mobile-first activity
   - Seat selection must work on all device sizes

2. **User Experience Priorities:**
   - Touch-friendly interface elements (minimum tap target: 44x44 pixels)
   - Thumb-zone optimization for primary actions on mobile
   - Simplified navigation patterns for smaller screens (hamburger menu, bottom navigation)
   - Reduced cognitive load with progressive disclosure of information
   - Fast load times critical for mobile networks (target: < 2s on 3G)

3. **Responsive Breakpoints:**
   | Breakpoint | Device Type | Width |
   |------------|-------------|-------|
   | `xs` | Mobile (portrait) | < 576px |
   | `sm` | Mobile (landscape) / Small tablets | 576px - 767px |
   | `md` | Tablets | 768px - 991px |
   | `lg` | Laptops / Desktops | 992px - 1199px |
   | `xl` | Large desktops | ≥ 1200px |

4. **Mobile-Specific Features:**
   - **QR Code Display**: Full-screen mode for easy scanning at venue entry
   - **Ticket Wallet**: Native-like experience for storing and displaying tickets
   - **Seat Map**: Pinch-to-zoom, responsive seat selection on mobile
   - **Camera Integration**: Seamless camera access for QR scanning (check-in staff)
   - **Offline Support**: Critical ticket information cached for offline access
   - **Pull-to-Refresh**: Natural gesture patterns for data updates
   - **Swipe Actions**: Intuitive swipe gestures for list interactions
   - **Search**: Mobile-optimized search interface
   - **Notifications**: Native-like notification experience
   - **Offline Support**: Critical information cached
   - **Pull-to-Refresh**: Natural gesture patterns

5. **Accessibility on Smaller Screens:**
   - Scalable typography (minimum 16px base font size)
   - Sufficient color contrast (WCAG AA: 4.5:1 for text)
   - Readable text without zooming
   - Form inputs with appropriate mobile keyboards (email, numeric, tel)
   - Adequate spacing between interactive elements
   - Support for screen readers and assistive technologies
   - Reduced motion option for users with vestibular disorders

6. **Mobile Performance Considerations:**
   - Lazy loading for images and non-critical content
   - Optimized image formats (WebP with fallbacks)
   - Minimal JavaScript bundle size
   - Critical CSS inlining for above-the-fold content
   - Service worker for caching and offline functionality

**Design Principles:**
- Mobile-first responsive design
- Consistent design language across all pages
- Accessibility compliance (WCAG 2.1 Level AA)
- Intuitive navigation and information architecture
- Loading states and skeleton screens for better UX
- Error handling with user-friendly messages

**Frontend Technologies:**
- Tailwind CSS for styling
- Alpine.js (included with Livewire) for interactivity
- Chart.js for data visualization
- Responsive images with lazy loading
- Progressive Web App (PWA) capabilities (optional)

**Performance Optimization:**
- Asset compilation and minification (Vite)
- Image optimization and WebP format support
- Lazy loading for images and components
- Database query optimization with eager loading
- Caching strategies for frequently accessed data
- CDN integration for static assets

### 4.4 Project Structure & Deployment

**Organized File Structure (Laravel 11):**
```
/project-root
├── /app
│   ├── /Http
│   │   ├── /Controllers
│   │   │   ├── /Admin (Admin controllers - Blade-based)
│   │   │   └── /Api (API controllers)
│   │   ├── /Livewire
│   │   │   ├── /Visitor (Visitor Livewire components)
│   │   │   ├── /Dashboard (Dashboard components)
│   │   │   ├── /SeatSelection (Seat map components)
│   │   │   ├── /Search (Search components)
│   │   │   ├── /Notifications (Notification components)
│   │   │   └── /Shared (Shared components)
│   │   └── /Middleware
│   ├── /Models
│   │   ├── Role.php (Spatie model extension)
│   │   ├── Permission.php (Spatie model extension)
│   │   ├── Venue.php (Venue/seating layout)
│   │   ├── Section.php (Venue sections)
│   │   ├── Seat.php (Individual seats)
│   │   ├── UserPreference.php
│   │   ├── Event.php
│   │   ├── Ticket.php
│   │   ├── Testimonial.php
│   │   ├── TestimonialRating.php
│   │   ├── EventLove.php
│   │   ├── Notification.php
│   │   ├── UserPreference.php
│   │   ├── Venue.php
│   │   ├── Section.php
│   │   └── Seat.php
│   ├── /Services
│   │   ├── RoleService.php
│   │   ├── PermissionService.php
│   │   ├── EventService.php
│   │   ├── SearchService.php
│   │   ├── NotificationService.php
│   │   ├── TestimonialService.php
│   │   ├── SeatService.php
│   │   └── QrCodeService.php
│   ├── /Repositories
│   └── /Mail
├── /bootstrap
│   ├── app.php (Application Configuration, Middleware, Exceptions)
│   └── providers.php (Service Providers)
├── /resources
│   ├── /views
│   │   ├── /admin (Blade templates for admin panel)
│   │   │   ├── /layouts (Admin layout templates)
│   │   │   ├── /events
│   │   │   ├── /seating (Seating configuration views)
│   │   │   ├── /tickets
│   │   │   ├── /reports (Reporting dashboard views)
│   │   │   ├── /activity-logs (Activity log views)
│   │   │   ├── /roles (Role management views)
│   │   │   ├── /permissions (Permission management views)
│   │   │   ├── /users (User management views)
│   │   │   └── /settings (Admin settings views)
│   │   ├── /visitor (Visitor/User interface views)
│   │   │   ├── /layouts (Visitor layout templates)
│   │   │   ├── /livewire (Livewire component views)
│   │   │   ├── /dashboard (Dashboard component views)
│   │   │   ├── /seat-selection (Seat selection views)
│   │   │   ├── /profile (User profile views)
│   │   │   ├── /events (Public event listing views)
│   │   │   └── /tickets (Ticket purchase/display views)
│   │   ├── /components (Shared Blade components)
│   │   └── /emails (Email templates)
│   ├── /css
│   │   ├── /admin (Admin-specific styles)
│   │   └── /visitor (Visitor-specific styles)
│   └── /js
│       ├── /admin (Admin-specific scripts)
│       ├── /seat-map (Seat selection JavaScript)
│       └── /visitor (Visitor-specific scripts)
├── /public
│   ├── /images
│   ├── /uploads (user-generated content)
│   └── /assets (compiled CSS/JS)
├── /database
│   ├── /migrations
│   ├── /seeders
│   └── /factories
├── /tests
│   ├── /Unit
│   ├── /Feature
│   └── /Browser
└── /config (Simplified configuration files)
```

**Interface Separation Explanation:**

The project architecture maintains a clear separation between **Admin** and **Visitor** interfaces:

| Aspect | Admin Interface | Visitor Interface |
|--------|-----------------|-------------------|
| **Technology** | Laravel Blade Templates | Laravel Livewire |
| **Controllers** | `/app/Http/Controllers/Admin` | `/app/Http/Livewire/Visitor` |
| **Views** | `/resources/views/admin` | `/resources/views/visitor` |
| **Styles** | `/resources/css/admin` | `/resources/css/visitor` |
| **Scripts** | `/resources/js/admin` | `/resources/js/visitor` |
| **Routes** | `routes/web.php` (prefix: `/admin`) | `routes/web.php` (public routes) |
| **Middleware** | `auth`, `admin` role check | `auth` (optional), `guest` |
| **Purpose** | Event management, reports, settings, seating config | Event browsing, ticket purchase, seat selection, profile |

**Key Benefits of This Separation:**
1. **Maintainability**: Each interface can be developed and updated independently
2. **Security**: Admin routes are isolated with dedicated middleware and access controls
3. **Performance**: Assets can be loaded separately, reducing bundle size for each interface
4. **Scalability**: Teams can work on admin and visitor features in parallel
5. **Testing**: Clear boundaries make unit and feature testing more straightforward

**Environment Configuration:**
- Separate `.env` files for different environments (local, staging, production)
- Sensitive configuration stored in environment variables
- Configuration caching for production performance
- Database seeding scripts for initial setup

**Deployment Considerations:**
- Automated deployment scripts (Bash or Laravel Envoy)
- Database migration strategy (zero-downtime migrations)
- Asset deployment and versioning
- Health check endpoints
- Logging and error tracking integration (Sentry, Bugsnag)
- Backup and restoration procedures

---

## Feature Configurability

### 5.1 Event Configuration
- Customizable event fields (add custom attributes via admin)
- Configurable ticket types with unlimited flexibility
- Seating layout builder for seated events
- Dynamic pricing rules engine
- Event categories and tags for search
- Multi-language support (optional)

### 5.2 Seating Configuration
- Venue template library (pre-built layouts)
- Custom venue builder (sections, rows, seats)
- Seat category management (pricing tiers)
- Accessibility seat designation
- Seat blocking/holding capabilities
- Real-time availability sync

### 5.3 QR Code Configuration
- Selectable QR code encoding algorithms
- Configurable security levels (basic, enhanced, high-security)
- QR code design customization (colors, logo overlay)
- Expiration policy configuration
- Scan limitation settings (single-use, multi-use, time-limited)

### 5.4 Notification Configuration
- Notification channel prioritization
- Template customization for different notification types
- Scheduling options (immediate, scheduled, digest)
- User preference management
- A/B testing capability for notification effectiveness

### 5.5 Role & Permission Configuration
- Custom role creation with flexible permission sets
- Permission inheritance and hierarchy management
- Dynamic permission assignment based on business rules
- Role-based UI component rendering
- Audit trail configuration for security compliance

### 5.6 Dashboard Personalization
- Customizable dashboard layouts and widgets
- User preference storage and synchronization
- Theme and accessibility configuration
- Notification and alert customization
- Social interaction and sharing preferences

---

## Deliverables

1. **Source Code:**
   - Complete Laravel application with all specified features
   - Version-controlled repository (Git)
   - Comprehensive README with setup instructions

2. **Documentation:**
   - API documentation (if API endpoints are exposed)
   - Database schema documentation
   - User manual for admin panel
   - Deployment guide
   - Security audit report

3. **Testing Artifacts:**
   - Test suite with passing tests
   - Test coverage report
   - Performance testing results

4. **Deployment Package:**
   - Organized external folder with all dependencies
   - Environment configuration templates
   - Database migration and seeding scripts
   - Deployment automation scripts

---

## Success Criteria

- All functional requirements implemented and tested
- Secure handling of authentication and sensitive data verified
- QR code generation and validation working reliably in real-world scenarios
- Seat selection system functional and performant
- Responsive design verified across devices (mobile, tablet, desktop)
- Performance benchmarks met (page load < 3s, API response < 500ms)
- Activity logging capturing all critical actions
- Log viewer accessible and functional for admins
- Code quality standards maintained throughout
- Comprehensive documentation provided
- Successfully deployable to production environment

---

## Optional Enhancements (Future Considerations)

- Integration with payment gateways (Stripe, PayPal, local providers)
- Mobile application (iOS/Android) using API
- Advanced analytics with AI-powered insights
- Waitlist management for sold-out events
- Affiliate/referral system for ticket sales
- Multi-currency support
- Real-time collaboration tools for event organizers
- 3D venue visualization
- Dynamic pricing based on demand