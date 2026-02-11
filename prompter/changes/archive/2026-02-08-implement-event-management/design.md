# Design: Event Management Implementation

## Architecture Overview

This design implements a comprehensive event management system following Laravel best practices and the established project patterns (Filament admin, service layer, Spatie permissions).

## Database Schema Design

### Events Table

```sql
CREATE TABLE events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description LONGTEXT NOT NULL,
    event_date DATETIME NOT NULL,
    event_end_date DATETIME NULL,
    location VARCHAR(500) NOT NULL,
    venue_name VARCHAR(255) NULL,
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    google_place_id VARCHAR(255) NULL,
    status ENUM('draft', 'published', 'sold_out', 'cancelled') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    sales_start_at TIMESTAMP NULL,
    sales_end_at TIMESTAMP NULL,
    seating_enabled BOOLEAN DEFAULT FALSE,
    total_capacity INT UNSIGNED DEFAULT 0,
    cancellation_reason TEXT NULL,
    created_by BIGINT UNSIGNED NULL,
    updated_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_status (status),
    INDEX idx_event_date (event_date),
    INDEX idx_published_at (published_at),
    INDEX idx_slug (slug),
    INDEX idx_location (latitude, longitude),
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);
```

**Design Decisions:**
- `slug` for SEO-friendly URLs, auto-generated from title
- `event_end_date` nullable for single-day events
- **Google Maps Integration:**
  - `latitude`/`longitude` for precise location coordinates (DECIMAL for accuracy)
  - `google_place_id` for Google Maps Place API integration
  - `location` remains as text fallback and display name
  - Composite index on (latitude, longitude) for proximity searches
- Multiple thumbnail sizes for responsive images
- `seating_enabled` flag for future seating layout feature
- `total_capacity` computed from ticket types
- `cancellation_reason` for audit trail
- `created_by`/`updated_by` for user tracking
- Soft deletes for audit trail

### Ticket Types Table

```sql
CREATE TABLE ticket_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    sold_count INT UNSIGNED DEFAULT 0,
    min_purchase INT UNSIGNED DEFAULT 1,
    max_purchase INT UNSIGNED DEFAULT 10,
    sales_start_at TIMESTAMP NULL,
    sales_end_at TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_event_id (event_id),
    INDEX idx_is_active (is_active),
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);
```

**Design Decisions:**
- `sold_count` for real-time inventory tracking
- `min_purchase`/`max_purchase` for purchase limits
- Per-ticket-type sales window (optional, inherits from event if null)
- `is_active` for temporarily disabling ticket types
- `sort_order` for display ordering
- Soft deletes for audit trail
- CASCADE delete when event is deleted

### Event Categories Table

```sql
CREATE TABLE event_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NULL,
    icon VARCHAR(100) NULL,
    color VARCHAR(7) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_slug (slug),
    INDEX idx_is_active (is_active)
);
```

### Event Tags Table

```sql
CREATE TABLE event_tags (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_slug (slug)
);
```

### Event-Category Relationship

```sql
ALTER TABLE events ADD COLUMN category_id BIGINT UNSIGNED NULL;
ALTER TABLE events ADD FOREIGN KEY (category_id) REFERENCES event_categories(id) ON DELETE SET NULL;
```

### Event-Tag Pivot Table

```sql
CREATE TABLE event_tag (
    event_id BIGINT UNSIGNED NOT NULL,
    tag_id BIGINT UNSIGNED NOT NULL,
    
    PRIMARY KEY (event_id, tag_id),
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES event_tags(id) ON DELETE CASCADE
);
```

### File Buckets Table (Polymorphic File Storage)

