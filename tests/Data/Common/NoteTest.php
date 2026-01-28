<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Data\Common\Note;
use Simpro\PhpSdk\Simpro\Data\Common\NoteAttachment;
use Simpro\PhpSdk\Simpro\Data\Common\NoteReference;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;

it('creates note from array with all fields', function () {
    $note = Note::fromArray([
        'ID' => 1,
        'Subject' => 'Meeting Notes',
        'Note' => '<p>Discussed project timeline and budget.</p>',
        'DateCreated' => '2024-01-15T10:30:00Z',
        'FollowUpDate' => '2024-01-22',
        'AssignTo' => [
            'ID' => 10,
            'Name' => 'Jane Doe',
            'Type' => 'employee',
            'TypeId' => 10,
        ],
        'SubmittedBy' => [
            'ID' => 5,
            'Name' => 'John Smith',
            'Type' => 'employee',
            'TypeId' => 5,
        ],
        'Reference' => [
            'Type' => 'Job',
            'Number' => 'JOB-001',
            'Text' => 'Kitchen Renovation',
        ],
        'Attachments' => [
            ['_href' => '/api/v1.0/attachments/1', 'FileName' => 'doc.pdf'],
        ],
    ]);

    expect($note->id)->toBe(1)
        ->and($note->subject)->toBe('Meeting Notes')
        ->and($note->note)->toBe('<p>Discussed project timeline and budget.</p>')
        ->and($note->dateCreated)->not->toBeNull()
        ->and($note->followUpDate)->not->toBeNull()
        ->and($note->assignTo)->toBeInstanceOf(StaffReference::class)
        ->and($note->assignTo->name)->toBe('Jane Doe')
        ->and($note->submittedBy)->toBeInstanceOf(StaffReference::class)
        ->and($note->submittedBy->name)->toBe('John Smith')
        ->and($note->reference)->toBeInstanceOf(NoteReference::class)
        ->and($note->reference->type)->toBe('Job')
        ->and($note->attachments)->toHaveCount(1)
        ->and($note->attachments[0])->toBeInstanceOf(NoteAttachment::class);
});

it('handles alternative text field names', function () {
    $note1 = Note::fromArray(['ID' => 1, 'Note' => 'Note field']);
    $note2 = Note::fromArray(['ID' => 2, 'Text' => 'Text field']);
    $note3 = Note::fromArray(['ID' => 3, 'Content' => 'Content field']);

    expect($note1->note)->toBe('Note field')
        ->and($note2->note)->toBe('Text field')
        ->and($note3->note)->toBe('Content field');
});

it('checks if note has content', function () {
    $withContent = Note::fromArray(['ID' => 1, 'Note' => 'Some content']);
    $withNull = Note::fromArray(['ID' => 2, 'Note' => null]);
    $withEmpty = Note::fromArray(['ID' => 3, 'Note' => '']);

    expect($withContent->hasContent())->toBeTrue()
        ->and($withNull->hasContent())->toBeFalse()
        ->and($withEmpty->hasContent())->toBeFalse();
});

it('generates preview of note text with HTML stripped', function () {
    $htmlNote = Note::fromArray(['ID' => 1, 'Note' => '<p>This is <strong>HTML</strong> content.</p>']);
    $longNote = Note::fromArray(['ID' => 2, 'Note' => str_repeat('a', 200)]);
    $emptyNote = Note::fromArray(['ID' => 3]);

    expect($htmlNote->preview())->toBe('This is HTML content.')
        ->and($longNote->preview(50))->toBe(str_repeat('a', 50).'...')
        ->and($emptyNote->preview())->toBe('');
});

it('checks if note has follow up date', function () {
    $withFollowUp = Note::fromArray(['ID' => 1, 'FollowUpDate' => '2024-02-01']);
    $withoutFollowUp = Note::fromArray(['ID' => 2]);

    expect($withFollowUp->hasFollowUp())->toBeTrue()
        ->and($withoutFollowUp->hasFollowUp())->toBeFalse();
});

it('checks if note has attachments', function () {
    $withAttachments = Note::fromArray([
        'ID' => 1,
        'Attachments' => [['_href' => '/test', 'FileName' => 'file.pdf']],
    ]);
    $emptyAttachments = Note::fromArray(['ID' => 2, 'Attachments' => []]);
    $noAttachments = Note::fromArray(['ID' => 3]);

    expect($withAttachments->hasAttachments())->toBeTrue()
        ->and($emptyAttachments->hasAttachments())->toBeFalse()
        ->and($noAttachments->hasAttachments())->toBeFalse();
});
