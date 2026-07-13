<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Requests\Jobs\Attachments\Files\CreateAttachmentFileRequest;

it('sends create job attachment file request to correct endpoint', function () {
    MockClient::global([
        CreateAttachmentFileRequest::class => MockResponse::make(['ID' => 1234], 201),
    ]);

    $request = new CreateAttachmentFileRequest(0, 4711, [
        'Filename' => 'report.pdf',
        'Base64Data' => 'ZXhhbXBsZQ==',
        'Public' => true,
        'Email' => false,
    ]);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(201);
});

it('returns a legacy integer ID as a string', function () {
    MockClient::global([
        CreateAttachmentFileRequest::class => MockResponse::make(['ID' => 1234], 201),
    ]);

    $request = new CreateAttachmentFileRequest(0, 4711, ['Filename' => 'report.pdf']);
    $id = $this->sdk->send($request)->dto();

    expect($id)->toBe('1234');
});

it('returns a numeric string ID unchanged', function () {
    MockClient::global([
        CreateAttachmentFileRequest::class => MockResponse::make(['ID' => '5678'], 201),
    ]);

    $request = new CreateAttachmentFileRequest(0, 4711, ['Filename' => 'report.pdf']);
    $id = $this->sdk->send($request)->dto();

    expect($id)->toBe('5678');
});

it('returns an opaque alphanumeric ID unchanged', function () {
    MockClient::global([
        CreateAttachmentFileRequest::class => MockResponse::make(
            ['ID' => 'Uk6fROW33VldTBT1nQgr7JDTj7YDujswrFoSsTFSss8'],
            201,
        ),
    ]);

    $request = new CreateAttachmentFileRequest(0, 4711, ['Filename' => 'report.pdf']);
    $id = $this->sdk->send($request)->dto();

    expect($id)->toBe('Uk6fROW33VldTBT1nQgr7JDTj7YDujswrFoSsTFSss8');
});

it('throws when the response body has no ID field', function () {
    MockClient::global([
        CreateAttachmentFileRequest::class => MockResponse::make(['other' => 'value'], 201),
    ]);

    $request = new CreateAttachmentFileRequest(0, 4711, ['Filename' => 'report.pdf']);

    expect(fn () => $this->sdk->send($request)->dto())
        ->toThrow(RuntimeException::class, 'missing ID');
});

it('throws when the response body ID is integer zero', function () {
    MockClient::global([
        CreateAttachmentFileRequest::class => MockResponse::make(['ID' => 0], 201),
    ]);

    $request = new CreateAttachmentFileRequest(0, 4711, ['Filename' => 'report.pdf']);

    expect(fn () => $this->sdk->send($request)->dto())
        ->toThrow(RuntimeException::class, 'empty/zero ID');
});

it('throws when the response body ID is the string zero', function () {
    MockClient::global([
        CreateAttachmentFileRequest::class => MockResponse::make(['ID' => '0'], 201),
    ]);

    $request = new CreateAttachmentFileRequest(0, 4711, ['Filename' => 'report.pdf']);

    expect(fn () => $this->sdk->send($request)->dto())
        ->toThrow(RuntimeException::class, 'empty/zero ID');
});

it('throws when the response body ID is an empty string', function () {
    MockClient::global([
        CreateAttachmentFileRequest::class => MockResponse::make(['ID' => ''], 201),
    ]);

    $request = new CreateAttachmentFileRequest(0, 4711, ['Filename' => 'report.pdf']);

    expect(fn () => $this->sdk->send($request)->dto())
        ->toThrow(RuntimeException::class, 'empty/zero ID');
});