```sql
CREATE TABLE file_buckets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fileable_type VARCHAR(255) NOT NULL,
    fileable_id BIGINT UNSIGNED NOT NULL,
    bucket_type VARCHAR(100) NOT NULL,
    collection JSON NULL,
    original_filename VARCHAR(255) NOT NULL,
    stored_filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    url VARCHAR(500) NULL,
    mime_type VARCHAR(100) NOT NULL,
    file_size INT UNSIGNED NOT NULL,
    width INT UNSIGNED NULL,
    height INT UNSIGNED NULL,
    metadata JSON NULL,
    sizes JSON NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_fileable (fileable_type, fileable_id),
    INDEX idx_bucket_type (bucket_type),
    INDEX idx_created_by (created_by),
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);
```

**Design Decisions:**
- Polymorphic relationship via `fileable_type` and `fileable_id`
- `bucket_type` categorizes files (event-banners, category-icons, etc.)
- `original_filename` preserves user's filename
- `stored_filename` is the actual filename on disk (unique, sanitized)
- `file_path` is the full path relative to storage root
- `width`/`height` for images (null for non-images)
- `metadata` JSON field for extensible data (EXIF, processing options, etc.)
- `sizes` JSON field stores generated image sizes:
  ```json
  {
    "thumbnail": "/path/to/thumbnail.jpg",
    "medium": "/path/to/medium.jpg",
    "large": "/path/to/large.jpg"
  }
  ```
- Soft deletes for audit trail
- Cascade delete handled at application level (not database) for file cleanup

## Model Design

### Event Model

```php
class Event extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    
    protected $fillable = [
        'title', 'slug', 'description', 'event_date', 'event_end_date',
        'location', 'venue_name', 'latitude', 'longitude', 'google_place_id',
        'banner_image', 'status', 'published_at',
        'sales_start_at', 'sales_end_at', 'seating_enabled', 'total_capacity',
        'cancellation_reason', 'category_id', 'created_by', 'updated_by'
    ];
    
    protected $casts = [
        'event_date' => 'datetime',
        'event_end_date' => 'datetime',
        'published_at' => 'datetime',
        'sales_start_at' => 'datetime',
        'sales_end_at' => 'datetime',
        'seating_enabled' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];
    
    // Relationships
    public function ticketTypes(): HasMany
    public function category(): BelongsTo
    public function tags(): BelongsToMany
    public function creator(): BelongsTo
    public function updater(): BelongsTo
    
    // Accessors
    public function getAvailableTicketsAttribute(): int
    public function getSoldTicketsAttribute(): int
    public function getIsSoldOutAttribute(): bool
    public function getIsPublishedAttribute(): bool
    public function getIsDraftAttribute(): bool
    public function getIsCancelledAttribute(): bool
    
    // Scopes
    public function scopePublished(Builder $query): void
    public function scopeDraft(Builder $query): void
    public function scopeUpcoming(Builder $query): void
    public function scopePast(Builder $query): void
    
    // Business Logic
    public function canBePublished(): bool
    public function canBeCancelled(): bool
    public function canBeDeleted(): bool
}
```

### TicketType Model

```php
class TicketType extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'event_id', 'name', 'description', 'price', 'quantity',
        'sold_count', 'min_purchase', 'max_purchase',
        'sales_start_at', 'sales_end_at', 'is_active', 'sort_order'
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'sales_start_at' => 'datetime',
        'sales_end_at' => 'datetime',
        'is_active' => 'boolean',
    ];
    
    // Relationships
    public function event(): BelongsTo
    
    // Accessors
    public function getAvailableCountAttribute(): int
    public function getIsSoldOutAttribute(): bool
    public function getIsAvailableForSaleAttribute(): bool
    
    // Business Logic
    public function canPurchase(int $quantity): bool
    public function reserveTickets(int $quantity): void
}
```

### FileBucket Model

