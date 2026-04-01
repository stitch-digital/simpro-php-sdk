<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Jobs\Notes\JobNote;
use Simpro\PhpSdk\Simpro\Data\Jobs\Notes\JobNoteAttachment;
use Simpro\PhpSdk\Simpro\Data\Jobs\Notes\JobNoteVisibility;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Notes\ListDetailedJobNotesRequest;

it('sends list detailed job notes request to correct endpoint', function () {
    MockClient::global([
        ListDetailedJobNotesRequest::class => MockResponse::fixture('list_detailed_job_notes_request'),
    ]);

    $request = new ListDetailedJobNotesRequest(companyId: 0, jobId: 52405);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('includes columns query parameter', function () {
    MockClient::global([
        ListDetailedJobNotesRequest::class => MockResponse::fixture('list_detailed_job_notes_request'),
    ]);

    $request = new ListDetailedJobNotesRequest(companyId: 0, jobId: 52405);
    $this->sdk->send($request);

    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('ID')
        ->and($query['columns'])->toContain('Note')
        ->and($query['columns'])->toContain('DateCreated')
        ->and($query['columns'])->toContain('Visibility')
        ->and($query['columns'])->toContain('Attachments')
        ->and($query['columns'])->toContain('AssignTo')
        ->and($query['columns'])->toContain('SubmittedBy');
});

it('parses list detailed job notes response correctly', function () {
    MockClient::global([
        ListDetailedJobNotesRequest::class => MockResponse::fixture('list_detailed_job_notes_request'),
    ]);

    $request = new ListDetailedJobNotesRequest(companyId: 0, jobId: 52405);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(JobNote::class)
        ->and($dto[0]->id)->toBe(2007)
        ->and($dto[0]->subject)->toBe('Email notification Job Scheduled')
        ->and($dto[0]->note)->toBe('<div style="font-size: 10pt;">Job has been scheduled for next week.</div>')
        ->and($dto[0]->dateCreated)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->followUpDate)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[1])->toBeInstanceOf(JobNote::class)
        ->and($dto[1]->id)->toBe(2001)
        ->and($dto[1]->followUpDate)->toBeNull();
});

it('parses visibility correctly', function () {
    MockClient::global([
        ListDetailedJobNotesRequest::class => MockResponse::fixture('list_detailed_job_notes_request'),
    ]);

    $request = new ListDetailedJobNotesRequest(companyId: 0, jobId: 52405);
    $dto = $this->sdk->send($request)->dto();

    expect($dto[0]->visibility)->toBeInstanceOf(JobNoteVisibility::class)
        ->and($dto[0]->visibility->customer)->toBeFalse()
        ->and($dto[0]->visibility->admin)->toBeTrue()
        ->and($dto[1]->visibility)->toBeInstanceOf(JobNoteVisibility::class)
        ->and($dto[1]->visibility->customer)->toBeTrue()
        ->and($dto[1]->visibility->admin)->toBeTrue();
});

it('parses attachments correctly', function () {
    MockClient::global([
        ListDetailedJobNotesRequest::class => MockResponse::fixture('list_detailed_job_notes_request'),
    ]);

    $request = new ListDetailedJobNotesRequest(companyId: 0, jobId: 52405);
    $dto = $this->sdk->send($request)->dto();

    expect($dto[0]->attachments)->toHaveCount(1)
        ->and($dto[0]->attachments[0])->toBeInstanceOf(JobNoteAttachment::class)
        ->and($dto[0]->attachments[0]->fileName)->toBe('schedule.pdf')
        ->and($dto[1]->attachments)->toBeEmpty();
});

it('parses submittedBy correctly', function () {
    MockClient::global([
        ListDetailedJobNotesRequest::class => MockResponse::fixture('list_detailed_job_notes_request'),
    ]);

    $request = new ListDetailedJobNotesRequest(companyId: 0, jobId: 52405);
    $dto = $this->sdk->send($request)->dto();

    expect($dto[0]->submittedBy)->toBeInstanceOf(StaffReference::class)
        ->and($dto[0]->submittedBy->id)->toBe(2350)
        ->and($dto[0]->submittedBy->name)->toBe('b.koshkarov@iqtechnology.io')
        ->and($dto[1]->submittedBy)->toBeInstanceOf(StaffReference::class)
        ->and($dto[1]->submittedBy->id)->toBe(1446)
        ->and($dto[1]->submittedBy->name)->toBe('John Trickett');
});

it('parses assignTo correctly', function () {
    MockClient::global([
        ListDetailedJobNotesRequest::class => MockResponse::fixture('list_detailed_job_notes_request'),
    ]);

    $request = new ListDetailedJobNotesRequest(companyId: 0, jobId: 52405);
    $dto = $this->sdk->send($request)->dto();

    expect($dto[0]->assignTo)->toBeInstanceOf(StaffReference::class)
        ->and($dto[0]->assignTo->id)->toBe(1446)
        ->and($dto[0]->assignTo->name)->toBe('John Trickett')
        ->and($dto[1]->assignTo)->toBeNull();
});
