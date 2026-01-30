<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\Attachment;
use Simpro\PhpSdk\Simpro\Requests\Employees\Licences\Attachments\ListLicenceAttachmentFilesRequest;

it('sends list licence attachment files request to correct endpoint', function () {
    MockClient::global([
        ListLicenceAttachmentFilesRequest::class => MockResponse::fixture('list_licence_attachment_files_request'),
    ]);

    $request = new ListLicenceAttachmentFilesRequest(0, 123, 456);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list licence attachment files response correctly', function () {
    MockClient::global([
        ListLicenceAttachmentFilesRequest::class => MockResponse::fixture('list_licence_attachment_files_request'),
    ]);

    $request = new ListLicenceAttachmentFilesRequest(0, 123, 456);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(Attachment::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->filename)->toBe('licence-scan.pdf')
        ->and($dto[0]->mimeType)->toBe('application/pdf')
        ->and($dto[0]->default)->toBeFalse()
        ->and($dto[1])->toBeInstanceOf(Attachment::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->filename)->toBe('certificate.jpg')
        ->and($dto[1]->default)->toBeTrue();
});