```php
class FileBucket extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'fileable_type', 'fileable_id', 'bucket_type',
        'original_filename', 'stored_filename', 'file_path',
        'mime_type', 'file_size', 'width', 'height',
        'metadata', 'sizes', 'created_by'
    ];
    
    protected $casts = [
        'metadata' => 'array',
        'sizes' => 'array',
    ];
    
    // Polymorphic Relationship
    public function fileable(): MorphTo
    
    // Relationships
    public function creator(): BelongsTo
    
    // Accessors
    public function getUrlAttribute(): string
    public function getSizeUrl(string $size): ?string
    public function getIsImageAttribute(): bool
    public function getFormattedSizeAttribute(): string
    
    // Scopes
    public function scopeOfType(Builder $query, string $bucketType): void
    public function scopeImages(Builder $query): void
    public function scopeDocuments(Builder $query): void
    
    // Business Logic
    public function delete(): bool
    {
        // Delete physical files before soft delete
        Storage::delete($this->file_path);
        if ($this->sizes) {
            foreach ($this->sizes as $path) {
                Storage::delete($path);
            }
        }
        return parent::delete();
    }
}
```

## Service Layer Design

### EventService

```php
class EventService
{
    public function createEvent(array $data): Event
    {
        // Generate slug
        // Upload and process banner image
        // Create event
        // Log activity
        return $event;
    }
    
    public function updateEvent(Event $event, array $data): Event
    {
        // Update slug if title changed
        // Handle banner image update
        // Update event
        // Log activity
        return $event;
    }
    
    public function publishEvent(Event $event): Event
    {
        // Validate for publishing
        // Update status and published_at
        // Queue notifications (future)
        // Log activity
        return $event;
    }
    
    public function cancelEvent(Event $event, string $reason): Event
    {
        // Update status and cancellation_reason
        // Queue refund notifications (future)
        // Log activity
        return $event;
    }
    
    public function validateForPublishing(Event $event): array
    {
        // Check required fields
        // Check at least one active ticket type
        // Return validation errors
    }
    
    public function calculateTotalCapacity(Event $event): int
    {
        // Sum all ticket type quantities
    }
}
```

### TicketTypeService

```php
class TicketTypeService
{
    public function createTicketType(Event $event, array $data): TicketType
    {
        // Create ticket type
        // Update event total_capacity
        // Log activity
    }
    
    public function updateTicketType(TicketType $ticketType, array $data): TicketType
    {
        // Update ticket type
        // Recalculate event total_capacity
        // Log activity
    }
    
    public function deleteTicketType(TicketType $ticketType): bool
    {
        // Check if any tickets sold
        // Delete ticket type
        // Recalculate event total_capacity
        // Log activity
    }
    
    public function reserveTickets(TicketType $ticketType, int $quantity): void
    {
        // Use database locking
        DB::transaction(function () use ($ticketType, $quantity) {
            $ticketType->lockForUpdate()->first();
            // Check availability
            // Increment sold_count
            // Check if event should be marked sold_out
        });
    }
}
```

### FileBucketService

