<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\Attachment;
use Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Files\GetEmployeeAttachmentFileRequest;

it('sends get employee attachment file request to correct endpoint', function () {
    MockClient::global([
        GetEmployeeAttachmentFileRequest::class => MockResponse::fixture('get_employee_attachment_file_request'),
    ]);

    $request = new GetEmployeeAttachmentFileRequest(0, 123, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get employee attachment file response correctly', function () {
    MockClient::global([
        GetEmployeeAttachmentFileRequest::class => MockResponse::fixture('get_employee_attachment_file_request'),
    ]);

    $request = new GetEmployeeAttachmentFileRequest(0, 123, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Attachment::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->filename)->toBe('resume.pdf')
        ->and($dto->mimeType)->toBe('application/pdf')
        ->and($dto->fileSizeBytes)->toBe(102400)
        ->and($dto->folder->id)->toBe(10)
        ->and($dto->default)->toBeFalse();
});
