<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Data\Common\Attachment;
use Simpro\PhpSdk\Simpro\Data\Common\Reference;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

it('creates attachment from array with all fields', function () {
    $attachment = Attachment::fromArray([
        'ID' => 1,
        'Filename' => 'invoice.pdf',
        'MimeType' => 'application/pdf',
        'FileSizeBytes' => 102400,
        'Folder' => [
            'ID' => 5,
            'Name' => 'Invoices',
        ],
        'DateAdded' => '2024-01-15T10:30:00Z',
        'AddedBy' => [
            'ID' => 10,
            'Name' => 'John Doe',
            'Type' => 'employee',
            'TypeId' => 10,
        ],
        'Public' => true,
        'Email' => false,
    ]);

    expect($attachment->id)->toBe(1)
        ->and($attachment->filename)->toBe('invoice.pdf')
        ->and($attachment->mimeType)->toBe('application/pdf')
        ->and($attachment->fileSizeBytes)->toBe(102400)
        ->and($attachment->folder)->toBeInstanceOf(Reference::class)
        ->and($attachment->folder->id)->toBe(5)
        ->and($attachment->dateAdded)->not->toBeNull()
        ->and($attachment->addedBy)->toBeInstanceOf(StaffReference::class)
        ->and($attachment->addedBy->type)->toBe('employee')
        ->and($attachment->public)->toBeTrue()
        ->and($attachment->email)->toBeFalse();
});

it('handles alternative field names', function () {
    $attachment = Attachment::fromArray([
        'ID' => 2,
        'FileName' => 'photo.jpg',
        'ContentType' => 'image/jpeg',
        'Size' => 5000,
        'DateCreated' => '2024-01-10T08:00:00Z',
    ]);

    expect($attachment->filename)->toBe('photo.jpg')
        ->and($attachment->mimeType)->toBe('image/jpeg')
        ->and($attachment->fileSizeBytes)->toBe(5000)
        ->and($attachment->dateAdded)->not->toBeNull();
});

it('handles catalog/employee context fields', function () {
    $attachment = Attachment::fromArray([
        'ID' => 3,
        'Filename' => 'signature.png',
        'Default' => true,
    ]);

    expect($attachment->default)->toBeTrue()
        ->and($attachment->isDefault())->toBeTrue()
        ->and($attachment->public)->toBeNull()
        ->and($attachment->email)->toBeNull();
});

it('handles base64 data', function () {
    $attachment = Attachment::fromArray([
        'ID' => 4,
        'Filename' => 'document.txt',
        'Base64Data' => 'SGVsbG8gV29ybGQ=',
    ]);

    expect($attachment->base64Data)->toBe('SGVsbG8gV29ybGQ=')
        ->and($attachment->hasBase64Data())->toBeTrue();
});

it('extracts file extension', function () {
    $pdf = Attachment::fromArray(['ID' => 1, 'Filename' => 'document.pdf']);
    $jpg = Attachment::fromArray(['ID' => 2, 'Filename' => 'photo.JPG']);
    $noExt = Attachment::fromArray(['ID' => 3, 'Filename' => 'noextension']);
    $noFile = Attachment::fromArray(['ID' => 4]);

    expect($pdf->extension())->toBe('pdf')
        ->and($jpg->extension())->toBe('jpg')
        ->and($noExt->extension())->toBeNull()
        ->and($noFile->extension())->toBeNull();
});

it('checks if attachment is image by mime type', function () {
    $image = Attachment::fromArray(['ID' => 1, 'MimeType' => 'image/png']);
    $pdf = Attachment::fromArray(['ID' => 2, 'MimeType' => 'application/pdf']);

    expect($image->isImage())->toBeTrue()
        ->and($pdf->isImage())->toBeFalse();
});

it('checks if attachment is image by extension', function () {
    $jpg = Attachment::fromArray(['ID' => 1, 'Filename' => 'photo.jpg']);
    $png = Attachment::fromArray(['ID' => 2, 'Filename' => 'image.png']);
    $pdf = Attachment::fromArray(['ID' => 3, 'Filename' => 'doc.pdf']);

    expect($jpg->isImage())->toBeTrue()
        ->and($png->isImage())->toBeTrue()
        ->and($pdf->isImage())->toBeFalse();
});

it('checks public visibility', function () {
    $public = Attachment::fromArray(['ID' => 1, 'Public' => true]);
    $private = Attachment::fromArray(['ID' => 2, 'Public' => false]);
    $unset = Attachment::fromArray(['ID' => 3]);

    expect($public->isPublic())->toBeTrue()
        ->and($private->isPublic())->toBeFalse()
        ->and($unset->isPublic())->toBeFalse();
});

it('checks email enabled', function () {
    $enabled = Attachment::fromArray(['ID' => 1, 'Email' => true]);
    $disabled = Attachment::fromArray(['ID' => 2, 'Email' => false]);

    expect($enabled->isEmailEnabled())->toBeTrue()
        ->and($disabled->isEmailEnabled())->toBeFalse();
});