```php
class FileBucketService
{
    protected array $bucketTypes = [
        'event-banners',
        'event-galleries',
        'category-icons',
        'ticket-type-images',
        'user-avatars',
    ];
    
    protected array $imageSizes = [
        'thumbnail' => [400, 300],
        'medium' => [800, 600],
        'large' => [1200, 900],
    ];
    
    /**
     * Upload a file and create FileBucket record
     *
     * @param Model $fileable The model that owns the file
     * @param UploadedFile $file The uploaded file
     * @param string $bucketType The bucket type (e.g., 'event-banners')
     * @param array $options Optional configuration:
     *   - 'sizes' => array Custom image sizes (overrides default)
     *   - 'preserve_aspect_ratio' => bool Maintain aspect ratio
     *   - 'replace' => bool Replace existing file of same bucket type
     * @return FileBucket
     */
    public function upload(Model $fileable, UploadedFile $file, string $bucketType, array $options = []): FileBucket
    {
        // Validate bucket type
        $this->validateBucketType($bucketType);
        
        // Validate file
        $this->validateFile($file, $bucketType);
        
        // Replace existing if requested
        if ($options['replace'] ?? false) {
            $this->deleteAll($fileable, $bucketType);
        }
        
        // Generate unique filename
        $storedFilename = $this->generateUniqueFilename($file);
        
        // Build file path
        $filePath = $this->buildFilePath($fileable, $bucketType, $storedFilename);
        
        // Store file
        Storage::disk('public')->put($filePath, file_get_contents($file->getRealPath()));
        
        // Create FileBucket record
        $fileBucket = FileBucket::create([
            'fileable_type' => get_class($fileable),
            'fileable_id' => $fileable->id,
            'bucket_type' => $bucketType,
            'original_filename' => $file->getClientOriginalName(),
            'stored_filename' => $storedFilename,
            'file_path' => $filePath,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'created_by' => auth()->id(),
        ]);
        
        // Process image if applicable
        if ($this->isImage($file)) {
            $this->processImage($fileBucket, $options);
        }
        
        // Log activity
        activity()
            ->performedOn($fileable)
            ->causedBy(auth()->user())
            ->withProperties(['bucket_type' => $bucketType, 'filename' => $storedFilename])
            ->log('File uploaded');
        
        return $fileBucket;
    }
    
    /**
     * Upload multiple files
     */
    public function uploadMultiple(Model $fileable, array $files, string $bucketType, array $options = []): Collection
    {
        $fileBuckets = collect();
        
        foreach ($files as $file) {
            $fileBuckets->push($this->upload($fileable, $file, $bucketType, $options));
        }
        
        return $fileBuckets;
    }
    
    /**
     * Retrieve a single file
     */
    public function retrieve(Model $fileable, string $bucketType, ?string $size = null): ?FileBucket
    {
        return FileBucket::where('fileable_type', get_class($fileable))
            ->where('fileable_id', $fileable->id)
            ->where('bucket_type', $bucketType)
            ->latest()
            ->first();
    }
    
    /**
     * Retrieve all files of a bucket type
     */
    public function retrieveAll(Model $fileable, string $bucketType): Collection
    {
        return FileBucket::where('fileable_type', get_class($fileable))
            ->where('fileable_id', $fileable->id)
            ->where('bucket_type', $bucketType)
            ->latest()
            ->get();
    }
    
    /**
     * Delete a single file
     */
    public function delete(FileBucket $fileBucket): bool
    {
        // Delete physical files
        Storage::disk('public')->delete($fileBucket->file_path);
        
        if ($fileBucket->sizes) {
            foreach ($fileBucket->sizes as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        
        // Soft delete record
        return $fileBucket->delete();
    }
    
    /**
     * Delete all files of a bucket type for an entity
     */
    public function deleteAll(Model $fileable, string $bucketType): int
    {
        $fileBuckets = $this->retrieveAll($fileable, $bucketType);
        $count = 0;
        
        foreach ($fileBuckets as $fileBucket) {
            if ($this->delete($fileBucket)) {
                $count++;
            }
        }
        
        return $count;
    }
    
    /**
     * Get public URL for a file
     */
    public function getUrl(FileBucket $fileBucket, ?string $size = null): string
    {
        if ($size && isset($fileBucket->sizes[$size])) {
            return Storage::disk('public')->url($fileBucket->sizes[$size]);
        }
        
        return Storage::disk('public')->url($fileBucket->file_path);
    }
    
    /**
     * Process image and generate sizes
     */
    public function processImage(FileBucket $fileBucket, array $options = []): void
    {
        $sizes = $options['sizes'] ?? $this->imageSizes;
        $preserveAspectRatio = $options['preserve_aspect_ratio'] ?? false;
        
        $fullPath = Storage::disk('public')->path($fileBucket->file_path);
        $image = Image::make($fullPath);
        
        // Store original dimensions
        $fileBucket->update([
            'width' => $image->width(),
            'height' => $image->height(),
        ]);
        
        // Generate sizes
        $generatedSizes = [];
        
        foreach ($sizes as $sizeName => [$width, $height]) {
            $sizedImage = clone $image;
            
            if ($preserveAspectRatio) {
                $sizedImage->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            } else {
                $sizedImage->fit($width, $height);
            }
            
            // Build size path
            $sizePath = $this->buildSizePath($fileBucket->file_path, $sizeName);
            
            // Save sized image
            Storage::disk('public')->put($sizePath, (string) $sizedImage->encode());
            
            $generatedSizes[$sizeName] = $sizePath;
        }
        
        // Update FileBucket with generated sizes
        $fileBucket->update(['sizes' => $generatedSizes]);
    }
    
    /**
     * Validate bucket type
     */
    protected function validateBucketType(string $bucketType): void
    {
        if (!in_array($bucketType, $this->bucketTypes)) {
            throw new InvalidArgumentException("Invalid bucket type: {$bucketType}");
        }
    }
    
    /**
     * Validate file
     */
    protected function validateFile(UploadedFile $file, string $bucketType): void
    {
        // Validate file size (5MB max)
        if ($file->getSize() > 5 * 1024 * 1024) {
            throw new ValidationException('File size exceeds maximum allowed (5MB)');
        }
        
        // Validate file type based on bucket type
        $allowedMimes = $this->getAllowedMimes($bucketType);
        
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new ValidationException('File type not supported');
        }
    }
    
    /**
     * Get allowed MIME types for bucket type
     */
    protected function getAllowedMimes(string $bucketType): array
    {
        return match ($bucketType) {
            'event-banners', 'event-galleries', 'category-icons', 'ticket-type-images', 'user-avatars' 
                => ['image/jpeg', 'image/png', 'image/webp'],
            default => ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'],
        };
    }
    
    /**
     * Generate unique filename
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        return Str::uuid() . '.' . $file->getClientOriginalExtension();
    }
    
    /**
     * Build file path
     */
    protected function buildFilePath(Model $fileable, string $bucketType, string $filename): string
    {
        $fileableType = Str::plural(Str::snake(class_basename($fileable)));
        return "file-buckets/{$bucketType}/{$fileableType}/{$fileable->id}/{$filename}";
    }
    
    /**
     * Build size path
     */
    protected function buildSizePath(string $originalPath, string $sizeName): string
    {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . "_{$sizeName}." . $pathInfo['extension'];
    }
    
    /**
     * Check if file is an image
     */
    protected function isImage(UploadedFile $file): bool
    {
        return Str::startsWith($file->getMimeType(), 'image/');
    }
}
```

