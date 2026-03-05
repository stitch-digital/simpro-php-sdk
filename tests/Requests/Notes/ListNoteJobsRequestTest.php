<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Notes\JobNoteJob;
use Simpro\PhpSdk\Simpro\Data\Notes\JobNoteListItem;
use Simpro\PhpSdk\Simpro\Data\Notes\NoteVisibility;
use Simpro\PhpSdk\Simpro\Requests\Notes\ListNoteJobsRequest;

it('sends list note jobs request to correct endpoint', function () {
    MockClient::global([
        ListNoteJobsRequest::class => MockResponse::fixture('list_note_jobs_request'),
    ]);

    $request = new ListNoteJobsRequest(companyId: 0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list note jobs response correctly', function () {
    MockClient::global([
        ListNoteJobsRequest::class => MockResponse::fixture('list_note_jobs_request'),
    ]);

    $request = new ListNoteJobsRequest(companyId: 0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(JobNoteListItem::class)
        ->and($dto[0]->id)->toBe(2007)
        ->and($dto[0]->subject)->toBe('Email notification Job Scheduled')
        ->and($dto[0]->visibility)->toBeInstanceOf(NoteVisibility::class)
        ->and($dto[0]->visibility->customer)->toBeFalse()
        ->and($dto[0]->visibility->admin)->toBeTrue()
        ->and($dto[0]->job)->toBeInstanceOf(JobNoteJob::class)
        ->and($dto[0]->job->id)->toBe(52405)
        ->and($dto[0]->job->name)->toBe('TEST templates')
        ->and($dto[0]->href)->toBe('/api/v1.0/companies/0/jobs/52405/notes/2007')
        ->and($dto[1])->toBeInstanceOf(JobNoteListItem::class)
        ->and($dto[1]->id)->toBe(2001)
        ->and($dto[1]->subject)->toBe('Reached out to subcontractor')
        ->and($dto[1]->visibility->customer)->toBeTrue()
        ->and($dto[1]->visibility->admin)->toBeTrue()
        ->and($dto[1]->job)->toBeNull();
});
