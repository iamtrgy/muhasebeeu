# Bunny CDN Storage Implementation

This document provides information about the implementation of Bunny CDN Storage in our application, including configuration details and usage examples.

## Overview

The application now uses Bunny CDN Storage as the default file storage system, replacing the local disk storage previously used. This change provides several benefits:

- Improved performance for file delivery with CDN capabilities
- Better scalability for file storage
- Reduced load on application servers
- Global distribution of files for faster access

## Configuration

The Bunny CDN storage is configured in the following files:

1. **config/filesystems.php** - Contains the Bunny CDN disk configuration
2. **.env** - Stores the environment-specific configuration values

### Environment Variables

Add the following variables to your `.env` file:

```
FILESYSTEM_DISK=bunny
BUNNY_STORAGE_ZONE_NAME=your-storage-zone
BUNNY_API_KEY=your-api-key
BUNNY_REGION=your-region
BUNNY_HOSTNAME=storage.bunnycdn.com
BUNNY_URL=your-zone-url.b-cdn.net
```

Replace the placeholder values with your actual Bunny CDN credentials.

## Implementation Details

### Custom Adapter

We use a custom `BunnyAdapter` class that implements the League Flysystem `FilesystemAdapter` interface to integrate with Laravel's storage system. The adapter handles all file operations including:

- Uploading files
- Downloading files
- Deleting files
- Checking file existence
- Listing directory contents

### Service Provider

The `BunnyStorageServiceProvider` registers the Bunny storage driver with Laravel's filesystem.

## Usage Examples

### Uploading Files

```php
// Using Laravel's Storage facade
Storage::disk('bunny')->put('path/to/file.txt', 'File contents');

// Using the request's file upload
$path = $request->file('document')->store('documents', 'bunny');
```

### Retrieving Files

```php
// Check if a file exists
if (Storage::disk('bunny')->exists('path/to/file.txt')) {
    // File exists
}

// Reading file contents
$contents = Storage::disk('bunny')->get('path/to/file.txt');

// Generate a URL to the file
$url = 'https://' . config('filesystems.disks.bunny.url') . '/path/to/file.txt';
```

### Deleting Files

```php
// Delete a single file
Storage::disk('bunny')->delete('path/to/file.txt');

// Delete multiple files
Storage::disk('bunny')->delete(['file1.txt', 'file2.txt']);
```

## Testing the Integration

The application includes a test page that verifies the Bunny CDN integration is working correctly. Access it at:

```
/bunny-test
```

This page runs a series of tests to check file upload, existence verification, content reading, and deletion.

## Migration from Local Storage

When implementing this change, a migration tool was used to transfer existing files from the local storage to Bunny CDN. You can run this migration again if needed:

```bash
php artisan storage:migrate-to-bunny
```

By default, this command migrates from the 'public' disk. To migrate from a different disk, specify it as an argument:

```bash
php artisan storage:migrate-to-bunny local
```

## Troubleshooting

If you encounter issues with the Bunny CDN integration, check the following:

1. Verify your API key and storage zone name are correct in the `.env` file
2. Ensure the storage zone has the appropriate permissions set
3. Check the application logs for specific error messages
4. Run the Bunny test page to diagnose specific issues with the integration

## Security Considerations

- The API key has full access to your storage zone, so keep it secure
- Don't commit the actual API key to version control - use environment variables
- Consider implementing additional access controls for sensitive files 