## Filament Resource Design

### EventResource Structure

```
app/Filament/Resources/Events/
├── EventResource.php
├── Pages/
│   ├── ListEvents.php
│   ├── CreateEvent.php (modal)
│   └── EditEvent.php (modal)
├── RelationManagers/
│   └── TicketTypesRelationManager.php
├── Schemas/
│   ├── EventForm.php
│   └── TicketTypeForm.php
└── Tables/
    └── EventsTable.php
```

### Form Design

**EventForm Components:**
1. Section: Basic Information
   - TextInput: title (with live slug generation)
   - Select: category_id
   - TagsInput: tags
   - **RichEditor: description (TipTap Editor)**
     - Filament's built-in RichEditor component (powered by TipTap)
     - Toolbar: bold, italic, underline, strike, link, bullet list, ordered list, blockquote, code block
     - HTML output with XSS protection
     - Min length: 50 characters

2. Section: Event Details
   - DateTimePicker: event_date
   - DateTimePicker: event_end_date (nullable)
   - **Google Maps Location Picker:**
     - Custom Filament field component using Google Maps Places Autocomplete API
     - TextInput: location (with autocomplete dropdown)
     - Hidden fields: latitude, longitude, google_place_id (auto-populated)
     - TextInput: venue_name (optional, can be auto-filled from place data)
     - Map preview showing selected location
     - **Implementation**: Use `filament/forms` custom field with Alpine.js + Google Maps JavaScript API
     - **API Key**: Store in `.env` as `GOOGLE_MAPS_API_KEY`

3. Section: Banner Image
   - FileUpload: banner_image (with image preview)
     - Integrated with FileBucketService
     - Bucket type: 'event-banners'
     - Auto-generates thumbnails via FileBucketService

