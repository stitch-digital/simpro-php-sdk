<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\NoteAttachment;
use Simpro\PhpSdk\Simpro\Data\Common\StaffReference;
use Simpro\PhpSdk\Simpro\Data\Notes\CustomerNoteDetailedListItem;
use Simpro\PhpSdk\Simpro\Requests\Notes\ListNoteCustomersDetailedRequest;

it('sends list note customers detailed request to correct endpoint', function () {
    MockClient::global([
        ListNoteCustomersDetailedRequest::class => MockResponse::fixture('list_note_customers_detailed_request'),
    ]);

    $request = new ListNoteCustomersDetailedRequest(companyId: 0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('includes columns query parameter', function () {
    MockClient::global([
        ListNoteCustomersDetailedRequest::class => MockResponse::fixture('list_note_customers_detailed_request'),
    ]);

    $request = new ListNoteCustomersDetailedRequest(companyId: 0);
    $this->sdk->send($request);

    $query = $request->query()->all();

    expect($query)->toHaveKey('columns')
        ->and($query['columns'])->toContain('Note')
        ->and($query['columns'])->toContain('DateCreated')
        ->and($query['columns'])->toContain('Attachments')
        ->and($query['columns'])->toContain('AssignTo')
        ->and($query['columns'])->toContain('SubmittedBy');
});

it('parses list note customers detailed response correctly', function () {
    MockClient::global([
        ListNoteCustomersDetailedRequest::class => MockResponse::fixture('list_note_customers_detailed_request'),
    ]);

    $request = new ListNoteCustomersDetailedRequest(companyId: 0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(CustomerNoteDetailedListItem::class)
        ->and($dto[0]->id)->toBe(101)
        ->and($dto[0]->subject)->toBe('Initial consultation')
        ->and($dto[0]->note)->toBe('<p>Discussed project requirements and timeline.</p>')
        ->and($dto[0]->dateCreated)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->followUpDate)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($dto[0]->attachments)->toHaveCount(1)
        ->and($dto[0]->attachments[0])->toBeInstanceOf(NoteAttachment::class)
        ->and($dto[0]->attachments[0]->filename)->toBe('proposal.pdf')
        ->and($dto[0]->assignTo)->toBeInstanceOf(StaffReference::class)
        ->and($dto[0]->assignTo->id)->toBe(10)
        ->and($dto[0]->assignTo->name)->toBe('John Smith')
        ->and($dto[0]->submittedBy)->toBeInstanceOf(StaffReference::class)
        ->and($dto[0]->submittedBy->id)->toBe(15)
        ->and($dto[0]->submittedBy->name)->toBe('Sarah Connor')
        ->and($dto[1])->toBeInstanceOf(CustomerNoteDetailedListItem::class)
        ->and($dto[1]->id)->toBe(102)
        ->and($dto[1]->followUpDate)->toBeNull()
        ->and($dto[1]->attachments)->toBeEmpty()
        ->and($dto[1]->assignTo)->toBeNull()
        ->and($dto[1]->submittedBy)->toBeInstanceOf(StaffReference::class)
        ->and($dto[1]->submittedBy->name)->toBe('John Smith');
});
