# file-bucket-management Specification

## Purpose
TBD - created by archiving change implement-event-management. Update Purpose after archive.
## Requirements
### Requirement: File Upload
Users must be able to upload files through a centralized polymorphic system.

#### Scenario: Upload event banner image
**Given** an authenticated user with `events.create` permission  
**And** a valid image file (JPG/PNG/WebP, ≤5MB)  
**When** they upload the file as an event banner  
**Then** a `FileBucket` record is created with bucket_type "event-banners"  
**And** the file is stored in `storage/app/public/file-buckets/event-banners/events/{id}/`  
**And** three image sizes are generated (thumbnail, medium, large)  
**And** metadata is stored (original_filename, mime_type, size, dimensions)  
**And** the polymorphic relationship to the event is established

#### Scenario: Upload category icon
**Given** an authenticated user with `events.create` permission  
**And** a valid image file  
**When** they upload the file as a category icon  
**Then** a `FileBucket` record is created with bucket_type "category-icons"  
**And** the file is stored in the appropriate directory  
**And** the polymorphic relationship to the category is established

#### Scenario: Upload invalid file type
**Given** an authenticated user uploading a file  
**When** they attempt to upload a file with an unsupported extension (e.g., .exe)  
**Then** the upload is rejected with error "File type not supported"  
**And** no `FileBucket` record is created

#### Scenario: Upload oversized file
**Given** an authenticated user uploading a file  
**When** they attempt to upload a file larger than the configured limit  
**Then** the upload is rejected with error "File size exceeds maximum allowed"  
**And** no `FileBucket` record is created

### Requirement: Multiple File Upload
Entities can have multiple files of the same bucket type.

#### Scenario: Upload multiple event gallery images
**Given** an authenticated user with `events.edit` permission  
**And** an existing event  
**When** they upload 5 images to the event gallery  
**Then** 5 `FileBucket` records are created with bucket_type "event-galleries"  
**And** all files are associated with the event  
**And** each file is processed independently

### Requirement: File Retrieval
Files must be retrievable by entity and bucket type.

#### Scenario: Retrieve event banner
**Given** an event with an uploaded banner  
**When** the banner is requested  
**Then** the `FileBucket` record is returned  
**And** the file URL is accessible

#### Scenario: Retrieve specific image size
**Given** an event with an uploaded banner (with generated sizes)  
**When** the thumbnail size is requested  
**Then** the thumbnail URL is returned  
**And** the URL points to the correct thumbnail file

#### Scenario: Retrieve all files for entity
**Given** an event with multiple gallery images  
**When** all gallery images are requested  
**Then** a collection of all `FileBucket` records with bucket_type "event-galleries" is returned  
**And** files are ordered by creation date (newest first)

#### Scenario: Retrieve non-existent file
**Given** an event without a banner  
**When** the banner is requested  
**Then** null is returned

### Requirement: File Deletion
Files can be deleted individually or in bulk.

#### Scenario: Delete single file
**Given** an authenticated user with `events.edit` permission  
**And** an event with an uploaded banner  
**When** they delete the banner  
**Then** the `FileBucket` record is soft-deleted  
**And** all associated files (original + sizes) are removed from storage  
**And** the polymorphic relationship is removed

#### Scenario: Delete all files of a type
**Given** an authenticated user with `events.edit` permission  
**And** an event with 5 gallery images  
**When** they delete all gallery images  
**Then** all 5 `FileBucket` records are soft-deleted  
**And** all associated files are removed from storage  
**And** the count of deleted files is returned

#### Scenario: Cascade delete on entity deletion
**Given** an event with uploaded files  
**When** the event is deleted  
**Then** all associated `FileBucket` records are soft-deleted  
**And** all files are removed from storage

### Requirement: Image Processing
Uploaded images must be automatically processed.

#### Scenario: Generate image sizes
**Given** an uploaded event banner image (1920x1080)  
**When** the image is processed  
**Then** three additional sizes are generated:
  - Thumbnail: 400x300 (cropped)
  - Medium: 800x600 (cropped)
  - Large: 1200x900 (cropped)  
