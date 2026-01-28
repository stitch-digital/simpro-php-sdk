# Job Attachments Resource

> [Jobs](../jobs-resource.md) > Attachments

Attachments allow you to add files and organize them into folders on jobs.

## Navigation

```php
// Access attachment files for a specific job
$connector->jobs(companyId: 0)->job(jobId: 123)->attachmentFiles()

// Access attachment folders for a specific job
$connector->jobs(companyId: 0)->job(jobId: 123)->attachmentFolders()
```

## Attachment Files

### Listing Files

```php
$files = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->attachmentFiles()
    ->list()
    ->all();

foreach ($files as $file) {
    echo "{$file->filename} ({$file->mimeType})\n";
}
```

### Getting a Single File

```php
$file = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->attachmentFiles()
    ->get(fileId: 789);

echo "Filename: {$file->filename}\n";
echo "Size: {$file->fileSizeBytes} bytes\n";
```

### Creating a File

```php
$fileId = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->attachmentFiles()
    ->create(data: [
        'Filename' => 'site-photo.jpg',
        'Base64Data' => base64_encode(file_get_contents('/path/to/photo.jpg')),
        'Public' => true,  // Visible in customer portal
    ]);

echo "Uploaded file with ID: {$fileId}\n";
```

### Updating a File

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->attachmentFiles()
    ->update(fileId: 789, data: [
        'Filename' => 'site-photo-updated.jpg',
        'Public' => false,
    ]);
```

### Deleting a File

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->attachmentFiles()
    ->delete(fileId: 789);
```

## Attachment Folders

### Listing Folders

```php
$folders = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->attachmentFolders()
    ->list()
    ->all();

foreach ($folders as $folder) {
    echo "Folder: {$folder->name}\n";
}
```

### Creating a Folder

```php
$folderId = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->attachmentFolders()
    ->create(data: [
        'Name' => 'Site Photos',
    ]);
```

### Updating a Folder

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->attachmentFolders()
    ->update(folderId: 101, data: [
        'Name' => 'Site Photos - Week 1',
    ]);
```

### Deleting a Folder

```php
$response = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->attachmentFolders()
    ->delete(folderId: 101);
```

## Response Structures

### AttachmentFile

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | File ID |
| `filename` | `?string` | File name |
| `mimeType` | `?string` | MIME type |
| `fileSizeBytes` | `?int` | File size in bytes |
| `dateAdded` | `?DateTimeImmutable` | Upload date |
| `public` | `?bool` | Visible in customer portal |
| `email` | `?bool` | Available in forms tab |
| `href` | `?string` | Download URL |

### AttachmentFolder

| Property | Type | Description |
|----------|------|-------------|
| `id` | `int` | Folder ID |
| `name` | `?string` | Folder name |

## Examples

### Upload Multiple Photos

```php
$photos = [
    '/path/to/before.jpg',
    '/path/to/during.jpg',
    '/path/to/after.jpg',
];

foreach ($photos as $photoPath) {
    $connector->jobs(companyId: 0)
        ->job(jobId: 123)
        ->attachmentFiles()
        ->create(data: [
            'Filename' => basename($photoPath),
            'Base64Data' => base64_encode(file_get_contents($photoPath)),
            'Public' => true,
        ]);
}
```

### Organize into Folders

```php
// Create folder
$folderId = $connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->attachmentFolders()
    ->create(data: [
        'Name' => 'Completion Photos',
    ]);

// Upload file to folder
$connector->jobs(companyId: 0)
    ->job(jobId: 123)
    ->attachmentFiles()
    ->create(data: [
        'Filename' => 'completion.jpg',
        'Base64Data' => base64_encode($imageData),
        'Folder' => ['ID' => $folderId],
    ]);
```
