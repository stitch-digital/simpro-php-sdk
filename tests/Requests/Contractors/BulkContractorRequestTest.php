<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Bulk\BulkResponse;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkCreateRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkDeleteRequest;
use Simpro\PhpSdk\Simpro\Requests\Bulk\BulkUpdateRequest;

it('bulk creates contractors', function () {
    MockClient::global([
        BulkCreateRequest::class => MockResponse::fixture('bulk_create_request'),
    ]);

    $response = $this->sdk->contractors(companyId: 0)->bulkCreate([
        ['GivenName' => 'Peter', 'FamilyName' => 'Smith'],
        ['GivenName' => 'Michael', 'FamilyName' => 'Dickson'],
    ]);

    expect($response)->toBeInstanceOf(BulkResponse::class)
        ->and($response->items)->toHaveCount(2)
        ->and($response->resourceIds())->toBe([1882, 1883]);
});

it('bulk updates contractors', function () {
    MockClient::global([
        BulkUpdateRequest::class => MockResponse::fixture('bulk_update_request'),
    ]);

    $response = $this->sdk->contractors(companyId: 0)->bulkUpdate([
        ['ID' => 1884, 'GivenName' => 'Pete'],
        ['ID' => 1885, 'GivenName' => 'Mike'],
    ]);

    expect($response)->toBeInstanceOf(BulkResponse::class)
        ->and($response->items)->toHaveCount(2)
        ->and($response->allSuccessful())->toBeTrue();
});

it('bulk deletes contractors', function () {
    MockClient::global([
        BulkDeleteRequest::class => MockResponse::fixture('bulk_delete_request'),
    ]);

    $messages = $this->sdk->contractors(companyId: 0)->bulkDelete([210787, 210788, 210789]);

    expect($messages)->toBeArray()
        ->and($messages[0])->toBe('2 section(s) deleted.');
});
