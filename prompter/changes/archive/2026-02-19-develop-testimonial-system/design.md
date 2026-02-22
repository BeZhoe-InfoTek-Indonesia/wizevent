# Design: Testimonial Management System

## Overview
This system enables event attendees to share their experiences through ratings, text, and photos. It includes an automated invitation system after completion and a moderation panel for organizers.

## Architecture

### 1. Data Model Enhancement
Existing `Testimonial` model will be updated:
- Rename or keep `status`? The user requested `is_published` (bool). I will transition from `status` enum to `is_published` and `is_featured` flags.
- Add `image_path` for photo uploads (stored as relative path in `storage/app/public/testimonials`).
- Add `image_original_name` to preserve original filename for reference.
- Add `image_mime_type` to store the optimized format (WEBP).
- Add `image_width` and `image_height` for display optimization.
- Add `image_file_size` to track optimized file size (in bytes).
- Add `is_featured` boolean.

### 2. Automated Notification Flow
- A scheduled job (`SendTestimonialReminders`) will run daily.
- It will query `tickets` that:
  - Belong to an event that ended 24 hours ago.
  - Were checked in (`checked_in_at` is not null).
  - The user hasn't submitted a testimonial for this event yet.
- Notification via Email/WhatsApp (Template based).

### 2.5 Image Processing Service
- New `ImageService` in `app/Services/` using `intervention/image` v3:
  - `validateImage(UploadedFile $file)`: Checks type, size, dimensions
  - `processTestimonialImage(UploadedFile $file)`: Handles full processing pipeline
  - `stripExif($image)`: Removes metadata for privacy
  - `optimizeImage($image)`: Compresses and converts to WEBP
  - `resizeImage($image, $maxWidth, $maxHeight)`: Constrains dimensions
  - `addWatermark($image)`: Optional branded overlay
- Async processing via `ProcessTestimonialImage` queue job
- Returns optimized image path for storage

### 3. Submission Interface (Visitor)
- A new Livewire component `TestimonialSubmission` at `/events/{event:slug}/review`.
- Access restricted to users with validated and checked-in tickets.
- UI: 5-star rating (interactive), text area, Filepond/WireUI image upload.
- **Image Processing**: Uses `intervention/image` for:
  - Validation: File type (JPG, PNG, WEBP), max size (5MB), dimensions (min 300x300, max 4096x4096)
  - Security: Strips EXIF metadata (location, device info)
  - Optimization: Auto-orient, compress to max 2MB, convert to WEBP format
  - Resizing: Constrain to max 1920x1080px for display
  - Watermark: Optional branded overlay for featured testimonials

### 4. Moderation Dashboard (Filament)
- `TestimonialResource` with:
  - Table showing content, rating, user, event, and flags.
  - Quick actions: Publish, Feature, Reject.
  - Notification ring in dashboard for new submissions (using Filament notifications).

## User Flows

### Visitor Flow
1. Receives notification 24h post-event.
2. Clicks link to review page.
3. System validates ticket purchase + check-in status.
4. User selects rating (1-5), writes message, uploads (optional) photo.
5. Image validation runs client-side + server-side (type, size, dimensions).
6. On submit, testimonial saved with `is_published = false`.
7. Image processing job queued async:
   - Validates file integrity
   - Strips EXIF metadata
   - Resizes to max 1920x1080px
   - Converts to WEBP format
   - Compresses to max 2MB
   - Stores in `storage/app/public/testimonials/{testimonial_id}.webp`
8. User receives confirmation; image appears after processing completes.

### Admin Flow
1. Receives notification in dashboard.
2. Views list of pending testimonials.
3. Reviews content and image.
4. Approves -> `is_published = true`.
5. Optionally marks as Featured -> `is_featured = true`.
6. Testimonial appears on event landing page.

## Trade-offs & Decisions
- **Storage**: Images will be stored in `storage/app/public/testimonials` with optimized WEBP format.
- **Image Security**:
  - `intervention/image` v3 for processing and validation
  - EXIF metadata stripping to protect user privacy
  - File type validation (JPG, PNG, WEBP only)
  - Size constraints: Upload max 5MB, stored max 2MB after optimization
  - Dimension limits: Min 300x300px, Max 4096x4096px
  - Auto-orient to correct rotation issues
- **Validation**: Strict check-in validation ensures authentic social proof.
- **Moderation**: Manual approval by default to prevent spam/abuse.
- **Performance**: Async image processing via queue jobs to prevent upload timeouts.

## Dependencies
- `intervention/image`: ^3.0 (Image processing and validation)
- `livewire/livewire`: ^3.0 (Reactive frontend components)
- `wireui/wireui`: ^2.5 (UI components for file upload)
- Existing queue infrastructure (Database driver)
- Existing notification system (Email/WhatsApp templates)
