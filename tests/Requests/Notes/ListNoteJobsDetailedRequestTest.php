<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\NoteAttachment;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;
use Simpro\PhpSdk\Simpro\Data\Notes\JobNoteDetailedListItem;
use Simpro\PhpSdk\Simpro\Requests\Notes\ListNoteJobsDetailedRequest;

it('sends list note jobs detailed request to correct endpoint', function () {
    MockClient::global([
        ListNoteJobsDetailedRequest::class => MockResponse::fixture('list_note_jobs_detailed_request'),
    ]);

    $request = new ListNoteJobsDetailedRequest(companyId: 0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('includes columns query parameter', function () {
    MockClient::global([
        ListNoteJobsDetailedRequest::class => MockResponse::fixture('list_note_jobs_detailed_request'),
    ]);

    $request = new ListNoteJobsDetailedRequest(companyId: 0);
    $this->sdk->send($request);

    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('Note')
        ->and($query['columns'])->toContain('DateCreated')
        ->and($query['columns'])->toContain('Attachments')
        ->and($query['columns'])->toContain('AssignTo')
        ->and($query['columns'])->toContain('SubmittedBy');
});

it('parses list note jobs detailed response correctly', function () {
    MockClient::global([
        ListNoteJobsDetailedRequest::class => MockResponse::fixture('list_note_jobs_detailed_request'),
    ]);

    $request = new ListNoteJobsDetailedRequest(companyId: 0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(JobNoteDetailedListItem::class)
        ->and($dto[0]->id)->toBe(2007)
        ->and($dto[0]->subject)->toBe('Email notification Job Scheduled')
        ->and($dto[0]->note)->toBe('<div style="font-size: 10pt;">Job has been scheduled for next week.</div>')
        ->and($dto[0]->dateCreated)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->followUpDate)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->attachments)->toHaveCount(1)
        ->and($dto[0]->attachments[0])->toBeInstanceOf(NoteAttachment::class)
        ->and($dto[0]->attachments[0]->filename)->toBe('schedule.pdf')
        ->and($dto[0]->assignTo)->toBeInstanceOf(StaffReference::class)
        ->and($dto[0]->assignTo->id)->toBe(1446)
        ->and($dto[0]->assignTo->name)->toBe('John Trickett')
        ->and($dto[0]->submittedBy)->toBeInstanceOf(StaffReference::class)
        ->and($dto[0]->submittedBy->id)->toBe(2350)
        ->and($dto[0]->submittedBy->name)->toBe('b.koshkarov@iqtechnology.io')
        ->and($dto[1])->toBeInstanceOf(JobNoteDetailedListItem::class)
        ->and($dto[1]->id)->toBe(2001)
        ->and($dto[1]->followUpDate)->toBeNull()
        ->and($dto[1]->attachments)->toBeEmpty()
        ->and($dto[1]->assignTo)->toBeNull()
        ->and($dto[1]->submittedBy)->toBeInstanceOf(StaffReference::class)
        ->and($dto[1]->submittedBy->name)->toBe('John Trickett');
});