4. Section: Sales Configuration
   - DateTimePicker: sales_start_at
   - DateTimePicker: sales_end_at
   - Toggle: seating_enabled

**TicketTypeForm Components:**
- TextInput: name
- Textarea: description
- TextInput: price (numeric, min 0)
- TextInput: quantity (numeric, min 1)
- TextInput: min_purchase
- TextInput: max_purchase
- DateTimePicker: sales_start_at
- DateTimePicker: sales_end_at
- Toggle: is_active

### Table Design

**EventsTable Columns:**
- TextColumn: title (searchable, sortable)
- TextColumn: category.name (badge)
- TextColumn: event_date (date format, sortable)
- TextColumn: status (badge with colors)
- TextColumn: available_tickets / total_capacity
- TextColumn: created_at (since format)

**Filters:**
- SelectFilter: status
- SelectFilter: category
- DateFilter: event_date
- TrashedFilter

**Actions:**
- EditAction (modal)
- Action: Publish (visible if draft)
- Action: Cancel (visible if published)
- DeleteAction (visible if draft)

## Permission Design

### Permission Definitions

```php
'events.view' => 'View events',
'events.create' => 'Create events',
'events.edit' => 'Edit events',
'events.publish' => 'Publish events',
'events.cancel' => 'Cancel events',
'events.delete' => 'Delete events',
```

### Role Assignments

- **Super Admin**: All permissions
- **Event Manager**: view, create, edit, publish (own events only)
- **Finance Admin**: view only
- **Check-in Staff**: view only
- **Visitor**: None (public events via frontend)

## Image Processing Pipeline

### Upload Flow

1. **Client Upload**
   - Validate file type (JPG, PNG, WebP)
   - Validate file size (max 5MB)

2. **Server Processing**
   - Store original in `storage/app/public/events/banners/{event-id}/original.{ext}`
   - Generate thumbnails:
     - 400x300 (list view)
     - 800x600 (detail view)
     - 1200x900 (hero image)
   - Store thumbnails in same directory

3. **Database Storage**
   - Store relative paths in event record
   - `banner_image`: original
   - `thumbnail_400`: 400x300
   - `thumbnail_800`: 800x600
   - `thumbnail_1200`: 1200x900

### Image Service

```php
class ImageService
{
    public function processEventBanner(UploadedFile $file, Event $event): array
    {
        // Create directory
        // Store original
        // Generate thumbnails
        // Return paths array
    }
    
    public function deleteEventBanner(Event $event): void
    {
        // Delete all image files
        // Clear database fields
    }
}
```

## Validation Rules

### Event Validation

```php
'title' => 'required|string|max:255',
'description' => 'required|string|min:50',
'event_date' => 'required|date|after:now',
'event_end_date' => 'nullable|date|after:event_date',
'location' => 'required|string|max:500',
'venue_name' => 'nullable|string|max:255',
'banner_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
'category_id' => 'nullable|exists:event_categories,id',
'sales_start_at' => 'nullable|date',
'sales_end_at' => 'nullable|date|after:sales_start_at|before:event_date',
```

### Ticket Type Validation

```php
'name' => 'required|string|max:255',
'description' => 'nullable|string',
'price' => 'required|numeric|min:0',
'quantity' => 'required|integer|min:1',
'min_purchase' => 'required|integer|min:1',
'max_purchase' => 'required|integer|min:1|gte:min_purchase',
'sales_start_at' => 'nullable|date',
'sales_end_at' => 'nullable|date|after:sales_start_at',
```

### Publishing Validation

```php
public function validateForPublishing(Event $event): array
{
    $errors = [];
    
    if (empty($event->title)) $errors[] = 'Title is required';
    if (empty($event->description)) $errors[] = 'Description is required';
    if (empty($event->event_date)) $errors[] = 'Event date is required';
    if (empty($event->location)) $errors[] = 'Location is required';
    if (empty($event->banner_image)) $errors[] = 'Banner image is required';
    if ($event->ticketTypes()->where('is_active', true)->count() === 0) {
        $errors[] = 'At least one active ticket type is required';
    }
    
    return $errors;
}
```

