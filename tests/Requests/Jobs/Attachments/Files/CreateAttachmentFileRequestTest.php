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

it('returns created attachment ID when the response body carries a valid integer ID', function () {
    MockClient::global([
        CreateAttachmentFileRequest::class => MockResponse::make(['ID' => 1234], 201),
    ]);

    $request = new CreateAttachmentFileRequest(0, 4711, ['Filename' => 'report.pdf']);
    $id = $this->sdk->send($request)->dto();

    expect($id)->toBe(1234);
});

it('accepts a numeric string ID and returns it as an int', function () {
    MockClient::global([
        CreateAttachmentFileRequest::class => MockResponse::make(['ID' => '5678'], 201),
    ]);

    $request = new CreateAttachmentFileRequest(0, 4711, ['Filename' => 'report.pdf']);
    $id = $this->sdk->send($request)->dto();

    expect($id)->toBe(5678);
});

it('throws when the response body has no ID field', function () {
    MockClient::global([
        CreateAttachmentFileRequest::class => MockResponse::make(['other' => 'value'], 201),
    ]);

    $request = new CreateAttachmentFileRequest(0, 4711, ['Filename' => 'report.pdf']);

    expect(fn () => $this->sdk->send($request)->dto())
        ->toThrow(RuntimeException::class, 'missing numeric ID');
});

it('throws when the response body ID is zero', function () {
    MockClient::global([
        CreateAttachmentFileRequest::class => MockResponse::make(['ID' => 0], 201),
    ]);

    $request = new CreateAttachmentFileRequest(0, 4711, ['Filename' => 'report.pdf']);

    expect(fn () => $this->sdk->send($request)->dto())
        ->toThrow(RuntimeException::class, 'non-positive ID');
});

it('throws when the response body ID is a non-numeric string', function () {
    MockClient::global([
        CreateAttachmentFileRequest::class => MockResponse::make(['ID' => 'abc'], 201),
    ]);

    $request = new CreateAttachmentFileRequest(0, 4711, ['Filename' => 'report.pdf']);

    expect(fn () => $this->sdk->send($request)->dto())
        ->toThrow(RuntimeException::class, 'missing numeric ID');
});
