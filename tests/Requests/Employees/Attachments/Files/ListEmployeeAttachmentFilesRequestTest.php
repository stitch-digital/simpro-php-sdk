<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\Attachment;
use Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Files\ListEmployeeAttachmentFilesRequest;

it('sends list employee attachment files request to correct endpoint', function () {
    MockClient::global([
        ListEmployeeAttachmentFilesRequest::class => MockResponse::fixture('list_employee_attachment_files_request'),
    ]);

    $request = new ListEmployeeAttachmentFilesRequest(0, 123);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list employee attachment files response correctly', function () {
    MockClient::global([
        ListEmployeeAttachmentFilesRequest::class => MockResponse::fixture('list_employee_attachment_files_request'),
    ]);

    $request = new ListEmployeeAttachmentFilesRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(Attachment::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->filename)->toBe('resume.pdf')
        ->and($dto[0]->mimeType)->toBe('application/pdf')
        ->and($dto[0]->fileSizeBytes)->toBe(102400)
        ->and($dto[0]->folder->id)->toBe(10)
        ->and($dto[0]->default)->toBeFalse()
        ->and($dto[1])->toBeInstanceOf(Attachment::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->filename)->toBe('signature.png')
        ->and($dto[1]->default)->toBeTrue();
});
