<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Quotes\Quote;
use Simpro\PhpSdk\Simpro\Data\Quotes\QuoteStatus;
use Simpro\PhpSdk\Simpro\Requests\Quotes\ListQuotesDetailedRequest;

it('sends list quotes detailed request to correct endpoint', function () {
    MockClient::global([
        ListQuotesDetailedRequest::class => MockResponse::fixture('list_quotes_detailed_request'),
    ]);

    $request = new ListQuotesDetailedRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list quotes detailed response correctly', function () {
    MockClient::global([
        ListQuotesDetailedRequest::class => MockResponse::fixture('list_quotes_detailed_request'),
    ]);

    $request = new ListQuotesDetailedRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(Quote::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->type)->toBe('Project')
        ->and($dto[0]->name)->toBe('Kitchen Quote')
        ->and($dto[0]->status)->toBeInstanceOf(QuoteStatus::class)
        ->and($dto[0]->status->name)->toBe('Approved')
        ->and($dto[0]->total->incTax)->toBe(15000.00);
});