## State Transition Logic

### Publishing

```php
public function publishEvent(Event $event): Event
{
    // Validate
    $errors = $this->validateForPublishing($event);
    if (!empty($errors)) {
        throw new ValidationException($errors);
    }
    
    // Update status
    $event->update([
        'status' => 'published',
        'published_at' => now(),
    ]);
    
    // Log activity
    activity()
        ->performedOn($event)
        ->causedBy(auth()->user())
        ->log('Event published');
    
    return $event;
}
```

### Cancellation

```php
public function cancelEvent(Event $event, string $reason): Event
{
    if (!$event->canBeCancelled()) {
        throw new BusinessException('Event cannot be cancelled');
    }
    
    $event->update([
        'status' => 'cancelled',
        'cancellation_reason' => $reason,
    ]);
    
    // Future: Queue refund notifications
    
    activity()
        ->performedOn($event)
        ->causedBy(auth()->user())
        ->withProperties(['reason' => $reason])
        ->log('Event cancelled');
    
    return $event;
}
```

## Translation Structure

### lang/en/event.php

```php
return [
    'title' => 'Title',
    'title_placeholder' => 'e.g., Summer Music Festival 2026',
    'description' => 'Description',
    'description_placeholder' => 'Describe your event in detail...',
    'event_date' => 'Event Date',
    'location' => 'Location',
    'location_placeholder' => 'e.g., Central Park, New York',
    'status_draft' => 'Draft',
    'status_published' => 'Published',
    'status_sold_out' => 'Sold Out',
    'status_cancelled' => 'Cancelled',
    // ... more translations
];
```

## Testing Strategy

### Unit Tests
- Event model state transitions
- TicketType inventory calculations
- EventService business logic
- Validation rules

### Feature Tests
- Event CRUD operations
- Image upload and processing
- Publishing workflow
- Permission checks
- Concurrent inventory updates

### Integration Tests
- Full event creation flow
- Publishing with validation
- Ticket type management
- Category and tag assignment

## Performance Considerations

### Database Indexing
- Index on `status` for filtering published events
- Index on `event_date` for date-based queries
- Index on `slug` for URL lookups
- Composite index on `(status, event_date)` for common queries

### Query Optimization
- Eager load relationships: `with(['ticketTypes', 'category', 'tags'])`
- Use `select()` to limit columns when full model not needed
- Cache category and tag lists (rarely change)

### Inventory Locking
- Use `lockForUpdate()` for ticket reservations
- Keep transactions short
- Consider Redis for high-volume events (future optimization)

## Security Considerations

### Authorization
- Policy-based authorization for all event operations
- Check ownership for Event Managers (can only edit own events)
- Super Admin can edit all events

### Input Validation
- Sanitize rich text editor output (Filament handles this)
- Validate image uploads (type, size, dimensions)
- Prevent SQL injection via Eloquent ORM

### XSS Prevention
- Use Blade's `{{ }}` for output escaping
- Rich text editor output is sanitized by Filament

## Monitoring & Logging

### Activity Logging
- Log all event CRUD operations
- Log status changes (publish, cancel)
- Log ticket type changes
- Include user who performed action

### Metrics to Track
- Events created per day
- Events published per day
- Average time from creation to publication
- Events by category
- Events by status

## Future Enhancements (Out of Scope)

1. **Event Duplication**
   - Clone existing event with all ticket types
   - Reset dates and status to draft

2. **Seating Layout Designer**
   - SVG-based visual designer
   - Section/row/seat mapping
   - Seat-to-ticket-type assignment

3. **Discount Codes**
   - Percentage and fixed amount discounts
   - Usage limits and expiration
   - Code validation at checkout

4. **Event Templates**
   - Save event configurations as templates
   - Quick-start event creation

5. **Scheduled Publishing**
   - Schedule publish date/time
   - Automatic status change via queue

6. **Multi-language Event Descriptions**
   - Translatable event content
   - Language-specific URLs
