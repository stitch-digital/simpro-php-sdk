<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Contractors\ContractorListItem;
use Simpro\PhpSdk\Simpro\Requests\Contractors\ListContractorsRequest;

it('sends list contractors request to correct endpoint', function () {
    MockClient::global([
        ListContractorsRequest::class => MockResponse::fixture('list_contractors_request'),
    ]);

    $request = new ListContractorsRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list contractors response correctly', function () {
    MockClient::global([
        ListContractorsRequest::class => MockResponse::fixture('list_contractors_request'),
    ]);

    $request = new ListContractorsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(2)
        ->and($dto[0])->toBeInstanceOf(ContractorListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('Bright Spark Electrical Limited')
        ->and($dto[1])->toBeInstanceOf(ContractorListItem::class)
        ->and($dto[1]->id)->toBe(2)
        ->and($dto[1]->name)->toBe('ABC Plumbing Services');
});
