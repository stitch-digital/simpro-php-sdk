<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Employees\Attachments\AttachmentFolder;
use Simpro\PhpSdk\Simpro\Requests\Employees\Attachments\Folders\ListEmployeeAttachmentFoldersRequest;

it('sends list employee attachment folders request to correct endpoint', function () {
    MockClient::global([
        ListEmployeeAttachmentFoldersRequest::class => MockResponse::fixture('list_employee_attachment_folders_request'),
    ]);

    $request = new ListEmployeeAttachmentFoldersRequest(0, 123);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list employee attachment folders response correctly', function () {
    MockClient::global([
        ListEmployeeAttachmentFoldersRequest::class => MockResponse::fixture('list_employee_attachment_folders_request'),
    ]);

    $request = new ListEmployeeAttachmentFoldersRequest(0, 123);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(AttachmentFolder::class)
        ->and($dto[0]->id)->toBe(10)
        ->and($dto[0]->name)->toBe('Documents')
        ->and($dto[0]->parentId)->toBeNull()
        ->and($dto[1])->toBeInstanceOf(AttachmentFolder::class)
        ->and($dto[1]->id)->toBe(11)
        ->and($dto[1]->name)->toBe('Images')
        ->and($dto[1]->parentId)->toBe(10);
});