**And** all sizes are stored in the same directory  
**And** file paths are stored in the `FileBucket` record

#### Scenario: Custom image sizes
**Given** an uploaded category icon  
**When** the image is processed with custom sizes [64x64, 128x128]  
**Then** the specified sizes are generated  
**And** file paths are stored in the `FileBucket` record

#### Scenario: Preserve aspect ratio option
**Given** an uploaded image with preserve_aspect_ratio option  
**When** the image is processed  
**Then** generated sizes maintain the original aspect ratio  
**And** no cropping is applied

### Requirement: File Metadata
Comprehensive metadata must be stored for each file.

#### Scenario: Store file metadata on upload
**Given** a file upload  
**When** the file is processed  
**Then** the following metadata is stored:
  - original_filename
  - mime_type
  - file_size (in bytes)
  - dimensions (width x height for images)
  - bucket_type
  - fileable_type and fileable_id (polymorphic)  
**And** metadata is accessible via the `FileBucket` model

### Requirement: File Organization
Files must be organized by bucket type and entity.

#### Scenario: Organized directory structure
**Given** multiple file uploads across different entities  
**When** files are stored  
**Then** they follow the pattern: `file-buckets/{bucket_type}/{fileable_type}/{fileable_id}/{filename}`  
**And** files are isolated by entity  
**And** bucket types are clearly separated

### Requirement: URL Generation
Public URLs must be generated for file access.

#### Scenario: Generate public URL for file
**Given** a stored file  
**When** the public URL is requested  
**Then** a fully qualified URL is returned  
**And** the URL is accessible via HTTP

#### Scenario: Generate URL for specific size
**Given** a stored image with multiple sizes  
**When** the URL for "thumbnail" size is requested  
**Then** the thumbnail URL is returned  
**And** the URL points to the correct thumbnail file

#### Scenario: Generate URL for original file
**Given** a stored file  
**When** the URL is requested without specifying size  
**Then** the original file URL is returned

### Requirement: Bucket Type Validation
Bucket types must be predefined and validated.

#### Scenario: Use valid bucket type
**Given** an authenticated user uploading a file  
**When** they specify bucket_type "event-banners"  
**Then** the upload proceeds successfully

#### Scenario: Use invalid bucket type
**Given** an authenticated user uploading a file  
**When** they specify an undefined bucket_type "invalid-type"  
**Then** the upload is rejected with error "Invalid bucket type"  
**And** no file is stored

### Requirement: File Replacement
Files can be replaced while maintaining history.

#### Scenario: Replace event banner
**Given** an event with an existing banner  
**When** a new banner is uploaded  
**Then** the old `FileBucket` record is soft-deleted  
**And** old files are removed from storage  
**And** a new `FileBucket` record is created  
**And** the new file is stored

### Requirement: Error Handling
Comprehensive error handling for file operations.

#### Scenario: Handle storage failure
**Given** a file upload  
**When** the storage system is unavailable  
**Then** an exception is thrown with error "File storage failed"  
**And** no `FileBucket` record is created  
**And** the user is notified of the failure

#### Scenario: Handle image processing failure
**Given** a corrupted image file  
**When** image processing is attempted  
**Then** an exception is thrown with error "Image processing failed"  
**And** the original file is still stored  
**And** no sizes are generated

### Requirement: Supported File Types
System must support multiple file types with appropriate validation.

#### Scenario: Supported image types
**Given** valid image files (JPG, PNG, WebP)  
**When** uploaded  
**Then** all are accepted and processed

#### Scenario: Supported document types (future)
**Given** valid document files (PDF)  
**When** uploaded  
**Then** they are accepted and stored  
**And** no image processing is attempted

#### Scenario: Unsupported file type
**Given** an unsupported file type (e.g., .exe, .bat)  
**When** upload is attempted  
**Then** validation fails with error "File type not supported"

